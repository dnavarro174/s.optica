<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Cache;
use App\Ingreso;
use App\Producto;
use App\Movimientos, App\CtaCorrientes;
use App\AccionesRolesPermisos;
use Carbon\Carbon;

use Illuminate\Http\Request,
    App\Repositories\ProveedorRepositorie,
    App\Http\Requests;

class IngresosController extends Controller
{   
    private $_proveedorRepo;
    public function __construct(ProveedorRepositorie $proveeRepo)
    {
        $this->middleware('auth');
        Carbon::setLocale('es');
        $this->_proveedorRepo = $proveeRepo;
    }

    public function index(Request $request)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["ingresos"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(session('cod_empresa') == false){ return redirect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "ingresos";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

        $search = $request->input('s');
        $text_fecha = $request->input('m');

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }

        $tpo_doc = '01';

        if($text_fecha || $search){
            Cache::flush();

            if($text_fecha and !$search){
                // text_fecha
                $text_partido = explode('/',$text_fecha);

                $ingresos_datos = Ingreso::join('cuentas_corrientes as cta', 'cta.cod_ruc','=','movimientos.cta_cte')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc',
                        'movimientos.responsable', 'cta.razon_social',
                        'movimientos.cta_cte','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado',
                        'movimientos.tc_mo_me', 'movimientos.tc_mn_me', 'movimientos.costo_tot_mn', 'movimientos.costo_tot_me'
                    )
                                ->fecha($text_fecha)
                                ->where("movimientos.nro_linea",0)
                                ->where("movimientos.tpo_doc",$tpo_doc)
                                //->where("movimientos.ano_doc",$y)
                                //->where("movimientos.mes_doc",$m)
                                ->where('movimientos.cod_empresa', session('cod_empresa'))
                                ->where('movimientos.cod_almacen', session('cod_almacen'))
                                ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                                ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                                ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                                ->paginate($pag);

            }elseif(!$text_fecha and $search){
                // text_search

                $ingresos_datos = Ingreso::join('cuentas_corrientes as cta', 'cta.cod_ruc','=','movimientos.cta_cte')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc',
                        'movimientos.responsable', 'cta.razon_social',
                        'movimientos.cta_cte','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado',
                        'movimientos.tc_mo_me', 'movimientos.tc_mn_me', 'movimientos.costo_tot_mn', 'movimientos.costo_tot_me'
                    )
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("cta.razon_social", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.cta_cte", "LIKE", '%'.$search.'%');
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);

            }else{

                $ingresos_datos = Ingreso::join('cuentas_corrientes as cta', 'cta.cod_ruc','=','movimientos.cta_cte')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc',
                        'movimientos.responsable', 'cta.razon_social',
                        'movimientos.cta_cte','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado',
                        'movimientos.tc_mo_me', 'movimientos.tc_mn_me', 'movimientos.costo_tot_mn', 'movimientos.costo_tot_me'
                    )
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->fecha($text_fecha)
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("cta.razon_social", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.cta_cte", "LIKE", '%'.$search.'%');
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);
            }

        }else{

            $key = 'ingresos.page.'.request('page', 1);
            $ingresos_datos = Cache::rememberForever($key, function() use ($pag, $tpo_doc){
                return Ingreso::where("nro_linea",0)
                    ->where("tpo_doc",$tpo_doc)
                    ->where('cod_empresa', session('cod_empresa'))
                    ->where('cod_almacen', session('cod_almacen'))
                    ->orderBy('ano_doc', request('sorted', 'DESC'))
                    ->orderBy('mes_doc', request('sorted', 'DESC'))
                    ->orderBy('nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);
            });

        }

        $fechas = Ingreso::
                select(DB::raw("DATE_FORMAT(created_at, '%m-%Y') as fecha,DATE_FORMAT(created_at, '%m') as mes, DATE_FORMAT(created_at, '%Y') as ano"))
                ->distinct()
                ->orderBy('fecha','desc')
                ->get();

        return view('ingresos.ingresos', compact('ingresos_datos', 'permisos', 'search', 'fechas'));
    }

    public function stock(Request $request){
        
        $stockVal = DB::table('productos')
                ->select('stock')
                ->where('id',$request->id)
                ->get();
        $stock = $stockVal[0]->stock;
        if($stock > 0){
            if($stock >= $request->cant){
                /*$update = DB::table('productos')
                            ->where('id',$request->id)
                            ->decrement('stock', $request->cant);*/

                $stock_arr = array([
                    'stock'=> 2,
                    'stock_bd'=> $stock
                ]);
            }else{
                $stock_arr = array([
                    'stock'=> 1,
                    'stock_bd'=> $stock
                ]);
            }
        }else{
            $stock_arr = array([
                    'stock'=> 0,
                    'stock_bd'=> $stock
                ]);
        }

        return json_encode($stock_arr);
    }

    // print pdf comprobante
    public function comprobante(Request $request){
        
        $salidas = Ingreso::where('id','=', $request->id)->get();
        $nompdf = "orden-salida-".$salidas[0]->id.".pdf";
        //dd($salidas);
        $detalles = DB::table('salidas_detalle')
                    ->select('salidas_detalle.id', 'salidas_detalle.idproducto', 'productos.nombre', 'salidas_detalle.cantidad', 'salidas_detalle.descripcion')
                    ->leftJoin('productos', 'productos.id', '=','salidas_detalle.idproducto')
                    ->where('salidas_detalle.idsalida','=',$request->id)
                    ->orderBy('salidas_detalle.id','ASC')
                    ->get();

        //return PDF::loadView('reportes.comprobante', $data )->save('pdf/'.$codigoG.'.pdf')->stream($codigoG.'.pdf');
        $pdf = PDF::loadView('reportes.comprobante', compact( 'detalles', 'salidas'));

        return $pdf->download($nompdf);
        //return view('reportes.comprobante');
    }

    public function create()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["salidas"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        if(session('cod_empresa') == false){ return redirect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }

        $nro = DB::table('movimientos')
                ->where('tpo_doc','01')
                ->where('cod_almacen',session('cod_almacen'))
                ->whereNull('cod_cabecera')
                ->select('nro_doc')
                ->orderBy('id', 'desc')
                ->count();
        /*
        // Correlativo nro_doc por mes
        $nro = DB::table('movimientos')
                ->where('tpo_doc','01')
                ->where('mes_doc', '=', Carbon::now()->format('m'))
                ->select('nro_doc')
                ->orderBy('id', 'desc')
                ->count();*/

        if($nro==0){
            $nro_doc = 1;
        }else{
            $nro_doc = DB::table('movimientos')
                        ->where('tpo_doc','01')
                        ->where('cod_almacen',session('cod_almacen'))
                        ->whereNull('cod_cabecera')
                        ->select('id','nro_doc')
                        ->orderBy('id', 'desc')
                        ->limit(1)->first();
   
            /*$nro_doc = DB::table('movimientos')
                        ->where('tpo_doc','01')
                        ->where('mes_doc', '=', Carbon::now()->format('m'))
                        ->select('nro_doc')
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->first();*/

            $nro_doc =$nro_doc->nro_doc;
            $nro_doc = preg_replace('/^0+/', '', $nro_doc);

            $nro_doc = ($nro_doc == 0 )?1:$nro_doc+1;

        }


        $numbers = $this->generate_numbers($nro_doc, 1, 10);

        $numbers = $numbers[0];
        
        $fecha = Carbon::now()->format('Y-m-d');

        $monedas = DB::table('monedas')->join('tipo_de_cambio', 'tipo_de_cambio.cod_moneda', '=', 'monedas.cod_moneda')
                ->select('tipo_de_cambio.id', "monedas.cod_moneda","nom_moneda","TC_venta_mn","TC_compra_mn","TC_me", DB::raw(" DATE_FORMAT(fecha, '%d/%m/%Y') as fecha"))
                ->where(DB::raw("STR_TO_DATE(fecha, '%Y-%m-%d')"),$fecha)
                //->orderBy('tipo_de_cambio.fecha','desc')
                //->limit(2)
                ->get();

        $tipos    = DB::table('tipo_de_cambio')->orderBy('id','asc')->get();

        return view('ingresos.create',compact('monedas', 'tipos', 'numbers'));
    }

    public function generate_numbers($start, $count, $digits) {
       $result = array();
       for ($n = $start; $n < $start + $count; $n++) {
          $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);
       }
       return $result;
    }

    public function store(Request $request)
    {
        $nro_doc     = $request->input('nro_doc');
        $fecha_desde = $request->input('fecha_desde');
        $cod_almacen = session('cod_almacen');
        $cod_empresa = session('cod_empresa');
        $cod_moneda2  = $request->input('moneda');
        $tpo_doc      = '01';
        $tipo_doc     = $request->input('tipo_doc');
        $flag_pcosto   = 'N';
        $flag_valoriza = 'S';
        $serie_d       = '000';
        $cod_subalm    = 1;
        $flag_saldo    = 'N';
        $flag_mov      = 'M';
        $flag_ccosto   = 1;
        $flag_anulado  = 'N';
        $flag_facturado= 'N';

        ////////CABECERA
        $mov_cab = new Movimientos();
        $mov_cab->cod_empresa = $cod_empresa;
        $mov_cab->cod_almacen = $cod_almacen;

        // fecha
        $arrf = explode("/", $fecha_desde);
        $fecha = $arrf[2]."-".$arrf[1]."-".$arrf[0];  

        $fecha = Carbon::createFromFormat('Y-m-d', $fecha);
        //$y = Carbon::parse($fecha)->format('Y-m-d');
        //$fecha_desde = Carbon::parse($fecha_desde)->format('Y-m-d');
        $yy = Carbon::parse($fecha)->format('Y');
        $m = Carbon::parse($fecha)->format('m');

        // validar si existe el NRO_DOC y FECHA en los ingresos
        $origen = "INV";
        $e = Movimientos::where('nro_doc', $nro_doc)
            ->where('mes_doc', $m)
            ->where('ano_doc', $yy)
            ->where('origen', $origen)
            ->where("tpo_doc", $tpo_doc)
            ->where('cod_almacen',$cod_almacen)
            ->count();

        if($e > 0){

            return \Response::json(['error' => "El Nº de Documento ya existe.","tipo"=>1], 404); 
            exit;
        }
        
        $mov_cab->ano_doc = $yy;
        $mov_cab->mes_doc = $m;
        $mov_cab->fecha = $fecha;
        $mov_cab->tpo_doc = $tpo_doc;
        $mov_cab->tipo_doc = $tipo_doc;
        $mov_cab->flag_pcosto   = $flag_pcosto;
        $mov_cab->flag_valoriza = $flag_valoriza;
        $mov_cab->serie_d       = $serie_d;
        $mov_cab->cod_subalm    = $cod_subalm;
        $mov_cab->flag_saldo    = $flag_saldo;
        $mov_cab->flag_mov      = $flag_mov;
        $mov_cab->flag_ccosto   = $flag_ccosto;
        $mov_cab->flag_anulado  = $flag_anulado;
        $mov_cab->flag_facturado= $flag_facturado;

        $mov_cab->nro_linea = 0;
        $mov_cab->origen = "INV";

        /*$arrRef = explode("-", $request->input('referencia'));
        if(isset($arrRef[1])){
            $nroR = $arrRef[0];
        }else{
            $nroR = $request->input('referencia');
        }*/
        $nroR = mb_strtoupper($request->input('referencia'));

        //validar
        $rsVerif = DB::table("movimientos")
                    ->where("cta_cte", $request->input('cod_emp2'))
                    ->where("nro_ref", $nroR)
                    ->where("mes_doc", $m)
                    ->where("ano_doc",$yy)
                    ->where("tpo_doc", $tpo_doc)
                    ->first();

        if($rsVerif){
            return \Response::json(['error' => "Ya existe el código de referencia.","tipo"=>1], 404); 
            exit;                        
        }

        $mov_cab->nro_ref = $nroR;
        $nro_ref = $nroR;
        $mov_cab->flag_tipo = 2;
        $mov_cab->nro_doc = $nro_doc;
        $mov_cab->nro_preing = $request->input('nro_preing');
        
        $mov_cab->cod_usuario = \Auth::User()->id;
        $mov_cab->cta_cte = $request->input('cod_ruc2');
        
        $moneda = DB::table("tipo_de_cambio")->where("id", $cod_moneda2 )->first();
        if($moneda){
            $cod_moneda = $moneda->cod_moneda;
            $msoles = $moneda->TC_compra_mn;// siempre soles
            $mdolar = $moneda->TC_me;       // siempre dolares  
        }else{
            $cod_moneda = 2;
            $msoles     = 0;
            $mdolar     = 0;
        }

        $mov_cab->cod_moneda = $cod_moneda2;//$cod_moneda2
        $mov_cab->tc_mo_me = $msoles;
        $mov_cab->tc_mn_me = $mdolar;

        //totales
        $tot3_a = $request->input('tot3_a');
        $tot3_a = ($tot3_a > 0) ? $tot3_a : 0;

        if($cod_moneda==1){//SOLES
            $costo_tot_mo = $tot3_a;
            $costo_tot_mn = $tot3_a * $msoles;
            $costo_tot_me = $tot3_a / $mdolar;
        }elseif($cod_moneda==2){//DOLARES
            $costo_tot_mo = $tot3_a;
            $costo_tot_mn = $tot3_a * $mdolar;
            $costo_tot_me = $tot3_a * $msoles;
        }

        //return "cod_moneda: $cod_moneda - tot3: $tot3_a - msoles: $msoles - mdolar: $mdolar - costo_tot_mo: $costo_tot_mo - costo_tot_mn: $costo_tot_mn - costo_tot_me: $costo_tot_me";
        $mov_cab->costo_tot_mo = $costo_tot_mo ;
        $mov_cab->costo_tot_mn = $costo_tot_mn ;
        $mov_cab->costo_tot_me = $costo_tot_me ;

        $mov_cab->save();
        $idCab = $mov_cab->id;

        $y = 1;
        for ($x = 1 ; $x <= $request->input('tot_reg') ; $x++) {
            $cod_art = $request->input('cod_art_'.$x);
            if( isset($cod_art)  ) {
                //echo $cod_art."<br>";
                $un_med = $request->input('uni_med_'.$x);
                $cant = $request->input('cant_'.$x);
                $costoOrig = $request->input('costo_mn_'.$x);
                $costoTotOrig = $request->input('subto_'.$x);

                ////////DETALLE
                
                $mov_det = new Movimientos();
                $mov_det->nro_doc     = $nro_doc;
                $mov_det->cod_empresa = $cod_empresa;
                $mov_det->ano_doc = $yy;
                $mov_det->mes_doc = $m;
                $mov_det->tpo_doc = $tpo_doc;
                $mov_det->tipo_doc = $tipo_doc;
                $mov_det->nro_linea = $y;
                $mov_det->origen = "INV";
                // add
                $mov_det->cod_moneda  = $cod_moneda2;//$cod_moneda2;
                $mov_det->cod_almacen = $cod_almacen;
                $mov_det->fecha      = $fecha;
                //$mov_det->nro_ref  = $request->input('cod_emp2');nro_ref
                $mov_det->nro_ref    = $nro_ref;
                $mov_det->flag_tipo  = 2;
                $mov_det->nro_preing = $request->input('nro_preing');
                $mov_det->cta_cte    = $request->input('cod_ruc2');

                $mov_det->tc_mo_me = $msoles;
                $mov_det->tc_mn_me = $mdolar;
                $mov_det->flag_pcosto   = $flag_pcosto;
                $mov_det->flag_valoriza = $flag_valoriza;
                $mov_det->serie_d       = $serie_d;
                $mov_det->cod_subalm    = $cod_subalm;
                $mov_det->flag_saldo    = $flag_saldo;
                $mov_det->flag_mov      = $flag_mov;
                $mov_det->flag_ccosto   = $flag_ccosto;
                $mov_det->flag_anulado  = $flag_anulado;
                $mov_det->flag_facturado= $flag_facturado;

                $mov_det->cod_artic = $cod_art;
                $mov_det->cod_umedida = $un_med;

                if($cod_moneda==1){//SOLES
                    $costo_mo = $costoOrig;
                    $costo_mn = $costo_mo * $msoles;
                    $costo_me = $costo_mo / $mdolar;
                    $costo_tot_mo = $costoTotOrig; 
                    $costo_tot_mn = $cant * $costo_mn;
                    $costo_tot_me = $cant * $costo_me;
                }elseif($cod_moneda==2){//DOLARES
                    $costo_mo = $costoOrig;
                    $costo_mn = $costo_mo * $mdolar;
                    $costo_me = $costo_mo;
                    $costo_tot_mo = $costoTotOrig; 
                    $costo_tot_mn = $cant * $costo_mn;
                    $costo_tot_me = $cant * $costo_me;
                }
                
                $mov_det->cant_mov = $cant;
                $mov_det->cant_ump = $cant;
                $mov_det->cant_uma = 0;
                $mov_det->costo_mo = $costo_mo ;
                $mov_det->costo_mn = $costo_mn ;
                $mov_det->costo_me = $costo_me ;
                $mov_det->costo_tot_mo = $costo_tot_mo ;
                $mov_det->costo_tot_mn = $costo_tot_mn ;
                $mov_det->costo_tot_me = $costo_tot_me ;
                $mov_det->costo_me_ump = $costo_me ;
                $mov_det->costo_mn_ump = $costo_mn ;

                $mov_det->cod_cabecera = $idCab;

                $mov_det->cod_usuario = \Auth::User()->id;
                $mov_det->save();
                $y++;

                DB::table('articulos')
                    ->where('cod_artic', $cod_art)
                    ->increment('stock_total', $cant);

                $stock_det = DB::table('stock_articulos_alm')
                    ->where('cod_empresa', $cod_empresa)
                    ->where('cod_almacen', $cod_almacen)
                    ->where('cod_artic', $cod_art)
                    ->count();

                if($stock_det == 0){
                    DB::table('stock_articulos_alm')->insert([
                        'cod_empresa'   => $cod_empresa,
                        'cod_almacen'   => $cod_almacen,
                        'cod_artic'     => $cod_art,
                        'stock_alm'     => $cant
                    ]);
                }else{
                    DB::table('stock_articulos_alm')
                    ->where('cod_empresa', $cod_empresa)
                    ->where('cod_almacen', $cod_almacen)
                    ->where('cod_artic', $cod_art)
                    ->increment('stock_alm', $cant);
                }

                /*DB::table('stock_articulos_alm')->insert([
                    'cod_empresa'   => $cod_empresa,
                    'cod_almacen'   => $cod_almacen,
                    'cod_artic'     => $cod_art,
                    'stock_alm'     => $cant
                ]);*/

                /*$rs_art = DB::table('articulos')->where('cod_artic', $cod_art)->first();
                $stock_act = $rs_art->stock_total + $cant;
                // actualizar stock tb_articulos
                $update = DB::table('articulos')->where('cod_artic', $cod_art)
                            ->update([
                                'stock_total'   =>  $stock_act,
                            ]);*/

            }
        }

        Cache::flush();
        $rs = array('id' => $idCab, 'ok' => 'ok' );
        return $rs;//'ok'
        
    }

    /*public function validar_fecha($fecha){
        $valores = explode('/', $fecha);//12/08/2019
        $fec = $valores[2].'-'.$valores[1].'-'.$valores[0];

        return $fec;
    }*/

    public function show($id)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["salidas"]["permisos"]["mostrar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $datos = Ingreso::findOrFail($id);

        $d_fecha = Carbon::parse($datos->fecha)->format('Y-m-d');

        $productos = Producto::all();
        $monedas = DB::table('monedas')->join('tipo_de_cambio', 'tipo_de_cambio.cod_moneda', '=', 'monedas.cod_moneda')
                ->select('tipo_de_cambio.id', "monedas.cod_moneda","nom_moneda","TC_compra_mn","TC_me", DB::raw(" DATE_FORMAT(fecha, '%d/%m/%Y') as fecha"))
                ->where(DB::raw("STR_TO_DATE(tipo_de_cambio.fecha, '%Y-%m-%d')"), $d_fecha)
                ->orderBy('tipo_de_cambio.fecha','desc')
                //->limit(2)
                ->get();

        $tipos    = DB::table('tipo_de_cambio')->orderBy('id','asc')->get();

        $empresa =  DB::table('cuentas_corrientes')->where("id", $datos->cod_empresa)->first();

        $items =  DB::table('movimientos')->join('articulos', 'articulos.cod_artic', '=', 'movimientos.cod_artic')
                    ->join('unidad_medida', 'unidad_medida.id', '=', 'articulos.cod_umedida')
                    ->select("movimientos.id",
                        "cant_mov",
                        "costo_mo",
                        "costo_tot_mo",
                        "nombre",
                        "articulos.cod_artic",
                        "movimientos.cod_umedida",
                        DB::raw("unidad_medida.cod_umedida as unidadMedida"),
                        "cant_mov"
                    )
                    ->where("cod_cabecera", $datos->id)->get();

        return view('ingresos.show',compact('datos', 'productos', 'monedas', 'tipos', 'empresa', 'items'));


        //return view('ingresos.show',compact('datos', 'productos', 'detalle'));
    }


    public function edit($id)
    { 
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["salidas"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }
        if(session('cod_empresa') == false){ return redirect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }


        $datos = DB::table("movimientos")->find($id);
        if(!$datos){
            return redirect()->route('ingresos.index');
        }

        $d_fecha = Carbon::parse($datos->fecha)->format('Y-m-d');

        $productos = Producto::all();
        $monedas = DB::table('monedas')->join('tipo_de_cambio', 'tipo_de_cambio.cod_moneda', '=', 'monedas.cod_moneda')
                ->select('tipo_de_cambio.id', "monedas.cod_moneda","nom_moneda","TC_compra_mn","TC_me", DB::raw(" DATE_FORMAT(fecha, '%d/%m/%Y') as fecha"))
                ->where(DB::raw("STR_TO_DATE(tipo_de_cambio.fecha, '%Y-%m-%d')"), $d_fecha)
                ->orderBy('tipo_de_cambio.fecha','desc')
                //->limit(2)
                ->get();

        $tipos    = DB::table('tipo_de_cambio')->orderBy('id','asc')->get();

        $empresa =  DB::table('cuentas_corrientes')->where("cod_ruc", $datos->cta_cte)->first();


        $items =  DB::table('movimientos')->join('articulos', 'articulos.cod_artic', '=', 'movimientos.cod_artic')
                    ->join('unidad_medida', 'unidad_medida.id', '=', 'articulos.cod_umedida')
                    ->select("movimientos.id",
                        "cant_mov",
                        "costo_mo",
                        "costo_tot_mo",
                        "nombre",
                        "articulos.cod_artic",
                        "movimientos.cod_umedida",
                        DB::raw("unidad_medida.cod_umedida as unidadMedida"),
                        "cant_mov"
                    )
                    ->where("cod_cabecera", $datos->id)->get();

        return view('ingresos.edit',compact('datos', 'productos', 'monedas', 'tipos', 'empresa', 'items'));
    }

    public function update(Request $request, $idCab)
    {
        $nro_doc     = $request->input('nro_doc');
        $fecha_desde = $request->input('fecha_desde');

        $cod_almacen = session('cod_almacen');
        $cod_empresa = session('cod_empresa');
        $cod_moneda2 = $request->input('moneda');
        $tpo_doc = '01';
        $tipo_doc     = $request->input('tipo_doc');
        $flag_pcosto   = 'N';
        $flag_valoriza = 'S';
        $serie_d       = '000';
        $cod_subalm    = 1;
        $cod_subalm    = 1;
        $flag_saldo    = 'N';
        $flag_mov      = 'M';
        $flag_ccosto   = 1;
        $flag_anulado  = 'N';
        $flag_facturado= 'N';

        ////////CABECERA
        $mov_cab = Movimientos::find($idCab);
        $mov_cab->cod_empresa = $cod_empresa;
        $mov_cab->cod_almacen = $cod_almacen;

        // fecha
        $arrf = explode("/", $fecha_desde);
        $fecha = $arrf[2]."-".$arrf[1]."-".$arrf[0];  

        $fecha = Carbon::createFromFormat('Y-m-d', $fecha);
        //$y = Carbon::parse($fecha)->format('Y-m-d');
        //$fecha_desde = Carbon::parse($fecha_desde)->format('Y-m-d');
        $yy = Carbon::parse($fecha)->format('Y');
        $m  = Carbon::parse($fecha)->format('m');

        // validar si existe el NRO_DOC y FECHA en los ingresos

        $origen = "INV";
        $e = Movimientos::where('nro_doc', $nro_doc)
            ->where('id','<>',$idCab)
            ->where('cod_cabecera','<>',$idCab)
            ->where('mes_doc', $m)
            ->where('ano_doc', $yy)
            ->where('origen', $origen)
            ->where('tpo_doc',$tpo_doc)
            ->where('cod_almacen',$cod_almacen)
            ->count();


        if($e > 0){
            //return "nro_doc: $nro_doc - id: $idCab - cod_cabecera: $idCab - mes_doc: $m - ano_doc: $yy - origen: $origen";
            return \Response::json(['error' => "El documento ya existe.","tipo"=>1], 404); 
            exit;
        }

        $mov_cab->ano_doc = $yy;
        $mov_cab->mes_doc = $m;
        $mov_cab->fecha = $fecha;
        $mov_cab->tpo_doc = $tpo_doc;
        $mov_cab->tipo_doc = $tipo_doc;
        $mov_cab->flag_pcosto   = $flag_pcosto;
        $mov_cab->flag_valoriza = $flag_valoriza;
        $mov_cab->serie_d       = $serie_d;
        $mov_cab->cod_subalm    = $cod_subalm;
        $mov_cab->flag_saldo    = $flag_saldo;
        $mov_cab->flag_mov      = $flag_mov;
        $mov_cab->flag_ccosto   = $flag_ccosto;
        $mov_cab->flag_anulado  = $flag_anulado;
        $mov_cab->flag_facturado= $flag_facturado;

        $mov_cab->nro_linea = 0;
        $mov_cab->origen = "INV";

        /*$arrRef = explode("-", $request->input('referencia'));
        if(isset($arrRef[1])){
            $nroR = $arrRef[0];
        }else{
            $nroR = $request->input('referencia');
        }*/

        $nroR = mb_strtoupper($request->input('referencia'));
        
        $mov_cab->nro_ref = $nroR;
        $nro_ref = $nroR;
        $mov_cab->flag_tipo = 2;
        $mov_cab->nro_doc = $nro_doc;
        $mov_cab->nro_preing = $request->input('nro_preing');
        
        $mov_cab->cod_usuario = \Auth::User()->id;
        $mov_cab->cta_cte = $request->input('cod_ruc2');
        
        $moneda = DB::table("tipo_de_cambio")->where("id", $cod_moneda2)->first();
        if($moneda){
            $cod_moneda = $moneda->cod_moneda;
            $msoles = number_format($moneda->TC_compra_mn,2);// siempre soles
            $mdolar = number_format($moneda->TC_me,2);       // siempre dolares  
        }else{
            $cod_moneda = 2;
            $msoles     = 0;
            $mdolar     = 0;
        }

        $mov_cab->cod_moneda = $cod_moneda2;//$cod_moneda2
        $mov_cab->tc_mo_me = $msoles;
        $mov_cab->tc_mn_me = $mdolar;

        //totales
        //$tot3_aa = $request->input('tot3_a');
        $tot3_a =  $request->input('tot3_a');
        $tot3_a =  str_replace(',', '', $tot3_a);
        //$xa = $tot3_a * $msoles;
        //return $tot3_aa ."==".$tot3_a . " -- ".$msoles. "----".$xa;
        $tot3_a = ($tot3_a > 0) ? $tot3_a : 0;

        if($cod_moneda==1){//SOLES
            $costo_tot_mo = $tot3_a;
            $costo_tot_mn = $tot3_a * $msoles;
            $costo_tot_me = $tot3_a / $mdolar;
        }elseif($cod_moneda==2){//DOLARES
            $costo_tot_mo = $tot3_a;
            $costo_tot_mn = $tot3_a * $mdolar;
            $costo_tot_me = $tot3_a * $msoles;
        }

        //return "cod_moneda: $cod_moneda - tot3: $tot3_a - msoles: $msoles - mdolar: $mdolar - costo_tot_mo: $costo_tot_mo - costo_tot_mn: $costo_tot_mn - costo_tot_me: $costo_tot_me";
        $mov_cab->costo_tot_mo = $costo_tot_mo ;
        $mov_cab->costo_tot_mn = $costo_tot_mn ;
        $mov_cab->costo_tot_me = $costo_tot_me ;
        $mov_cab->save();

        // verifica si tiene detalles el documento

        $rs_prod = Movimientos::where("cod_cabecera",$idCab)
                    ->select('nro_doc','nro_ref','cod_artic', 'cant_mov')->get();

        $va   = array();
        $va2  = array();
        $vcan = array();
        $v_eliminados = 0;

        $tot_reg = $request->input('tot_reg');
        
        // Actualizar stock

        $this->actualizarStock($rs_prod,$request,$cod_empresa,$cod_almacen);

        //borra para rehacer
        Movimientos::where("cod_cabecera",$idCab)->delete();

        $y = 1;
        for ($x = 1 ; $x <= $request->input('tot_reg') ; $x++) {
            $cod_art = $request->input('cod_art_'.$x);
            if( isset($cod_art)  ) {
                //echo $cod_art."<br>";
                $un_med = $request->input('uni_med_'.$x);
                $cant = $request->input('cant_'.$x);
                $cant = str_replace(',', '', $cant);
                $costoOrig = $request->input('costo_mn_'.$x);

                $costoOrig = str_replace(',', '', $costoOrig);
                $costoTotOrig = $request->input('subto_'.$x);
                $costoTotOrig = str_replace(',', '', $costoTotOrig);

                ////////DETALLE
                
                $mov_det = new Movimientos();
                $mov_det->nro_doc     = $nro_doc;
                $mov_det->cod_moneda  = $cod_moneda2;//$cod_moneda2
                $mov_det->cod_empresa = $cod_empresa;
                $mov_det->ano_doc = $yy;
                $mov_det->mes_doc = $m;
                $mov_det->tpo_doc = $tpo_doc;
                $mov_det->tipo_doc = $tipo_doc;
                $mov_det->nro_linea = $y;
                $mov_det->origen = "INV";
                $mov_det->cod_almacen = $cod_almacen;
                $mov_det->fecha = $fecha;
                //$mov_det->nro_ref = $request->input('cod_emp2');
                $mov_det->nro_ref    = $nro_ref;
                $mov_det->flag_tipo  = 2;
                $mov_det->nro_preing = $request->input('nro_preing');
                $mov_det->cta_cte = $request->input('cod_ruc2');

                $mov_det->tc_mo_me = $msoles;
                $mov_det->tc_mn_me = $mdolar;
                $mov_det->flag_pcosto   = $flag_pcosto;
                $mov_det->flag_valoriza = $flag_valoriza;
                $mov_det->serie_d       = $serie_d;
                $mov_det->cod_subalm    = $cod_subalm;
                $mov_det->flag_saldo    = $flag_saldo;
                $mov_det->flag_mov      = $flag_mov;
                $mov_det->flag_ccosto   = $flag_ccosto;
                $mov_det->flag_anulado  = $flag_anulado;
                $mov_det->flag_facturado= $flag_facturado;
        
                $mov_det->cod_artic = $cod_art;
                $mov_det->cod_umedida = $un_med;
 
                if($cod_moneda==1){//SOLES
                    $costo_mo = $costoOrig;
                    $costo_mn = $costo_mo * $msoles;
                    $costo_me = $costo_mo / $mdolar;
                    $costo_tot_mo = $costoTotOrig; 
                    $costo_tot_mn = $cant * $costo_mn;
                    $costo_tot_me = $cant * $costo_me;
                }elseif($cod_moneda==2){//DOLARES
                    $costo_mo = $costoOrig;
                    $costo_mn = $costo_mo * $mdolar;
                    $costo_me = $costo_mo;
                    $costo_tot_mo = $costoTotOrig; 
                    $costo_tot_mn = $cant * $costo_mn;
                    $costo_tot_me = $cant * $costo_me;
                }
                
                $mov_det->cant_mov = $cant;
                $mov_det->cant_ump = $cant;
                $mov_det->cant_uma = 0;
                $mov_det->costo_mo =$costo_mo ; //(float) 
                $mov_det->costo_mn = $costo_mn ;
                $mov_det->costo_me = $costo_me ;
                $mov_det->costo_tot_mo = floatval($costo_tot_mo)??0 ;
                $mov_det->costo_tot_mn = $costo_tot_mn ;
                $mov_det->costo_tot_me = $costo_tot_me ;
                $mov_det->costo_me_ump = $costo_me ;
                $mov_det->costo_mn_ump = $costo_mn ;

                $mov_det->cod_cabecera = $idCab;

                $mov_det->cod_usuario = \Auth::User()->id;
                $mov_det->save();
                $y++;
        
            }
        }

        Cache::flush();

        $rs = array('id' => $idCab, 'ok' => 'ok' );
        return $rs;//'ok'
    }
   
    public function eliminarVarios(Request $request)
    {   
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["salidas"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        
        foreach ($tipo_doc as $value) {
            
            Ingreso::where('id',$value)->delete();

            // descontar estos productos en la tb. articulos
            $rs_prod = Movimientos::where("cod_cabecera",$value)
                        ->select('nro_doc','nro_ref','cod_artic', 'cant_mov')->get();

            foreach ($rs_prod as $pp) {
                $id_art_reg = $pp->cod_artic;
                $cant_reg   = $pp->cant_mov;

                // hacer sql que vea x el tpo_doc: 04 y el prod ç
                // si tiene salida un prod no permita eliminar
                
                $a = Movimientos::where('cod_artic',$value)
                        ->count();

                if($a>0){
                    alert()->warning('Artículo con documentos asociados','Mensaje');
                    return back();
                }
                Producto::where('cod_artic',$value)
                        ->delete();

                DB::table('stock_articulos_alm')
                            ->where('cod_empresa', session('cod_empresa'))
                            ->where('cod_almacen', session('cod_almacen'))
                            ->where('cod_artic', $id_art_reg)
                            ->decrement('stock_alm', $cant_reg);               
            }
            
            Ingreso::where('cod_cabecera',$value)->delete();
        }

        
        Cache::flush();

        alert()->error('Registros borrados.','Eliminado');
        return back();
    }

    public function buscarProveedor(Request $request)
    {
        return $this->_proveedorRepo->findByName($request->input('q'));
    }

    public function buscarCliente(Request $request)
    {
        return $this->_proveedorRepo->findByNameClient($request->input('q'));
    }

    public function buscarProyecto(Request $request)
    {
        return $this->_proveedorRepo->findByNameProy($request->input('q'));
    }

    public function findLaboratorio(Request $request)
    {
        return $this->_proveedorRepo->findByNameLaboratorio($request->input('q'));
    }

    public function check_nrodoc(Request $request){
        $nro_doc        = trim($request->input('nro_doc'));
        $fecha_desde    = $request->input('f_desde');
        $tipo           = $request->input('tipo');

        $f = Carbon::createFromFormat('d/m/Y', $fecha_desde);
        $f    = Carbon::parse($f)->format('Y-m-d');
        $m    = Carbon::parse($f)->format('m');
        //return $fecha_desde .'--'.$f .'-'.$m;

        $monedas = DB::table('monedas')->join('tipo_de_cambio', 'tipo_de_cambio.cod_moneda', '=', 'monedas.cod_moneda')
                ->select('tipo_de_cambio.id', "monedas.cod_moneda","nom_moneda","TC_venta_mn","TC_compra_mn","TC_me", DB::raw(" DATE_FORMAT(fecha, '%d/%m/%Y') as fecha"))
                ->where(DB::raw("STR_TO_DATE(fecha, '%Y-%m-%d')"),$f)
                ->limit(2)
                ->get();


        $nro_doc = Movimientos::where('nro_doc', $nro_doc)->where('mes_doc',$m)->count();

        return compact('nro_doc', 'monedas', 'tipo');
    }

    public function prov_add($fecha,$tc_id)
    {
        return view('ingresos.add_proveedor', compact('tc_id')); 
    }

    public function storeProv(Request $request){

        try {

            $tc_id = $request->input('tc_id');
            $cod_ruc = trim($request->input('cod_ruc'));
            $razon_social = $request->input('razon_social');
            $contacto_1 = $request->input('contacto_1');
            $tele = $request->input('tele');

            $v = substr($cod_ruc,0,2);
            //dd($v);
            if($v=="10"){
                $tipo_persona = "01";
                $tipo_docum   = "N";
            }elseif($v=="20"){
                $tipo_persona = "02";
                $tipo_docum   = "J";
            }else{
                return \Response::json(['error' => "Error: El RUC debe iniciar con 10 o 20" ], 404); 
            }

                if($tc_id==0){//NEW

                    $n = CtaCorrientes::where('cod_ruc', $cod_ruc)->count();
                    if($n > 0){
                        return \Response::json(['error' => "El proveedor con RUC: $cod_ruc ya existe." ], 404); 
                    }

                    $actividad = new CtaCorrientes() ;
                    $actividad->cod_empresa = 1;//$request->session()->get('cod_empresa');
                    $actividad->cod_usuario = \Auth::User()->id;
                    $actividad->cod_ruc = $cod_ruc;
                    $actividad->razon_social = $razon_social;
                    $actividad->contacto_1 = $contacto_1;
                    $actividad->tele = $tele;
                    $actividad->flag_tipo = 2;
                    $actividad->tipo_persona = $tipo_persona;
                    $actividad->tipo_docum = $tipo_docum;
                    $actividad->fecha_hora = Carbon::now();
                    $actividad->created_at = Carbon::now();
                    $actividad->updated_at = Carbon::now();
                    $actividad->save();

                    $rs = array('cod_ruc' => $cod_ruc, 'razon_social' => $razon_social, 'ok' => 'ok' );
                    return $rs;
                }

                $rs = array('cod_ruc' => $cod_ruc, 'razon_social' => $razon_social, 'ok' => 'no' );
                return $rs;

            }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 
    }

    private function actualizarStock($products,$request,$cod_empresa,$cod_almacen){
        $productosn=array();
        if(count($products)>0){
            foreach ($products as $pp) {
                $id_art_reg = $pp->cod_artic;
                $cant_reg   = $pp->cant_mov;
                $cant_reg = floatval($cant_reg)??0;
                $productosn[$id_art_reg]=array(
                "cant_reg"=>$cant_reg,
                "nuevo"=>false,
                "borrado"=>true,
                "cant_new"=>0
                );
            }
        }
        for ($x = 1 ; $x <= $request->input('tot_reg') ; $x++) {
            $id_art_new = $request->input('cod_art_'.$x);
            $cant_new   = $request->input('cant_'.$x);
            $cant_new   = str_replace(',', '', $cant_new);
            $productor = $productosn[$id_art_new]??false;
            $cant_reg = $productor ? $productor["cant_reg"]:0;
            $nuevo = !$productor ? true : false;
            $cant_reg = floatval($cant_reg)??0;$cant_new = floatval($cant_new)??0;
            //echo $id_art_new,"'''''---";
            $productosn[$id_art_new]=array(
                "cant_reg"=>$cant_reg,
                "nuevo"=>$nuevo,
                "borrado"=>false,
                "cant_new"=>$cant_new
            );
        }
        $n = count($productosn);
        if($n>0){
            foreach($productosn as $cod=>$pro){
                $cant_reg = $pro["cant_reg"];
                $nuevo = $pro["nuevo"];
                $borrado = $pro["borrado"];
                $cant_new = $pro["cant_new"];
                if($nuevo){//Si es nuevo
                    $n_stock = DB::table('stock_articulos_alm')
                    ->where('cod_empresa', $cod_empresa)
                    ->where('cod_almacen', $cod_almacen)
                    ->where('cod_artic', $cod)
                    ->count();

                    if($n_stock>0){
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen)
                        ->where('cod_artic', $cod)
                        ->increment('stock_alm', $cant_new);

                    }else{
                        DB::table('stock_articulos_alm')->insert([
                            'cod_empresa'   => $cod_empresa,
                            'cod_almacen'   => $cod_almacen,
                            'cod_artic'     => $cod,
                            'stock_alm'     => $cant_new
                        ]);
                    }
                }elseif($borrado){//Si se borra
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen)
                        ->where('cod_artic', $cod)
                        ->decrement('stock_alm', $cant_reg);
                }else{//si se modifica la cantidad del producto
                    if($cant_reg > $cant_new){
                        DB::table('stock_articulos_alm')
                            ->where('cod_empresa', $cod_empresa)
                            ->where('cod_almacen', $cod_almacen)
                            ->where('cod_artic', $cod)
                            ->decrement('stock_alm', $cant_reg - $cant_new);
                    }
                    if($cant_reg < $cant_new){
                        DB::table('stock_articulos_alm')
                            ->where('cod_empresa', $cod_empresa)
                            ->where('cod_almacen', $cod_almacen)
                            ->where('cod_artic', $cod)
                            ->increment('stock_alm', $cant_new - $cant_reg);
                    }                    
                }
            }
        }
        return true;         
    }

    /*end*/
}
