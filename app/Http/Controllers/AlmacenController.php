<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use DB;
use App\Almacen, App\Ingreso, App\Producto, App\Salida;
use Carbon\Carbon;
use App\AccionesRolesPermisos;

use Alert;
use Auth;

class AlmacenController extends Controller
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
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "productos";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS


        $productos  = DB::select("select a.cod_artic,a.nombre, sum(m.cant_mov) AS suma  FROM movimientos m INNER JOIN articulos a ON m.cod_artic=a.cod_artic WHERE m.tpo_doc=04 AND m.nro_linea<>0 GROUP BY a.cod_artic, a.nombre ORDER BY suma DESC LIMIT 3");

        $proyectos  = DB::select("select p.nom_proy, sum(m.cant_mov) AS suma  FROM movimientos m INNER JOIN proyectos p ON m.proyectos_id=p.id WHERE m.tpo_doc='04' AND m.nro_linea<>0 GROUP BY p.nom_proy ORDER BY suma DESC LIMIT 5");

         /*
        SELECT a.cod_artic,a.nombre, sum(m.cant_mov) AS suma  FROM movimientos m INNER JOIN articulos a ON m.cod_artic=a.cod_artic WHERE m.tpo_doc='04' AND m.nro_linea<>0 GROUP BY a.cod_artic, a.nombre ORDER BY suma DESC LIMIT 5
        ;*/

        if(session('cod_empresa')==false){
            return redirect('/login');
        }

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }

            Cache::flush();
        if($request->get('s')){
            $search = $request->get('s');

            $almacen_datos = Almacen::where('cod_empresa', session('cod_empresa'))
                ->where("almacen", "LIKE", '%'.$search.'%')
                ->orWhere("descripcion", "LIKE", '%'.$search.'%')
                ->orderBy('id', request('sorted', 'ASC'))
                ->paginate($pag);

        }else{


            $key = 'almacen.page.'.request('page', 1);
            $almacen_datos = Cache::rememberForever($key, function() use ($pag){
                return Almacen::where('cod_empresa', session('cod_empresa'))
                ->orderBy('id', request('sorted', 'ASC'))
                ->paginate($pag);

            });
        }

        $text_search = '';

        return view('almacen.almacen', compact('almacen_datos', 'permisos', 'text_search', 'productos', 'proyectos'));
      
    }

    public function menu_almacen(Request $request){ 

        if($request->get('cod_almacen')){
            $almacen = Almacen::findOrFail($request->get('cod_almacen'));
            $almacen = array('nombre'=>$almacen->almacen, 'direccion'=>$almacen->direccion);
            session(['cod_almacen'=> $request->get('cod_almacen'), 'almacen'=>$almacen ]);

        }

        $productos = Producto::count();
        
        //return view('almacen.menu_almacen');

        return view('web.home', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["productos"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        return view('almacen.create');
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
            'almacen'=>'required',
            //'cboEstado'=>'required',
        ]);

        $flag_costea = $request->input('flag_costea');

        DB::table('almacen')->insert([
            'almacen' => mb_strtoupper($request->input('almacen')),
            'descripcion' => $request->input('descripcion'),
            'direccion' => $request->input('direccion'),
            'cod_empresa'   => session('cod_empresa'),
            'flag_costea'   => $flag_costea,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);

        Cache::flush();
        alert()->success('Registro Grabado.','Mensaje');

        return redirect()->route('almacen.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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

        $almacen_datos = Almacen::findOrFail($id);

        return view('almacen.show', compact('almacen_datos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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

        $datos = Almacen::findOrFail($id);

        return view('almacen.edit',compact('datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $flag_costea = $request->input('flag_costea');

        DB::table('almacen')->where('id',$id)->update([
            'almacen' => mb_strtoupper($request->input('almacen')),
            'descripcion' => $request->input('descripcion'),
            'direccion' => $request->input('direccion'),
            'cod_empresa'   => session('cod_empresa'),
            'flag_costea'   => $flag_costea,
            'updated_at'=>Carbon::now()

        ]);

        Cache::flush();
        alert()->success('Registro Actualizado.','Mensaje');

        return redirect()->route('almacen.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["productos"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $n = Ingreso::where('cod_almacen', $id)->count();
        if($n > 0){
            alert()->warning('Almacén contiene documentos registrados','Eliminado');
            return back();
        }

        Almacen::where('id', $id)->delete();
        Cache::flush();
        alert()->error('Registro borrado.','Eliminado');
        return back();
    }

    public function eliminarVarios(Request $request)
    {   
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["productos"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            
            Almacen::where('id',$value)->delete();
        }

        
        Cache::flush();

        //alert()->error('Registros borrados.','Eliminado');
        //return redirect()->route('almacen.index');
        return back()->with('danger','Registros borrados.');
    }
}
