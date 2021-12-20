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
                      {{-- <i class="mdi mdi-account-plus text-info icon-lg"></i> --}}
                      <i class="mdi mdi-message-text"></i>
                    </div>
                    <div class="wrapper">
                      <h4 class="card-text mb-0"><a href="{{ route('almacen.index')}}">Partes de ingreso</a></h4>
                      <div class="fluid-container">
                        <p>
                          Módulo de registros de productos, proveedores y stock.
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
                      <h4 class="card-text mb-0"><a href="{{ route('productos.index')}}">Vales de consumo</a></h4>
                      <div class="fluid-container">
                        <p>
                          Módulo de vale de consumo, registro de proyectos y responsable de proyectos.
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

@section('footer')
<script src="{{ asset('js_a/horizontal-menu.js') }}"></script>
@endsection