<?php

namespace App\Http\Controllers;
use App\Proyecto, App\AccionesRolesPermisos, App\Movimientos, App\CtaCorrientes;
use Illuminate\Http\Request;
use Auth, DB, Carbon\Carbon, Cache, Alert;


class ProyectosController extends Controller
{
    
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
Cache::flush();
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
        //if(session('cod_almacen') == false){ return redirect('/'); }

        $text_search = $request->input('s');

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $ctas_datos = Proyecto::where("flag_activo", 1)
                ->where(function ($query) use ($search) {
                $query->where("nom_proy", "LIKE", '%'.$search.'%')
                ->orWhere("direccion", "LIKE", '%'.$search.'%')
                ->orWhere("descripcion", "LIKE", '%'.$search.'%')
                ->orWhere("cod_ruc", "LIKE", '%'.$search.'%')
                ->orderBy('id', request('sorted', 'DESC'));
            })
            ->paginate($pag);

        }else{

            $key = 'proyectos.page.'.request('page', 1);
            $ctas_datos = Cache::rememberForever($key, function() use ($pag){
                return Proyecto::orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);
            });
        }

        return view('proyectos.index', compact('ctas_datos', 'permisos', 'text_search'));
        
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

        return view('proyectos.create', compact('cuenta_tipo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $cod_empresa = session('cod_empresa');
            $cod_usuario = \Auth::User()->id;

            $tc_id = $request->input('tc_id');
            $cod_ruc = trim($request->input('cta_cte'));
            $cliente = mb_strtoupper($request->input('cliente'));
            $nom_proy = mb_strtoupper($request->input('nom_proy'));
            $direccion = mb_strtoupper($request->input('direccion'));
            $descripcion = mb_strtoupper($request->input('descripcion'));
            $flag_activo = 1;

            if(!$cod_ruc){
                return \Response::json(['error'=>'El Cliente no esta registrado'], 404);
            }

                if($tc_id==0){//NEW

                    /*$n = Proyecto::where('cod_ruc', $cod_ruc)->count();
                    if($n > 0){
                        return \Response::json(['error' => "El proveedor con RUC: $cod_ruc ya existe." ], 404); 
                    }*/

                    $proyecto = new Proyecto() ;
                    $proyecto->cod_empresa = $cod_empresa;
                    $proyecto->cod_usuario = $cod_usuario;
                    $proyecto->cod_ruc = $cod_ruc;
                    $proyecto->nom_proy = $nom_proy;
                    $proyecto->direccion = $direccion;
                    $proyecto->descripcion = $descripcion;
                    $proyecto->flag_activo = 1;
                    $proyecto->created_at = Carbon::now();
                    $proyecto->updated_at = Carbon::now();
                    $proyecto->save();

                    Cache::flush();
                    alert()->success('Registro Grabado.','Mensaje');

                    return redirect()->route('proyectos.index');
                }

              
            }
            catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 

        
    }

   
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proyecto  $ctaCorrientes
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

        $datos = Proyecto::where('id',$id)->first();
        $cliente = CtaCorrientes::where('cod_ruc', $datos->cod_ruc)->first();

        return view('proyectos.edit', compact('datos', 'cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proyecto  $ctaCorrientes
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

        try {
            $cod_empresa = session('cod_empresa');
            $cod_usuario = \Auth::User()->id;

            $tc_id = $request->input('tc_id');
            $cod_ruc = trim($request->input('cta_cte'));
            $cliente = mb_strtoupper($request->input('cliente'));
            $nom_proy = mb_strtoupper($request->input('nom_proy'));
            $direccion = mb_strtoupper($request->input('direccion'));
            $descripcion = mb_strtoupper($request->input('descripcion'));
            $flag_activo = $request->input('estado');

            if(!$cod_ruc){
                return \Response::json(['error'=>'El Cliente no esta registrado'], 404);
            }

                    $proyecto = Proyecto::find($id);
                    $proyecto->cod_empresa = $cod_empresa;
                    $proyecto->cod_usuario = $cod_usuario;
                    $proyecto->cod_ruc = $cod_ruc;
                    $proyecto->nom_proy = $nom_proy;
                    $proyecto->direccion = $direccion;
                    $proyecto->descripcion = $descripcion;
                    $proyecto->flag_activo = $flag_activo;
                    $proyecto->created_at = Carbon::now();
                    $proyecto->updated_at = Carbon::now();
                    $proyecto->save();

                    //$rs = array('cod_ruc' => $cod_ruc, 'proyecto' => $nom_proy, 'ok' => 'ok' );
                    //return $rs;
                

                //$rs = array('cod_ruc' => $cod_ruc, 'proyecto' => $nom_proy, 'ok' => 'no' );
                //return $rs;

                Cache::flush();
                alert()->success('Registro Modificado.','Mensaje');

                return redirect()->route('proyectos.index');

            }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 

        
    }

    public function eliminarVarios(Request $request)
    {   
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ctas_corrientes"]["permisos"]["eliminar"] ) ){  
            Auth::logout();
            return redirect('/login');
        }
        Cache::flush();

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {

            $reg = Movimientos::where('proyectos_id', $value)->count();
            
            if($reg>0){
                alert()->warning('El registro esta siendo usado','Advertencia');
                return back();
            }
            Proyecto::where('id', $value)->delete();
        }

        Cache::flush();

        alert()->error('Registros borrados.','Eliminado');
        return back();
    }
}
