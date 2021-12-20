<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use App\Producto, App\Categoria;
use App\Movimientos;
use App\AccionesRolesPermisos;
use Illuminate\Http\Request;
use Cache, Alert;

class ProductosController extends Controller
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
            $pag = 15;
        }

        if(session('cod_empresa') == false){
            return redirect()->route('almacen.index');
        }

        $text_search = $request->input('s');
        $tipo  = $request->get('tipo');
        $cat   = $request->get('cat');
        $stock = $request->get('stock');

        if($text_search){
            Cache::flush();
            $search = $request->get('s');

            $productos_datos = Producto::join('stock_articulos_alm as s', 's.cod_artic','=','articulos.cod_artic')
                ->select(
                    'articulos.cod_artic',
                    'articulos.cod_sunat',
                    'articulos.nombre',
                    'articulos.marca',
                    'articulos.stock_min',
                    'articulos.cod_categoria',
                    'articulos.cod_umedida',
                    'articulos.flag_activo',
                    'articulos.fecha_hora',
                    'articulos.tipo_articulo',
                    'articulos.precio_compra',
                    'articulos.precio_venta',
                    's.stock_alm as stock_total'
                )
            ->where('s.cod_empresa', session('cod_empresa'))
            ->where('s.cod_almacen', session('cod_almacen'))
            ->where(function ($query) use ($search) {
                    $query->where("articulos.nombre", "LIKE", '%'.$search.'%')
                    ->orWhere("articulos.descripcion", "LIKE", '%'.$search.'%')
                    ->orWhere("articulos.marca", "LIKE", '%'.$search.'%');
                    //->orWhere(DB::raw('CONCAT(ap_paterno," ", ap_materno,", ", nombres)'), 'LIKE' , '%'.$search.'%');
                })
            ->orderBy('articulos.cod_artic', request('sorted', 'DESC'))
            ->paginate($pag);

            //return session('cod_empresa')."--".session('cod_almacen');

        }elseif($tipo){
            $productos_datos = Producto::join('stock_articulos_alm as s', 's.cod_artic','=','articulos.cod_artic')
                ->select(
                    'articulos.cod_artic',
                    'articulos.cod_sunat',
                    'articulos.nombre',
                    'articulos.marca',
                    'articulos.stock_min',
                    'articulos.cod_categoria',
                    'articulos.cod_umedida',
                    'articulos.flag_activo',
                    'articulos.fecha_hora',
                    'articulos.tipo_articulo',
                    'articulos.precio_compra',
                    'articulos.precio_venta',
                    's.stock_alm as stock_total'
                )
            ->where('s.cod_empresa', session('cod_empresa'))
            ->where('s.cod_almacen', session('cod_almacen'))
            ->where("articulos.tipo_articulo", $tipo)
            ->orderBy('articulos.cod_artic', request('sorted', 'DESC'))
            ->paginate($pag);

        }elseif($cat){
            $productos_datos = Producto::join('stock_articulos_alm as s', 's.cod_artic','=','articulos.cod_artic')
                ->select(
                    'articulos.cod_artic',
                    'articulos.cod_sunat',
                    'articulos.nombre',
                    'articulos.marca',
                    'articulos.stock_min',
                    'articulos.cod_categoria',
                    'articulos.cod_umedida',
                    'articulos.flag_activo',
                    'articulos.fecha_hora',
                    'articulos.tipo_articulo',
                    'articulos.precio_compra',
                    'articulos.precio_venta',
                    's.stock_alm as stock_total'
                )
            ->where('s.cod_empresa', session('cod_empresa'))
            ->where('s.cod_almacen', session('cod_almacen'))
            ->where("articulos.cod_categoria", $cat)
            ->orderBy('articulos.cod_artic', request('sorted', 'DESC'))
            ->paginate($pag);

        }elseif($stock){
            if($stock == 1){
                $stock_ini = 0;
                $stock_fin = 0;
            }elseif($stock == 10){
                $stock_ini = 1;
                $stock_fin = 10;
            }elseif($stock == 50){
                $stock_ini = 11;
                $stock_fin = 50;
            }elseif($stock == 100){
                $stock_ini = 51;
                $stock_fin = 100;
            }else{
                $stock_ini = 101;
                $stock_fin = 100000;
            }
            $productos_datos = Producto::join('stock_articulos_alm as s', 's.cod_artic','=','articulos.cod_artic')
                ->select(
                    'articulos.cod_artic',
                    'articulos.cod_sunat',
                    'articulos.nombre',
                    'articulos.marca',
                    'articulos.stock_min',
                    'articulos.cod_categoria',
                    'articulos.cod_umedida',
                    'articulos.flag_activo',
                    'articulos.fecha_hora',
                    'articulos.tipo_articulo',
                    'articulos.precio_compra',
                    'articulos.precio_venta',
                    's.stock_alm as stock_total'
                )
            ->where('s.cod_empresa', session('cod_empresa'))
            ->where('s.cod_almacen', session('cod_almacen'))
            ->where("s.stock_alm", '>=', $stock_ini)
            ->where("s.stock_alm", '<=', $stock_fin)
            ->orderBy('articulos.nombre', request('sorted', 'ASC'))
            ->orderBy('s.stock_alm', request('sorted', 'ASC'))
            ->paginate($pag);

        }else{

            $key = 'productos.page.'.request('page', 1);
            $productos_datos = Cache::rememberForever($key, function() use ($pag){
                return Producto::join('stock_articulos_alm as s', 's.cod_artic','=','articulos.cod_artic')
                ->select(
                    'articulos.cod_artic',
                    'articulos.cod_sunat',
                    'articulos.nombre',
                    'articulos.marca',
                    'articulos.stock_min',
                    'articulos.cod_categoria',
                    'articulos.cod_umedida',
                    'articulos.flag_activo',
                    'articulos.fecha_hora',
                    'articulos.tipo_articulo',
                    'articulos.precio_compra',
                    'articulos.precio_venta',
                    's.stock_alm as stock_total'
                )
                ->where('s.cod_empresa', session('cod_empresa'))
                ->where('s.cod_almacen', session('cod_almacen'))
                ->orderBy('articulos.cod_artic', request('sorted', 'DESC'))
                /*->orderBy('articulos.nombre', request('sorted', 'ASC'))
                ->orderBy('articulos.marca', request('sorted', 'ASC'))*/
                ->paginate($pag);
            });
        }

        $cats = Categoria::all();

        return view('productos.productos', compact('productos_datos', 'permisos', 'text_search', 'cats'));
      
    }

    public function generate_numbers($start, $count, $digits) {
       $result = array();
       for ($n = $start; $n < $start + $count; $n++) {
          $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);
       }
       return $result;
    }
    
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

        return view('productos.create', compact('categorias', 'medidas', 'numbers'));
    }

 
    public function store(Request $request)
    {
        $this->validate($request,[
            'precio_compra'=>" required|regex:/^\d+(\.\d{1,2})?$/",
            'precio_venta'=>" required|regex:/^\d+(\.\d{1,2})?$/",
            //'cboEstado'=>'required',
        ]);

        $precio_compra = number_format($request->precio_compra, 2);
        $precio_venta  = number_format($request->precio_venta, 2);

        DB::table('articulos')->insert([
            'cod_empresa'  => $request->session()->get('cod_empresa'),
            'cod_almacen'  => session('cod_almacen'),
            'cod_usuario'  => \Auth::User()->id,
            'cod_cencosto' => 0, // en add prod = 0;
            'cod_sunat'     => $request->input('cod_sunat'),
            'nombre'        => mb_strtoupper($request->input('nombre')),
            'ubicacion'     => mb_strtoupper($request->input('ubicacion')),
            'descripcion'   => mb_strtoupper($request->input('descripcion')),
            'marca'         => mb_strtoupper($request->input('marca')),
            'tipo_articulo' => $request->input('tipo_articulo'),
            'cod_categoria' => $request->input('cod_categoria'),
            'cod_umedida'   => $request->input('cod_umedida'),
            'stock_min'     => $request->input('stock_min'),
            'precio_compra' => $precio_compra,
            'precio_venta'  => $precio_compra,
            'fecha_hora'    => Carbon::now(),
            'afecto_igv'    => 'S', // N
            'flag_act_stock'=> 'S', // N
            'flag_mov'      => 'S', // N - S
            'flag_activo'   => 'S', // N - S
            'flag_facturado'=> 'S', // N - S

            /*'stock_total' => $request->input('stock_total'),
            'stock_trans' => $request->input('stock_trans'),
            'stock_min' => $request->input('stock_min'),
            'stock_max' => $request->input('stock_max'),
            'costo_mn' => $request->input('costo_mn'),
            'costo_me' => $request->input('costo_me'),
            'costo_e_mn' => $request->input('costo_e_mn'),
            'costo_e_me' => $request->input('costo_e_me'),
            */
        ]);

        $cod_art = DB::getPdo()->lastInsertId();

        DB::table('stock_articulos_alm')->insert([
                        'cod_empresa'   => session('cod_empresa'),
                        'cod_almacen'   => session('cod_almacen'),
                        'cod_artic'     => $cod_art,
                        'stock_alm'     => 0
                    ]);

        Cache::flush();
        alert()->success('Registro Grabado.','Mensaje');
        return redirect()->route('productos.index');
    }

    public function show($id)
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

        return view('productos.show',compact('productos_datos', 'categorias', 'medidas'));
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

        $categorias = DB::table('categorias')->orderBy('categoria','asc')->get();
        $medidas    = DB::table('unidad_medida')->orderBy('cod_umedida','asc')->get();

        $productos_datos = Producto::where('cod_artic',$id)->firstOrFail();

        return view('productos.edit',compact('productos_datos', 'categorias', 'medidas'));
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
        //$s = $request->input('stock_min');
        //dd(floatval($s));
        //DB::table('productos')->where('id',$id)->update([
        DB::table('articulos')->where('cod_artic', $id)->update([
            'cod_empresa'  => $request->session()->get('cod_empresa'),
            'cod_almacen'  => session('cod_almacen'),
            'cod_usuario'  => \Auth::User()->id,
            'cod_cencosto' => 0, // en add prod = 0;
            'cod_sunat'     => $request->input('cod_sunat'),
            'nombre'        => mb_strtoupper($request->input('nombre')),
            'ubicacion'     => mb_strtoupper($request->input('ubicacion')),
            'descripcion' => mb_strtoupper($request->input('descripcion')),
            'marca'         => mb_strtoupper($request->input('marca')),
            'tipo_articulo' => $request->input('tipo_articulo'),
            'cod_categoria' => $request->input('cod_categoria'),
            'cod_umedida'   => $request->input('cod_umedida'),
            'stock_min'     => $request->input('stock_min'),
            'fecha_hora'    => Carbon::now(),
            'afecto_igv'    => 'S', // N
            'flag_act_stock' => 'S', // N
            'flag_mov'      => 'S', // N - S
            'flag_activo'   => 'S', // N - S
            'flag_facturado' => 'S', // N - S

            /*'stock_total' => $request->input('stock_total'),
            'stock_trans' => $request->input('stock_trans'),
            'stock_min' => $request->input('stock_min'),
            'stock_max' => $request->input('stock_max'),

            'costo_mn' => $request->input('costo_mn'),
            'costo_me' => $request->input('costo_me'),
            'costo_e_mn' => $request->input('costo_e_mn'),
            'costo_e_me' => $request->input('costo_e_me'),

            um_alterna
            factor_conv  // quitar - NC / NO CONSIDERAR
            ope_conv // NC
            */
        ]);

        Cache::flush();
        alert()->success('Registro Actualizado.','Mensaje');
        return redirect()->route('productos.index');

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
            $a = Movimientos::where('cod_artic',$value)->count();
            if($a>0){
                alert()->warning('Artículo con documentos asociados','Mensaje');
                return back();
            }
            Producto::where('cod_artic',$value)
                    ->delete();
        }

        Cache::flush();

        alert()->error('Registros borrados.','Eliminado');
        //return redirect()->route('productos.index');
        return back();
        //return back()->with('danger','Registros borrados.');
    }


}
