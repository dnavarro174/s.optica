<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Usuario;
use App\Roles;
use App\UsuarioRol;
use App\AccionesRolesPermisos;
use Auth;

class UsuariosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Carbon::setLocale('es');
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["inicio"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "usuarios";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $usuarios_datos = Usuario::where("name", "LIKE", '%'.$search.'%')
            ->orWhere("email", "LIKE", '%'.$search.'%')
            ->orWhere("created_at", "LIKE", '%'.$search.'%')
            ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

        }else{

            $key = 'usuarios.page.'.request('page', 1);
            $usuarios_datos = Cache::rememberForever($key, function(){
                return Usuario::orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

            });
        }

        //$usuarios_datos = Usuario::orderBy('id','desc')->get();
        return view('usuarios.usuarios',compact('usuarios_datos','permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["nuevo"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
        ]);

        $verMail = Usuario::where("email",$request->input('email'))->first();

        if(!($verMail)){
            $usuario = new Usuario();
            $usuario->name  = $request->input('name');
            $usuario->email = $request->input('email');
            $usuario->password    = bcrypt($request->input('password'));
            $usuario->estado      = $request->input('cboEstado');
            $usuario->created_at  = Carbon::now();
            $usuario->updated_at  = Carbon::now();
            $usuario->cod_empresa = session('cod_empresa');
            $usuario->save();

            Cache::flush();

            //alert()->success('Registro grabado.','Mensaje Satisfactorio');

            return redirect()->route('usuarios.index')->with('success', 'Registro grabado');
        }else{

            return redirect()->back()->with('success', 'Email ya existe en el sistema.');
            //return redirect()->route('usuarios.create');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$usuarios_datos = Usuario::where('id',$id)->first();
        $usuarios_datos = Usuario::findOrFail($id);

        return view('usuarios.show',compact('usuarios_datos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["editar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $usuarios_datos = Usuario::where('id',$id)->first();
        ///dd($usuarios_datos);
        return view('usuarios.edit',compact('usuarios_datos'));
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
        //dd($request->input('password'));
        if(!is_null($request->input('password'))){

            DB::table('users')->where('id',$id)->update([
                 'name'        => $request->input('name'),
                 //'email'     => $request->input('email'),
                 'estado'      => $request->input('cboEstado'),
                 'password'    => bcrypt($request->input('password')),
                 'cod_empresa' => session('cod_empresa'),
                 'updated_at'  => Carbon::now()
            ]);

        }else{

            DB::table('users')->where('id',$id)->update([
                 'name'        => $request->input('name'),
                 'estado'      => $request->input('cboEstado'),
                 'cod_empresa' => session('cod_empresa'),
                 'updated_at'  => Carbon::now()
            ]);

        }
            Cache::flush();

            //alert()->success('Registro Actualizado.','Mensaje Satisfactorio');

            return redirect()->route('usuarios.index')->with('success','Usuario Modificado');
        
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
    {   $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["usuarios"]["permisos"]["eliminar"]   ) ){  
            Auth::logout();
            return redirect('/login');
        }

        $tipo_doc = $request->tipo_doc;
        foreach ($tipo_doc as $value) {
            UsuarioRol::where('user_id',$value)->delete();            
            Usuario::where('id',$value)->delete();
        }

        Cache::flush();
        //alert()->error('Registros borrados.','Eliminado');
        //return redirect()->route('usuarios.index');
        return redirect()->route('usuarios.index')->with('error','Usuario Eliminado');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roles($id)
    {   
        $usuarios_datos = Usuario::where('id',$id)->first(); 
        $roles = Roles::orderBy("rol","ASC")->get();

        $rolesUs = DB::table('user_role')
                    ->where('user_id', $id)
                    ->get();       

        return view("usuarios.roles", 
            [   'usuarios_datos' => $usuarios_datos, 
                'roles'          => $roles,
                'rolesUs'        => $rolesUs
            ]
        );        
    }

    //public function eliminarVarios(Request $request)
    public function storeRoles(Request $request)
    {   
       
        try {
            if( !($request["cboRol"])  ){
                return \Response::json(['error' => "Elegir al menos un rol."], 404); 
                exit;                    
            }
            $id = $request['id'];

            DB::table('user_role')->where('user_id', $id)->delete();

            for($i=0; $i< count($request["cboRol"]) ; $i++){
                $rol = new UsuarioRol();
                $rol->user_id = $id;
                $rol->role_id = $request["cboRol"][$i];
                $rol->save(); 
            }
            
            Cache::flush();
                
        }
        catch (\Exception $e) {
            return \Response::json(['error' => $e->getMessage() ], 404); 
        }        
    }    
}
