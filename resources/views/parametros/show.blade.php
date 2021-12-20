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
      <!-- partial:partials/_sidebar.html -->
      
      @include('layouts.menu_iz')
      <!-- end menu_right -->
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-10 offset-md-1">
                  
                      <h4 class="card-title mb-4">Productos</h4>
                      

                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                        

                            
    
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Código</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="text" class="form-control text-uppercase" id="codigo" name="codigo" placeholder="Código" disabled='' value="{{ $productos_datos->codigo }}" >
                                    {!! $errors->first('codigo', '<span class=error>:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Nombre</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="text" class="form-control text-uppercase" id="nombre" name="nombre" placeholder="Nombre" required=""disabled='' value="{{ $productos_datos->nombre }}" >
                                    {!! $errors->first('nombre', '<span class=error>:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Descripción</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="text" class="form-control text-uppercase" id="descripcion" name="descripcion" placeholder="Descripción" disabled='' value="{{ $productos_datos->descripcion }}" >
                                    {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Stock</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="number" required="" class="form-control text-uppercase" id="stock" name="stock" placeholder="Stock" disabled='' value="{{ $productos_datos->stock }}" >
                                    {!! $errors->first('stock', '<span class=error>:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Unidad de Medida</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="text" class="form-control text-uppercase" id="unidad_med" name="unidad_med" placeholder="Unidad de Medida" disabled='' value="{{ $productos_datos->unidad_med }}" >
                                    {!! $errors->first('unidad_med', '<span class=error>:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Modelo</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="text" class="form-control text-uppercase" id="modelo" name="modelo" placeholder="Modelo" disabled='' value="{{ $productos_datos->modelo }}" >
                                    {!! $errors->first('modelo', '<span class=error>:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Marca</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="text" class="form-control text-uppercase" id="marca" name="marca" placeholder="Marca" disabled='' value="{{ $productos_datos->marca }}" >
                                    {!! $errors->first('marca', '<span class=error>:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Tipo</label>
                                <div class="col-lg-9 col-xl-9">
                                  <select class="form-control text-uppercase" disabled="" id="tipo" name="tipo">
                                    <option value="">SELECCIONE</option>
                                    <option value="M"
                                      @if ('M' === $productos_datos->tipo)
                                        selected
                                      @endif
                                    >Material</option>
                                    <option value="H"
                                      @if ('H' === $productos_datos->tipo)
                                        selected
                                      @endif>Herramientas</option>
                                  </select>

                                    
                                </div>
                            </div>

                            <div class="form-group row">
                              <div class="col-sm-12 text-center mt-4">
                                
                                <a href="{{ route('productos.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
                              </div>

                            </div>


                      
                      
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