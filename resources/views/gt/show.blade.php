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
                  
                      <h4 class="card-title mb-4">Guía de Transferencia</h4>
                      
                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                      <div class="row">
                        <div class="col-sm-12 col-md-2">
                          <div class="form-group">
                            <label for="fecha">Fecha <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">

                              <div id="datepicker-popup" class="input-group date datepicker">
                                <input type="text" readonly="" name="fecha_desde" id="fecha_desde" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ \Carbon\Carbon::parse($datos->fecha)->format('d/m/Y') }}" required="">
                                <span class="input-group-addon input-group-append border-left">
                                  <span class="mdi mdi-calendar input-group-text"></span>
                                </span>
                              </div>
                            </div>


                          </div>
                        </div>
                        <div class="col-sm-12 col-md-2">
                          <div class="form-group">
                            <label for="nro_doc">Nro Doc. <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                                <input type="text" readonly="" name="nro_doc" id="nro_doc" class="form-control" value="{{$datos->nro_doc}}" required="">
                            </div>          
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                          <div class="form-group">
                            <label for="referencia">Referencia </label>
                            <div class="input-group mb-2">
                                <input type="text" readonly="" name="referencia" id="referencia" class="form-control" value="{{ $datos->nro_ref }}" placeholder="INGRESE SERIE" title="INGRESE SERIE" autocomplete="off">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                          <div class="form-group">
                            <label for="almacen_or">Almacén Origen <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input readonly="" required="" type="text" name="almacen_or" value="{{ $almacen_o->almacen }}" class="form-control">
                              <input type="hidden" name="almacen_o" value="{{ $almacen_o->id }}" class="form-control">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                          <div class="form-group">
                            <label for="almacen_d">Almacén Destino <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <select class="form-control" required="" name="almacen_d" id="almacen_d" readonly="">
                                <option value="">SELECCIONE</option>
                                @foreach($almacen_d as $dest)
                                <option value="{{ $dest->id }}" @if($datos->cod_alm_d == $dest->id) selected="" @endif>{{ $dest->almacen }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>

                      </div> 
                      <div class="row">{{-- add productos --}}

                        <div class="col-sm-12 col-md-1">
                          <div class="form-group">
                            <label for="cod_artic">Código </label>
                            <div class="input-group mb-2">
                              <input readonly="" type="text" maxlength="10" name="cod_artic" id="cod_artic" class="form-control" value="{{ old('cod_artic') }}" placeholder="00001">
                              <input type="hidden" name="cod_artic2" id="cod_artic2">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                          <div class="form-group">
                            <label for="producto">Producto <span class="text-danger">*</span></label>
                            <div class=" mb-2">
                              <input readonly="" type="text" name="producto" id="producto" class="form-control" value="{{ old('producto') }}" placeholder="INGRESAR PRODUCTO">
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12 col-md-2">
                          <div class="form-group">
                            <label for="cod_umedida">UM </label>
                            <div class="input-group mb-2">
                              <input readonly="" type="text" name="cod_umedida" id="cod_umedida" class="form-control" placeholder="">
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-2">
                          <div class="form-group">
                            <label for="cant">Cantidad <span class="text-danger">*</span></label>
                            <div class="input-group mb-2">
                              <input readonly="" type="number" name="cant" id="cant" class="form-control" value="{{ old('cant') }}" placeholder="0.00">
                            </div>
                          </div>
                        </div>

                        {{-- costo unitario y subtotal --}}

                        <div class="col-sm-12 col-md-1 px-0">
                          <div class="form-group">
                            <label for="subtotal"> </label>
                            <div class="input-group mb-2">
                              <button type="button" class="btn btn-primary" id="add_producto2" >
                                <span>+</span></button>
                              
                            </div>
                          </div>
                              
                        </div>
                        

                      </div> {{-- end row --}}
                      
                      
                      {{-- ocultado --}}


                      <div class="row mostrar_1">
                        <div id="msg_stock" class="msg_stock col-xs-12 col-md-12"></div>
                          <div class="col-xs-12 col-md-12 ">
                           
                              
                              <table class="table" id="ma_detalle" width="100%">
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">Itém</th>
                                    <th scope="col" width="10%">Código</th>
                                    <th scope="col" width="70%">Producto</th>
                                    <th scope="col">U.M</th>
                                    <th scope="col" >Cant.</th>
                                    <th cope="col" class="text-center" width="20%">Acciones</th>
                                  </tr>
                                </thead>
                                <tbody id="filas_contenedor">
                                  <?php 
                                  $i = 1;
                                  $sum1 = 0;
                                  $sum2 = 0;
                                  $sum3 = 0;
                                  
                                  foreach ($items as $item) { 
                                    $sum1 = $sum1 + $item->cant_mov;
                                    $sum2 = $sum2 + $item->costo_mo;
                                    $sum3 = $sum3 + $item->costo_tot_mo;                
                                    ?>
                                    <tr id="td_{{$i}}">
                                      <td style="text-align: center">{{$i}}</td>
                                      <td style="text-align: center"><input type="text" class="textos" readonly="" name="cod_art_{{$i}}" value="{{$item->cod_artic}}"></td>
                                      <td>{{$item->nombre}}</td>
                                      <td style="text-align: center"><input type="text" class="textos" readonly="" name="uni_med_{{$i}}" value="{{$item->unidadMedida}}"></td>
                                      <td style="text-align: center"><input type="text" class="textos cant_{{$i}}" readonly="" name="cant_{{$i}}" value="{{(int)$item->cant_mov}}"></td>
                                      
                                      <td style="text-align: center">
                                        <i class="mdi mdi-pencil text-dark icon-md btnAdd" num="{{$i}}" title="Editar"></i>
                                        <i class="mdi mdi-content-save text-dark icon-md btnSave save_{{$i}}" num="{{$i}}" title="Guardar" style="display: none;"></i>
                                        <i class="mdi mdi-delete text-danger icon-md btnQuitar" num="{{$i}}" title="Quitar"></i>
                                      </td>
                                    </tr>
                                  <?php 
                                    $i++;
                                  } ?>
                                </tbody>
                                <tfoot>
                                  <tr id="td_totales">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Totales</td>
                                    <td align="center"><strong id="tot1">{{ $sum1}}</strong></td>
                                    
                                    <td></td>
                                  </tr>
                                </tfoot>
                              </table>

                              {{-- <p class="pl-2">
                                <a href="#" class="btn btn-primary" id="add_row" >
                                  <span>Agregar</span>
                                </a>
                              </p> --}}

                              
                          </div>
                      </div>

                      <div class="form-group row">
                        <div class="col-sm-12 text-center mt-4">
                          <a href="{{ route('gt.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
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
@section('footer')
<style type="text/css">
  .textos{
    max-width: 45px;
    text-align: center;
  }
  .textos2{
    max-width: 80px;
    text-align: center;
  }
</style>

@endsection