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

<input type="hidden" name="tot_reg" id="tot_reg" value="<?php echo count($items)?>">
<input type="hidden" name="ruta_alm" id="ruta_alm" value="{{ route('ingresos.index') }}">
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="fecha">Nro Doc. <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
              <input type="text" name="nro_doc" id="nro_doc" class="form-control" value="{{$datos->nro_doc}}" autofocus="" required="">
          </div>
          
        </div>
      </div>
      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="tipo_doc">Tipo Doc. <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <select required="" class="form-control" required="" name="tipo_doc" id="tipo_doc">
              <option value="">SELECCIONE</option>
              <option value="FA" data-id="01" @if($datos->tipo_doc == "FA") selected="" @endif>FACTURA</option>
              <option value="BO" data-id="03" @if($datos->tipo_doc == "BO") selected="" @endif>BOLETA</option>
              <option value="TI" data-id="12" @if($datos->tipo_doc == "TI") selected="" @endif>TICKETS</option>
              <option value="NI" data-id="10" @if($datos->tipo_doc == "NI") selected="" @endif>NOTA DE INGRESO</option>
              <option value="NS" data-id="11" @if($datos->tipo_doc == "NO") selected="" @endif>NOTA DE SALIDA</option>
              <option value="OTROS" data-id="00" @if($datos->tipo_doc == "OTROS") selected="" @endif>OTROS</option>
            </select>
          </div>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="fecha">Fecha <span class="text-danger">*</span></label>
          <div class="input-group mb-2">

            <div id="datepicker-popup" class="input-group date datepicker">
              <input type="text" name="fecha_desde" id="fecha_desde" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ \Carbon\Carbon::parse($datos->fecha)->format('d/m/Y') }}" required="">
              <span class="input-group-addon input-group-append border-left">
                <span class="mdi mdi-calendar input-group-text"></span>
              </span>
            </div>
          </div>


        </div>
      </div>
      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="referencia">Referencia </label>
          <div class="input-group mb-2">
              <input type="text" name="referencia" id="referencia" class="form-control text-uppercase" value="{{ $datos->nro_ref }}" placeholder="INGRESE SERIE" title="INGRESE SERIE">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="nro_preing">Documento <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <select class="form-control" required="" name="nro_preing" id="nro_preing">
              <option value="">SELECCIONE</option>
              <option value="1" <?php if((int)$datos->nro_preing==1){ echo "selected"; }?>>SALDO INICIAL</option>
              <option value="2" <?php if((int)$datos->nro_preing==2){ echo "selected"; }?>>AJUSTE POR INVENTARIO</option>
              <option value="3" <?php if((int)$datos->nro_preing==3){ echo "selected"; }?>>COMPRAS</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="moneda">Moneda <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <select required="" class="form-control" required="" name="moneda" id="moneda">
              <option value="">SELECCIONE</option>
              @foreach ($monedas as $m)
              <option val_dolares="{{ $m->TC_me }}" val_soles="{{ $m->TC_compra_mn }}" tipo="{{$m->cod_moneda}}" val_compra_mn="{{ $m->TC_compra_mn }}" value="{{ $m->id }}" fecha="{{$m->fecha}}" @if($datos->cod_moneda == $m->id) selected="" @endif>{{ $m->nom_moneda }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="tipo_cambio">Tipo Cambio <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <input readonly type="text" maxlength="15" name="tipo_cambio" id="tipo_cambio" class="form-control" value="" placeholder="">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="cod_ruc">RUC <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <input disabled="" required="" type="text" maxlength="15" name="cod_ruc" id="cod_ruc" class="form-control" value="{{ $empresa->cod_ruc }}"placeholder="">
            <input type="hidden" name="cod_ruc2" id="cod_ruc2" value="{{ $empresa->cod_ruc }}">
            <input type="hidden" name="cod_emp2" id="cod_emp2" value="{{ $empresa->id }}">
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-md-9">
        <div class="form-group ">
          <label for="razon_social">Razón Social <span class="text-danger">*</span></label>
          <div class="mb-2 col-md-12 p-0">
            <input required="" type="text" name="razon_social" id="razon_social" class="form-control" value="{{ $empresa->razon_social }}" placeholder="EMSAG"> 
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

      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="producto">Producto <span class="text-danger">*</span></label>
          <div class="">
            <input type="text" name="producto" id="producto" class="form-control" value="{{ old('producto') }}" placeholder="INGRESAR PRODUCTO">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-1">
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
            <input type="number" name="cant" id="cant" class="form-control text-right" value="{{ old('cant') }}" placeholder="0.00" step="any" onkeyup="saltar(event,'costo_mn')">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="costo_mn">Costo Unitario </label>
          <div class="input-group mb-2">
            <input type="number" name="costo_mn" id="costo_mn" class="form-control text-right" value="{{ old('costo_mn') }}" placeholder="0.00" step="any">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="subtotal">Subtotal </label>
          <div class="input-group mb-2">
            <input type="text" name="subtotal" id="subtotal" class="form-control text-right" value="{{ old('subtotal') }}" placeholder="0.00">
          </div>
        </div>
      </div>

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


    <div class="row">
      <div id="msg_stock" class="msg_stock col-xs-12 col-md-12"></div>
        <div class="col-xs-12 col-md-12 ">
         
            
            <table class="table" id="ma_detalle" width="100%">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Itém</th>
                  <th scope="col" width="10%">Código</th>
                  <th scope="col" width="40%">Producto</th>
                  <th scope="col" >U.M</th>
                  <th scope="col" >Cant.</th>
                  <th scope="col" >Costo</th>
                  <th cope="col" >Subtotal</th>
                  <th cope="col" >Acciones</th>
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
                    <td style="text-align: center"><input type="text" class="textos cod_art_{{$i}}" readonly="" name="cod_art_{{$i}}" value="{{$item->cod_artic}}"></td>
                    <td>{{$item->nombre}}</td>
                    <td style="text-align: center"><input type="text" class="textos uni_med_{{$i}}" readonly="" name="uni_med_{{$i}}" value="{{$item->unidadMedida}}"></td>
                    <td style="text-align: center"><input type="text" class="textos cant_{{$i}}" readonly="" name="cant_{{$i}}" value="{{(int)$item->cant_mov}}"></td>
                    <td style="text-align: center"><input type="text" class="textos2 costo_{{$i}}" readonly="" name="costo_mn_{{$i}}" value="{{number_format($item->costo_mo,4)}}"></td>
                    <td style="text-align: center"><input type="text" class="textos2 subt_{{$i}}" readonly="" name="subto_{{$i}}" value="{{number_format($item->costo_tot_mo,4)}}"></td>
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
                  <td align="center"><strong id="tot2">{{number_format($sum2,2)}}</strong></td>
                  <td align="center"><strong id="tot3">{{number_format($sum3,4)}}</strong>
                    <input type="hidden" id="tot3_a" value="{{number_format($sum3,4)}}" name="tot3_a">
                  </td>
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
        <div id="save_form" class="alert alert-success " role="alert">
          <strong>
            Registro guardado!!
          </strong>
        </div>
        <button id="actionSubmit_salida2" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
        <a href="{{ route('ingresos.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
        <a href="{{ route('ingresos.create')}}" class="btn btn-light"><i class='mdi mdi-plus'></i>Crear Nuevo</a>
      </div>

    </div>
