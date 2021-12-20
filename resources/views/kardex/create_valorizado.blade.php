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
                        <h4 class="card-title mb-0">Generar Kardex Valorizado</h4>
                      </div>

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                      @else
                            <form class="forms-sample" id="ingresosForm2" action="{{ route('kardex_va.store') }}" method="post">
                              {!! csrf_field() !!}

                              <div class="row">

                                

                                <div class="col-sm-12 col-md-6">
                                  <div class="form-group mb-4">
                                    <div class="form-radio">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="optFecha" id="optFecha1" value="1" checked="" />
                                        Por Rango de Fecha
                                        <i class="input-helper"></i>
                                      </label>
                                    </div>
                                  </div>

                                </div>
                                <div class="col-sm-12 col-md-6">
                                  <div class="form-group mb-4">
                                    <div class="form-radio">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="optFecha" id="optFecha2" value="2" />
                                        Por Año
                                        <i class="input-helper"></i>
                                      </label>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 ano d-none">
                                  <div class="form-group">
                                    <label for="ano">Año <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                      <select  name="ano" id="ano" class="form-control">
                                        <option value="">SELECCIONE</option>
                                        <option value="2018" selected>2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 fecha_fin" >
                                  <div class="form-group">
                                    <label id="fecha_inicio" for="fecha_inicio">Fecha Inicio <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                      <div id="datepicker-popup" class="input-group date datepicker">
                                        <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control text-uppercase" placeholder="01/01/2021" value="{{ old('fecha_entrega', date('d/m/Y') ) }}" required="">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                      </div>

                                    </div>
                                  </div>
                                </div>

                                <div class="col-sm-12 col-md-6 fecha_fin">
                                  <div class="form-group">
                                    <label for="fecha_fin">Fecha Fin <span class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                      <div id="datepicker-popup2" class="input-group date datepicker">
                                        <input type="text" name="fecha_fin" id="fecha_fin" class="form-control text-uppercase" placeholder="01/01/2021" value="{{ old('fecha_entrega', date('d/m/Y') ) }}" required="">
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
                                    <label for="cod_artic">Código Producto <span class="text-danger">*</span></label></label>
                                    <div class="input-group mb-2">
                                        <input type="text" name="cod_artic" id="cod_artic" class="form-control" value="{{ old('cod_artic') }}" placeholder="CÓDIGO" title="CÓDIGO" autocomplete="off">
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
document.querySelector('#optFecha1').checked = true;
document.getElementById('prod_tipo').value = 1;
document.getElementById('ano').value = "";

$('document').ready(function(){


  $('#prod_tipo').change(function(e){
    console.log('Rep.K');
    let a = $(this).val();
    if(a==1){
      $('#cod').fadeOut();
      $('#cod_artic').removeAttr('required');
    }else{
      $('#cod').fadeIn('slow');
      $('#cod_artic').prop('required',true);
    }
  });

  $("#optFecha1").click((e)=>{
    //e.preventDefault();
    $('.fecha_fin').removeClass('d-none').addClass('d-block');
    $('.ano').addClass('d-none').removeClass('d-block');
    $('#ano').removeAttr('required');
  });
  $("#optFecha2").click((e)=>{
    //e.preventDefault();
    console.log("Cli fec");
    
    $('.fecha_fin').addClass('d-none').removeClass('d-block');
    $('.ano').addClass('d-block').removeClass('d-none').attr('required'); // class capa
    $('#ano').prop('required',true);
  });

});
</script>
@endsection