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
              <h4 class="card-title">Productos 
                <a href="{{ route('menu_almacen.index')}}" class="btn btn-link"><i class="mdi mdi-reply-all"></i> Menú</a>
                <a href="{{ route('almacen.index')}}" class="btn btn-link"><i class="mdi mdi-reply-all"></i> Almacen</a></h4>
              <div class="row" id="capBusqueda" style="display: block;">
                <div class="col-xs-12 col-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-4 col-xs-12">
                        <input type="text" class="form-control text-uppercase" placeholder="BUSCAR" name="s" value="@if($text_search){{$text_search}} @endif">

                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar h4" href=' {{route('productos.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                          <?php } ?>
                      </div>
                      <div class=" col-sm-2 col-xs-12">
                        <select name="stock" class="form-control text-uppercase" onchange="submit();">
                          <option value="">RANGO DE STOCK</option>
                          <option value="1">0</option>
                          <option value="10">1 - 10</option>
                          <option value="50">11 - 50</option>
                          <option value="100">51 - 100</option>
                          <option value="all">100 +</option>
                        </select>
                      </div>
                      <div class=" col-sm-1 col-xs-12">
                        <select name="tipo" class="form-control text-uppercase" onchange="submit();">
                          <option value="">TIPO</option>
                          <option value="H">HERRAMIENTAS</option>
                          <option value="M">MATERIALES</option>
                        </select>
                      </div>
                      <div class=" col-sm-2 col-xs-12">
                        <select name="cat" class="form-control text-uppercase" onchange="submit();">
                          <option value="">CATEGORÍA</option>
                          @foreach($cats as $c)
                          <option value="{{$c->id}}">{{$c->categoria}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-xs-12 col-sm-1 text-right mb-4">
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
                     
                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" ><i class="mdi mdi-magnify text-white icon-md"></i>BUSCAR</button>
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

                  <form action="{{ route('productos.eliminarVarios') }}" role='form' method="POST" id="form-delete">
                    {!! csrf_field() !!}

                    <div class="row">

                      <div class="col-xs-12  col-sm-8  mb-4">

                        @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                        <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                        @endif

                        @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                          <a href="{{ route('productos.create') }}" class="btn btn-sm btn-dark"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                        @endif

                        @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                          <div class="btn-group" id="btn_2" role="group">
                              <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Reportes
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="top-start">
                                <a class="dropdown-item" href="{{ route('stock.stock_valorizado') }}">Stock valorizado</a>
                                <a class="dropdown-item" href="{{ route('kardex.create') }}">Kardex sin valorizar</a>
                                <a class="dropdown-item" href="{{ route('kardex_va.create') }}">Kardex valorizado</a>
                              </div>
                          </div>
                        @endif

                        <a href="{{ route('categorias.index') }}" class="btn btn-sm btn-info">Categorías</a>

                        @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                        <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                        @endif

                      </div> {{-- end derecha --}}
                      
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $productos_datos->firstItem() }} - {{ $productos_datos->lastItem() }} de
                          {{ $productos_datos->total() }}
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
                              <th style="width: 5%;">Código</th>
                              <th style="width: 20%;">Nombre</th>
                              <th style="width: 10%;">Marca</th>
                              <th style="width: 20%;">Categoría</th>
                              <th style="width: 5%;">U.M.</th>
                              <th style="width: 7%;" title="Stock Mínimo">S.Min</th>
                              <th style="width: 7%;" title="Stock Actual">S.Act</th>
                              <th style="width: 7%;" title="Precio de Compra">P.Compra</th>
                              <th style="width: 7%;" title="Precio de Venta">P.Venta</th>
                              <th class="text-center" style="width: 10%;">Tipo</th>
                              {{-- <th style="width: 5%;text-align:center;">Estado</th> --}}
                              <th style="width: 8%;">Registrado</th>
                              {{-- <th class="sorting_desc aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" aria-sort="descending" style="width: 61px;">Estado</th> --}}
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($productos_datos)==0)
                              <th colspan="12">No existe registros</th>
                            @else
                            @foreach ($productos_datos as $datos)
                            <tr role="row" class="odd" @if($datos->stock_total < $datos->stock_min) style="background: #ffbebe;" @endif>
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->cod_artic }}" data-icod_articd="{{ $datos->cod_artic }}"></td>
                              <td nowrap>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('productos.edit',$datos->cod_artic)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif

                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('productos.show',$datos->cod_artic)}}" class="">
                                    <i class="mdi mdi-eye text-dark icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                </td>
                                <td align="center">{{ $datos->cod_artic }}</td>
                                <td>{{ $datos->nombre }}</td>
                                <td>{{ $datos->marca }}</td>
                                <td>{{ $datos->Cat->categoria }}</td>
                                <td>{{ $datos->medida->dsc_umedida }}</td>
                                <td class="text-center">{{ number_format($datos->stock_min,2) }}</td>
                                <th class="text-center">{{ number_format($datos->stock_total,2) }}</th>
                                <th class="text-center">{{ $datos->precio_compra }}</th>
                                <th class="text-center">{{ $datos->precio_venta }}</th>
                                <td align="center">
                                  @if($datos->tipo_articulo == 'M')
                                  <label class="badge badge-primary btn-sm">Material</label>
                                  @endif
                                  @if($datos->tipo_articulo == 'H')
                                  <label class="badge badge-dark btn-sm">Herramienta</label>
                                  @endif
                                </td>
                                
                                {{-- <td align="center">
                                  @if($datos->flag_activo == 'S')
                                  <i class="mdi mdi-map-marker-circle text-success" title="Activo"></i>
                                  @else
                                  <i class="mdi mdi-map-marker-circle text-secondary" title="Activo"></i>
                                  @endif
                                </td> --}}
                                <td>{{ \Carbon\Carbon::parse($datos->fecha_hora)->format('d.m.Y') }}</td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                        {!! $productos_datos->appends(request()->query())->links() !!}

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