<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB, Carbon\Carbon;
use \App\Almacen, \App\Movimientos, App\Producto, App\Proyecto, App\CtaCorrientes;
use App\AccionesRolesPermisos;
use PDF;
//use Excel;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class SPController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function sp_stock()
    {
        $a = "1";
        $b = "1";
        $datos = DB::select("CALL sp_stock(".$a.",'$b')");
        dd($datos);
    }

    public function kardex_basic ()
    {
        

        $almacen_o = Almacen::where('id','=',session('cod_almacen'))->first();
        $almacen_d = Almacen::where('id','<>',session('cod_almacen'))->get();

        $numbers = '000';

        $numbers = $numbers[0];
        
        $fecha = Carbon::now()->format('Y-m-d');

        return view('kardex.index',compact( 'numbers', 'almacen_o', 'almacen_d'));

    }

    /* kardex sin valorizar */

    public function create()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ingresos"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(session('cod_empresa') == false){ return redirect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }

        $almacen_o = Almacen::where('id','=',session('cod_almacen'))->first();
        $almacen_d = Almacen::where('id','<>',session('cod_almacen'))->get();
        
        $fecha = Carbon::now()->format('Y-m-d');

        return view('kardex.create',compact( 'almacen_o', 'almacen_d'));
    }

    public function store(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ingresos"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(session('cod_empresa') == false){ return redionrect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }

        $cod_artic = $request->input('cod_artic');
        $prod_tipo = $request->input('prod_tipo');

        //dd("$cod_artic - $prod_tipo");

        if(!$prod_tipo){ return redirect()->route('kardex.create'); }
        
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin    = $request->input('fecha_fin');

        /*fechas*/
        $arrf = explode("/", $fecha_inicio);
        $fecha_inicio = $arrf[2]."-".$arrf[1]."-".$arrf[0];  
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', $fecha_inicio);

        $arrf = explode("/", $fecha_fin);
        $fecha_fin = $arrf[2]."-".$arrf[1]."-".$arrf[0];  
        $fecha_fin = Carbon::createFromFormat('Y-m-d', $fecha_fin);

        $itpo_doc = '01';
        $etpo_doc = '04';

        //if($prod_tipo == 0){
        if($prod_tipo == 2){

            $producto = Producto::where('cod_artic',$cod_artic)->count();
            if($producto==0){
                alert()->warning('El código del producto no existe', 'Alerta');
                return back();
            }

            $producto = Producto::where('cod_artic',$cod_artic)->first();
            $xcod_artic = $producto->cod_artic;
            $cod_sunat  = $producto->cod_sunat;
            $desc       = $producto->nombre;
            $tipo       = $producto->tipo_articulo;
            $key = 0;

            $datos = Movimientos::select('fecha')
                    ->where("nro_linea",'<>',0)
                    ->where('cod_artic', $cod_artic)
                    ->where(function ($query) use ($etpo_doc, $itpo_doc) {
                        $query->orWhere("tpo_doc",$etpo_doc);
                        $query->orWhere("tpo_doc",$itpo_doc);
                        })
                    ->where('cod_empresa', session('cod_empresa'))
                    ->where('cod_almacen', session('cod_almacen'))
                    ->whereDate('fecha','>=',$fecha_inicio)
                    ->whereDate('fecha','<=',$fecha_fin)
                    ->orderBy('fecha', request('sorted', 'asc'))
                    ->count();

            if($datos==0){
                alert()->warning('El Producto no tiene movimientos', 'Alerta');
                return back();
            }

            $articulos[$key] = $this->productKardex($producto,$key,$etpo_doc,$itpo_doc,$fecha_inicio, $fecha_fin);

        }else{

            $producto = Producto::join('movimientos as m', 'm.cod_artic','=','articulos.cod_artic')
                                    ->select('articulos.cod_artic', 'articulos.cod_sunat', 'articulos.nombre','articulos.tipo_articulo', 'articulos.cod_umedida')
                                    ->where('m.cod_almacen',session('cod_almacen'))
                                    ->distinct('articulos.cod_artic')
                                    ->orderBy('articulos.cod_artic')
                                    ->get();
            $kardex = array();
            $articulos = array();

            foreach ($producto as $key => $prod) {

                $xcod_artic = $prod->cod_artic;
                //$xcod_artic = 3;
                $cod_sunat  = $prod->cod_sunat;
                $desc       = $prod->nombre;
                $tipo       = $prod->tipo_articulo;

                $articulos[$key] = $this->productKardex($prod,$key,$etpo_doc,$itpo_doc,$fecha_inicio, $fecha_fin);
            }
        }

        return view('kardex.index', compact('articulos', 'producto'));
    }

    private function productKardex($prod, $key,$etpo_doc,$itpo_doc, $fecha_inicio, $fecha_fin){

        $dtpo_doc = '02';
        $ttpo_doc = '03';

        $kardex = array();
        $saldo = 0; $ecant = 0; $icant = 0;
                    $tot_i = 0; $tot_e = 0; $tot_s = 0;$scant = 0;$n=0;

        $xcod_artic = $prod->cod_artic;
        //$xcod_artic = 3;
        $cod_sunat  = $prod->cod_sunat;
        $desc       = $prod->nombre;
        /*$um         = DB::table('unidad_medida')->where('id',$prod->cod_umedida)
                        ->select('dsc_umedida','cod_umedida')
                        ->first();*/
        
        $um         = $this->cod_medida($prod->cod_umedida);

        $tipo       = $prod->tipo_articulo;

        $datos = Movimientos::
                select(
                    'movimientos.id',
                    'movimientos.fecha','movimientos.tpo_doc',
                    'movimientos.tipo_doc',
                    'movimientos.nro_doc',
                    'movimientos.cant_mov',
                    'movimientos.proyectos_id',
                    'movimientos.cta_cte', 'movimientos.cod_subalm_d'
                )
                ->where("movimientos.nro_linea",'<>',0)
                ->where('movimientos.cod_artic', $xcod_artic)
                ->whereDate('fecha','>=',$fecha_inicio)
                ->whereDate('fecha','<=',$fecha_fin)
                ->where(function ($query) use ($etpo_doc, $itpo_doc, $dtpo_doc, $ttpo_doc) {
                    $query->orWhere("movimientos.tpo_doc",$etpo_doc);
                    $query->orWhere("movimientos.tpo_doc",$itpo_doc);
                    $query->orWhere("movimientos.tpo_doc",$dtpo_doc);
                    $query->orWhere("movimientos.tpo_doc",$ttpo_doc);
                    })
                ->where('movimientos.cod_empresa', session('cod_empresa'))
                ->where('movimientos.cod_almacen', session('cod_almacen'))
                ->orderBy('movimientos.fecha', request('sorted', 'asc'))
                ->get();

        $kardex = array();

        if(count($datos) > 0)
        {

            $saldo = 0; $ecant = 0; $icant = 0;
            $tot_i  = 0; $tot_e = 0; $tot_s = 0;$scant = 0;$n=0;
            
            foreach($datos as $i => $d)
            {
                $cod_artic = $d->cod_artic;
                
                $xid       = $d->id;//$i+1;
                $n         = count($datos);
                $fecha     = $d->fecha;
                $fecha     = Carbon::parse($fecha)->format('d-m-Y');
                $tpo_doc   = $d->tpo_doc;
                $tipo_doc  = $d->tipo_doc. "/".$d->tpo_doc;
                $documento = $d->nro_doc;
                
                $icant     = $d->cant_mov;
                $ecant     = $d->cant_mov;
                $obs       = $d->razon_social;

                if($tpo_doc == '01' or $tpo_doc == '02'){
                    if($tpo_doc == '01'){
                        $clientes = CtaCorrientes::where('cod_ruc',$d->cta_cte)->first();
                        $obs      = $clientes->razon_social;
                    }else{
                        $proy   = Proyecto::findOrFail($d->proyectos_id);
                        $obs    = $proy->nom_proy;

                        $tipo_doc = 'DE'. "/".$d->tpo_doc;
                    }

                    $ecant = number_format(0,2);

                    if($saldo == 0 && $icant > 0 && $scant == 0){
                        // primera vez
                        $kardex[$i] = array(
                            'id'        => $xid,
                            'fecha'     => $fecha,
                            'tipo_doc'  => $tipo_doc,
                            'documento' => $documento,
                            'codigo'    => $cod_artic,
                            'icant'     => $icant,
                            'ecant'     => $ecant,
                            'scant'     => $icant,
                            'obs'       => $obs
                        );
                        $saldo = $icant;

                    }elseif($saldo > 0 && $icant > 0){

                        // agrega ingresos
                        $saldo = str_replace(',','',$saldo);
                        $icant = str_replace(',','',$icant);
                        $saldo = $saldo + $icant;
                        $saldo = number_format($saldo,2);

                        $kardex[$i] = array(
                            'id'        => $xid,
                            'fecha'     => $fecha,
                            'tipo_doc'  => $tipo_doc,
                            'documento' => $documento,
                            'codigo'    => $cod_artic,
                            'icant'     => $icant,
                            'ecant'     => $ecant,
                            'scant'     => $saldo,
                            'obs'       => $obs
                        );

                    }else{
                        // falta
                    }

                    $tot_i = str_replace(',','',$tot_i);
                    $tot_e = str_replace(',','',$tot_e);

                    $tot_i += $icant;
                    $tot_e += 0;
                    
                //}else{
                }
                if($tpo_doc == '04' or $tpo_doc == '03'){
                    if($tpo_doc == '04'){
                        $proy   = Proyecto::findOrFail($d->proyectos_id);
                        $obs    = $proy->nom_proy;
                        $tipo_doc = 'NS'. "/".$d->tpo_doc;
                    }else{
                        $proy   = Almacen::findOrFail($d->cod_subalm_d);
                        $obs    = $proy->almacen;
                        $tipo_doc = 'TR'. "/".$d->tpo_doc;
                    }

                    // agrega ingresos
                    $icant = number_format(0,2);
                    $ecant = str_replace(',','',$ecant);
                    $saldo = str_replace(',','',$saldo);
                    $saldo = $saldo - $ecant;
                    $saldo = number_format($saldo,2);

                    $kardex[$i] = array(
                        'id'        => $xid,
                        'fecha'     => $fecha,
                        'tipo_doc'  => $tipo_doc,
                        'documento' => $documento,
                        'codigo'    => $cod_artic,
                        'icant'     => $icant,
                        'ecant'     => $ecant,
                        'scant'     => $saldo,
                        'obs'       => $obs
                    );

                    $ecant = str_replace(',','',$ecant);
                    $tot_e = str_replace(',','',$tot_e);
                    $tot_e += $ecant;
                }
                $tot_i = str_replace(',','',$tot_i);
                $tot_e = str_replace(',','',$tot_e);
                $tot_i = number_format($tot_i,2);
                $tot_e = number_format($tot_e,2);
            }
        }
        

        return array(
            'id'        => $key + 1,
            'cod_artic' => $xcod_artic,
            'cod_sunat' => $cod_sunat,
            'desc'      => $desc,
            'um'        => $um,
            'tipo'      => $tipo,
            'data'      => $kardex,
            'tot_i'     => $tot_i,
            'tot_e'     => $tot_e
        );
    }

    /* kardex valorizado */
    public function create_va()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ingresos"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(session('cod_empresa') == false){ return redirect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }

        $almacen_o = Almacen::where('id','=',session('cod_almacen'))->first();
        $almacen_d = Almacen::where('id','<>',session('cod_almacen'))->get();
        
        $fecha = Carbon::now()->format('Y-m-d');

        return view('kardex.create_valorizado',compact( 'almacen_o', 'almacen_d'));
    }

    public function store_va(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ingresos"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

            if(session('cod_empresa') == false){ return redionrect('/'); }
            if(session('cod_almacen') == false){ return redirect('/'); }
            

            $cod_artic = $request->input('cod_artic');
            $prod_tipo = $request->input('prod_tipo');

            /*$ver = ($prod_tipo)?0:3;
            if($ver == 3){ return redirect()->route('kardex_va.create'); }*/
            
            $fecha_inicio = $request->input('fecha_inicio');
            $fecha_fin    = $request->input('fecha_fin');

            /*fechas*/
            $arrf = explode("/", $fecha_inicio);
            $fecha_inicio = $arrf[2]."-".$arrf[1]."-".$arrf[0];  
            $fecha_inicio = Carbon::createFromFormat('Y-m-d', $fecha_inicio);

            $arrf = explode("/", $fecha_fin);
            $fecha_fin = $arrf[2]."-".$arrf[1]."-".$arrf[0];  
            $fecha_fin = Carbon::createFromFormat('Y-m-d', $fecha_fin);


            $itpo_doc = '01';
            $dtpo_doc = '02';
            $etpo_doc = '04';
            $ttpo_doc = '03';

            // optFecha: 1: rango fechas 2: año
            // prod_tipo: 1: todos - 2: codigo
            $tipoFecha = $request->input('optFecha');
            // filtro por año:
            $fecha_ano = $request->input('ano');

            if($tipoFecha == 1 and $prod_tipo == 2){
                // por rango de fechas y codigo
                
                $producto = Producto::where('cod_artic',$cod_artic)->count();
                if($producto==0){
                    alert()->warning('El código del producto no existe', 'Alerta');
                    return back();
                }

                $producto = Producto::where('cod_artic',$cod_artic)->first();
                $xcod_artic = $producto->cod_artic;
                $cod_sunat  = $producto->cod_sunat;
                $desc       = $producto->nombre;
                $tipo       = $producto->tipo_articulo;
                $key = 0;

                $datos = Movimientos::select('fecha')
                        ->where("nro_linea",'<>',0)
                        ->where('cod_artic', $cod_artic)
                        ->whereDate('fecha','>=',$fecha_inicio)
                        ->whereDate('fecha','<=',$fecha_fin)
                        ->where(function ($query) use ($etpo_doc,$dtpo_doc, $itpo_doc, $ttpo_doc) {
                            $query->orWhere("tpo_doc",$etpo_doc);
                            $query->orWhere("tpo_doc",$itpo_doc);
                            $query->orWhere("tpo_doc",$ttpo_doc);
                            })
                        ->where('cod_empresa', session('cod_empresa'))
                        ->where('cod_almacen', session('cod_almacen'))
                        ->orderBy('fecha', request('sorted', 'asc'))
                        ->count();

                if($datos==0){
                    alert()->warning('El Producto no tiene movimientos en el rango de fecha indicada', 'Alerta')->persistent('Cerrar');
                    return back();
                }

                $articulos[$key] = $this->productKardex_valorizado($producto,$key,$etpo_doc,$dtpo_doc,$itpo_doc,$ttpo_doc, $fecha_inicio, $fecha_fin,$tipoFecha);
                
            }elseif($tipoFecha == 1 and $prod_tipo == 1){
                // por rango de fechas y Todos

                $producto = Producto::join('movimientos as m', 'm.cod_artic','=','articulos.cod_artic')
                            ->select('articulos.cod_artic', 'articulos.cod_sunat', 'articulos.nombre','articulos.tipo_articulo', 'articulos.cod_umedida')
                            ->where('m.cod_almacen',session('cod_almacen'))
                            ->distinct('articulos.cod_artic')
                            ->orderBy('articulos.cod_artic')
                            ->get();

                $kardex = array();
                $articulos = array();

                foreach ($producto as $key => $prod) {

                    $xcod_artic = $prod->cod_artic;
                    //$xcod_artic = 3;
                    $cod_sunat  = $prod->cod_sunat;
                    $desc       = $prod->nombre;
                    $tipo       = $prod->tipo_articulo;

                    $articulos[$key] = $this->productKardex_valorizado($prod,$key,$etpo_doc,$dtpo_doc,$itpo_doc,$ttpo_doc, $fecha_inicio, $fecha_fin,$tipoFecha);
                }
                
                
            }elseif($tipoFecha == 2 and $prod_tipo == 2 and !empty($cod_artic)){
                // por año y codigo
                $fecha_inicio = $fecha_ano;
                $fecha_fin    = $fecha_inicio;

                $producto = Producto::where('cod_artic',$cod_artic)->count();
                if($producto==0){
                    alert()->warning('El código del producto no existe', 'Alerta');
                    return back();
                }

                $producto = Producto::where('cod_artic',$cod_artic)->first();
                $xcod_artic = $producto->cod_artic;
                $cod_sunat  = $producto->cod_sunat;
                $desc       = $producto->nombre;
                $tipo       = $producto->tipo_articulo;
                $key = 0;

                $datos = Movimientos::select('fecha')
                        ->where("nro_linea",'<>',0)
                        ->where('cod_artic', $cod_artic)
                        //->whereDate('fecha','>=',$fecha_inicio)
                        ->whereYear('fecha', $fecha_inicio)
                        ->where(function ($query) use ($etpo_doc,$dtpo_doc, $itpo_doc, $ttpo_doc) {
                            $query->orWhere("tpo_doc",$etpo_doc);
                            $query->orWhere("tpo_doc",$itpo_doc);
                            $query->orWhere("tpo_doc",$ttpo_doc);
                            })
                        ->where('cod_empresa', session('cod_empresa'))
                        ->where('cod_almacen', session('cod_almacen'))
                        ->orderBy('fecha', request('sorted', 'asc'))
                        ->count();

                if($datos==0){
                    alert()->warning('El Producto no tiene movimientos en el rango de fecha indicada', 'Alerta')->persistent('Cerrar');
                    return back();
                }

                $articulos[$key] = $this->productKardex_valorizado($producto,$key,$etpo_doc,$dtpo_doc,$itpo_doc,$ttpo_doc, $fecha_inicio, $fecha_fin,$tipoFecha);

            }else{
                //and !empty($cod_artic)

                $fecha_inicio = $fecha_ano;
                $fecha_fin    = $fecha_inicio;

                // por año y Todos

                $producto = Producto::join('movimientos as m', 'm.cod_artic','=','articulos.cod_artic')
                            ->select('articulos.cod_artic', 'articulos.cod_sunat', 'articulos.nombre','articulos.tipo_articulo', 'articulos.cod_umedida')
                            ->where('m.cod_almacen',session('cod_almacen'))
                            ->whereYear('m.fecha', $fecha_inicio)
                            ->distinct('articulos.cod_artic')
                            ->orderBy('articulos.cod_artic')
                            ->get();

                $kardex = array();
                $articulos = array();

                foreach ($producto as $key => $prod) {

                    $xcod_artic = $prod->cod_artic;
                    //$xcod_artic = 3;
                    $cod_sunat  = $prod->cod_sunat;
                    $desc       = $prod->nombre;
                    $tipo       = $prod->tipo_articulo;

                    $articulos[$key] = $this->productKardex_valorizado($prod,$key,$etpo_doc,$dtpo_doc,$itpo_doc,$ttpo_doc, $fecha_inicio, $fecha_fin,$tipoFecha);
                }

            }

            return view('kardex.kardex_valorizado', compact('articulos', 'producto'));
            
    }

    private function productKardex_valorizado($prod, $key,$etpo_doc,$dtpo_doc,$itpo_doc,$ttpo_doc, $fecha_inicio, $fecha_fin, $tipoFecha=null){

        

        $saldo = 0; $ecant = 0; $ecosto = 0; $ecostot = 0;
                    $icant = 0; $icosto = 0; $icostot = 0;
                    $scant = 0;
                    $tot_i = 0; $tot_e = 0; $tot_s = 0;$n=0;
                    $tot_ecosto=0; $tot_icosto=0;$tot_scosto=0;

        $xcod_artic = $prod->cod_artic;
        //$xcod_artic = 3;
        $cod_sunat  = $prod->cod_sunat;
        $desc       = $prod->nombre;
        $tipo       = $prod->tipo_articulo;
        $um         = $this->cod_medida($prod->cod_umedida);

        $datos = Movimientos::
                select(
                    'movimientos.id',
                    'movimientos.fecha','movimientos.tpo_doc',
                    'movimientos.tipo_doc',
                    'movimientos.nro_doc',
                    'movimientos.cant_mov','movimientos.costo_mn','movimientos.cup_mn',
                    'movimientos.proyectos_id','movimientos.cta_cte', 'movimientos.cod_subalm_d'
                    //'c.razon_social',
                )
                ->where("movimientos.nro_linea",'<>',0)
                ->where('movimientos.cod_artic', $xcod_artic)
                ->whereYear('movimientos.fecha', $fecha_inicio)
                //->whereDate('movimientos.fecha','>=',$fecha_inicio)
                //->whereDate('movimientos.fecha','<=',$fecha_fin)
                ->where(function ($query) use ($etpo_doc, $itpo_doc, $ttpo_doc, $dtpo_doc) {
                    $query->orWhere("movimientos.tpo_doc",$etpo_doc);
                    $query->orWhere("movimientos.tpo_doc",$itpo_doc);
                    $query->orWhere("movimientos.tpo_doc",$ttpo_doc);
                    $query->orWhere("movimientos.tpo_doc",$dtpo_doc);
                    })
                ->where('movimientos.cod_empresa', session('cod_empresa'))
                ->where('movimientos.cod_almacen', session('cod_almacen'))
                ->orderBy('movimientos.fecha', request('sorted', 'asc'))
                ->orderBy('movimientos.id', request('sorted', 'asc'))
                ->get();

        $kardex = array();

        if(count($datos) > 0)
        {
            $saldo = 0; $ecant = 0; $ecosto = 0; $ecostot = 0; 
                        $icant = 0; $icosto = 0; $icostot = 0; 
                        $scant = 0; $scosto = 0; $scostot = 0; 

                        $tot_i = 0;    $tot_e = 0;    $tot_s = 0;    $n=0;
                        $tot_ecosto=0; $tot_icosto=0; $tot_scosto=0;
            
            foreach($datos as $i => $d)
            {
                $cod_artic = $xcod_artic;
                
                $xid       = $d->id;
                $n         = count($datos);
                $fecha     = $d->fecha;
                $fecha     = Carbon::parse($fecha)->format('d-m-Y');
                $tpo_doc   = $d->tpo_doc;
                $tipo_doc  = $d->tipo_doc. "/".$d->tpo_doc;
                $documento = $d->nro_doc;

                $icant     = str_replace(',','',$d->cant_mov);
                $icant     = number_format($icant,2);

                $icosto     = str_replace(',','',$d->costo_mn);
                $icosto    = number_format($icosto,4);
                $icostot   = number_format($d->costo_mn * $d->cant_mov,2);

                $ecant     = number_format($d->cant_mov,2);
                $ecosto    = number_format($d->costo_mn,4);
                $ecostot   = number_format($d->costo_mn * $d->cant_mov,2);

                //$obs       = $d->razon_social;

                if($tpo_doc == '01' or $tpo_doc == '02'){
                    if($tpo_doc == '01'){
                        $clientes = CtaCorrientes::where('cod_ruc',$d->cta_cte)->first();
                        $obs      = $clientes->razon_social;

                    }elseif($tpo_doc == '02'){
                        $proy   = Proyecto::findOrFail($d->proyectos_id);
                        $obs    = $proy->nom_proy;

                        $tipo_doc = 'DE'. "/".$d->tpo_doc;

                    }else{
                        $obs = ""; $tipo_doc = "";
                    }
                        

                    if($saldo == 0 && $icant > 0 && $scant == 0){
                        // primera vez
                        
                        $ecant   = number_format(0,2);
                        $ecosto  = number_format(0,4);
                        $ecostot = number_format(0,2);

                        $scant   = $icant;
                        $scosto  = $icosto;
                        $scostot = $icostot;
                        $saldo   = $icant;

                        $kardex[$i] = array(
                            'id'        => $xid,
                            'fecha'     => $fecha,
                            'tipo_doc'  => $tipo_doc,
                            'documento' => $documento,
                            'codigo'    => $cod_artic,
                            'icant'     => $icant,
                            'icosto'    => $icosto,
                            'icostot'   => $icostot,
                            'ecant'     => $ecant,
                            'ecosto'    => $ecosto,
                            'ecostot'   => $ecostot,
                            'scant'     => $icant,
                            'scosto'    => $scosto,
                            'scostot'   => $scostot,
                            'obs'       => $obs
                        );

                        
                        

                    }elseif($saldo > 0 && $icant > 0){
                        // agrega ingresos

                        $icant   = str_replace(',','',$icant);

                        if($tpo_doc == '02'){

                            $scosto  = str_replace(',','',$scosto);
                            $icostot = str_replace(',','',$icostot);
                            $icosto  = $scosto;
                            $icostot = $icant * $icosto;
                        }

                        $ecant   = number_format(0,2);
                        $ecosto  = number_format(0,4);
                        $ecostot = number_format(0,2);

                       
                        $scant   = str_replace(',','',$scant);
                        $scant   += $icant;
                        $scostot = str_replace(',','',$scostot);
                        $icostot = str_replace(',','',$icostot);
                        $scostot += $icostot;
                        $scosto  = $scostot / $scant;
                        
                        $saldo   = str_replace(',','',$saldo);
                        $saldo   += $icant;

                        // formatos
                        $scant   = number_format($scant,2);
                        $scostot = number_format($scostot,2);
                        $icostot = number_format($icostot,2);
                        $scosto  = number_format($scosto,4);
                        $saldo   = number_format($saldo,2);

                        $kardex[$i] = array(
                            'id'        => $xid,
                            'fecha'     => $fecha,
                            'tipo_doc'  => $tipo_doc,
                            'documento' => $documento,
                            'codigo'    => $cod_artic,
                            'icant'     => $icant,
                            'icosto'    => $icosto,
                            'icostot'   => $icostot,
                            'ecant'     => $ecant,
                            'ecosto'    => $ecosto,
                            'ecostot'   => $ecostot,
                            'scant'     => $scant,
                            'scosto'    => $scosto,
                            'scostot'   => $scostot,
                            'obs'       => $obs
                        );
                    }
                    $icant      = str_replace(',','',$icant);
                    $tot_i      = str_replace(',','',$tot_i);
                    $icostot    = str_replace(',','',$icostot);
                    $tot_icosto = str_replace(',','',$tot_icosto);
                    $tot_ecosto = str_replace(',','',$tot_ecosto);

                    $tot_i      += $icant;
                    $tot_icosto += $icostot;

                    $tot_e      += 0;
                    $tot_ecosto += 0;

                    $tot_s      += $icant;
                    $tot_scosto += $icostot;
                    
                }

                if($tpo_doc == '04' or $tpo_doc == '03'){
                    
                    if($tpo_doc == '03'){
                        $proy   = Almacen::findOrFail($d->cod_subalm_d);
                        $obs    = $proy->almacen;
                        $tipo_doc = 'TR'. "/".$d->tpo_doc;
                        
                    }elseif($tpo_doc == '04'){
                        $proy   = Proyecto::findOrFail($d->proyectos_id);
                        $obs    = $proy->nom_proy;
                        $tipo_doc = 'NS'. "/".$d->tpo_doc;

                    }else{
                        $obs = ""; $tipo_doc = "";
                    }
                    
                    // agrega egresos
                    $icant   = number_format(0,2);
                    $icosto  = number_format(0,4);
                    $icostot = number_format(0,2);

                    $ecosto  = str_replace(',', '', $scosto);
                    
                    $ecant   = str_replace('.00', '', $ecant);
                    //dd("$ecosto - $ecant");
                    $ecant   = intval($ecant);
                    $ecostot = $ecosto * $ecant;
                    $scant   = str_replace(',', '', $scant);
                    $scant   = $scant - $ecant;
                    $scostot = str_replace(',', '', $scostot) - $ecostot;
                    
                    if($scant == 0){
                        $scosto  = 0;
                    }else{
                        $scosto  = $scostot / $scant;
                    }

                    // formatos
                    $scosto  = number_format($scosto,4);
                    $ecant   = number_format($ecant,2);
                    $scant   = number_format($scant,2);

                    $ecostot = number_format($ecostot,2);
                    $scostot = number_format($scostot,2);

                    $kardex[$i] = array(
                        'id'        => $xid,
                        'fecha'     => $fecha,
                        'tipo_doc'  => $tipo_doc,
                        'documento' => $documento,
                        'codigo'    => $cod_artic,
                        'icant'     => $icant,
                        'icosto'    => $icosto,
                        'icostot'   => $icostot,
                        'ecant'     => $ecant,
                        'ecosto'    => $ecosto,
                        'ecostot'   => $ecostot,
                        'scant'     => $scant,
                        'scosto'    => $scosto,
                        'scostot'   => $scostot,
                        'obs'       => $obs
                    );

                    $ecant      = str_replace(',','',$ecant);
                    $tot_e      = str_replace(',','',$tot_e);
                    $ecostot    = str_replace(',','',$ecostot);
                    $tot_ecosto = str_replace(',','',$tot_ecosto);
                    $tot_icosto = str_replace(',','',$tot_icosto);

                    $tot_e      += $ecant;
                    $tot_ecosto += $ecostot;
                    $tot_icosto += 0;
                    //dd("$tot_icosto - $tot_ecosto"); //aqui suma todos pero no se muestra en el total

                }

                // sumatorias 
                $tot_i      = str_replace(',','',$tot_i);
                $tot_icosto = str_replace(',','',$tot_icosto);
                $tot_e      = str_replace(',','',$tot_e);
                $tot_ecosto = str_replace(',','',$tot_ecosto);

                $tot_i      = number_format($tot_i,2);
                $tot_icosto = number_format($tot_icosto,2);
                $tot_e      = number_format($tot_e,2);
                $tot_ecosto = number_format($tot_ecosto,2);
            }


        }
        return array(
            'id'        => $key + 1,
            'cod_artic' => $xcod_artic,
            'cod_sunat' => $cod_sunat,
            'desc'      => $desc,
            'um'        => $um,
            'tipo'      => $tipo,
            'data'      => $kardex,
            'tot_i'     => $tot_i,
            'tot_ecosto'=> $tot_ecosto,
            'tot_e'     => $tot_e,
            'tot_icosto'=> $tot_icosto,
            'tot_s'     => number_format($tot_s,2),
            'tot_scosto'=> number_format($tot_scosto,2)
        );
    }

    public function kardex_excel(Request $request)
    {
       
        $cod_artic = $request->input('cod_artic');
        
        $prod_tipo = 0;
        $cod_artic = 1;
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin    = $request->input('fecha_fin');


        $itpo_doc = '01';
        $etpo_doc = '04';

        return Excel::download(new UsersExport, 'kardex.xlsx');
        //return (new UsersExport)->download('products.tsv', \Maatwebsite\Excel\Excel::TSV);


        

        /*Excel::create('Participantes', function($excel) {
 
            //$estudiantes = Estudiante::all();
            $estudiantes = Producto::where('cod_artic',1)
            ->orderBy('cod_artic','asc')
            ->get();
            return ($estudiantes);

            //sheet -> nomb de hoja
            $excel->sheet('Estudiante', function($sheet) use($estudiantes) {
                //$sheet->fromArray($estudiantes); // muestra todos los campos
               
                foreach($estudiantes as $index => $estud) {
                    $sheet->row($index+2, [
                        $estud->cod_artic, $estud->cod_sunat, $estud->nombre
                    ]); 
                }
            });
        })->export('xlsx');*/




    }

    // codigo de Unid Medida
    private function cod_medida($cod_umedida){
        $um         = DB::table('unidad_medida')->where('id',$cod_umedida)
                        ->select('dsc_umedida','cod_umedida')
                        ->first();
        
        $um         = $um->cod_umedida;
        return $um;
    }


    
    
}
