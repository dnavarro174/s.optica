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
                        <h4 class="card-title mb-0">Cálculo de Costos</h4>
                      </div>

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>

                      @else
                            <form class="forms-sample" id="calculo_costos" action="{{ route('calculo_costos.store') }}" method="post">
                              {!! csrf_field() !!}

                              <div class="row">
                                <div class="col-sm-12 col-md-6">
                                  <div class="form-group">
                                    <label for="fecha_inicio">Fecha Inicio <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                      <div id="datepicker-popup3" class="input-group date datepicker" >
                                        <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ old('fecha_entrega', date('m/Y') ) }}" required="">
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
                                      <div id="datepicker-popup4" class="input-group date datepicker">
                                        <input type="text" name="fecha_fin" id="fecha_fin" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ old('fecha_entrega', date('m/Y') ) }}" required="">
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
                                    <div class="input-grou mb-2">
                                      <!-- Default unchecked -->
                                      <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="sinCheck" name="prod_tipo" value="0" checked>
                                        <label style="vertical-align: baseline;" class="custom-control-label" for="sinCheck"> Rango</label>
                                      </div>

                                      <!-- Default checked -->
                                      <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="check_todos" name="prod_tipo" value="1">
                                        <label style="vertical-align: baseline;" class="custom-control-label" for="check_todos">Todos</label>
                                      </div>

                                      {{-- <select required="" name="prod_tipo" id="prod_tipo" class="form-control">
                                        <option value="0">POR CÓDIGO</option>
                                        <option value="1">TODOS</option>
                                      </select> --}}
                                    </div>
                                  </div>
                                </div>

                                <div class="col-sm-12 col-md-6" id="cod">
                                  <div class="form-group">
                                    <label for="cod_artic">Código Inicial </label>
                                    <div class="input-group mb-2">
                                        <input required="" type="text" name="cod_artic" id="cod_artic" class="form-control" value="{{ old('cod_artic') }}" placeholder="CÓDIGO" autocomplete="off">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label for="cod_artic_fin">Código Final </label>
                                    <div class="input-group mb-2">
                                        <input required="" type="text" name="cod_artic_fin" id="cod_artic_fin" class="form-control" value="{{ old('cod_artic_fin') }}" placeholder="CÓDIGO" autocomplete="off">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              
                              <div class="form-group row">
                                <div class="col-sm-12 text-center mt-4">
                                  <button id="btnEnviar" value="Guardar" type="submit" class="btn btn-dark mr-2"> Cálculo de Costos</button>
                                  <a href="{{ route('menu_almacen.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al menú</a>

                                  <div id="spinner" style="display: none;" class="spinner-border text-dark" role="status">
                                    <span class="sr-only">Loading...</span>
                                  </div>
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

  /*$('#prod_tipo').change(function(e){
    console.log('e0');
    let a = $(this).val();
    if(a==1){
      $('#cod').fadeOut();
      $('#cod_artic').removeAttr('required');
    }else{
      $('#cod').fadeIn('slow');
      $('#cod_artic').prop('required',true);
    }
  });*/

  $('#check_todos').click(()=>{
    if($('#check_todos').is(':checked')) { 
      $('#cod_artic,#cod_artic_fin').removeAttr('required');
      $('#cod').fadeOut();
    }
  });

  $('#sinCheck').click(()=>{
    if($('#sinCheck').is(':checked')) { 
      $('#cod').fadeIn('slow');
      $('#cod_artic,#cod_artic_fin').prop('required',true);
    }
  });

  //submit
  $('#btnEnviar').click(function (e) {
    e.preventDefault();
    $('#spinner').fadeIn();
    $('#btnEnviar').attr('disabled','disabled');
    $('form#calculo_costos').submit();

  });

});
</script>
@endsection