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
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12">
                  
                      <h4 class="card-title mb-4">Editar categoría</h4>
                      
                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                         <form class="forms-sample" id="estudiantesForm"  action="{{ route('categorias.update', $datos->id) }}" method="post">
                            {!! method_field('PUT') !!}
                            {!! csrf_field() !!}

                            <div class="form-group row">
                                <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">NOMBRE</label>
                                <div class="col-xs-12 col-lg-10">
                                    <input type="text" class="form-control text-uppercase" id="categoria" name="categoria" placeholder="Nombre de la categoría" required="" value="{{ $datos->categoria }}" >
                                    {!! $errors->first('categoria', '<span class=error>:message</span>') !!}
                                </div>
                            </div>


                            <div class="form-group row">
                              <div class="col-sm-12 text-center mt-4">
                                <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
                                <a href="{{ route('categorias.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
                                <a href="{{ route('productos.create')}}" class="btn btn-light">Registrar Nuevo Producto</a>
                              </div>

                            </div>


                        </form>
                      
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