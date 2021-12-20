<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use App\Producto;
use App\Salida;

use Auth;
use Cache;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {   
        if(session('cod_empresa') == false){ return redionrect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }
        
        $reportes_datos = null;
        return view('reportes.stock', compact('reportes_datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function herr_pendientes(Request $request)
    {
        if($request->get('pag')){
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }
        Cache::flush();

        $key = 'salidas.page.'.request('page', 1);
        $salidas_datos = Cache::rememberForever($key, function() use ($pag){
            return Salida::join('articulos as a', 'a.cod_artic','=','movimientos.cod_artic')
                ->select('movimientos.id','movimientos.nro_ref','movimientos.fecha',
                    'movimientos.created_at','movimientos.responsable','a.cod_sunat',
                    'a.nombre','movimientos.cant_mov',
                    'movimientos.doc_tipo','movimientos.doc_estado'
                )
                //ano_doc,mes_doc,nro_ref,proyectos_id,responsable,created_at
                ->where("movimientos.nro_linea",'<>',0)
                ->where("movimientos.tpo_doc",'04')
                ->where("movimientos.doc_tipo",'H')
                ->where("movimientos.doc_estado",'P')
                ->where('movimientos.cod_empresa', session('cod_empresa'))
                ->where('movimientos.cod_almacen', session('cod_almacen'))
                ->orderBy('movimientos.fecha', request('sorted', 'asc'))
                ->paginate($pag);
        });
        /*SELECT m.nro_ref,m.fecha,m.responsable,m.cod_artic,a.nombre,m.cant_mov,m.doc_estado FROM movimientos as m, articulos as a
        WHERE m.cod_empresa=a.cod_empresa and
              m.cod_artic=a.cod_artic and 
              m.responsable='Dany Navarro' and 
              m.doc_tipo='H' and 
              m.tpo_doc='04' and 
              m.nro_linea <> 0
              */

        return view('reportes.herr_pendientes', compact('salidas_datos', 'permisos'));
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function prueba(){
        //return view('reportes.prueba');
        $datos = Producto::all();
        dd($datos);
    }
}
