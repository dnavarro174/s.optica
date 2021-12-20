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
      
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper pt-3">
          <div class="row justify-content-center">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12 ">{{-- offset-md-1 --}}
                  
                      <div class="d-flex justify-content-between mb-4">
                        <h4 class="card-title mb-0">Editar Parte de Ingreso Valorizado </h4>
                      </div>
                      
                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('ingresos.index') }}" class="btn btn-success">Regresar</a>

                      @else
              
                        <form class="forms-sample" id="ingresosForm2"  action="{{ route('ingresos.update', $datos->id) }}" method="post">
                            {{method_field('PUT')}}
                            {!! csrf_field() !!}
                          {!! csrf_field() !!}
                          @include ('ingresos.form-edit')
 
                        </form>
                      @endif

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

@endsection
@section('footer')
<script src="{{ asset('js/ingresos.js')}}"></script>

<link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.min.css?id=1') }}">
{{-- <link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.themes.min.css') }}"> --}}

<script src="{{ asset('js_auto/jquery.easy-autocomplete.js')}}"></script>
<script src="{{ asset('js/autocomplete.js')}}"></script>
@endsection