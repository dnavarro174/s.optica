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
            <div class="col-md-10 grid-margin strech-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Listado de Roles</h4>
                  <div class="row" id="capBusqueda" style="display: block;">
                    <div class="col-xs-12 col-12">
                      <form action="{{ route('roles.index') }}" method="GET">
                        <div class="form-row ">{{-- align-items-center --}}
                          <div class=" col-sm-10 col-xs-12">
                            <input type="text" class="form-control" placeholder="BUSCAR" name="s"  value="@if($text_search){{$text_search}} @endif">
                            {{-- @if($text_search){{$text_search}} @endif --}}
                            <?php
                               if (isset($_GET['s'])){ ?>
                               <a class="ml-2 small btn-cerrar h4" href=' {{route('roles.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
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

                      <form id="form-delete" method="post" style="display: inline;" action="{{route('roles.eliminarVarios')}}" >

                        <div class="row">{{-- cap: opciones --}}
                      
                          <div class="col-xs-12  col-sm-10 text-right mb-4">
                            <div class="form-row">
                                @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                                <a href="{{ route('roles.create') }}" class="btn btn-dark mr-2"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                                @endif
                              
                                @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                                <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                                @endif

                            </div>

                          </div> {{-- end derecha --}}
                          <div class="col-xs-12 col-sm-2 text-right mb-4">
                            <span class="small pull-left ptt-3">
                              <strong>Mostrando</strong>
                              {{ $roles_datos->firstItem() }} - {{ $roles_datos->lastItem() }} de
                              {{ $roles_datos->total() }}
                            </span>
                          </div>{{-- end izq --}}
                        </div> {{-- end cap: opciones --}}

                      <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                          <div class="col-sm-12">
                            <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                              <thead class="thead-dark">
                                <tr role="row">
                                  <th style="width:2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                                  <th style="width: 4%;"></th>
                                  <th style="width:2%;">Item</th>
                                  <th style="width: 25%;">Rol</th>
                                  <th style="width: 25%;">Descripción</th>
                                </tr>
                              </thead>
                              <tbody>
                                
                                @foreach ($roles_datos as $datos)
                                <tr role="row" class="odd">
                                  <td><input type="checkbox" class="form btn-delete" name="ids_roles[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                                    <td class="text-center">
                                      @if(@isset($permisos['permisos']['permiso']) and  $permisos['permisos']['permiso'] == 1)
                                      <a href="{{ route('roles.permisos',$datos->id)}}" class=""><img src="{{ asset('images/ico/key.png')}}" class="acciones" width="14" alt="permisos icono" title="Editar Permisos"></a>
                                      @endif
                                      @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                      <a href="{{ route('roles.show',$datos->id)}}" class=""><i class="mdi mdi-eye text-dark icon-md" title="Mostrar"></i></a>
                                      @endif
                                      @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                      <a href="{{ route('roles.edit',$datos->id)}}" class=""><i class="mdi mdi-pencil text-info icon-md" title="Editar"></i></a>
                                      @endif
                                    </td>
                                    <td>{{ $datos->id }}</td>
                                    <td>{{ $datos->rol }}</td>
                                    <td>{{ $datos->descripcion }}</td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                            {!! $roles_datos->appends(request()->query())->links() !!}
                            
                          </div>
                        </div>
                      </div>

                    </form>{{-- end close form --}}
        

                    </div>
                  </div>
                </div>
              </div>
              
            </div>
            
          </div>
        </div> <!-- end listado table -->

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


@endsection