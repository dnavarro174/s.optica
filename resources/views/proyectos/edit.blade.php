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
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12">
                  
                      <h4 class="card-title mb-4">Editar Proyecto</h4>
                      
                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                         <form class="forms-sample" id="estudiantesForm"  action="{{ route('proyectos.update', $datos->id) }}" method="post">
                            {!! method_field('PUT') !!}
                            {!! csrf_field() !!}

                            <div class="form-group row">
                              <label for="nom_proy" class="col-sm-4 col-form-label d-block">Proyecto <span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                              <input type="text" required="" autocomplete="off" class="form-control text-uppercase" name="nom_proy" placeholder="PROYECTO" value="{{ $datos->nom_proy }}" />
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="direccion" class="col-sm-4 col-form-label d-block">Dirección </label>
                              <div class="col-sm-8">
                            <input type="text" class="form-control text-uppercase" name="direccion" placeholder="DIRECCIÓN" value="{{ $datos->direccion }}" />
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="descripcion" class="col-sm-4 col-form-label d-block">Descripción </label>
                              <div class="col-sm-8">
                            <input type="text" class="form-control text-uppercase" name="descripcion" placeholder="DESCRIPCIÓN" value="{{ $datos->descripcion }}" />
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="cliente" class="col-sm-4 col-form-label d-block">Cliente <span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                              <input type="text" required="" class="form-control text-uppercase" name="cliente" id="cliente" placeholder="CLIENTE" autocomplete="off" value="{{ $cliente->razon_social }}" />
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="cta_cte" class="col-sm-4 col-form-label d-block">RUC <span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                            <input type="text" required="" readonly="" class="form-control text-uppercase" name="cta_cte" id="cta_cte" placeholder="RUC" value="{{ $datos->cod_ruc }}" />
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="cta_cte" class="col-sm-4 col-form-label d-block">ESTADO </label>
                              <div class="col-sm-8">
                                <select name="estado" class="form-control" id="">
                                  <option value="">SELECCIONE</option>
                                  <option value="1" @if($datos->flag_activo == 1) selected="" @endif>ACTIVO</option>
                                  <option value="0" @if($datos->flag_activo == 2) selected="" @endif>INACTIVO</option>
                                </select>
                              </div>
                            </div>

                            <div class="form-group row">
                              <div class="col-sm-12 text-center mt-4">
                                <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
                                <a href="{{ route('proyectos.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
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
@section('footer')
<link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.min.css?id=1') }}">
{{-- <link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.themes.min.css') }}"> --}}

<script src="{{ asset('js_auto/jquery.easy-autocomplete.js')}}"></script>
<script src="{{ asset('js/autocomplete.js')}}"></script>
<script>

</script>
@endsection
