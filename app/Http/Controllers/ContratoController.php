<?php

namespace App\Http\Controllers;

use App\Contrato;

use DB;
use Auth;
use Carbon\Carbon;
use App\Producto, App\Categoria, App\CtaCorrientes, App\laboratorio;
use App\Movimientos;
use App\AccionesRolesPermisos;
use Illuminate\Http\Request;
use Cache, Alert;

class ContratoController extends Controller
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

        if(session('cod_empresa') == false){ return redirect('/'); }
        if(session('cod_almacen') == false){ return redirect('/'); }
        
        //return session('cod_empresa')." - ".session('cod_almacen')." - ";
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

        if($request->get('pag')){
            Cache::flush();
            session(['pag'=> $request->get('pag') ]);
            $pag = session('pag');
        }else{
            $pag = 5;
        }

        if(session('cod_empresa') == false){
            return redirect()->route('almacen.index');
        }

        $text_search = $request->input('s');
        $estado = $request->input('e');
        $tipo  = $request->get('tipo');
        $cat   = $request->get('cat');
        $stock = $request->get('stock');

        if($text_search){
            Cache::flush();
            $search = $request->get('s');

            /*SELECT c.id,c.cod_empresa,c.cod_almacen,c.descripcion,c.medida_od,c.medida_oi,
                c.medida_add,c.medida_dip,c.precio_total,c.acuenta,c.saldo,c.created_at,cc.razon_social,cc.cod_ruc
                FROM contratos AS c INNER JOIN cuentas_corrientes as cc ON c.cliente_id=cc.cod_ruc;
                */
            $contratos_datos = Contrato::join('cuentas_corrientes as cc', 'cc.cod_ruc','=','contratos.cliente_id')
                ->select(
                    'contratos.id',
                    'contratos.cod_empresa',
                    'contratos.cod_almacen',
                    'contratos.descripcion',
                    'contratos.medida_od',
                    'contratos.medida_oi',
                    'contratos.medida_add',
                    'contratos.medida_dip',
                    'contratos.precio_total',
                    'contratos.acuenta',
                    'contratos.saldo',
                    'contratos.estado',
                    'contratos.created_at',
                    'cc.razon_social',
                    'cc.cod_ruc'
                )
            ->where('contratos.cod_empresa', session('cod_empresa'))
            ->where('contratos.cod_almacen', session('cod_almacen'))
            ->where(function ($query) use ($search) {
                    $query->where("contratos.descripcion", "LIKE", '%'.$search.'%')
                    ->orWhere("cc.razon_social", "LIKE", '%'.$search.'%')
                    ->orWhere("cc.cod_ruc", "LIKE", '%'.$search.'%');
                    //->orWhere(DB::raw('CONCAT(ap_paterno," ", ap_materno,", ", nombres)'), 'LIKE' , '%'.$search.'%');
                })
            ->orderBy('contratos.id', request('sorted', 'DESC'))
            ->paginate($pag);

            //return session('cod_empresa')."--".session('cod_almacen');

        }elseif($estado>0){
            $contratos_datos = Contrato::join('cuentas_corrientes as cc', 'cc.cod_ruc','=','contratos.cliente_id')
                ->select(
                    'contratos.id',
                    'contratos.cod_empresa',
                    'contratos.cod_almacen',
                    'contratos.descripcion',
                    'contratos.medida_od',
                    'contratos.medida_oi',
                    'contratos.medida_add',
                    'contratos.medida_dip',
                    'contratos.precio_total',
                    'contratos.acuenta',
                    'contratos.saldo',
                    'contratos.estado',
                    'contratos.created_at',
                    'cc.razon_social',
                    'cc.cod_ruc'
                )
            ->where('contratos.cod_empresa', session('cod_empresa'))
            ->where('contratos.cod_almacen', session('cod_almacen'))
            ->where('contratos.estado', $estado)
            ->orderBy('contratos.id', request('sorted', 'DESC'))
            ->paginate($pag);
        }else{

            $key = 'contratos.page.'.request('page', 1);
            $contratos_datos = Cache::rememberForever($key, function() use ($pag){
                return Contrato::join('cuentas_corrientes as cc', 'cc.cod_ruc','=','contratos.cliente_id')
                ->select(
                    'contratos.id',
                    'contratos.cod_empresa',
                    'contratos.cod_almacen',
                    'contratos.descripcion',
                    'contratos.medida_od',
                    'contratos.medida_oi',
                    'contratos.medida_add',
                    'contratos.medida_dip',
                    'contratos.precio_total',
                    'contratos.acuenta',
                    'contratos.saldo',
                    'contratos.estado',
                    'contratos.created_at',
                    'cc.razon_social',
                    'cc.cod_ruc'
                )
                ->where('contratos.cod_empresa', session('cod_empresa'))
                ->where('contratos.cod_almacen', session('cod_almacen'))
                ->orderBy('contratos.id', request('sorted', 'DESC'))
                /*
                ->orderBy('articulos.marca', request('sorted', 'ASC'))*/
                ->paginate($pag);
            });
        }

        

        return view('contratos.contratos', compact('contratos_datos', 'permisos', 'text_search'));
      
    }

    public function generate_numbers($start, $count, $digits) {
       $result = array();
       for ($n = $start; $n < $start + $count; $n++) {
          $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);
       }
       return $result;
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

        if(session('cod_almacen') == false){
            return redirect()->route('almacen.index');
        }

        $nro = DB::table('articulos')->select('cod_artic')->orderBy('cod_artic', 'desc')->count();

        if($nro==0){
            $nro_doc = 1;
        }else{
            $nro_doc = DB::table('articulos')->select('cod_sunat')->orderBy('cod_artic', 'desc')->limit(1)->first();
            $nro_doc = $nro_doc->cod_sunat;
            $nro_doc = preg_replace('/^0+/', '', $nro_doc);

            $nro_doc = ($nro_doc == 0 )?1:$nro_doc+1;
        }

        $numbers = $this->generate_numbers($nro_doc, 1, 6);
        $numbers = $numbers[0];

        $categorias = DB::table('categorias')->orderBy('categoria','asc')->get();
        $medidas    = DB::table('unidad_medida')->orderBy('cod_umedida','asc')->get();

        return view('contratos.create', compact('categorias', 'medidas', 'numbers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$this->validate($request,[
            'codigo'=>'required',
            //'cboEstado'=>'required',
        ]);*/

        try{

            /*$flight = new Flight;
            $flight->name = $request->name;
            $flight->save();*/
            $dni = $request->cod_ruc2;
            $id  = $request->_id;

            $precio = number_format($request->precio, 2);//, ".", ","
            $acuenta = number_format($request->acuenta, 2);
            $saldo = number_format($request->saldo, 2);

            Cache::flush();
            if($id == 0) { // NEW
                $contrato = new Contrato;
                $contrato->descripcion = $request->descripcion;
                $contrato->cod_empresa = $request->session()->get('cod_empresa');
                $contrato->cod_almacen = session('cod_almacen');
                $contrato->cod_usuario = \Auth::User()->id;
                $contrato->cliente_id  = $dni;
                $contrato->medida_od    = $request->medida_od;
                $contrato->medida_oi    = $request->medida_oi;
                $contrato->medida_add   = $request->medida_add;
                $contrato->medida_dip   = $request->medida_dip;
                $contrato->precio_total = $precio;
                $contrato->acuenta      = $acuenta;
                $contrato->saldo        = $saldo;
                $contrato->estado       = $request->estado;// 1:pendiente 2: entregado
                $contrato->created_at   = Carbon::now();
                $contrato->updated_at   = Carbon::now();
                $contrato->save();

                return 1;
                
            }else{ // EDIT
                $contrato = Contrato::find($id);
                $contrato->descripcion = $request->descripcion;
                $contrato->cod_empresa = $request->session()->get('cod_empresa');
                $contrato->cod_almacen = session('cod_almacen');
                $contrato->cod_usuario = \Auth::User()->id;
                $contrato->cliente_id  = $dni;
                $contrato->medida_od    = $request->medida_od;
                $contrato->medida_oi    = $request->medida_oi;
                $contrato->medida_add   = $request->medida_add;
                $contrato->medida_dip   = $request->medida_dip;
                $contrato->precio_total = $precio;
                $contrato->acuenta      = $acuenta;
                $contrato->saldo        = $saldo;
                $contrato->estado       = $request->estado;// 1:pendiente 2: entregado
                //$contrato->created_at   = Carbon::now();
                $contrato->updated_at   = Carbon::now();
                $contrato->save();

                return 2;
            }

        }catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 

        
       /* $cod_art = DB::getPdo()->lastInsertId();

        DB::table('stock_articulos_alm')->insert([
                        'cod_empresa'   => session('cod_empresa'),
                        'cod_almacen'   => session('cod_almacen'),
                        'cod_artic'     => $cod_art,
                        'stock_alm'     => 0
                    ]);*/

        
        //alert()->success('Registro Grabado.','Mensaje');
        //return redirect()->route('contratos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function show(Contrato $contrato)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["productos"]["permisos"]["mostrar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $categorias = DB::table('categorias')->orderBy('categoria','asc')->get();
        $medidas    = DB::table('unidad_medida')->orderBy('cod_umedida','asc')->get();

        $productos_datos = Producto::where('cod_artic',$id)->firstOrFail();

        return view('contratos.show',compact('productos_datos', 'categorias', 'medidas'));
    }

    public function editContrato($id){
        //$datos = Contrato::where('id',$id)->firstOrFail();
        $datos = Contrato::join('cuentas_corrientes as cc', 'cc.cod_ruc','=','contratos.cliente_id')
                ->select(
                    'contratos.id',
                    'contratos.cod_empresa',
                    'contratos.cod_almacen',
                    'contratos.descripcion',
                    'contratos.medida_od',
                    'contratos.medida_oi',
                    'contratos.medida_add',
                    'contratos.medida_dip',
                    'contratos.precio_total',
                    'contratos.acuenta',
                    'contratos.saldo',
                    'contratos.created_at',
                    'cc.razon_social',
                    'cc.cod_ruc'
                )
            ->where('contratos.cod_empresa', session('cod_empresa'))
            ->where('contratos.cod_almacen', session('cod_almacen'))
            ->where('contratos.id', $id)
            
            ->firstOrFail();
            //->orderBy('contratos.id', request('sorted', 'DESC'))
            //->paginate($pag);
        
        return $datos;
    }

    public function edit(Contrato $contrato)
    {
        $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["productos"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $categorias = DB::table('categorias')->orderBy('categoria','asc')->get();
        $medidas    = DB::table('unidad_medida')->orderBy('cod_umedida','asc')->get();

        $productos_datos = Producto::where('cod_artic',$id)->firstOrFail();

        return view('contratos.edit',compact('productos_datos', 'categorias', 'medidas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contrato $contrato)
    {
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contrato $contrato)
    {
        //
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
            
            Contrato::where('id',$value)
                    ->delete();
        }

        Cache::flush();

        //alert()->error('Registros borrados.','Eliminado');
        return back();
    }

    public function clienteAdd($tc_id)
    {
        
        return view('contratos.add_cliente', compact('tc_id')); 
    }
    

    public function clienteStore(Request $request){

        try {

            $tc_id = $request->input('tc_id');

            $cod_ruc = trim($request->input('cod_ruc'));
            $razon_social = $request->input('razon_social');
            $direccion = $request->input('direccion');
            $tele = $request->input('tele');
            $edad = $request->input('edad');

            /*$v = substr($cod_ruc,0,2);
            
            if($v=="10"){
                $tipo_persona = "01";
                $tipo_docum   = "N";
            }elseif($v=="20"){
                $tipo_persona = "02";
                $tipo_docum   = "J";
            }else{
                return \Response::json(['error' => "Error: El RUC debe iniciar con 10 o 20" ], 404); 
            }*/
            $tipo_persona = "01";
            $tipo_docum   = "N";

                if($tc_id==0){//NEW
                    
                    $n = CtaCorrientes::where('cod_ruc', $cod_ruc)->count();
                    if($n > 0){
                        return \Response::json(['error' => "El Cliente con DNI: $cod_ruc ya existe en la Base de Datos." ], 404); 
                    }

                    $actividad = new CtaCorrientes() ;
                    $actividad->cod_empresa = 1;//$request->session()->get('cod_empresa');
                    $actividad->cod_usuario = \Auth::User()->id;
                    $actividad->cod_ruc = $cod_ruc;
                    $actividad->razon_social = mb_strtoupper($razon_social);
                    $actividad->direccion = mb_strtoupper($direccion);
                    $actividad->tele = $tele;
                    $actividad->edad = $edad;
                    $actividad->flag_tipo = 2;
                    $actividad->tipo_persona = $tipo_persona;
                    $actividad->tipo_docum = $tipo_docum;
                    $actividad->fecha_hora = Carbon::now();
                    $actividad->created_at = Carbon::now();
                    $actividad->updated_at = Carbon::now();
                    $actividad->save();

                    $rs = array('cod_ruc' => $cod_ruc, 'razon_social' => $razon_social, 'ok' => 'ok', 'edad'=>$edad, 'tele'=>$tele );
                    return $rs;
                }

                $rs = array('cod_ruc' => $cod_ruc, 'razon_social' => $razon_social, 'ok' => 'no' );
                return $rs;

            }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 
    }

    public function laboratorioAdd($tc_id)
    {
        
        return view('contratos.add_laboratorio', compact('tc_id')); 
    }
    

    public function laboratorioStore(Request $request){

        try {

            $tc_id = $request->input('tc_id');
            $laboratorio = $request->input('laboratorio');


                if($tc_id==0){//NEW
                    
                    $n = Laboratorio::where('laboratorio', $laboratorio)->count();
                    if($n > 0){
                        return \Response::json(['error' => "El laboratorio: $laboratorio ya existe en la Base de Datos." ], 404); 
                    }

                    $lab = new Laboratorio() ;
                    $lab->laboratorio = $laboratorio;
                    $lab->save();

                    $rs = array('laboratorio' => $laboratorio,'id_laboratorio' => $lab->id, 'ok' => 'ok');
                    return $rs;
                }

                $rs = array('laboratorio' => $laboratorio,'id_laboratorio' => 0, 'ok' => 'no' );
                return $rs;

            }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        } 
    }
}
