<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Cache;
use App\Salida;
use App\Producto;
use App\Movimientos, App\Proyecto;
use App\AccionesRolesPermisos;
use Carbon\Carbon;
use PDF;

use Illuminate\Http\Request,
    App\Repositories\ProductRepositorie,
    App\Http\Requests;

class SalidasController extends Controller
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

        $tpo_doc = '04';

        if($text_fecha || $search || $estado){
            Cache::flush();

            if($text_fecha and !$search and !$estado){

                $salidas_datos = Salida::join('proyectos as p', 'p.id','=','movimientos.proyectos_id')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc','movimientos.nro_preing',
                        'movimientos.responsable', 'p.nom_proy',
                        'movimientos.proyectos_id','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado'
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
            
            }elseif(!$text_fecha and $search and !$estado){

                $salidas_datos = Salida::join('proyectos as p', 'p.id','=','movimientos.proyectos_id')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc','movimientos.nro_preing',
                        'movimientos.responsable', 'p.nom_proy',
                        'movimientos.proyectos_id','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado'
                    )
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.responsable", "LIKE", '%'.$search.'%')
                        ->orWhere("p.nom_proy", "LIKE", '%'.$search.'%');
                        //->orWhere("movimientos.organizacion", "LIKE", '%'.$search.'%')
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);

            }elseif(!$text_fecha and !$search and $estado){
                $salidas_datos = Salida::join('proyectos as p', 'p.id','=','movimientos.proyectos_id')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc','movimientos.nro_preing',
                        'movimientos.responsable', 'p.nom_proy',
                        'movimientos.proyectos_id','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado'
                    )
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where("movimientos.doc_estado",$estado)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);

            }elseif($text_fecha and $search and !$estado){
                $salidas_datos = Salida::join('proyectos as p', 'p.id','=','movimientos.proyectos_id')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc','movimientos.nro_preing',
                        'movimientos.responsable', 'p.nom_proy',
                        'movimientos.proyectos_id','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado'
                    )
                    ->fecha($text_fecha)
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.responsable", "LIKE", '%'.$search.'%')
                        ->orWhere("p.nom_proy", "LIKE", '%'.$search.'%');
                        //->orWhere("movimientos.organizacion", "LIKE", '%'.$search.'%')
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);

            }elseif(!$text_fecha and $search and $estado){
                $salidas_datos = Salida::join('proyectos as p', 'p.id','=','movimientos.proyectos_id')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc','movimientos.nro_preing',
                        'movimientos.responsable', 'p.nom_proy',
                        'movimientos.proyectos_id','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado'
                    )
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where("movimientos.doc_estado",$estado)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.responsable", "LIKE", '%'.$search.'%')
                        ->orWhere("p.nom_proy", "LIKE", '%'.$search.'%');
                        //->orWhere("movimientos.organizacion", "LIKE", '%'.$search.'%')
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);

            }elseif($text_fecha and !$search and $estado){
                $salidas_datos = Salida::join('proyectos as p', 'p.id','=','movimientos.proyectos_id')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc','movimientos.nro_preing',
                        'movimientos.responsable', 'p.nom_proy',
                        'movimientos.proyectos_id','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado'
                    )
                    ->fecha($text_fecha)
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    //->where("movimientos.doc_estado",$estado)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.responsable", "LIKE", '%'.$search.'%')
                        ->orWhere("p.nom_proy", "LIKE", '%'.$search.'%');
                        //->orWhere("movimientos.organizacion", "LIKE", '%'.$search.'%')
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);

            }else{
                // ref
                $salidas_datos = Salida::join('proyectos as p', 'p.id','=','movimientos.proyectos_id')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc','movimientos.nro_preing',
                        'movimientos.responsable', 'p.nom_proy',
                        'movimientos.proyectos_id','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado'
                    )
                    ->fecha($text_fecha)
                    ->where("movimientos.nro_linea",0)
                    ->where("movimientos.tpo_doc",$tpo_doc)
                    ->where("movimientos.doc_estado",$estado)
                    ->where('movimientos.cod_empresa', session('cod_empresa'))
                    ->where('movimientos.cod_almacen', session('cod_almacen'))
                    ->where(function ($query) use ($search) {
                    $query->where("movimientos.nro_ref", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.nro_doc", "LIKE", '%'.$search.'%')
                        ->orWhere("movimientos.responsable", "LIKE", '%'.$search.'%')
                        ->orWhere("p.nom_proy", "LIKE", '%'.$search.'%');
                        //->orWhere("movimientos.organizacion", "LIKE", '%'.$search.'%')
                    })
                    ->orderBy('movimientos.ano_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.mes_doc', request('sorted', 'DESC'))
                    ->orderBy('movimientos.nro_doc', request('sorted', 'DESC'))
                    ->paginate($pag);
            }

        }else{

            $key = 'salidas.page.'.request('page', 1);
            $salidas_datos = Cache::rememberForever($key, function() use ($pag, $tpo_doc){
                return Salida::join('proyectos as p', 'p.id','=','movimientos.proyectos_id')
                    ->select('movimientos.id','movimientos.ano_doc','movimientos.mes_doc',
                        'movimientos.nro_ref','movimientos.nro_doc','movimientos.responsable','movimientos.nro_preing',
                        'movimientos.proyectos_id','movimientos.created_at',
                        'movimientos.doc_tipo','movimientos.doc_estado'
                    )
                    //ano_doc,mes_doc,nro_ref,proyectos_id,responsable,created_at
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

        return view('salidas.salidas', compact('salidas_datos', 'permisos', 'search', 'fechas'));
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

            $stock_a = $stockVal->stock_alm;
            $stock = number_format($stockVal->stock_alm, 2);
            if($stock_a > 0){
                if($stock_a >= $request->cant){

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

        if(session('cod_empresa') == false){ return redirect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }

        $id      = $request->id;
        $tpo_doc = '04';
        
        $sa = Salida::where('id', $id)->count();
        if($sa == 0){
            alert()->warning('El ID del documento no existe.', 'Alerta');
            return back();
        }
        $salidas = Salida::where('id', $id)->first();

        $nompdf = "orden-salida-".$salidas->nro_doc.".pdf";

        $detalles = Salida::
                    select('id','ano_doc','mes_doc',
                        'nro_ref','nro_doc',
                        'responsable','cod_artic',
                        'proyectos_id','created_at',
                        'doc_tipo','doc_estado',
                        'cod_umedida','cant_mov'
                    )
                    ->where("cod_cabecera",$id)
                    ->where("nro_linea",'<>',0)
                    ->where("tpo_doc",$tpo_doc)
                    ->where('cod_empresa', session('cod_empresa'))
                    ->where('cod_almacen', session('cod_almacen'))
                    ->orderBy('ano_doc', request('sorted', 'DESC'))
                    ->orderBy('mes_doc', request('sorted', 'DESC'))
                    ->orderBy('nro_doc', request('sorted', 'DESC'))
                    ->get();

        $data = array();
        
        return PDF::loadView('reportes.comprobante', compact( 'detalles', 'salidas') )->save('pdf/'.$nompdf.'.pdf')->stream($nompdf.'.pdf');
        
        //$pdf = PDF::loadView('reportes.comprobante', compact( 'detalles', 'salidas'));
        //return $pdf->download($nompdf);
        //return view('reportes.comprobante', compact('salidas','detalles'));
    }

    public function comprobante_ant(Request $request){
        
        $sa = Salida::where('id', $request->id)->count();
        if($sa == 0){
            alert()->warning('El ID del documento no existe.', 'Alerta');
            return back();
        }
        $salidas = Salida::where('id', $request->id)->first();

        $nompdf = "orden-salida-".$request->id.".pdf";

        $detalles = DB::table('salidas_detalle')
                    ->select('salidas_detalle.id', 'salidas_detalle.idproducto', 'productos.nombre', 'salidas_detalle.cantidad', 'salidas_detalle.descripcion')
                    ->leftJoin('productos', 'productos.id', '=','salidas_detalle.idproducto')
                    ->where('salidas_detalle.idsalida','=',$request->id)
                    ->where('salidas_detalle.idsalida','=',$request->id)
                    ->orderBy('salidas_detalle.id','ASC')
                    ->get();

        //return PDF::loadView('reportes.comprobante', $data )->save('pdf/'.$codigoG.'.pdf')->stream($codigoG.'.pdf');
        //$pdf = PDF::loadView('reportes.comprobante', compact( 'detalles', 'salidas'));

        //return $pdf->download($nompdf);
        return view('reportes.comprobante');
    }

    public function create()
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["salidas"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

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

        return view('salidas.create',compact('monedas', 'tipos', 'numbers'));
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

        $datos = Salida::findOrFail($id);
        //$datos = DB::table("movimientos")->find($id);

        if(!$datos){
            return redirect()->route('salidas.index');
        }

        $d_fecha = Carbon::parse($datos->fecha)->format('Y-m-d');

        $tipos    = DB::table('tipo_de_cambio')->orderBy('id','asc')->get();
        
        $proyectos = Proyecto::where('id',$datos->proyectos_id)->first();

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

        return view('salidas.show',compact('datos', 'proyectos', 'tipos', 'empresa', 'items'));
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
            return redirect()->route('salidas.index');
        }

        $d_fecha = Carbon::parse($datos->fecha)->format('Y-m-d');

        $tipos    = DB::table('tipo_de_cambio')->orderBy('id','asc')->get();
        
        $proyectos = Proyecto::where('id',$datos->proyectos_id)->first();

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

        return view('salidas.edit',compact('datos', 'proyectos', 'tipos', 'empresa', 'items'));
    }

    public function update(Request $request, $idCab)
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
        //$doc_estado     = $request->input('doc_estado');
        $obs            = $request->input('obs');

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

            $cli = Proyecto::find($proyectos_id);
            
            $cli->cod_empresa = $cod_empresa;
            $cli->cod_usuario = $cod_usuario;
            $cli->cod_ruc     = $cod_ruc;
            $cli->nom_proy    = $cliente_doc;
            $cli->tipocomp    = $tipo_c;
            $cli->numerodoc   = $numerodoc;
            $cli->flag_activo = 1;
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
        $mov_cab = Movimientos::find($idCab);
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
            ->where('id','<>',$idCab)
            ->where('cod_cabecera','<>',$idCab)
            ->where('mes_doc', $m)
            ->where('ano_doc', $yy)
            ->where('origen', $origen)
            ->where('tpo_doc',$tpo_doc)
            ->where('cod_almacen',$cod_almacen)
            ->count();

        if($e > 0){

            return \Response::json(['error' => "El documento ya existe.","tipo"=>1], 404); 
            exit;
        }

        $mov_cab->ano_doc = $yy;
        $mov_cab->mes_doc = $m;
        $mov_cab->fecha   = $fecha;
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
        
        $mov_cab->nro_doc = $nro_doc;
        $mov_cab->nro_preing = $nro_preing;
        $mov_cab->cod_moneda = $cod_moneda2;
        
        $mov_cab->cod_usuario = \Auth::User()->id;
        $mov_cab->cta_cte = $proyectos_id;
        
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
        $mov_cab->updated_at   = Carbon::now();
        $mov_cab->doc_tipo     = $doc_tipo;
        $mov_cab->doc_estado   = $doc_estado;
        $mov_cab->obs          = $obs;
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


        // descontar estos productos en la tb. stoc
        /*$rs_prod = Movimientos::where("cod_cabecera",$idCab)
                    ->select('nro_doc','nro_ref','cod_artic', 'cant_mov')->get();

        $va   = array();
        $va2  = array();
        $vcan = array();
        
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

                        DB::table('stock_articulos_alm')
                            ->where('cod_empresa', $cod_empresa)
                            ->where('cod_almacen', $cod_almacen)
                            ->where('cod_artic', $id_art_reg)
                            ->increment('stock_alm', $dif);

                    }elseif($cant_reg == $cant_new){
                        //$t .= " cant_reg igual: $cant_reg -- $cant_new .";

                    }else{
                        // la $cant_new > $cant_reg
                        $dif = $cant_new - $cant_reg;
                        DB::table('stock_articulos_alm')
                            ->where('cod_empresa', $cod_empresa)
                            ->where('cod_almacen', $cod_almacen)
                            ->where('cod_artic', $id_art_reg)
                            ->decrement('stock_alm', $dif);
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
                ->where('cod_almacen', $cod_almacen)
                ->where('cod_artic', $_id)
                ->count();

            if($n_stock>0){
                DB::table('stock_articulos_alm')
                ->where('cod_empresa', $cod_empresa)
                ->where('cod_almacen', $cod_almacen)
                ->where('cod_artic', $_id)
                ->decrement('stock_alm', $_cant);

            }else{

                return \Response::json(['error' => "El producto no tiene registrado su stock","tipo"=>1], 404); 
                exit;
                
            }
        }*/

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
                $mov_det->serie_d        = $serie_d;
                $mov_det->flag_tipo      = $flag_tipo;
                $mov_det->proyectos_id   = $proyectos_id;
                $mov_det->responsable    = $responsable;

                $mov_det->cod_almacen = $cod_almacen;
                $mov_det->cod_subalm  = $cod_subalm;
                $mov_det->fecha = Carbon::now();
                //$mov_det->nro_ref = $cod_ruc2;
                $mov_det->nro_ref    = $nro_ref;
                
                $mov_det->nro_preing = $nro_preing;
                $mov_det->cta_cte = $proyectos_id; //$cod_ruc2;

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
                $mov_det->costo_mo =(float) $costo_mo ;
                $mov_det->costo_mn =(float) $costo_mn ;
                $mov_det->costo_me = (float)$costo_me ;
                $mov_det->costo_tot_mo = (float)$costo_tot_mo ;
                $mov_det->costo_tot_mn = (float)$costo_tot_mn ;
                $mov_det->costo_tot_me = (float)$costo_tot_me ;

                $mov_det->cod_cabecera = $idCab;

                $mov_det->cod_usuario  = \Auth::User()->id;
                $mov_det->updated_at   = Carbon::now();
                $mov_det->doc_tipo     = $doc_tipo;
                $mov_det->doc_estado   = $doc_estado;
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
                        ->select('nro_doc','nro_ref','cod_artic', 'cant_mov')->get();

            foreach ($rs_prod as $pp) {
                $id_art_reg = $pp->cod_artic;
                $cant_reg   = $pp->cant_mov;

                DB::table('stock_articulos_alm')
                            ->where('cod_empresa', session('cod_empresa'))
                            ->where('cod_almacen', session('cod_almacen'))
                            ->where('cod_artic', $id_art_reg)
                            ->increment('stock_alm', $cant_reg); 
            }

            Salida::where('cod_cabecera',$value)->delete();
        }

        
        Cache::flush();

        alert()->error('Registros borrados.','Eliminado');
        return back();
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

                    $rs = array('cod_ruc' => $cod_ruc, 'id_proy'=> $proyecto->id, 'proyecto' => $nom_proy, 'ok' => 'ok' );
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
