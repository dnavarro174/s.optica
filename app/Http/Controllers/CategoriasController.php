<?php

namespace App\Http\Controllers;
use App\Categoria, App\AccionesRolesPermisos, App\Producto, App\UMedida;
use Illuminate\Http\Request;
use Auth, DB, Carbon\Carbon, Cache, Alert;

class CategoriasController extends Controller
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
        if(!isset( session("permisosTotales")["productos"]["permisos"]["inicio"]   ) ){  
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

            Cache::flush();
        if($request->get('pag')){
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }

        if(session('cod_empresa') == false){
            return redirect()->route('almacen.index');
        }

        if(isset($request->id)){
            if($request->id == 1 or $request->id == 2){
                session(['cuenta_tipo'=>$request->id]);
            }else{
                return redirect('/');
            }
        }

        $text_search = $request->input('s');
            Cache::flush();

        if($request->get('s')){
            $search = $request->get('s');

            $ctas_datos = Categoria::where("categoria", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate($pag);

        }else{

            $key = 'catorias.page.'.request('page', 1);
            $ctas_datos = Cache::rememberForever($key, function() use ($pag){
                return Categoria::orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);
            });
        }

        return view('categorias.index', compact('ctas_datos', 'permisos', 'text_search'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /*if(isset($request->id)){
            if($request->id == 1 or $request->id == 2){
                session(['cuenta_tipo'=>$request->id]);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('/');
        }*/
        
        $cuenta_tipo = session('cuenta_tipo');

        return view('categorias.create', compact('cuenta_tipo'));
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
            //'categoria'=>'required'
        ]);
        
        try {

            $tipo = $request->input('tipo');
            if($tipo=="categoria"){
                DB::table('categorias')->insert([
                    'categoria' => mb_strtoupper($request->input('categoria')),
                ]);

                $id = DB::getPdo()->lastInsertId();

                $categoria = Categoria::orderBy('categoria', 'asc')->get();

                $select = "<option value=''>SELECCIONE</option>";
                foreach ($categoria as $cat) {
                    if($cat->id == $id)
                        $select .= "<option value='".$cat->id."' selected>".$cat->categoria."</option>";
                    else
                        $select .= "<option value='".$cat->id."'>".$cat->categoria."</option>";
                }

            }else{
                DB::table('unidad_medida')->insert([
                    'cod_umedida' => mb_strtoupper($request->input('cod_umedida')),
                    'dsc_umedida' => mb_strtoupper($request->input('dsc_umedida')),
                    'fecha_hora'  => Carbon::now(),
                    'cod_usuario' => \Auth::User()->id

                ]);

                $id = DB::getPdo()->lastInsertId();

                $categoria = UMedida::orderBy('dsc_umedida', 'asc')->get();
                
                $select = "<option value=''>SELECCIONE</option>";
                foreach ($categoria as $cat) {
                    if($cat->id == $id)
                        $select .= "<option value='".$cat->id."' selected>".$cat->dsc_umedida." - ".$cat->cod_umedida."</option>";
                    else
                        $select .= "<option value='".$cat->id."'>".$cat->dsc_umedida." - ".$cat->cod_umedida."</option>";
                }

            }

            Cache::flush();
            alert()->success('Registro Grabado.','Mensaje');

            //return redirect()->route('categorias.index');
            //return redirect('/categorias/'.$id.'/edit');
            
            $rs = array('categoria' => $select, 'ok' => 'ok','tipo'=>$tipo );
            return $rs;
            
        } catch (Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        }
       

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
        if(!isset( session("permisosTotales")["productos"]["permisos"]["mostrar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

      

        $cuenta_tipo = session('cuenta_tipo');
        //$datos = Categoria::where('id',$id)->first();
       
        $datos = Categoria::findOrFail($id);

        return view('categorias.show', compact('datos', 'cuenta_tipo'));
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
        if(!isset( session("permisosTotales")["productos"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        
        $cuenta_tipo = session('cuenta_tipo');
        $datos = Categoria::where('id',$id)->first();

        return view('categorias.edit', compact('datos', 'cuenta_tipo'));
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
        if(!isset( session("permisosTotales")["productos"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        DB::table('categorias')->where('id', $id)->update([
            'categoria' => mb_strtoupper($request->input('categoria')),
        ]);

        Cache::flush();
        alert()->success('Registro Modificado.','Mensaje');

        return redirect()->route('categorias.index');
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
        if(!isset( session("permisosTotales")["productos"]["permisos"]["eliminar"] ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            $n = Producto::where('cod_artic', $value)->count();
            if($n > 0){
                alert()->warning('Categoria contiene productos registrados','Eliminado');
                return back();
            }
            Categoria::where('id',$value)->delete();
        }

        Cache::flush();

        alert()->error('Registros borrados.','Eliminado');
        //return redirect()->route('productos.index');
        return back();
    }
}
