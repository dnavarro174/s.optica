<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CcostoController extends Controller
{

    public function index()
    {

        /*$x = "5,000.00";
        //$xx = str_replace(',', '', $x);
        $y = "2,000.00";
        //$yy = str_replace(',', '', $y);
        $c = str_replace(',', '', $x) + str_replace(',', '', $y);
        $d= number_format($c,2);
        dd("$c - $d");*/
    }

    public function create()
    {
        return view('kardex.create_calculodecostos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();//('1','2020','1','1','1','1');
        $cod_empresa = session('cod_empresa');
        $ano         = $request->input('fecha_inicio');
        $mes_ini     = $request->input('fecha_inicio');
        $mes_fin     = $request->input('fecha_fin');
        $artic_ini   = $request->input('cod_artic');
        $artic_fin   = $request->input('cod_artic_fin');
        $prod_tipo   = $request->input('prod_tipo');

        if($prod_tipo == 1){
            $rs = DB::select(DB::raw('SELECT min(cod_artic) as artic_ini, max(cod_artic) as artic_fin FROM articulos LIMIT 1'));
            $artic_ini = $rs[0]->artic_ini;
            $artic_fin = $rs[0]->artic_fin;
        }

        $array = explode("/",$ano);
        $ano     = $array[1];
        $mes_ini = $array[0];
        $mes_ini = str_replace('0','',$mes_ini);

        // mes_fin
        $array = explode("/",$mes_fin);
        $mes_fin = $array[0];
        $mes_fin = str_replace('0','',$mes_fin);

        //return "$cod_empresa - $ano - $mes_ini - $mes_fin - $artic_ini - $artic_fin";

        try {
            $data = DB::select('CALL sp_calculo_costos(?, ?, ?, ?, ?, ?)', array($cod_empresa, $ano, $mes_ini, $mes_fin, $artic_ini, $artic_fin));
            //$data = DB::select('CALL sp_stock(?,?)', array($mes_ini, $mes_fin));

            alert()->success('Realizado correctamente','Proceso')->persistent('Cerrar');
            return redirect()->route('calculo_costos.create');
            
        } catch (Exception $e) {
            return "Sucedio un error.";
        }
        //return $data;

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
}
