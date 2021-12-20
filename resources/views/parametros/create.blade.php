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
            <div class="col-md-9 pl-4">
              <h4 class="card-title text-transform-none m-0">Crear Almacén</h4>
            </div>
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  {{-- <h4 class="card-title text-transform-none"> Almacén</h4> --}}

                  @if (session('alert'))
                      <div class="alert alert-success">
                          {{ session('alert') }}
                      </div>
                  @endif

                  <form class="forms-sample pr-4 pl-4" id="almacenForm" action="{{ route('almacen.store') }}" method="post">
                    {!! csrf_field() !!}
                    
                      <div class="form-group row">
                        <label for="almacen" class="col-sm-2 col-form-label d-block">Almacén <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="almacen" placeholder="Nombre del Almacén *" value="{{ old('almacen') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="direccion" class="col-sm-2 col-form-label d-block">Dirección </label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="direccion" placeholder="Dirección del Almacén" value="{{ old('direccion') }}" />
                        </div>
                      </div>

                      <div class="form-group row">

                        <label for="descripcion" class="col-sm-2 col-form-label d-block">Descripción</label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripción" class="form-control" name="descripcion" id="" cols="30" rows="10">{{ old('descripcion') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>


                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        
                        <a href="{{ route('almacen.index') }}" class="btn btn-light">Atras</a>
                      </div>

                    </div>

                  </form>
                  
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