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
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Tipo de Cambio  <a class="btn btn-link" href="{{ route('ingresos.index') }}"><i class="mdi mdi-reply-all"></i> Parte de Ingresos</a></h4>
              
              <div class="row" id="capBusqueda" style="display: block;">
                <div class="col-xs-12 col-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-10 col-xs-12">
                        <input type="text" class="form-control text-uppercase" placeholder="BUSCAR" name="s" value="@if($text_search){{$text_search}} @endif">

                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar btn-link h4" href=' {{route('tc.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                          <?php } ?>
                      </div>
                     
                    

                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar">BUSCAR</button>
                        <select onchange="submit()" class="form-control" name="pag" id="pag">
                          @if(isset($_GET['pag']))
                          <option value="15" @if(($_GET['pag'] == 15)) selected="" @endif>15</option>
                          <option value="20" @if(($_GET['pag'] == 20)) selected="" @endif>20</option>
                          <option value="30" @if(($_GET['pag'] == 30)) selected="" @endif>30</option>
                          <option value="50" @if(($_GET['pag'] == 50)) selected="" @endif>50</option>
                          <option value="100" @if(($_GET['pag'] == 100)) selected="" @endif>100</option>
                          <option value="500" @if(($_GET['pag'] == 500)) selected="" @endif>500</option>
                          @else
                          <option value="15">15</option><option value="20">20</option><option value="30" >30</option><option value="50" >50</option><option value="100">100</option><option value="500">500</option>{{-- <option value="-1" >Todos</option> --}}
                          @endif
                        </select>
                      </div>
                    </div>
                  </form>
                  
                  
                </div>
              </div>


              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
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

                  <form action="{{ route('tc.eliminarVarios') }}" role='form' method="POST" id="form-delete">
                    {!! csrf_field() !!}

                    <div class="row">

                      <div class="col-xs-12  col-sm-8  mb-2">

                        @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                        <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                        @endif
                        <?php if(session('cuenta_tipo') == 1) {$id_tipo = 1;}else{$id_tipo = 2;} ?>
                        @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        <a href="{{ route('tc.create', ['id'=>$id_tipo]) }}" class="btn btn-dark"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                        @endif

                        @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                        <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                        @endif

                        

                      </div> {{-- end derecha --}}
                      
                      <div class="col-xs-12 col-sm-4 text-right mb-2">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $tc_datos->firstItem() }} - {{ $tc_datos->lastItem() }} de
                          {{ $tc_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                      
                    </div> {{-- end row --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 3%;" class="sinpadding">
                                <input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1">
                              </th>
                              <th style="width: 2%;"></th>
                              <th style="width: 10%;">Fecha</th>
                              <th style="width: 5%;">Moneda</th>
                              <th style="width: 10%;">TC Soles</th>
                              <th style="width: 30%;">TC Dolares</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($tc_datos as $datos)
                            <tr role="row" class="odd">
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('tc.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif

                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('tc.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                </td>
                                <td><a href="{{ route('tc.edit',$datos->id)}}" class="">{!! \Carbon\Carbon::parse($datos->fecha)->format('d.m.Y') !!}</a></td>
                                <td>
                                  @if($datos->cod_moneda == 1)
                                  <label class="badge badge-primary">Soles</label>
                                  @endif
                                  @if($datos->cod_moneda == 2)
                                  <label class="badge badge-dark">Dolares</label>
                                  @endif

                                </td>
                                <td>{{ $datos->TC_compra_mn }}</td>
                                <td>{{ $datos->TC_me }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $tc_datos->appends(request()->query())->links() !!}

                      </div>
                    </div>
                  </div>
  
                  </form>
                  {{-- {{ Form::close() }} --}} {{-- end close form --}}

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