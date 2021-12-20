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
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-7 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row justify-content-center">
                    <div class="col-md-10">
                  
                      <h4 class="card-title mb-4">Agregar Tipo de Cambio</h4>
                      

                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('categorias.index') }}" class="btn btn-success">Regresar</a>

                      @else
                        <form class="forms-sample" id="estudiantesForm" action="{{ route('tc.store') }}" method="post">
                          {!! csrf_field() !!}
                          
                          <div class="form-group row">
                            <input type="hidden" name="modo" value="1">
                              <label for="fecha" class="col-sm-4 col-form-label d-block">Fecha <span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                                <div id="datepicker-popup" class="input-group date datepicker">
                                  <input type="text" name="fecha" id="fecha" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ date('d/m/Y')  }}" required="">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                                </div>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="TC_compra_mn" class="col-sm-4 col-form-label d-block">Compra en Soles <span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                            <input type="text" required="" class="form-control" name="TC_compra_mn" placeholder="1" value="{{ old('TC_compra_mn') }}" />
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="TC_me" class="col-sm-4 col-form-label d-block">Compra en Dolares <span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                            <input type="text" required="" class="form-control" name="TC_me" placeholder="3.35" value="{{ old('TC_me') }}" />
                              </div>
                            </div>
                        
                        <div class="form-group row">
                          <div class="col-sm-12 text-center mt-4">
                            <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
                            <a href="{{ route('tc.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
                          </div>
                        </div>

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