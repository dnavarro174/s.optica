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
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3">
                      <i class="mdi mdi-message-text"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('ingresos.index')}}">Partes de Ingreso</a></h4>
                      <div class="fluid-container">
                        <p>
                          Agregar productos al sistema. / Registro de boletas y facturas al sistema.
                        </p>
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3">
                      {{-- <i class="mdi mdi-account-plus text-info icon-lg"></i> --}}
                      <i class="mdi mdi-message-text"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('salidas.index', ['id'=> 1])}}">Vales de consumo</a></h4>
                      <div class="fluid-container">
                        <p>
                          Registrar salidas de productos a proyectos u obras.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3"><i class="mdi mdi-checkbox-marked-circle-outline"></i></div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('ctas_corrientes.index', ['id'=> 2]) }}">Guía de devolución</a></h4>
                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          Registrar una nueva guía de devolución.
                        </p>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center">
                    <div class="highlight-icon bg-light mr-3"><i class="mdi mdi-checkbox-marked-circle-outline"></i></div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{route('almacen.index')}}">Guía de transferencia</a></h4>
                      <div class="fluid-container">
                        <p class="card-text mb-0">
                          Registrar una nueva guía de transferencia.
                        </p>
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
