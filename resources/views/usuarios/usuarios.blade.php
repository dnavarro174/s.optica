@extends('layouts.theme')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layouts.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layouts.menutop_setting_panel')
      <!-- end menu_user -->
      
      <div class="main-panel">
        
        <div class="content-wrapper pt-0">
          <div class="row justify-content-center">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Listado de Usuarios</h4>
                  <div class="row" id="capBusqueda" style="display: block;">
                    <div class="col-xs-12 col-12">
                      
                      <form>
                        <div class="form-row ">{{-- align-items-center --}}
                          <div class=" col-sm-10 col-xs-12">
                            <input type="text" class="form-control" placeholder="BUSCAR" name="s"  value="">
                            {{-- @if($text_search){{$text_search}} @endif --}}
                            <?php
                               if (isset($_GET['s'])){ ?>
                               <a class="ml-2 small btn-cerrar h4" href=' {{route('usuarios.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                              <?php } ?>
                          </div>
                          
                          <div class=" col-sm-2 col-xs-12">
                            <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" ><i class="mdi mdi-magnify text-white icon-md"></i>BUSCAR</button>
                          </div>

                        </div>
                      </form>
                    </div>
                  </div>


                  @if(Session::has('error'))
                  <p class="alert alert-danger">{{ Session::get('error') }}</p>
                  @endif
                  {{-- @if(Session::has('success'))
                  <p class="alert alert-success">{{ Session::get('success') }}</p>
                  @endif --}}
                  @if (session('success'))
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class='mdi mdi-trophy'></i></strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                  @endif
                  @if (session('danger'))
                      
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class='mdi mdi-delete-sweep'></i></strong> {{ session('danger') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                  @endif
                  
                  <div class="row">
                    <div class="col-12">
                      <form action="{{ route('usuarios.eliminarVarios') }}" role='form' method="POST" id="form-delete">
                        {!! csrf_field() !!}
        
                      
                      <div class="row">{{-- cap: opciones --}}
                      
                          <div class="col-xs-12  col-sm-10 text-right mb-4">
                            <div class="form-row">
                                @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                                <a href="{{ route('usuarios.create') }}" class="btn btn-dark mr-2"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                                @endif
                              
                                @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                                <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                                @endif

                            </div>

                          </div> {{-- end derecha --}}
                          <div class="col-xs-12 col-sm-2 text-right mb-4">
                            <span class="small pull-left ptt-3">
                              <strong>Mostrando</strong>
                              {{ $usuarios_datos->firstItem() }} - {{ $usuarios_datos->lastItem() }} de
                              {{ $usuarios_datos->total() }}
                            </span>
                          </div>{{-- end izq --}}
                        </div> {{-- end cap: opciones --}}

                      <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                          <div class="col-sm-12 table-responsive-lg">
                            <table id="order-listing" class="table ">
                              <thead class="thead-dark">
                                <tr role="row">
                                  <th style="width:2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                                  <th style="width: 9%;"></th>
                                  <th style="width: 40%;">Usuario</th>
                                  <th style="width: 20%;">Email</th>
                                  <th style="width: 10%;" class="text-center">Estado</th>
                                  <th style="width: 10%;">FechaRegistro</th>
                                </tr>
                              </thead>
                              <tbody>
                                
                                @foreach ($usuarios_datos as $datos)
                                  @if($datos->id !== 1)
                                    <tr role="row" class="odd">
                                      <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                                      <td class="text-center">
                                          @if(@isset($permisos['roles']['permiso']) and  $permisos['roles']['permiso'] == 1)
                                          <a href="{{ route('usuarios.roles',$datos->id)}}" class="" >
                                            <i class="mdi mdi-key text-dark icon-md" title="Editar Roles de Usuario"></i>
                                          </a>
                                          @endif
                                          @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                          <a href="{{ route('usuarios.show',$datos->id)}}" class=""><i class="mdi mdi-eye text-dark icon-md" title="Mostrar"></i></a>
                                          @endif
                                          @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                          <a href="{{ route('usuarios.edit',$datos->id)}}" class="">
                                            <i class="mdi mdi-pencil text-info icon-md" title="Editar Usuario"></i>
                                          </a>
                                          @endif
                                        </td>
                                        
                                        <td>{{ $datos->name }}</td>
                                        <td>{{ $datos->email }}</td>{{-- categoria // @if(Auth::check()) ? yes : no @endif --}}
                                        <td class="text-center">
                                          @if($datos->estado == '1')

                                          <i class="mdi mdi-account-circle text-success h4" title="Activo"></i>

                                          @else
                                          <i class="mdi mdi-account-circle text-secondary h4" title="Inactivo"></i>

                                          @endif
                                        </td>
                                        <td>
                                        {!! \Carbon\Carbon::parse($datos->updated_at)->format('d.m.Y') !!}</td>
                                        
                                    </tr>
                                @endif
                                @endforeach
                              </tbody>
                            </table>
                            {!! $usuarios_datos->appends(request()->query())->links() !!}

                          </div>
                        </div>
                      </div>

                      </form> {{-- end close form --}}

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- end listado table -->
        </div>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layouts.footer')
        <!-- end footer.php -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
{{-- form importar --}}
<!--
<div class="modal fade ass" id="Modal_roles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      test
    </div>
  </div>
</div>
-->

<div class="modal fade" id="Modal_roles" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"></div>
  </div>
</div>
@endsection
