<?php
// php artisan make:middleware VerifyCsrfToken

namespace App\Http\Controllers;
use Auth;
use App\User;
use App\AccionesRolesPermisos;
use DB;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*$this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["productos"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }*/
        $emp = DB::table('ctrl_inv')->join('users as u','u.cod_empresa','=','ctrl_inv.cod_empresa')
                ->where('u.cod_empresa',\Auth::User()->cod_empresa)
                ->select('ctrl_inv.cod_empresa', 'ctrl_inv.nombre_empresa','ctrl_inv.sigla_empresa','ctrl_inv.ruc_empresa','ctrl_inv.direc_empresa')
                ->first();

        $nom_empresa = $emp->nombre_empresa;
        $sigla = $emp->sigla_empresa;
        $ruc = $emp->ruc_empresa;
        $direccion = $emp->direc_empresa;

        $empresa = array('nom_empresa'=>$nom_empresa, 'sigla'=>$sigla, 'ruc'=>$ruc, 'direccion'=>$direccion);

        $user = User::where('id',\Auth::User()->id)->first();
        session(['cod_empresa' => $user->cod_empresa, 'empresa'=>$empresa]);
        
        //Add session almacen
        $almacen = array('nombre'=>'', 'direccion'=>'');
        session(['cod_almacen'=> '', 'almacen'=>$almacen ]);
        
        //return view('web.home');
        return redirect('almacen');
    }

    public function maestros()
    {
        return view('menu.maestros');
    }

    public function documentos()
    {
        return view('menu.documentos');
    }

    public function operaciones()
    {
        return view('menu.operaciones');
    }

    public function reportes()
    {
        return view('menu.reportes');
    }
}
