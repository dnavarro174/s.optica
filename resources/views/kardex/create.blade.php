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
            <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12 ">{{-- offset-md-1 --}}
                  
                      <div class="d-flex justify-content-between mb-4">
                        <h4 class="card-title mb-0">Generar Kardex </h4>
                      </div>

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('gt.index') }}" class="btn btn-success">Regresar</a>

                      @else
                            <form class="forms-sample" id="ingresosForm2" action="{{ route('kardex.store') }}" method="post">
                              {!! csrf_field() !!}

                              <div class="row">
                                <div class="col-sm-12 col-md-6">
                                  <div class="form-group">
                                    <label for="fecha_inicio">Fecha Inicio <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                      <div id="datepicker-popup" class="input-group date datepicker">
                                        <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ old('fecha_entrega', date('d/m/Y') ) }}" required="">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                      </div>

                                    </div>
                                  </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                  <div class="form-group">
                                    <label for="fecha_fin">Fecha Fin <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                      <div id="datepicker-popup2" class="input-group date datepicker">
                                        <input type="text" name="fecha_fin" id="fecha_fin" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ old('fecha_entrega', date('d/m/Y') ) }}" required="">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                      </div>

                                    </div>
                                  </div>
                                </div>

                              </div>
                              <div class="row">

                                <div class="col-sm-12 col-md-6">
                                  <div class="form-group">
                                    <label for="referencia">Productos <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                      <select required="" name="prod_tipo" id="prod_tipo" class="form-control">
                                        {{-- <option value="">SELECCIONE</option> --}}
                                        <option value="2">POR CÓDIGO</option>
                                        <option value="1" selected="">TODOS</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-sm-12 col-md-6" id="cod" style="display: none;">
                                  <div class="form-group">
                                    <label for="cod_artic">Código Producto </label>
                                    <div class="input-group mb-2">
                                        <input  type="text" name="cod_artic" id="cod_artic" class="form-control" value="{{ old('cod_artic') }}" placeholder="CÓDIGO" title="CÓDIGO" autocomplete="off">
                                    </div>
                                  </div>
                                </div>

                              </div>
                              
                              <div class="form-group row">
                                <div class="col-sm-12 text-center mt-4">
                                  <button id="btnEnviar" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Consultar Kardex</button>
                                  <a href="{{ route('menu_almacen.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al menú</a>
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
@section('footer')

<script>
$('document').ready(function(){

  $('#prod_tipo').change(function(e){
    console.log('e0');
    let a = $(this).val();
    if(a==1){
      $('#cod').fadeOut();
      $('#cod_artic').removeAttr('required');
    }else{
      $('#cod').fadeIn('slow');
      $('#cod_artic').prop('required',true);
    }
  });

});
</script>
@endsection