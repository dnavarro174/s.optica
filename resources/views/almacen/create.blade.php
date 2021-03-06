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
              <h4 class="card-title text-transform-none m-0">Crear Tienda</h4>
            </div>
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  {{-- <h4 class="card-title text-transform-none"> Tienda</h4> --}}

                  @if (session('alert'))
                      <div class="alert alert-success">
                          {{ session('alert') }}
                      </div>
                  @endif

                  <form class="forms-sample pr-4 pl-4" id="almacenForm" action="{{ route('almacen.store') }}" method="post">
                    {!! csrf_field() !!}
                    
                      <div class="form-group row">
                        <label for="almacen" class="col-sm-2 col-form-label d-block">Tienda <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                          <input type="text" required="" class="form-control" name="almacen" placeholder="Nombre de la Tienda *" value="{{ old('almacen') }}" />
                        </div>

                        <div class="col-sm-3">
                          <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input" id="defaultUnchecked" name="flag_costea" value="S">
                              <label class="custom-control-label mt-1" for="defaultUnchecked"> Costear Tienda</label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="direccion" class="col-sm-2 col-form-label d-block">Direcci??n </label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="direccion" placeholder="Direcci??n del Tienda" value="{{ old('direccion') }}" />
                        </div>
                      </div>

                      <div class="form-group row">

                        <label for="descripcion" class="col-sm-2 col-form-label d-block">Descripci??n</label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripci??n" class="form-control" name="descripcion" id="" cols="30" rows="10">{{ old('descripcion') }}</textarea>
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