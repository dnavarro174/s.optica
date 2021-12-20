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
        <div class="content-wrapper p-4">
          <div class="row">

            <div class="col-md-6 col-sm-6">
              <div class="col-md-12 col-sm-12 grid-margin stretch-card">
                <div class="card">{{-- d-flex  --}}
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                      <h5 class="card-title m-0">Cajas</h5>
                      <span class="badge badge-dark  badge-lg mb-2">
                        <a class="text-white" href="{{ route('almacen.create') }}">CREAR CAJA</a>
                      </span>
                      
                    </div>
                    @php
                      $cajas_datos = array();
                    @endphp

                    @foreach ($cajas_datos as $j => $datos)
                    <?php $i = $j+1; 
                    $entrada = array("warning", "success", "dark", "danger", "primary");
                    ?>
                    <div class="d-flex align-items-start pb-3 pt-1 mb-4 border-bottom">
                      <img src="{{asset('images/brand/'.$i.'.png')}}" alt="brand logo">
                      <div class="wrapper w-100 pl-3">
                        <div class="d-flex align-items-center ">
                          <span class="badge badge-@if($i==1)warning @elseif($i==2)success @elseif($i==3)dark @elseif($i==4)danger @else primary @endif badge-lg mb-2">
                            <a class="text-white" href="{{ route('menu_almacen.index', array('cod_almacen'=>$datos->id)) }}">{{ $datos->almacen}}</a>
                          </span>
                          <span class="text-gray text-small"><a title="Editar Tienda" class="dropdown-item px-2" href="{{route('almacen.edit', $datos->id)}}" class="text-dark"><i class="mdi mdi-pencil text-dark"></i></a></span>
                          <span class="text-gray text-small">
                             <form style="display: inline;" method="POST" action="{{ route('almacen.destroy', $datos->id)}}">
                          {!! csrf_field() !!}
                          {!! method_field('DELETE') !!}
                          <button class="dropdown-item p-0" type="submit" title="Eliminar Tienda"><i class="mdi mdi-delete text-danger"></i></button>
                        </form>
                          </span>

                        </div>
                        <p><a class="text-dark" href="{{ route('menu_almacen.index', array('cod_almacen'=>$datos->id)) }}">{{$datos->direccion}}</a></p>
                      </div>
                    </div>
                    @endforeach

                    @php
                      $i = 1;
                    @endphp
                    <div class="d-flex align-items-start p-3 mb-4 border border-dark">
                      <div class="wrapper w-100 pl-3">
                        <div class="row">
                          <h5>Nombre de Caja</h5>
                        </div>
                        <div class="row ">
                          <div class="col-sm-6 px-0">
                            <p class="">
                              <a href="#" class="btn btn-sm btn-dark mt-2">
                                NUEVA SESIÓN
                              </a>
                            </p>
                          </div>
                          <div class="col-sm-6">
                            <p>Última Fecha de Cierre: 12/12/2021</p>
                            <p>Último Saldo de Caja al Cierre:<br> S/ 133,60 </p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="dropdown float-right">
                            <button class="btn btn-white dropdown-toggle pr-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones">
                              <i class="mdi mdi-chevron-down h3"></i>
                            </button>
                            
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 51px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item" href=""><i class="mdi mdi-brush"></i> Editar Evento</a>
                                <a class="dropdown-item disabled" href="">Ver</a>
                                <a class="dropdown-item" href=""><i class="mdi mdi-brush"></i> Pedidos</a>
                                <a class="dropdown-item" href=""><i class="mdi mdi-brush"></i> Sessiones</a>
                                  <form id="formEvento" style="display: inline;" method="POST" action="https://www.enc-ticketing.org/eventos/160">
                                  <input type="hidden" name="_token" value="3wH47Qu6TZyzWi7u1ofLahYZB7nD7O1sYiC8ElhY">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <button class="dropdown-item" type="submit" id="btnDeleteEvento"><i class="mdi mdi-delete"></i> Borrar</button>
                                </form>
                              </div>
                             
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row justify-content-center">
                      <div class="col-md-10 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">

                            <div class="row justify-content-center">
                              <div class="col-md-10">
                            
                                <h4 class="card-title mb-4">Crear Caja</h4>
                                
                                <form class="forms-sample" id="estudiantesForm" action="{{ route('categorias.store') }}" method="post">
                                    {!! csrf_field() !!}
                                    @include ('cajas.create')

                                  </form>

                              </div>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                   
                  </div>
                </div>
              </div>
            </div>

            <div class="row col-md-6 col-sm-6">
              <div class="col-md-6 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-light mr-3">
                        <i class="mdi mdi-book-multiple"></i>
                      </div>
                      <div class="wrapper">
                        <h4 class="card-text mb-0"><a href="{{ route('productos.index')}}">Productos</a></h4>
                        <div class="fluid-container">
                          <p>
                            Módulo principal del sistema, permite ver los productos y su stock.<br>
                            <strong class="text-dark">Total: {{$productos}}</strong>
                          </p>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-light mr-3"><i class="mdi mdi-note"></i></div>
                      <div class="wrapper">
                        <h4 class="card-text mb-0">Documentos</h4>
                        <div class="fluid-container">
                          <p class="card-text mb-0">
                            Registro de documentos del almacén.<br>
                            - <a href="{{route('ingresos.index')}}" class="btn btn-link p-0">Parte de ingreso</a><br>
                            - <a href="{{route('salidas.index')}}" class="btn btn-link p-0">Vale de consumo</a><br>
                            {{-- - <a href="{{route('gt.index')}}" class="btn btn-link p-0">Generar Transferencia</a><br>
                            - <a href="{{route('guia_devolucion.index')}}" class="btn btn-link p-0">Guía de devolución</a> --}}
                          </p>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-light mr-3"><i class="mdi mdi-gauge"></i></div>
                      <div class="wrapper">
                        <h4 class="card-text mb-0"><a href="{{route('operaciones.index')}}">Operaciones</a></h4>
                        <div class="fluid-container">
                          <p class="card-text mb-0">
                            Registrar operaciones internas.<br>
                            - <a href="{{route('calculo_costos.create')}}" class="btn btn-link p-0">Realizar cálculo de costos</a>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-light mr-3"><i class="mdi mdi-chart-pie"></i></div>
                      <div class="wrapper">
                        <h4 class="card-text mb-0"><a href="{{route('reportes.index_menu')}}">Reportes</a></h4>
                        <div class="fluid-container">
                          <p class="card-text mb-0">
                            Reportes de los productos.<br>
                            - <a href="{{route('kardex.create')}}" class="btn btn-link p-0">Kardex sin valorizar</a><br>
                            - <a href="{{route('kardex_va.create')}}" class="btn btn-link p-0">Kardex valorizado</a>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>

            

          </div>
          
          
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


  {{-- code js --}}

@endsection
