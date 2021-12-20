@extends('layouts.theme')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layouts.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial -->
      <div class="main-panel">
      <style>
      .bloque_login:hover{background:#f1f1f1;transition:all ease-out .5s;border: 1px solid #dee2e6!important}
      </style> 
        <div class="content-wrapper p-4 pt-2">
                  @if (session('alert'))
                      <div class="alert alert-success">
                          {{ session('alert') }}
                      </div>
                  @endif

          <div class="row">
            <div class="col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card">{{-- d-flex  --}}
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="card-title m-0">Tiendas</h5>
                    <span class="badge badge-dark  badge-lg mb-2">
                      <a class="text-white" href="{{ route('almacen.create') }}">Crear Tienda</a>
                    </span>
                    
                  </div>

                  @foreach ($almacen_datos as $j => $datos)
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
                 
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Productos con más movimientos</h5>
                  <div class="row border-bottom pb-3 mb-3">
                    <div class="col-12 py-4 my-3"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                      <canvas id="DashboardBarChart-1" style="height: 100px; display: block; width: 250px;" width="250" height="100" class="chartjs-render-monitor"></canvas>
                    </div>
                    <?php $t = 0; ?>
                    @foreach($productos as $prod)
                      <?php 
                      $e = $prod->suma;
                      $t += $e;
                      ?>
                    @endforeach
                    @foreach($productos as $k => $p)

                    <?php $a = $p->suma;
                          if($a == 0)$a = 1;
                          $b = ($a / $t)*100;
                    ?>
                    <div class="col-12 mt-3">
                      <div class="d-flex align-items-end">
                        <h1 class="display-4 font-weight-semibold mb-0">{{number_format($p->suma,0)}}</h1>
                        <h5 class="ml-3 mb-2">Productos</h5>
                      </div>
                      <p class="mt-0 mb-2">{{$p->nombre}}</p>
                      <div class="d-flex align-items-center">
                        <div class="progress progress-md w-100 mr-3">
                          <div class="progress-bar bg-success" role="progressbar" style="width: {{$b}}%" aria-valuenow="{{$b}}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-0">{{number_format($b,2)}}%</p>
                      </div>
                    </div>
                    @endforeach
                  </div>

                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Destinados a:</h5>
                  @foreach($proyectos as $a => $pr)
                  <?php $a = $a+1; 
                  $entrada = array("warning", "success", "dark", "danger", "primary");
                  ?>
                  <div class="d-flex align-items-start pb-3 pt-1 mb-4 border-bottom">
                    
                    <div class="wrapper w-100 pl-3">
                      <div class="d-flex align-items-center justify-content-between">
                        <span class="text-gray text-small">{{$pr->nom_proy}}</span>
                        <span class="badge badge-dark badge-lg mb-2">
                          S/ {{ number_format($pr->suma, 2, '.', ',')}}
                        </span>{{-- {{money_format('%=*(#10.2n', $pr->suma)}} --}}
                      </div>
                      {{-- <p>{{$pr->nom_proy}}</p> --}}
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>

          </div>


          <div class="row">
            {!! $almacen_datos->appends(request()->query())->links() !!}
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