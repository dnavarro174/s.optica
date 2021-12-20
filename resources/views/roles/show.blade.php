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
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Roles</h4>
                  <p class="card-description">
                  </p>
                  
                  <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="usuario">Rol <span class="text-danger">*</span></label>
                        <input disabled="" type="text" class="form-control" id="rol" name="rol" placeholder="Rol" value="{{ $datos->rol }}" >
                        {!! $errors->first('rol', '<span class=error>:message</span>') !!}
                      </div>
                    </div>


                    
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="usuario">Descripción <span class="text-danger">*</span></label>
                        <input disabled="" type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" value="{{ $datos->descripcion }}" >
                        {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                      </div> 
                      
                    </div>


                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <a href="{{ route('roles.index')}}" class="btn btn-light">Volver al listado</a>
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