<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Cache;
use App\Salida, App\Almacen;
use App\Producto;
use App\Movimientos, App\Proyecto;
use App\AccionesRolesPermisos;
use Carbon\Carbon;

use Illuminate\Http\Request,
    App\Repositories\ProductRepositorie,
    App\Http\Requests;

class GuiaTransfController extends Controller
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

        if(session('cod_empresa') == false){ return redionrect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "ingresos";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);

        $search = $request->input('s');
        $text_fecha  = $request->input('m');
        //$estado      = $request->input('sta');
        // falta validar estado

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 15;
        }

        //Cache::flush();
        $tpo_doc = '03';

        if($text_fecha || $search){
            Cache::flush();

            if($text_fecha and !$search){

                $gt_datos = Salida::join('almacen as a', 'movimientos.cod_alm_d','=','a.id')
                            ->select('movimientos.id',
                                'movimientos.nro_doc',
                                'movimientos.tpo_doc',
                                'movimientos.mes_doc',
                                'movimientos.ano_doc',
                                'movimientos.nro_ref',
                                'movimientos.cod_almacen',
                                'a.almacen',
                                'movimientos.fecha_hora'
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

                $gt_datos = Salida::join('almacen as a', 'movimientos.cod_alm_d','=','a.id')
                    ->select('movimientos.id',
                        'movimientos.nro_doc',
                        'movimientos.tpo_doc',
                        'movimientos.mes_doc',
                        'movimientos.ano_doc',
                        'movimientos.nro_ref',
                        'movimientos.cod_almacen',
                        'a.almacen',
                        'movimientos.fecha_hora'
                    )
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.responsable", "LIKE", '%'.$search.'%')
                        ->orWhere("a.almacen", "LIKE", '%'.$search.'%');
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);

            }else{
                $gt_datos = Salida::join('almacen as a', 'movimientos.cod_alm_d','=','a.id')
                    ->select('movimientos.id',
                        'movimientos.nro_doc',
                        'movimientos.tpo_doc',
                        'movimientos.mes_doc',
                        'movimientos.ano_doc',
                        'movimientos.nro_ref',
                        'movimientos.cod_almacen',
                        'a.almacen',
                        'movimientos.fecha_hora'
                    )
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->fecha($text_fecha)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.responsable", "LIKE", '%'.$search.'%')
                        ->orWhere("a.almacen", "LIKE", '%'.$search.'%');
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);
            }

        }else{

                $key = 'gt.page.'.request('page', 1);
                $gt_datos = Cache::rememberForever($key, function() use ($pag, $tpo_doc){
                return Salida::join('almacen as a', 'movimientos.cod_alm_d','=','a.id')
                    ->select('movimientos.id',
                        'movimientos.nro_doc',
                        'movimientos.tpo_doc',
                        'movimientos.mes_doc',
                        'movimientos.ano_doc',
                        'movimientos.nro_ref',
                        'movimientos.cod_almacen',
                        'a.almacen',
                        'movimientos.fecha_hora'
                    )
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);
                });

        }

        $fechas = Salida::
                select(DB::raw("DATE_FORMAT(fecha, '%m-%Y') as fecha,DATE_FORMAT(fecha, '%m') as mes, DATE_FORMAT(fecha, '%Y') as ano"))
                ->distinct()
                ->orderBy('fecha','asc')
                ->get();
                //->pluck('created_at');

        return view('gt.index', compact('gt_datos', 'permisos', 'fechas'));
    }

    public function stock(Request $request){

        //return "Stock cero: id=".$request->id.' cant: '.$request->cant;
        $cod_artic = $request->id;
        $cant = $request->cant;
        
        $stockVal = DB::table('stock_articulos_alm')
                ->select('stock_alm')
                ->where('cod_empresa', session('cod_empresa'))
                ->where('cod_almacen', session('cod_almacen'))
                ->where('cod_artic', $cod_artic)
                ->first();

        if($stockVal){

            $stock = number_format($stockVal->stock_alm, 2);
            if($stock > 0){
                if($stock >= $request->cant){

                    $stock_arr = array([
                        'stock'=> "D",
                        'stock_bd'=> $stock
                    ]);

                }else{
                    $stock_arr = array([
                        'stock'=> "C",
                        'stock_bd'=> $stock
                    ]);
                }
            }else{
                $stock_arr = array([
                        'stock'=> "B",
                        'stock_bd'=> $stock
                    ]);
            }
        }else{
            $stock_arr = array([
                        'stock'=> "A",
                        'stock_bd'=> 0 // no tiene stock - no esta registrado en la tb stock
                    ]);
        }

        //return json_encode($stock_arr);
        return $stock_arr;
    }

    // print pdf comprobante
    public function comprobante(Request $request){
        
        $salidas = Salida::where('id','=', $request->id)->get();
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

        // Correlativo nro_doc por mes
        $nro = DB::table('movimientos')
                ->where('tpo_doc','03')
                ->where('cod_almacen',session('cod_almacen'))
                ->whereNull('cod_cabecera')
                ->select('nro_doc')
                ->orderBy('id', 'desc')
                ->count();



        if($nro==0){
            $nro_doc = 1;
        }else{
            $nro_doc = DB::table('movimientos')
                        ->where('tpo_doc','03')
                        ->where('cod_almacen',session('cod_almacen'))
                        ->whereNull('cod_cabecera')
                        ->select('id','nro_doc')
                        ->orderBy('id', 'desc')
                        ->limit(1)->first();

            $nro_doc =$nro_doc->nro_doc;
            $nro_doc = preg_replace('/^0+/', '', $nro_doc);

            $nro_doc = ($nro_doc == 0 )?1:$nro_doc+1;

        }

        $almacen_o = Almacen::where('id','=',session('cod_almacen'))->first();
        $almacen_d = Almacen::where('id','<>',session('cod_almacen'))->get();

        $numbers = $this->generate_numbers($nro_doc, 1, 10);

        $numbers = $numbers[0];
        
        
        $fecha = Carbon::now()->format('Y-m-d');

        return view('gt.create',compact( 'numbers', 'almacen_o', 'almacen_d'));
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
        $flag_doc_aux = $request->input('flag_doc_aux');
        $nro_preing = $request->input('nro_preing');
        //$referencia = $request->input('referencia');
        $nroR     = $request->input('referencia');
        $cod_ruc2 = $request->input('cod_ruc');
        $proyectos_id = 0;
        $tpo_doc = '03';
        $flag_saldo  = "N";
        $flag_mov    = "M";
        //$flag_trans  = "N";
        $flag_ccosto = 1;
        $flag_pcosto = "N";
        $orden_compra   = 0;
        $flag_anulado   = "N";
        $flag_facturado = "N";
        $tipo           = 0;

        $cod_almacen = session('cod_almacen');
        $cod_empresa = session('cod_empresa');
        $cod_almacen_d = $request->input('almacen_d');//cod_alm_d
        $cod_subalm_d  = $request->input('almacen_d');
        $destino       = Almacen::where('id',$cod_almacen_d)->first();
        $destino       = $destino->direccion;

        $cod_moneda2 = 0;

        ////////CABECERA
        $mov_cab = new Movimientos();
        $mov_cab->cod_empresa = $cod_empresa;
        $mov_cab->cod_almacen = $cod_almacen;
        $mov_cab->cod_alm_d   = $cod_almacen_d;
        $mov_cab->cod_subalm_d = $cod_subalm_d;
        $mov_cab->direccion    = $destino;
        // solo para salidas
        $mov_cab->flag_saldo  = $flag_saldo;
        $mov_cab->flag_mov    = $flag_mov;
        $mov_cab->flag_ccosto = $flag_ccosto;
        $mov_cab->flag_pcosto = $flag_pcosto;
        $mov_cab->orden_compra   = $orden_compra;
        $mov_cab->flag_anulado   = $flag_anulado;
        $mov_cab->flag_facturado = $flag_facturado;
        $mov_cab->tipo           = $tipo;
        $mov_cab->proyectos_id   = $proyectos_id;
        
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
        $mov_cab->flag_tipo = 1;
        $mov_cab->nro_doc = $nro_doc;
        $mov_cab->nro_preing = $nro_preing;
        $mov_cab->cod_moneda = $cod_moneda2;
        
        $mov_cab->cod_usuario = \Auth::User()->id;
        $mov_cab->cta_cte = $cod_ruc2;
        
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
                $mov_det->proyectos_id   = $proyectos_id;

                // add
                $mov_det->cod_moneda  = $cod_moneda2;
                $mov_det->cod_almacen = $cod_almacen;
                $mov_det->cod_alm_d   = $cod_almacen_d;
                $mov_det->cod_subalm_d = $cod_subalm_d;
                $mov_cab->direccion    = $destino;
                $mov_det->fecha      = Carbon::now();
                $mov_det->nro_ref    = $nro_ref;
                $mov_det->flag_tipo  = 1;
                $mov_det->nro_preing = $nro_preing;
                $mov_det->cta_cte    = $cod_ruc2;

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
                $mov_det->costo_mo = (float)$costo_mo ;
                $mov_det->costo_mn = (float)$costo_mn ;
                $mov_det->costo_me = (float)$costo_me ;
                $mov_det->costo_tot_mo = (float)$costo_tot_mo ;
                $mov_det->costo_tot_mn = (float)$costo_tot_mn ;
                $mov_det->costo_tot_me = (float)$costo_tot_me ;

                $mov_det->cod_cabecera = $idCab;

                $mov_det->cod_usuario = \Auth::User()->id;
                $mov_det->save();
                $y++;

                $n_stock = DB::table('stock_articulos_alm')->join('articulos as a', 'stock_articulos_alm.cod_artic','=','a.cod_artic')
                            ->where('stock_articulos_alm.cod_empresa', $cod_empresa)
                            ->where('stock_articulos_alm.cod_almacen', $cod_almacen)
                            ->where('a.cod_artic', $cod_art)
                            ->get();

                if(count($n_stock)>0){

                    // verificar si existe stock del almacen asignado

                    $alm_new = DB::table('stock_articulos_alm')->join('articulos as a', 'stock_articulos_alm.cod_artic','=','a.cod_artic')
                            ->where('stock_articulos_alm.cod_empresa', $cod_empresa)
                            ->where('stock_articulos_alm.cod_almacen', $cod_almacen_d)
                            ->where('a.cod_artic', $cod_art)
                            ->count();

                    if($alm_new > 0){
                        // quita
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen)
                        ->where('cod_artic', $cod_art)
                        ->decrement('stock_alm', $cant);

                        // agrega
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen_d)
                        ->where('cod_artic', $cod_art)
                        ->increment('stock_alm', $cant);

                    }else{

                        // quita
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen)
                        ->where('cod_artic', $cod_art)
                        ->decrement('stock_alm', $cant);

                        // agrega
                        DB::table('stock_articulos_alm')->insert([
                            'cod_empresa' => $cod_empresa,
                            'cod_almacen' => $cod_almacen_d,
                            'cod_artic'   => $cod_art,
                            'stock_alm'   => $cant
                        ]);
                    }

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

        $datos = DB::table("movimientos")->find($id);

        if(!$datos){
            return redirect()->route('gt.index');
        }

        $almacen_o = Almacen::where('id','=',session('cod_almacen'))->first();
        $almacen_d = Almacen::where('id','<>',session('cod_almacen'))->get();

        $d_fecha = Carbon::parse($datos->fecha)->format('Y-m-d');

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

        return view('gt.show',compact('datos', 'tipos', 'empresa', 'items', 'almacen_o', 'almacen_d'));

        
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
            return redirect()->route('gt.index');
        }

        $almacen_o = Almacen::where('id','=',session('cod_almacen'))->first();
        $almacen_d = Almacen::where('id','<>',session('cod_almacen'))->get();

        $d_fecha = Carbon::parse($datos->fecha)->format('Y-m-d');

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

        return view('gt.edit',compact('datos', 'tipos', 'empresa', 'items', 'almacen_o', 'almacen_d'));
    }

    public function update(Request $request, $idCab)
    {
        $nro_doc     = $request->input('nro_doc');
        $fecha_desde = $request->input('fecha_desde');
        $flag_doc_aux = $request->input('flag_doc_aux');
        $nro_preing = $request->input('nro_preing');
        //$referencia = $request->input('referencia');
        $nroR     = $request->input('referencia');
        $cod_ruc2 = $request->input('cod_ruc');
        $proyectos_id = 0;
        $tpo_doc = '03';
        $flag_saldo  = "N";
        $flag_mov    = "M";
        //$flag_trans  = "N";
        $flag_ccosto = 1;
        $flag_pcosto = "N";
        $orden_compra   = 0;
        $flag_anulado   = "N";
        $flag_facturado = "N";
        $tipo           = 0;

        $cod_almacen = session('cod_almacen');
        $cod_empresa = session('cod_empresa');
        $cod_almacen_d = $request->input('almacen_d');
        $cod_subalm_d  = $request->input('almacen_d');
        //return "$cod_almacen_d  -- $cod_subalm_d";
        $destino       = Almacen::where('id',$cod_almacen_d)->first();
        $destino       = $destino->direccion;
        $cod_moneda2 = 0;

        ////////CABECERA
        $mov_cab = Movimientos::find($idCab);
        $mov_cab->cod_empresa = $cod_empresa;
        $mov_cab->cod_almacen = $cod_almacen;
        $mov_cab->cod_alm_d   = $cod_almacen_d;
        $mov_cab->cod_subalm_d = $cod_subalm_d;
        $mov_cab->direccion    = $destino;
        // solo para salidas
        $mov_cab->flag_saldo  = $flag_saldo;
        $mov_cab->flag_mov    = $flag_mov;
        $mov_cab->flag_ccosto = $flag_ccosto;
        $mov_cab->flag_pcosto = $flag_pcosto;
        $mov_cab->orden_compra   = $orden_compra;
        $mov_cab->flag_anulado   = $flag_anulado;
        $mov_cab->flag_facturado = $flag_facturado;
        $mov_cab->tipo           = $tipo;
        $mov_cab->proyectos_id   = $proyectos_id;

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
            ->where('id','<>',$idCab)
            ->where('cod_cabecera','<>',$idCab)
            ->where('mes_doc', $m)
            ->where('ano_doc', $yy)
            ->where('origen', $origen)
            ->where('tpo_doc',$tpo_doc)
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

        /*$arrRef = explode("-", $request->input('referencia'));
        if(isset($arrRef[1])){
            $nroR = $arrRef[0];
        }else{
            $nroR = $request->input('referencia');
        }*/

        
        $mov_cab->nro_ref = $nroR;
        $nro_ref = $nroR;
        $mov_cab->flag_tipo = 1;
        $mov_cab->nro_doc = $nro_doc;
        $mov_cab->nro_preing = $nro_preing;
        $mov_cab->cod_moneda = $cod_moneda2;
        
        $mov_cab->cod_usuario = \Auth::User()->id;
        $mov_cab->cta_cte = $cod_ruc2;
        
        $moneda = DB::table("tipo_de_cambio")->where("id", $cod_moneda2)->first();
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
        $mov_cab->save();

        // descontar estos productos en la tb. stock

        $rs_prod = Movimientos::where("cod_cabecera",$idCab)
                    ->select('nro_doc','nro_ref','cod_artic', 'cant_mov')->get();

        $va = array();
        $va2 = array();
        $vcan=array();
        
        for ($x = 1 ; $x <= $request->input('tot_reg') ; $x++) {

            $id_art_new = $request->input('cod_art_'.$x);
            $cant_new   = $request->input('cant_'.$x);
            $cant_new   = number_format($cant_new,2);
            array_push($va,$id_art_new);
            array_push($vcan,$cant_new);//$vcan[] = $cant_new;

            foreach ($rs_prod as $pp) {
                $id_art_reg = $pp->cod_artic;
                $cant_reg   = $pp->cant_mov;

                $dif = 0;

                // items repetidos - repiten

                if($id_art_reg == $id_art_new){

                    if($cant_reg > $cant_new){
                        $dif = $cant_reg - $cant_new;

                        /*DB::table('stock_articulos_alm')
                            ->where('cod_empresa', $cod_empresa)
                            ->where('cod_almacen', $cod_almacen)
                            ->where('cod_artic', $id_art_reg)
                            ->increment('stock_alm', $dif);*/

                        // cant_reg: 10 -- cant_new:5 = 5

                        // agrega 
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen)
                        ->where('cod_artic', $id_art_reg)
                        ->increment('stock_alm', $dif);

                        // quita
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen_d)
                        ->where('cod_artic', $id_art_reg)
                        ->decrement('stock_alm', $dif);

                    }elseif($cant_reg == $cant_new){
                        //$t .= " cant_reg igual: $cant_reg -- $cant_new .";

                    }else{
                        // la $cant_new > $cant_reg
                        $dif = $cant_new - $cant_reg;

                        /*DB::table('stock_articulos_alm')
                            ->where('cod_empresa', $cod_empresa)
                            ->where('cod_almacen', $cod_almacen)
                            ->where('cod_artic', $id_art_reg)
                            ->decrement('stock_alm', $dif);*/

                        // quita 
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen)
                        ->where('cod_artic', $id_art_reg)
                        ->decrement('stock_alm', $dif);

                        // agrega
                        DB::table('stock_articulos_alm')
                        ->where('cod_empresa', $cod_empresa)
                        ->where('cod_almacen', $cod_almacen_d)
                        ->where('cod_artic', $id_art_reg)
                        ->increment('stock_alm', $dif);
                    }
                    
                }
            }
        }

        foreach ($rs_prod as $pp) {
            array_push($va2, $pp->cod_artic);
        }
        
        $nuevos = array_diff_assoc($va, $va2);
        $vnuevos=array();
        if(count($nuevos)>0){
            foreach($nuevos as $xindex=>$xid){
                $xcan=$vcan[$xindex];
                array_push($vnuevos, array("id"=>$xid,"index"=>$xindex,"cantidad"=>$xcan));
            } 
        }
        //$repiten = array_intersect_assoc($va, $va2);
        //return compact('va', 'va2');
        //return compact('id','can','nuevos','vnuevos', 'repiten');
        
        // items nuevos
        foreach ($vnuevos as $nue) {
            $_id = $nue['id'];
            $_cant = $nue['cantidad'];
            
            $n_stock = DB::table('stock_articulos_alm')
                ->where('cod_empresa', $cod_empresa)
                ->where('cod_almacen', $cod_almacen_d)
                ->where('cod_artic', $_id)
                ->count();

            if($n_stock>0){

                /*DB::table('stock_articulos_alm')
                ->where('cod_empresa', $cod_empresa)
                ->where('cod_almacen', $cod_almacen)
                ->where('cod_artic', $_id)
                ->decrement('stock_alm', $_cant);*/

                // quita 
                DB::table('stock_articulos_alm')
                ->where('cod_empresa', $cod_empresa)
                ->where('cod_almacen', $cod_almacen)
                ->where('cod_artic', $_id)
                ->decrement('stock_alm', $_cant);

                // agrega
                DB::table('stock_articulos_alm')
                ->where('cod_empresa', $cod_empresa)
                ->where('cod_almacen', $cod_almacen_d)
                ->where('cod_artic', $_id)
                ->increment('stock_alm', $_cant);

            }else{

                // quita
                DB::table('stock_articulos_alm')
                ->where('cod_empresa', $cod_empresa)
                ->where('cod_almacen', $cod_almacen)
                ->where('cod_artic', $_id)
                ->decrement('stock_alm', $_cant);

                // agrega
                DB::table('stock_articulos_alm')->insert([
                    'cod_empresa' => $cod_empresa,
                    'cod_almacen' => $cod_almacen_d,
                    'cod_artic'   => $_id,
                    'stock_alm'   => $_cant
                ]);

                /* return \Response::json(['error' => "El producto no tiene registrado su stock","tipo"=>1], 404); 
                exit;

                El producto no tiene registrado su tb stock

                DB::table('stock_articulos_alm')->insert([
                    'cod_empresa'   => $cod_empresa,
                    'cod_almacen'   => $cod_almacen,
                    'cod_artic'     => $_id,
                    'stock_alm'     => $_cant
                ]);*/
            }
        }

        //borra para rehacer
        Movimientos::where("cod_cabecera",$idCab)->delete();

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
                $mov_det->cod_moneda  = $cod_moneda2;
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
                $mov_det->proyectos_id   = $proyectos_id;

                $mov_det->cod_almacen = $cod_almacen;
                $mov_det->cod_alm_d   = $cod_almacen_d;
                $mov_det->cod_subalm_d = $cod_subalm_d;
                $mov_cab->direccion    = $destino;
                $mov_det->fecha = Carbon::now();
                //$mov_det->nro_ref = $cod_ruc2;
                $mov_det->nro_ref    = $nro_ref;
                $mov_det->flag_tipo  = 1;
                $mov_det->nro_preing = $nro_preing;
                $mov_det->cta_cte = $cod_ruc2;

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
                $mov_det->costo_mo =(float) $costo_mo ;
                $mov_det->costo_mn =(float) $costo_mn ;
                $mov_det->costo_me = (float)$costo_me ;
                $mov_det->costo_tot_mo = (float)$costo_tot_mo ;
                $mov_det->costo_tot_mn = (float)$costo_tot_mn ;
                $mov_det->costo_tot_me = (float)$costo_tot_me ;

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
            
            Salida::where('id',$value)->delete();

            // descontar estos productos en la tb. articulos
            $rs_prod = Movimientos::where("cod_cabecera",$value)
                        ->select('nro_doc','nro_ref','cod_artic', 'cant_mov', 'cod_empresa', 'cod_almacen', 'cod_alm_d')->get();

            foreach ($rs_prod as $pp) {
                $id_art_reg = $pp->cod_artic;
                $cant_reg   = $pp->cant_mov;
                $cod_empresa = $pp->cod_empresa;
                $cod_almacen = $pp->cod_almacen;
                $cod_almacen_d = $pp->cod_alm_d;

                //return "$id_art_reg - $cant_reg - $cod_empresa - $cod_almacen - $cod_almacen_d";

                /*DB::table('stock_articulos_alm')
                            ->where('cod_empresa', session('cod_empresa'))
                            ->where('cod_almacen', session('cod_almacen'))
                            ->where('cod_artic', $id_art_reg)
                            ->increment('stock_alm', $cant_reg);*/

                // agrega 
                DB::table('stock_articulos_alm')
                ->where('cod_empresa', $cod_empresa)
                ->where('cod_almacen', $cod_almacen)
                ->where('cod_artic', $id_art_reg)
                ->increment('stock_alm', $cant_reg);

                // quita
                DB::table('stock_articulos_alm')
                ->where('cod_empresa', $cod_empresa)
                ->where('cod_almacen', $cod_almacen_d)
                ->where('cod_artic', $id_art_reg)
                ->decrement('stock_alm', $cant_reg);

            }

            Salida::where('cod_cabecera',$value)->delete();
        }

        
        Cache::flush();

        alert()->error('Registros borrados.','Eliminado');
        return back();
        //return back()->with('danger','Registros borrados.');
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

    public function proy_add($fecha,$tc_id)
    {
        //return view('salidas.add_proyecto', compact('tc_id')); 
    }

    public function storeProy(Request $request){
    
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

                    $rs = array('cod_ruc' => $cod_ruc, 'proyecto' => $nom_proy, 'ok' => 'ok' );
                    return $rs;
                }

                $rs = array('cod_ruc' => $cod_ruc, 'proyecto' => $nom_proy, 'ok' => 'no' );
                return $rs;

            }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 
    }

    public function findProduct(Request $req){
        return $this->_prodRepo->findByName($req->input('q'));
    }

    // Actualizar stock
    private function actualizarStock($products,$request,$cod_empresa,$cod_almacen){
        $productosn=array();
        // Detalle d productos que estan grabados
        if(count($products)>0){
            foreach ($products as $pp) {
                $id_art_reg = $pp->cod_artic;
                $cant_reg   = $pp->cant_mov;
                $cant_reg = floatval($cant_reg)??0;
                $productosn[$id_art_reg]=array(
                "cant_reg"  => $cant_reg,
                "nuevo"     => false,
                "borrado"   => true,
                "cant_new"  => 0
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
                "cant_reg" => $cant_reg,
                "nuevo"    => $nuevo,
                "borrado"  => false,
                "cant_new" => $cant_new
            );
        }
        $n = count($productosn);
        if($n>0){
            foreach($productosn as $cod=>$pro){
                $cant_reg = $pro["cant_reg"];
                $nuevo    = $pro["nuevo"];
                $borrado  = $pro["borrado"];
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
                        ->decrement('stock_alm', $cant_new);

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
                        ->increment('stock_alm', $cant_reg);
                }else{//si se modifica la cantidad del producto
                    if($cant_reg > $cant_new){
                        DB::table('stock_articulos_alm')
                            ->where('cod_empresa', $cod_empresa)
                            ->where('cod_almacen', $cod_almacen)
                            ->where('cod_artic', $cod)
                            ->increment('stock_alm', $cant_reg - $cant_new);
                    }
                    if($cant_reg < $cant_new){
                        DB::table('stock_articulos_alm')
                            ->where('cod_empresa', $cod_empresa)
                            ->where('cod_almacen', $cod_almacen)
                            ->where('cod_artic', $cod)
                            ->decrement('stock_alm', $cant_new - $cant_reg);
                    }                    
                }
            }
        }
        return true;         
    }

    /*end*/
}
