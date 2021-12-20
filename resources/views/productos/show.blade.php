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
        <div class="content-wrapper pt-3">
          <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12">
                  
                      <h4 class="card-title mb-4">Producto</h4>
                      

                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('productos.index') }}" class="btn btn-success">Regresar</a>

                      @else

                          <div class="row">
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input disabled='' required="" type="text" class="form-control" name="nombre" placeholder="NOMBRE DEL PRODUCTO"  value="{{ $productos_datos->nombre }}" >
                                  {!! $errors->first('nombre', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="marca">Marca</label>
                                <div class="input-group mb-2">
                                  <input disabled='' required="" type="text" class="form-control" name="marca" placeholder="MARCA DEL PRODUCTO"  value="{{ $productos_datos->marca }}" >
                                  {!! $errors->first('marca', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <div class="input-group mb-2">
                                  <textarea disabled="" name="descripcion" class="form-control" id="" cols="30" rows="3" placeholder="DESCRIPCIÓN">{{ $productos_datos->descripcion }}</textarea>
                                  {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="ubicacion">Ubicación</label>
                                <div class="input-group mb-2">
                                  <textarea disabled="" name="ubicacion" class="form-control" id="" cols="30" rows="3" placeholder="UBICACIÓN">{{ $productos_datos->ubicacion }}</textarea>
                                  {!! $errors->first('ubicacion', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="tipo_articulo">Tipo Producto <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <select disabled="" class="form-control text-uppercase" required="" id="tipo_articulo" name="tipo_articulo">
                                    <option value="">SELECCIONE</option>
                                    <option value="M" @if($productos_datos->tipo_articulo=="M") selected="" @endif>Material</option>
                                    <option value="H" @if($productos_datos->tipo_articulo=="H") selected="" @endif>Herramientas</option>
                                  </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="cod_categoria">Categoría <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <select disabled="" name="cod_categoria" id="cod_categoria" class="form-control" required="">
                                    <option value="">SELECCIONE</option>
                                    @foreach($categorias as $c)
                                    <option value="{{ $c->id }}" @if($c->id == $productos_datos->cod_categoria) selected="" @endif>{{ $c->categoria }}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="cod_umedida">Unidad de medida <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <select disabled="" name="cod_umedida" id="cod_umedida" class="form-control" required="">
                                    <option value="">SELECCIONE</option>
                                    @foreach($medidas as $m)
                                    <option value="{{ $m->id }}" @if($m->id == $productos_datos->cod_umedida) selected="" @endif>{{ $m->dsc_umedida }}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="stock_min">Stock mínimo <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input disabled='' type="number" pattern="^\d*(\.\d{0,2})?$" class="form-control text-uppercase" required="" id="stock_min" name="stock_min" placeholder="STOCK MÍNIMO"  value="{{ $productos_datos->stock_min }}" >
                                  {!! $errors->first('stock_min', '<span class=error>:message</span>') !!}
                                </div>
                                <input disabled='' type="hidden" name="cod_sunat" value="{{$productos_datos->cod_sunat}}">
                              </div>
                            </div>

                          </div> {{-- end row --}}

                          <div class="form-group row">
                            <div class="col-sm-12 text-center mt-4">
                              <a href="{{ route('productos.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
                            </div>

                          </div>


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