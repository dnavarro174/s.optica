<?php

namespace App\Http\Controllers;

use App\Comprobante;
use App\Comprobante_Det;
use App\Venta;

use DB;
use Auth;
use Cache;
use App\Producto;
use App\AccionesRolesPermisos;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;

use App\Movimientos, App\Proyecto;
use App\Salida;

use Illuminate\Http\Request,
    App\Repositories\ProductRepositorie,
    App\Http\Requests;

class VentasController extends Controller
{
    private $_prodRepo;

    public function __construct(ProductRepositorie $prodRepo)
    {
        $this->middleware('auth');
        Carbon::setLocale('es');
        $this->_prodRepo = $prodRepo;
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
        $text_fecha  = $request->input('m');
        $estado      = $request->input('sta');
        // falta validar estado

        if($request->get('pag')){
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }
        DB::enableQueryLog();
        $tpo_doc = '04';

        Cache::flush();
        $rs = Comprobante::join('cuentas_corrientes as c', 'c.cod_ruc', '=', 'comprobantes.cod_cliente')
            ->select('comprobantes.id', 'tpo_doc', 'serie_doc',
                'nro_doc', 'cod_cliente', 'igv_total','total',
                'descuento_valor', 'descuento_porc','forma_pago','monto',
                'observacion', 'comprobantes.created_at',
                'c.razon_social', 'comprobantes.tpo_com'
            );
        if ($text_fecha) $rs->fecha($text_fecha);
        if ($search)$rs->where(function ($query) use ($search) {
            $query->where("c.razon_social", "LIKE", '%'.$search.'%')
                ->orWhere("nro_doc", "LIKE", '%'.$search.'%');
        });
        //if($estado)$rs->where("movimientos.doc_estado",$estado);
        //$rs->where("tpo_doc", $tpo_doc)
        //->where('cod_empresa', session('cod_empresa'))
        //->where('movimientos.cod_almacen', session('cod_almacen'))
        //->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
        //->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
        $rs->orderBy('comprobantes.id', request('sorted', 'DESC'));
        $ventas_datos = $rs->paginate($pag);

        $fechas = Comprobante::
        select(DB::raw("DATE_FORMAT(created_at, '%m-%Y') as fecha,DATE_FORMAT(created_at, '%m') as mes, DATE_FORMAT(created_at, '%Y') as ano"))
            ->distinct()
            ->orderBy('fecha','asc')
            ->get();
        //->pluck('created_at');
        $forma_pagos = ['EFECTIVO','CHEQUE',4=>'TARJETA DE CRÉDITO',6=>'TRANSFERENCIA'];


        return view('ventas.ventas', compact('ventas_datos', 'permisos', 'search', 'fechas','text_fecha','forma_pagos'));
    }
    public function index2(Request $request)
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
        $text_fecha  = $request->input('m');
        $estado      = $request->input('sta');
        // falta validar estado

        if($request->get('pag')){
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }
        DB::enableQueryLog();
        $tpo_doc = '04';

        Cache::flush();
        $rs = Salida::join('proyectos as p', 'p.id', '=', 'movimientos.proyectos_id')
            ->select('movimientos.id', 'movimientos.ano_doc', 'movimientos.mes_doc',
                'movimientos.nro_ref', 'movimientos.nro_doc', 'movimientos.nro_preing',
                'movimientos.responsable', 'p.nom_proy',
                'movimientos.proyectos_id', 'movimientos.created_at',
                'movimientos.doc_tipo', 'movimientos.doc_estado'
            );
        if ($text_fecha) $rs->fecha($text_fecha);
        if($search)$rs->where(function ($query) use ($search) {
            $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                ->orWhere("movimientos.responsable", "LIKE", '%'.$search.'%')
                ->orWhere("p.nom_proy", "LIKE", '%'.$search.'%');
            //->orWhere("movimientos.organizacion", "LIKE", '%'.$search.'%')
        });
        if($estado)$rs->where("movimientos.doc_estado",$estado);
        $rs->where("movimientos.nro_linea", 0)
            ->where("movimientos.tpo_doc", $tpo_doc)
            //->where("movimientos.ano_doc",$y)
            //->where("movimientos.mes_doc",$m)
            ->where('movimientos.cod_empresa', session('cod_empresa'))
            ->where('movimientos.cod_almacen', session('cod_almacen'))
            ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
            ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
            ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'));
        $ventas_datos = $rs->paginate($pag);

        $fechas = Salida::
        select(DB::raw("DATE_FORMAT(fecha, '%m-%Y') as fecha,DATE_FORMAT(fecha, '%m') as mes, DATE_FORMAT(fecha, '%Y') as ano"))
            ->distinct()
            ->orderBy('fecha','asc')
            ->get();
        //->pluck('created_at');
        return view('ventas.ventas', compact('ventas_datos', 'permisos', 'search', 'fechas'));
    }



    public function create()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["salidas"]["permisos"]["nuevo"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        //$nro_doc = $this->getGeneraNroDoc();
        $nro_doc = $this->getGenerateNro();
        $numbers = $this->generate_numbers($nro_doc, 1, 10);
        $numbers = $numbers[0];
        $fecha = Carbon::now()->format('Y-m-d');
        $monedas = $this->getGeneraMonedas($fecha);
        $tipos    = DB::table('tipo_de_cambio')->orderBy('id','asc')->get();
        $comprobante = new Comprobante();
        return view('ventas.create',compact('comprobante','monedas', 'tipos', 'numbers'));
    }
    public function edit(Comprobante $comprobante)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["salidas"]["permisos"]["editar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        $fecha = $comprobante->created_at->format('Y-m-d');
        $nro_doc = $this->getGeneraNroDoc();
        $numbers = $this->generate_numbers($nro_doc, 1, 10);
        $numbers = $numbers[0];
        $numbers = $comprobante->nro_doc;

        $tipos    = DB::table('tipo_de_cambio')->orderBy('id','asc')->get();
        $monedas = $this->getGeneraMonedas($fecha);

        $details = Comprobante_Det::select("cod_articulo", "precio", "cantidad","articulos.nombre","articulos.stock_min","unidad_medida.cod_umedida",'s.stock_alm')
            ->join("articulos","articulos.cod_artic","=","comprobantes_det.cod_articulo")
            ->leftJoin('stock_articulos_alm as s', 's.cod_artic','=','articulos.cod_artic')

            ->join("unidad_medida","articulos.cod_umedida","=","unidad_medida.id")
            ->where("cod_comprobante","=",$comprobante->id)
            ->get();
        $comprobante->tot_reg=$details->count();


        return view('ventas.edit',compact('comprobante','details','monedas', 'tipos', 'numbers'));
    }

    public function getGenerateNro($tpo_com='05'){
        $num = Comprobante::where('tpo_com',$tpo_com)
            ->where('cod_almacen',session('cod_almacen'))
            ->where('cod_empresa',session('cod_empresa'))
            ->max('nro_doc');
        return $num?$num+1:1;
    }
    public function getGeneraNroDoc(){
        $nro = DB::table('movimientos')
            ->where('tpo_doc','04')
            ->where('cod_almacen',session('cod_almacen'))
            ->whereNull('cod_cabecera')
            ->select('nro_doc')
            ->orderBy('id', 'desc')
            ->count();
        /*
        // Correlativo nro_doc por mes
        $nro = DB::table('movimientos')
                ->where('tpo_doc','04')
                ->where('mes_doc', '=', Carbon::now()->format('m'))
                ->select('nro_doc')
                ->orderBy('id', 'desc')
                ->count();*/

        if($nro==0){
            $nro_doc = 1;
        }else{
            $nro_doc = DB::table('movimientos')
                ->where('tpo_doc','04')
                ->where('cod_almacen',session('cod_almacen'))
                ->whereNull('cod_cabecera')
                ->select('id','nro_doc')
                ->orderBy('id', 'desc')
                ->limit(1)->first();

            /*$nro_doc = DB::table('movimientos')
                        ->where('tpo_doc','04')
                        ->where('mes_doc', '=', Carbon::now()->format('m'))
                        ->select('nro_doc')
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->first();*/

            $nro_doc =$nro_doc->nro_doc;
            $nro_doc = preg_replace('/^0+/', '', $nro_doc);

            $nro_doc = ($nro_doc == 0 )?1:$nro_doc+1;

        }
        return $nro_doc;
    }
    public function getGeneraMonedas($fecha){
        $monedas = DB::table('monedas')->join('tipo_de_cambio', 'tipo_de_cambio.cod_moneda', '=', 'monedas.cod_moneda')
            ->select('tipo_de_cambio.id', "monedas.cod_moneda","nom_moneda","TC_venta_mn","TC_compra_mn","TC_me", DB::raw(" DATE_FORMAT(fecha, '%d/%m/%Y') as fecha"))
            ->where(DB::raw("STR_TO_DATE(fecha, '%Y-%m-%d')"),$fecha)
            //->orderBy('tipo_de_cambio.fecha','desc')
            //->limit(2)
            ->get();
        return $monedas;
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
        $tpo_doc = $request->input('nro_preing');
        $cod_almacen = session('cod_almacen');
        $cod_empresa = session('cod_empresa');
        $serie_doc = "";
        $nro_doc = $request->input('nro_doc');
        $cod_cliente = $request->input('cod_ruc');

        $igv_total = $request->input('xigv');
        $total = $request->input('xtotal');

        $cod_usuario = auth()->user()->id;
        $descuento_valor = $request->input('xdesc');
        $descuento_porc = $request->input('descuento');
        $forma_pago = $request->input('forma_pago');
        $observacion = $request->input('obsercion');
        $monto = $request->input('total_recibido');

        $tot_reg = $request->input('tot_reg');

        $razon_social = $request->input('razon_social');
        $direccion = $request->input('direccion');
        $importe = $request->input('importe');

        $tpo_com = '05';

        if($cod_cliente==0)
            return \Response::json(['error' => "No existe el cliente","tipo"=>1], 404);
        if($tot_reg==0)
            return \Response::json(['error' => "Ingrese producto","tipo"=>1], 404);

        $e = Comprobante::where('nro_doc', $nro_doc)
            ->where("tpo_doc", $tpo_doc)
            ->where('cod_almacen',$cod_almacen)
            ->count();

        if($e > 0){
            return \Response::json(['error' => "El documento ya existe.","tipo"=>1], 404);
        }
        $comprobante = Comprobante::create(compact("tpo_doc","cod_empresa","cod_almacen","serie_doc","nro_doc",
            "cod_cliente","igv_total","total","cod_usuario","descuento_valor","descuento_porc","razon_social","direccion","tpo_com","importe",
            "forma_pago","observacion","monto"));
        //$cod_comprobante = $request->input('cod_art_'.$i);
        $cod_comprobante = $comprobante->id;
        for($i=1;$i<=$tot_reg;$i++){
            $cod_articulo = $request->input('cod_art_'.$i);
            $precio = $request->input('costo_'.$i);
            $cantidad = $request->input('cant_'.$i);
            $det = Comprobante_Det::insert(compact( "cod_comprobante","cod_empresa","cod_usuario","cod_articulo","precio",
                "cantidad"));
        }
        Cache::flush();
        return array('id' => $cod_comprobante, 'ok' => 'ok' );//'ok'
    }

    public function store2(Request $request)
    {
        $nro_doc     = mb_strtoupper($request->input('nro_doc'));
        $fecha_desde = $request->input('fecha_desde');
        $flag_doc_aux = null;
        $nro_preing = $request->input('nro_preing');
        //$referencia = $request->input('referencia');
        $nroR     = mb_strtoupper($request->input('referencia'));
        $cod_ruc2 = $request->input('cod_ruc');
        $proyectos_id = $request->input('proyectos_id');
        $responsable = mb_strtoupper($request->input('responsable'));
        $tpo_doc     = '04';
        $flag_saldo  = "N";
        $flag_mov    = "M";
        $flag_trans  = "N";
        $flag_ccosto = 0;
        $flag_pcosto = "N";
        $orden_compra   = null;
        $flag_anulado   = "N";
        $flag_facturado = "N";
        $tipo           = 0;
        $serie_d        = '000';
        $cod_subalm     = 1;
        $flag_tipo      = null;//cant_ump
        $doc_tipo       = $request->input('doc_tipo');
        $doc_estado     = ($doc_tipo=='H')?'P':'';

        $cod_almacen = session('cod_almacen');
        $cod_empresa = session('cod_empresa');
        $cod_usuario = \Auth::User()->id;
        $cod_moneda2 = 0;

        if($nro_preing == 3){

            $tc_id = $request->input('tc_id');
            $cod_ruc = "0";
            $cliente_doc = mb_strtoupper($request->input('cliente_doc'));
            $tipo_c = mb_strtoupper($request->input('tipo_c'));
            $numerodoc = mb_strtoupper($request->input('numerodoc'));
            $flag_activo = 1;

            $cli = new Proyecto() ;
            $cli->cod_empresa = $cod_empresa;
            $cli->cod_usuario = $cod_usuario;
            $cli->cod_ruc     = $cod_ruc;
            $cli->nom_proy    = $cliente_doc;
            $cli->tipocomp    = $tipo_c;
            $cli->numerodoc   = $numerodoc;
            $cli->flag_activo = 1;
            $cli->created_at  = Carbon::now();
            $cli->updated_at  = Carbon::now();
            $cli->save();

            $proyectos_id = $cli->id;
            $val = "";
            if($tipo_c=="F")$val = "FACTURA";
            elseif ($tipo_c=="B")$val = "BOLETA";
            else $val = "TICKET";
            $responsable  = $val.": ".$numerodoc;
        }

        ////////CABECERA
        $mov_cab = new Movimientos();
        $mov_cab->cod_empresa = $cod_empresa;
        $mov_cab->cod_almacen = $cod_almacen;
        // solo para salidas
        $mov_cab->flag_saldo  = $flag_saldo;
        $mov_cab->flag_mov    = $flag_mov;
        $mov_cab->flag_trans  = $flag_trans;
        $mov_cab->flag_ccosto = $flag_ccosto;
        $mov_cab->flag_pcosto = $flag_pcosto;
        $mov_cab->orden_compra   = $orden_compra;
        $mov_cab->flag_anulado   = $flag_anulado;
        $mov_cab->flag_facturado = $flag_facturado;
        $mov_cab->tipo           = $tipo;
        $mov_cab->serie_d        = $serie_d;
        $mov_cab->cod_subalm     = $cod_subalm;
        $mov_cab->flag_tipo      = $flag_tipo;
        $mov_cab->proyectos_id   = $proyectos_id;
        $mov_cab->responsable    = $responsable;

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

            return \Response::json(['error' => "El documento ya existe.","tipo"=>1], 404);
            exit;
        }

        $mov_cab->ano_doc = $yy;
        $mov_cab->mes_doc = $m;
        $mov_cab->fecha = $fecha;
        $mov_cab->tpo_doc = $tpo_doc;
        $mov_cab->nro_linea = 0;
        $mov_cab->origen = "INV";
        $mov_cab->flag_doc_aux = $flag_doc_aux;

        //validar
        $rsVerif = DB::table("movimientos")
            ->where("cta_cte", $cod_ruc2)
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

        $mov_cab->nro_doc = $nro_doc;
        $mov_cab->nro_preing = $nro_preing;
        $mov_cab->cod_moneda = $cod_moneda2;

        $mov_cab->cod_usuario = \Auth::User()->id;
        $mov_cab->cta_cte = $proyectos_id; //$cod_ruc2;

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
        $mov_cab->costo_tot_mn = (float)$costo_tot_mn ;
        $mov_cab->costo_tot_me = (float)$costo_tot_me ;
        $mov_cab->doc_tipo     = $doc_tipo;
        $mov_cab->doc_estado   = $doc_estado;

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
                $mov_det->nro_linea = $y;
                $mov_det->origen = "INV";
                $mov_det->flag_doc_aux = $flag_doc_aux;

                //solo para salidas
                $mov_det->flag_saldo  = $flag_saldo;
                $mov_det->flag_mov    = $flag_mov;
                $mov_det->flag_trans  = "";
                $mov_det->flag_ccosto = $flag_ccosto;
                $mov_det->flag_pcosto = $flag_pcosto;
                $mov_det->orden_compra   = $orden_compra;
                $mov_det->flag_anulado   = $flag_anulado;
                $mov_det->flag_facturado = $flag_facturado;
                $mov_det->tipo           = $tipo;
                $mov_det->serie_d        = $serie_d;
                $mov_det->cod_subalm     = $cod_subalm;
                $mov_det->flag_tipo      = $flag_tipo;
                $mov_det->proyectos_id   = $proyectos_id;
                $mov_det->responsable    = $responsable;

                // add
                $mov_det->cod_moneda  = $cod_moneda2;
                $mov_det->cod_almacen = $cod_almacen;
                $mov_det->fecha      = Carbon::now();
                $mov_det->nro_ref    = $nro_ref;

                $mov_det->nro_preing = $nro_preing;
                $mov_det->cta_cte    = $proyectos_id;

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
                $mov_det->costo_mo = (float)$costo_mo ;
                $mov_det->costo_mn = (float)$costo_mn ;
                $mov_det->costo_me = (float)$costo_me ;
                $mov_det->costo_tot_mo = (float)$costo_tot_mo ;
                $mov_det->costo_tot_mn = (float)$costo_tot_mn ;
                $mov_det->costo_tot_me = (float)$costo_tot_me ;

                $mov_det->cod_cabecera = $idCab;
                $mov_det->doc_tipo     = $doc_tipo;
                $mov_det->doc_estado   = $doc_estado;
                $mov_det->cod_usuario = \Auth::User()->id;
                $mov_det->save();
                $y++;

                $n_stock = DB::table('stock_articulos_alm')->join('articulos as a', 'stock_articulos_alm.cod_artic','=','a.cod_artic')
                    ->where('stock_articulos_alm.cod_empresa', $cod_empresa)
                    ->where('stock_articulos_alm.cod_almacen', $cod_almacen)
                    ->where('a.cod_artic', $cod_art)
                    ->get();

                if(count($n_stock)>0){

                    // validar si existe stock suficiente
                    DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen)
                        ->where('cod_artic', $cod_art)
                        ->decrement('stock_alm', $cant);

                }else{
                    // no hay stock suficiente - error
                    return \Response::json(['error' => "Producto no tiene stock.","tipo"=>1], 404);
                    exit;
                }

            }
        }

        Cache::flush();
        $rs = array('id' => $idCab, 'ok' => 'ok' );
        return $rs;//'ok'

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comprobante $comprobante)
    {
        $tpo_doc = $request->input('nro_preing');
        $cod_almacen = session('cod_almacen');
        $cod_empresa = session('cod_empresa');
        $serie_doc = "";
        $nro_doc = $request->input('nro_doc');
        $cod_cliente = $request->input('cod_ruc');

        $igv_total = $request->input('xigv');
        $total = $request->input('xtotal');

        $cod_usuario = auth()->user()->id;
        $descuento_valor = $request->input('xdesc');
        $descuento_porc = $request->input('descuento');
        $forma_pago = $request->input('forma_pago');
        $observacion = $request->input('obsercion');
        $monto = $request->input('total_recibido');

        $tot_reg = $request->input('tot_reg');

        $razon_social = $request->input('razon_social');
        $direccion = $request->input('direccion');
        $importe = $request->input('importe');
        $tpo_com = '05';



        if($cod_cliente==0)
            return \Response::json(['error' => "No existe el cliente","tipo"=>1], 404);
        if($tot_reg==0)
            return \Response::json(['error' => "Ingrese producto","tipo"=>1], 404);

        $e = Comprobante::where('nro_doc', $nro_doc)
            ->where("tpo_doc", $tpo_doc)
            ->where('cod_almacen',$cod_almacen)
            ->where('id','!=',$comprobante->id)
            ->count();

        if($e > 0){
            return \Response::json(['error' => "El documento ya existe.","tipo"=>1], 404);
        }
        $comprobante->update(compact("tpo_doc","cod_empresa","cod_almacen","serie_doc","nro_doc",
            "cod_cliente","igv_total","total","cod_usuario","descuento_valor","descuento_porc","razon_social","direccion","tpo_com","importe",
            "forma_pago","observacion","monto"));

        //$cod_comprobante = $request->input('cod_art_'.$i);
        $q = Comprobante_Det::where("cod_comprobante","=",$comprobante->id);
        if($q->count()>0)$q->delete();
        $cod_comprobante = $comprobante->id;
        for($i=1;$i<=$tot_reg;$i++){
            $cod_articulo = $request->input('cod_art_'.$i);
            $precio = $request->input('costo_'.$i);
            $cantidad = $request->input('cant_'.$i);
            $det = Comprobante_Det::insert(compact( "cod_comprobante","cod_empresa","cod_usuario","cod_articulo","precio",
                "cantidad"));
        }
        Cache::flush();
        return array('id' => $cod_comprobante, 'ok' => 'ok' );//'ok'

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        //
    }

    public function ticket(Comprobante $comprobante)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["salidas"]["permisos"]["editar"]   ) ){
            Auth::logout();
            return redirect('/login');
        }
        $fecha = $comprobante->created_at->format('Y-m-d');
        $nro_doc = $this->getGeneraNroDoc();
        $numbers = $this->generate_numbers($nro_doc, 1, 10);
        $numbers = $numbers[0];
        $numbers = $comprobante->nro_doc;

        $tipos    = DB::table('tipo_de_cambio')->orderBy('id','asc')->get();
        $monedas = $this->getGeneraMonedas($fecha);

        $details = Comprobante_Det::select("cod_articulo", "precio", "cantidad","articulos.nombre","articulos.stock_min","unidad_medida.cod_umedida")
            ->join("articulos","articulos.cod_artic","=","comprobantes_det.cod_articulo")
            ->join("unidad_medida","articulos.cod_umedida","=","unidad_medida.id")
            ->where("cod_comprobante","=",$comprobante->id)
            ->get();
        $comprobante->tot_reg=$details->count();

        $data = compact('comprobante','details','monedas', 'tipos', 'numbers');
        //$dompdf = new Dompdf();
        $pdf = PDF::loadView('ventas.ticket', $data);
        return $pdf->download('ejemplo.pdf');

        //return view('ventas.ticket', $data);
    }

    
}
