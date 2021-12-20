<?php

namespace App\Http\Controllers;
use App\AccionesRolesPermisos, App\TC;
use Auth, DB, Carbon\Carbon, Cache, Alert;

use Illuminate\Http\Request;

class TC_Controller extends Controller
{
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
            $permParam["modulo_alias"] = "productos";
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


        $text_search = $request->input('s');
        Cache::flush();

        if($request->get('s')){
            $search = $request->get('s');

            $tc_datos = TC::where("cod_moneda", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
            ->paginate($pag);

        }else{

            $key = 'tc.page.'.request('page', 1);
            $tc_datos = Cache::rememberForever($key, function() use ($pag){
                return TC::orderBy('id', request('sorted', 'DESC'))
                ->paginate($pag);
            });
        }

        return view('tc.index', compact('tc_datos', 'permisos', 'text_search'));
        
    }

    public function create(Request $request)
    {
        

        return view('tc.create');
    }

    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ctas_corrientes"]["permisos"]["mostrar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

      

        $cuenta_tipo = session('cuenta_tipo');
        //$datos = Categoria::where('id',$id)->first();
       
        $datos = TC::findOrFail($id);

        return view('tc.show', compact('datos', 'cuenta_tipo'));
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

        
        $cuenta_tipo = session('cuenta_tipo');
        $datos = TC::where('id',$id)->first();

        return view('tc.edit', compact('datos', 'cuenta_tipo'));
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

        /*DB::table('tipo_de_cambio')->where('id', $id)->update([
            'fecha'         => mb_strtoupper($request->input('fecha')),
            'TC_compra_mn'  => mb_strtoupper($request->input('fecha')),
            'TC_me'         => mb_strtoupper($request->input('fecha')),
        ]);*/

        $TC_compra_mn = trim($request->input('TC_compra_mn'));
        $TC_me = trim($request->input('TC_me'));

        $arrf = explode("/", $request->input('fecha'));
        $fecha = $arrf[2]."-".$arrf[1]."-".$arrf[0]; 

        DB::table('tipo_de_cambio')->insert([
                        'cod_moneda'    => 1,
                        'fecha'         => $fecha,
                        'TC_venta_mn'   => 0,
                        'TC_compra_mn'  => $TC_compra_mn,
                        'TC_me'         => $TC_me,
                        'fecha_hora'    => Carbon::now(),
                        'cod_usuario'   => \Auth::User()->id
                    ]);

        Cache::flush();
        alert()->success('Registro Modificado.','Mensaje');

        return redirect()->route('tc.index');
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
            TC::where('id',$value)->delete();
        }

        Cache::flush();

        alert()->error('Registros borrados.','Eliminado');
        //return redirect()->route('productos.index');
        return back();
    }





    public function tc_add($fecha,$tc_id)
    {
        $fecha = str_replace('-','/',$fecha);
       
        /*$fecha = strtotime($fecha);
        $fecha = date('d/m/Y', $fecha); */
        
        //$tc_id = 1;
        //$tipo_cambio = DB::table('tipo_de_cambio')->where('id', $tc_id)->first();

        //Actividade::find($actividad_id);


        //return view('tc.form', compact('fecha', 'tipo_cambio','tc_id' )); 
        return view('tc.form', compact('fecha','tc_id')); 
    }

    public function store(Request $request){
        //dd($request->all());

        try {

            $modo = $request->input('modo');

                $tc_id = $request->input('tc_id');
                $fecha = $request->input('fecha');
                $fecha2 = $request->input('fecha');
                $TC_compra_mn = trim($request->input('TC_compra_mn'));
                $TC_me = trim($request->input('TC_me'));

                if($tc_id==0){//NEW
                    $arrf = explode("/", $request->input('fecha'));
                    $fecha = $arrf[2]."-".$arrf[1]."-".$arrf[0];        

                    $f = Carbon::createFromFormat('Y-m-d', $fecha);
                    $f = Carbon::parse($f)->format('Y-m-d');

                    /*$actividad = new Actividade() ;
                    $actividad->fecha_desde = $fecha;
                    $actividad->save();*/

                    $existe = DB::table('tipo_de_cambio')->where(DB::raw("STR_TO_DATE(fecha, '%Y-%m-%d')"),$fecha)->count();
                    if($existe>0){
                        $rs = 0;
                        $monedas = "";
                        if($modo == 1){
                            alert()->warning("El tipo de cambio con la fecha: $fecha2 ya existe.",'Mensaje');
                            return redirect()->back();

                        }else{
                            return compact('rs','monedas');

                        }
                    }

                    $rs = 1;

                    $rs_a = DB::table('tipo_de_cambio')->insert([
                        'cod_moneda'    => 1,
                        'fecha'         => $fecha,
                        'TC_venta_mn'   => 0,
                        'TC_compra_mn'  => $TC_compra_mn,
                        'TC_me'         => $TC_me,
                        'fecha_hora'    => Carbon::now(),
                        'cod_usuario'   => \Auth::User()->id
                    ]);
                    $rs_a = DB::table('tipo_de_cambio')->insert([
                        'cod_moneda'    => 2,
                        'fecha'         => $fecha,
                        'TC_venta_mn'   => 0,
                        'TC_compra_mn'  => $TC_compra_mn,
                        'TC_me'         => $TC_me,
                        'fecha_hora'    => Carbon::now(),
                        'cod_usuario'   => \Auth::User()->id
                    ]);

                    $monedas = DB::table('monedas')->join('tipo_de_cambio', 'tipo_de_cambio.cod_moneda', '=', 'monedas.cod_moneda')
                        ->select('tipo_de_cambio.id', "monedas.cod_moneda","nom_moneda","TC_venta_mn","TC_compra_mn","TC_me", DB::raw(" DATE_FORMAT(fecha, '%d/%m/%Y') as fecha"))
                        ->where(DB::raw("STR_TO_DATE(fecha, '%Y-%m-%d')"),$f)
                        ->limit(2)
                        ->get();

                    if($modo == 1){
                        Cache::flush();
                        alert()->success('Registro Grabado.','Mensaje');

                        return redirect()->route('tc.index');

                    }else{
                        return compact('rs', 'monedas');
                    }


                }/*else{//UPDATE
                    $actividad = Actividade::find($actividad_id) ;
                    
                    $actividad->titulo = $request->input('titulo');
                    $actividad->subtitulo = $request->input('subtitulo');
                    $actividad->desc_actividad = $request->input('desc_actividad');
                    $actividad->desc_ponentes = $request->input('desc_ponentes');
                    $actividad->vacantes = $request->input('vacantes');


                    $actividad->hora_inicio = $request->input('hora_inicio');
                    $actividad->hora_final = $request->input('hora_final');
                    $actividad->ubicacion = $request->input('ubicacion');
                    $actividad->save();
                }*/

            }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 
    }
}
