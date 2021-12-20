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
              <h4 class="card-title">@if(session('cuenta_tipo') == 1) Clientes @else Proveedores @endif  <a class="btn btn-link" href="{{ route('maestros.index') }}"> <i class="mdi mdi-reply-all"></i>Menú</a></h4>
              
              <div class="row" id="capBusqueda" style="display: block;">
                <div class="col-xs-12 col-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-10 col-xs-12">
                        <input type="text" class="form-control text-uppercase" placeholder="BUSCAR" name="s" value="@if($text_search){{$text_search}} @endif">

                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar btn-link h4" href=' {{route('ctas_corrientes.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
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

                  <form action="{{ route('ctas_corrientes.eliminarVarios') }}" role='form' method="POST" id="form-delete">
                    {!! csrf_field() !!}

                    <div class="row">

                      <div class="col-xs-12  col-sm-8  mb-2">

                        @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                        <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                        @endif
                        <?php if(session('cuenta_tipo') == 1) {$id_tipo = 1;}else{$id_tipo = 2;} ?>
                        @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        <a href="{{ route('ctas_corrientes.create', ['id'=>$id_tipo]) }}" class="btn btn-dark"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                        @endif

                        @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                        <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                        @endif

                        

                      </div> {{-- end derecha --}}
                      
                      <div class="col-xs-12 col-sm-4 text-right mb-2">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $ctas_datos->firstItem() }} - {{ $ctas_datos->lastItem() }} de
                          {{ $ctas_datos->total() }}
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
                              <th style="width: 6%;">RUC</th>
                              <th style="width: 25%;">RAZÓN SOCIAL</th>
                              <th style="width: 15%;">CONTACTO</th>
                              <th style="width: 15%;">TELÉFONO</th>
                              <th style="width: 15%;">TELÉFONO 2</th>
                              <th style="width: 10%;">EMAIL</th>
                              <th style="width: 10%;">EMAIL 2</th>
                              <th style="width: 15%;">TIPO</th>
                              <th style="width: 5%;">ACTIVO</th>
                              {{-- <th class="sorting_desc aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" aria-sort="descending" style="width: 61px;">Estado</th> --}}
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($ctas_datos as $datos)
                            <tr role="row" class="odd">
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('ctas_corrientes.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif

                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('ctas_corrientes.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                  {{-- <form style="display: inline;" method="POST" action="{{ route('ctas_corrientes.destroy', $datos->id)}}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="button_submit" title="Eliminar"><img src="images/ico/trash.png" class="acciones" width="14" alt="edit icono"></button>
                                  </form> --}}
                                </td>
                                <td>{{ $datos->cod_ruc }}</td>
                                <td>{{ $datos->razon_social }}</td>
                                <td>{{ $datos->contacto_1 }}</td>
                                <td>{{ $datos->tele }}</td>
                                <td>{{ $datos->tele_contac }}</td>
                                <td>{{ $datos->e_mail }}</td>
                                <td>{{ $datos->e_mail_aux }}</td>
                                <td>
                                  @if($datos->tipo_persona == '01')
                                  <label class="badge badge-success">Natural</label>
                                  @endif
                                  @if($datos->tipo_persona == '02')
                                  <label class="badge badge-dark">Juridica</label>
                                  @endif
                                </td>
                                <td align="center">
                                  @if($datos->flag_activo == 'S')
                                  <i class="mdi mdi-account-circle text-success h4" title="Activo"></i>
                                  @else
                                  <i class="mdi mdi-account-circle text-secondary h4" title="Inactivo"></i>
                                  @endif
                                </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $ctas_datos->appends(request()->query())->links() !!}

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