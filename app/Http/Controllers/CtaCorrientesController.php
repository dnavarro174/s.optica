<?php

namespace App\Http\Controllers;
use App\CtaCorrientes, App\AccionesRolesPermisos, App\Movimientos;
use Illuminate\Http\Request;
use Auth, DB, Carbon\Carbon, Cache, Alert;


class CtaCorrientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ctas_corrientes"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        if(Cache::has('permisos.all')){
            $permisos = Cache::get('permisos.all');

        }else{

            $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
            $permParam["modulo_alias"] = "ctas_corrientes";
            $permParam["roles"] = $roles;
            $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

            Cache::put('permisos.all', $permisos, 5);

        }
        ////FIN DE PERMISOS

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }

        if(session('cod_empresa') == false){ return redirect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }

        if(isset($request->id)){
            if($request->id == 1 or $request->id == 2){
                session(['cuenta_tipo'=>$request->id]);
            }else{
                return redirect('/');
            }
        }

        $text_search = $request->input('s');

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $ctas_datos = CtaCorrientes::where("flag_tipo", session('cuenta_tipo'))
                ->where(function ($query) use ($search) {
                $query->where("cod_ruc", "LIKE", '%'.$search.'%')
                ->orWhere("razon_social", "LIKE", '%'.$search.'%')
                ->orWhere("contacto_1", "LIKE", '%'.$search.'%')
                ->orWhere("contacto_2", "LIKE", '%'.$search.'%')
                ->orWhere("tele", "LIKE", '%'.$search.'%')
                ->orWhere("tele_contac", "LIKE", '%'.$search.'%')
                ->orWhere("e_mail", "LIKE", '%'.$search.'%')
                ->orWhere("e_mail_aux", "LIKE", '%'.$search.'%')
                ->orderBy('id', request('sorted', 'DESC'));
            })
            ->paginate($pag);

        }else{

            $key = 'ctas_corrientes.page.'.request('page', 1);
            $ctas_datos = Cache::rememberForever($key, function() use ($pag){
                return CtaCorrientes::where('flag_tipo',session('cuenta_tipo'))->orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);
            });
        }

        return view('ctas_corrientes.index', compact('ctas_datos', 'permisos', 'text_search'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(session('cod_empresa') == false){ return redirect('/'); }else{

            if(isset($request->id)){
                if($request->id == 1 or $request->id == 2){
                    session(['cuenta_tipo'=>$request->id]);
                }else{
                    return redirect('/');
                }
            }else{
                return redirect('/');
            }
        }

            $cuenta_tipo = session('cuenta_tipo');

        return view('ctas_corrientes.create', compact('cuenta_tipo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'cod_ruc'=>'required|unique:cuentas_corrientes,cod_ruc',
            'e_mail' => 'required'
        ]);
        
        // 1 = CLIENTE 2 = PROVEED
        // 10 PERSONA NATURA // 20 PERSONA JURIDICA
        $ruc = $request->input('cod_ruc');
        $check_ruc = CtaCorrientes::where('cod_ruc',$ruc)->count();
        if($check_ruc >= 1){
            alert()->warning('El RUC ya esta registrado.','Mensaje');
            return back();  
        }

        $t_persona = "";
        $t_persona = starts_with($ruc, '10');
        if($t_persona == true){
            $t_persona = "01";
            $tipo_docum   = "N";
        }else{
            $t_persona = "02";
            $tipo_docum   = "J";
        }

        DB::table('cuentas_corrientes')->insert([
            //'cod_cencosto' => 0, // id pk
            'cod_empresa'  => $request->session()->get('cod_empresa'),
            'cod_usuario'  => Auth::User()->id,

            'cod_ruc' => ($request->input('cod_ruc')),
            'razon_social' => mb_strtoupper($request->input('razon_social')),
            'direccion' => mb_strtoupper($request->input('direccion')),
            'descripcion' => mb_strtoupper($request->input('descripcion')),
            'e_mail' => ($request->input('e_mail')),
            'e_mail_aux' => $request->input('e_mail_aux'),
            'tele' => $request->input('tele'),
            'tele_contac' => $request->input('tele_contac'),
            'flag_retiene' => 'N',
            'flag_detraccion' => 'N',

            'tipo_persona' => $t_persona, // 01 = PNATURAL 02 PJURIDICA
            'tipo_docum' => $tipo_docum, 
            'contacto_1' => mb_strtoupper($request->input('contacto_1')),
            'contacto_2' => mb_strtoupper($request->input('contacto_2')),
            'flag_tipo' => $request->input('cuenta_tipo'), // N - S
            'flag_activo' => 'S', // N - S
            'fecha_hora' => Carbon::now(),
        ]);

        Cache::flush();
        alert()->success('Registro Grabado.','Mensaje');

        return redirect()->route('ctas_corrientes.index');
        //return back()->with('alert','Registro Grabado!');
        //return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CtaCorrientes  $ctaCorrientes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ctas_corrientes"]["permisos"]["mostrar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(session('cuenta_tipo') == true){
            if(!session('cuenta_tipo') == 1 or !session('cuenta_tipo') == 2){
                return redirect('/');
            }
        }else{
            return redirect('/');
        }

        $cuenta_tipo = session('cuenta_tipo');
        //$datos = CtaCorrientes::where('id',$id)->first();
       
        $datos = CtaCorrientes::findOrFail($id);

        return view('ctas_corrientes.show', compact('datos', 'cuenta_tipo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CtaCorrientes  $ctaCorrientes
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ctas_corrientes"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(session('cuenta_tipo') == true){
            if(!session('cuenta_tipo') == 1 or !session('cuenta_tipo') == 2){
                return redirect('/');
            }
        }else{
            return redirect('/');
        }

        $cuenta_tipo = session('cuenta_tipo');
        $datos = CtaCorrientes::where('id',$id)->first();

        return view('ctas_corrientes.edit', compact('datos', 'cuenta_tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CtaCorrientes  $ctaCorrientes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ctas_corrientes"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $ruc = $request->input('cod_ruc');

        $t_persona = "";
        $t_persona = starts_with($ruc, '10');
        if($t_persona == true){
            $t_persona = "01";
            $tipo_docum = "N";
        }else{
            $t_persona = "02";
            $tipo_docum = "J";
        }

        DB::table('cuentas_corrientes')->where('id',$id)->update([
            //'cod_cencosto' => 0, // id pk
            'cod_empresa'  => $request->session()->get('cod_empresa'),
            'cod_usuario'  => Auth::User()->id,

            'cod_ruc' => ($request->input('cod_ruc')),
            'razon_social' => mb_strtoupper($request->input('razon_social')),
            'direccion' => mb_strtoupper($request->input('direccion')),
            'descripcion' => mb_strtoupper($request->input('descripcion')),
            'e_mail' => ($request->input('e_mail')),
            'e_mail_aux' => $request->input('e_mail_aux'),
            'tele' => $request->input('tele'),
            'tele_contac' => $request->input('tele_contac'),
            'flag_retiene' => 'N',
            'flag_detraccion' => 'N',

            'tipo_persona' => $t_persona, // 01 = PNATURAL 02 PJURIDICA
            'tipo_docum' => $tipo_docum,
            'contacto_1' => mb_strtoupper($request->input('contacto_1')),
            'contacto_2' => mb_strtoupper($request->input('contacto_2')),
            'flag_tipo' => $request->input('cuenta_tipo'), // N - S
            'flag_activo' => 'S', // N - S
            'fecha_hora' => Carbon::now(),
        ]);

        Cache::flush();
        alert()->success('Registro Modificado.','Mensaje');

        return redirect()->route('ctas_corrientes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CtaCorrientes  $ctaCorrientes
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function eliminarVarios(Request $request)
    {   
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ctas_corrientes"]["permisos"]["eliminar"] ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {

            $datos = CtaCorrientes::where('id', $value)->first();

            $reg = Movimientos::where('cta_cte', $datos->cod_ruc)->count();
            
            if($reg>0){
                alert()->warning('El registro esta siendo usado','Advertencia');
                return back();
            }
            CtaCorrientes::where('id', $value)->delete();
        }

        Cache::flush();

        alert()->error('Registros borrados.','Eliminado');
        return back();
    }
}
