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
                        <h4 class="card-title mb-0">Crear Guía de Devolución </h4>
                      </div>
                      
                      @if (session('warning'))
                          <div class="alert alert-warning ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('warning') }}</strong>
                          </div>
                      @endif

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('guia_devolucion.index') }}" class="btn btn-success">Regresar</a>

                      @else
                        <form class="forms-sample" id="ingresosForm2" action="{{ route('guia_devolucion.store') }}" method="post">
                          {!! csrf_field() !!}
                          @include ('guia_devolucion.form')

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


  {{-- TC  --}}
  <div class="modal fade ass" id="Modal_add_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content"> 
        <form  id="f_actividad" name="f_actividad" method="post" action="{{ route('tc.store') }}" class="formarchivo" >
            {!! csrf_field() !!}
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="exampleModalLabel">Registrar Tipo de Cambio</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body pt-0 form-act">


        </div>
        <div class="modal-footer">
          <a href="{{route('tc.index')}}" target="_blank" class="btn btn-link">Ver listado</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-dark" id="saveActividades">Guardar</button>{{-- btnImport1 --}}
        </div>
        </form>
      </div>
    </div>
  </div>
  {{-- fin modal --}}
  {{-- PROVEEDORES --}}
  <div class="modal fade ass" id="Modal_add_provee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content"> 
        <form  id="f_proveedor" name="f_proveedor" method="post" action="{{ route('proy.store') }}" class="formarchivo" >
            {!! csrf_field() !!}
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="exampleModalLabel">Registrar Nuevo Proyecto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body pt-0 form-act">
          <input type="hidden" name="tc_id" name="tc_id" value="{{ 0}}">{{-- $tc_id --}}
            

                      <div class="form-group row">
                        <label for="nom_proy" class="col-sm-4 col-form-label d-block">Proyecto <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                        <input type="text" required="" autocomplete="off" class="form-control text-uppercase" name="nom_proy" placeholder="PROYECTO" value="{{ old('nom_proy') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="direccion" class="col-sm-4 col-form-label d-block">Dirección </label>
                        <div class="col-sm-8">
                      <input type="text" class="form-control text-uppercase" name="direccion" placeholder="DIRECCIÓN" value="{{ old('direccion') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="descripcion" class="col-sm-4 col-form-label d-block">Descripción </label>
                        <div class="col-sm-8">
                      <input type="text" class="form-control text-uppercase" name="descripcion" placeholder="DESCRIPCIÓN" value="{{ old('descripcion') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="cliente" class="col-sm-4 col-form-label d-block">Cliente <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                        <input type="text" required="" class="form-control text-uppercase" name="cliente" id="cliente" placeholder="CLIENTE" autocomplete="off" value="{{ old('cliente') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cta_cte" class="col-sm-4 col-form-label d-block">RUC <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                      <input type="text" required="" readonly="" class="form-control text-uppercase" name="cta_cte" id="cta_cte" placeholder="RUC" value="{{ old('cta_cte') }}" />
                        </div>
                      </div>
                        
                      <div id="cargador_excel" class="content-wrapper p-0 d-none" align="center">  {{-- msg cargando --}}
                        <div class="card bg-white" style="background:#f3f3f3 !important;" >
                          <div class="">
                            <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                            <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                          </div>
                        </div>
                      </div>{{-- msg cargando --}}

        </div>
        <div class="modal-footer">
          <a href="{{route('ctas_corrientes.create', ['id'=>1])}}" target="_blank" class="btn btn-link p-0 pr-3">Registrar Cliente</a>
          <a href="{{route('proyectos.index')}}" target="_blank" class="btn btn-link p-0 pr-3">Ver listado</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-dark" id="saveProveedor">Guardar</button>{{-- btnImport1 --}}
        </div>
        </form>
      </div>
    </div>
  </div>
  {{-- fin modal --}}

@endsection
@section('footer')
<script src="{{ asset('js/guia_devolucion.js')}}"></script>

<link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.min.css?id=1') }}">
{{-- <link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.themes.min.css') }}"> --}}

<script src="{{ asset('js_auto/jquery.easy-autocomplete.js')}}"></script>
<script src="{{ asset('js/autocomplete.js')}}"></script>

@endsection