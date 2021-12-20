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

<input type="hidden" name="tot_reg" id="tot_reg" value="0">
<input type="hidden" name="ruta_alm" id="ruta_alm" value="{{ route('ingresos.index') }}">
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="nro_doc">Nro Doc. <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
              <input type="text" name="nro_doc" id="nro_doc" class="form-control" value="{{$numbers}}" autofocus="" required="">
          </div>
          
        </div>
      </div>

      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="tipo_doc">Tipo Doc. <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <select required="" class="form-control" required="" name="tipo_doc" id="tipo_doc">
              <option value="">SELECCIONE</option>
              <option value="FA" data-id="01">FACTURA</option>
              <option value="BO" data-id="03">BOLETA</option>
              <option value="TI" data-id="12">TICKETS</option>
              <option value="NI" data-id="10">NOTA DE INGRESO</option>
              <option value="NS" data-id="11">NOTA DE SALIDA</option>
              <option value="OTROS" data-id="00">OTROS</option>
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
              <input type="text" name="fecha_desde" id="fecha_desde" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ old('fecha_entrega', date('d/m/Y') ) }}" required="">
              <span class="input-group-addon input-group-append border-left">
                <span class="mdi mdi-calendar input-group-text"></span>
              </span>
            </div>


            {{-- <div class="input-group-prepend">
              <input required="" type="text" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', date('d/m/Y') ) }}" placeholder="01/01/2020">
            </div> --}}
          </div>


        </div>
      </div>
      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="referencia">Referencia </label>
          <div class="input-group mb-2">
              <input type="text" name="referencia" id="referencia" class="form-control text-uppercase" value="{{ old('referencia') }}" placeholder="INGRESE SERIE" title="INGRESE SERIE">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="nro_preing">Documento <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <select class="form-control" required="" name="nro_preing" id="nro_preing">
              <option value="">SELECCIONE</option>
              <option value="1">SALDO INICIAL</option>
              <option value="2">AJUSTE POR INVENTARIO</option>
              <option value="3">COMPRAS</option>
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
              <option val_dolares="{{ $m->TC_me }}" val_soles="{{ $m->TC_compra_mn }}" tipo="{{$m->cod_moneda}}" val_compra_mn="{{ $m->TC_compra_mn }}" value="{{ $m->id }}" fecha="{{$m->fecha}}">{{ $m->nom_moneda }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
   
      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="tipo_cambio">Tipo Cambio <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <input readonly type="text" maxlength="15" name="tipo_cambio" id="tipo_cambio" class="form-control" value="{{ old('tipo_cambio') }}" placeholder="">
          </div>
          <div class="" id="add_TC" @if(count($monedas) != 0) style="display: none;" @endif>
            <a href="#" class="btn btn-link btn-small p-0" style="font-size: 12px;" onclick="formActividad('1','0','{{ url('') }}')">Agregar tipo de cambio</a>
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="cod_ruc">RUC <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <input disabled="" required="" type="text" maxlength="15" name="cod_ruc" id="cod_ruc" class="form-control" value="{{ old('cod_ruc') }}" placeholder="">
            <input type="hidden" name="cod_ruc2" id="cod_ruc2">
            <input type="hidden" name="cod_emp2" id="cod_emp2">
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-md-9">
        <div class="form-group ">
          <label for="razon_social">Razón Social <span class="text-danger">*</span> <a href="#" id="AddProveedor" class="btn btn-link py-0" style="font-size: 12px;" onclick="formActividad('1','proveedor','{{ url('') }}')">Registrar Proveedor</a></label>

          {{-- <a href="#" class="btn btn-link btn-small p-0" style="font-size: 12px;" onclick="formActividad('1','0','{{ url('') }}')">Agregar tipo de cambio</a> --}}

          <div class="mb-2 col-md-12 p-0">
            <input required="" type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}" placeholder="EMSAG"> 
          </div>
        </div>
      </div>
      

    </div> 
    <div class="row mostrar_1" style="display: none;">{{-- add productos --}}

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
            <input type="text" name="producto" id="producto" class="form-control" value="{{ old('producto') }}" placeholder="INGRESAR PRODUCTO" onkeyup="saltar(event,'cant')">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-1">
        <div class="form-group">
          <label for="cod_umedida">UM </label>
          <div class="input-group mb-2">
            <input readonly="" type="text" name="cod_umedida" id="cod_umedida" class="form-control">
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="cant">Cantidad <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <input type="number" autocomplete="off" name="cant" id="cant" class="form-control text-right" value="{{ old('cant') }}" placeholder="0.00" step="any" onkeyup="saltar(event,'costo_mn');">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="costo_mn">Costo Unitario </label>
          <div class="input-group mb-2">
            <input type="number" autocomplete="off" name="costo_mn" id="costo_mn" class="form-control text-right" value="{{ old('costo_mn') }}" placeholder="0.00" onkeyup="saltar(event,'subtotal');" step="any">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="subtotal">Subtotal </label>
          <div class="input-group mb-2">
            <input type="text" name="subtotal" id="subtotal" class="form-control text-right" value="{{ old('subtotal') }}" placeholder="0.00" onkeyup="saltar(event,'add_producto2')">
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


    <div class="row mostrar_1" style="display: none;">
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
                <tr class="reg_ejm"><td colspan="8">No hay datos</td></tr>
              </tbody>
              <tfoot>
                <tr id="td_totales">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>Totales</td>
                  <td align="center"><strong id="tot1"></strong></td>
                  <td align="center"><strong id="tot2"></strong></td>
                  <td align="center"><strong id="tot3"></strong>
                    <input type="hidden" id="tot3_a" value="" name="tot3_a">
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
      </div>

    </div>
