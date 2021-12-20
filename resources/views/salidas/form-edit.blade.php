<style type="text/css">
.textos{max-width:45px;text-align:center}.textos2{max-width:80px;text-align:center}
</style>

<input type="hidden" name="tot_reg" id="tot_reg" value="<?php echo count($items)?>">
<input type="hidden" name="ruta_alm" id="ruta_alm" value="{{ route('salidas.index') }}">
    
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
      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="nro_doc">Nro Doc. <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
              <input type="text" name="nro_doc" id="nro_doc" class="form-control" value="{{$datos->nro_doc}}" required="">
          </div>          
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="referencia">Referencia </label>
          <div class="input-group mb-2">
              <input type="text" name="referencia" id="referencia" class="form-control text-uppercase" value="{{ $datos->nro_ref }}" placeholder="INGRESE SERIE" title="INGRESE SERIE" autocomplete="off">
          </div>
        </div>
      </div>

      {{--<div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="flag_doc_aux">Documento Asociado <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <select class="form-control" required="" name="flag_doc_aux" id="flag_doc_aux">
               <option value="">SELECCIONE</option>
              <option value="1">NOTA DE PEDIDO</option>
              <option value="2">ORDEN SALIDA / SERVICIOS</option>
              <option value="4">ORDEN DE PRODUCCIÓN</option> 
              <option value="5" @if($datos->flag_doc_aux == 5) selected="" @endif>NINGUNO</option>
            </select>
          </div>
        </div>
      </div>--}}

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="nro_preing">Origen Doc <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <select class="form-control" required="" name="nro_preing" id="nro_preing" @if($datos->nro_preing == 3) disabled="" @endif>
              <option value="">SELECCIONE</option>
              <option value="2" @if($datos->nro_preing == 2) selected="" @endif>CONSUMO INTERNO</option>
              <option value="3" @if($datos->nro_preing == 3) selected="" @endif>PARA VENTAS</option>
              <option value="1" @if($datos->nro_preing == 1) selected="" @endif>PARA ORDEN DE TRABAJO</option>
            </select>
          </div>
          <input type="hidden" name="nro_preing" value="3">
        </div>
      </div>
      @if($datos->doc_tipo == 'H')
      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="doc_tipo">Tipo <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <select class="form-control" required="" name="doc_tipo" id="doc_tipo">
              <option value="">SELECCIONE</option>
              <option value="H" @if($datos->doc_tipo == 'H') selected="" @endif>HERRAMIENTA</option>
              <option value="M" @if($datos->doc_tipo == 'M') selected="" @endif>MATERIAL</option>
            </select>
          </div>
        </div>
      </div>
      @endif
      

      {{--<div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="cod_ruc">RUC <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <input readonly="" required="" type="text" name="cod_ruc" id="cod_ruc" class="form-control" value="{{ $datos->cta_cte }}" placeholder="">
            
          </div>
        </div>
      </div>
       <div class="col-xs-12 col-md-5">
        <div class="form-group ">
          <label for="razon_social">Razón Social <span class="text-danger">*</span> <a href="#" id="AddProveedor" class="btn btn-link py-0" style="font-size: 12px;" onclick="formActividad('1','proveedor','{{ url('') }}')">Registrar Proveedor</a></label>

          <div class="mb-2 col-md-12 p-0">
            <input required="" type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}" placeholder="EMSAG"> 
          </div>
        </div>
      </div> --}}
      </div>
      <input type="hidden" name="cod_ruc2" id="cod_ruc2" value="{{ $datos->cta_cte }}">
      <input type="hidden" name="proyectos_id" id="proyectos_id" value="{{ $datos->proyectos_id }}">
      
      <div id="placa" class="row" @if($proyectos->tipocomp!="0") style="display: none;" @endif>
        <div class="col-sm-12 col-md-6">
          <div class="form-group">
            <label for="proyecto">Placa de Vehículo <span class="text-danger">*</span> <a href="#" id="AddProveedor" class="btn btn-link py-0" style="font-size: 12px;" onclick="formActividad('1','proyecto','{{ url('') }}')">Registrar Vehículo</a></label>
            <div class="input-group mb-2">
                <input required="" type="text" name="proyecto" id="proyecto" class="form-control" value="{{ $proyectos->nom_proy }}" placeholder="INGRESE PLACA" title="INGRESE PLACA" autocomplete="off">
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-md-5">
          <div class="form-group">
            <label for="responsable">Responsable <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input required="" type="text" name="responsable" id="responsable" class="form-control text-uppercase" placeholder="INGRESE RESPONSABLE" autocomplete="off" value="{{ $datos->responsable }}">
            </div>
          </div>
        </div>
      </div>
      
      <div id="xcliente" class="row" @if($proyectos->tipocomp=="0") style="display: none;" @endif>
        <div class="col-sm-12 col-md-6">
          <div class="form-group">
            <label for="cliente_doc" class="tit_proyecto"><span id="xtit">Cliente</span> <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input type="text" name="cliente_doc" id="cliente_doc" class="form-control text-uppercase" value="{{ $proyectos->nom_proy }}" placeholder="INGRESE CLIENTE" title="INGRESE CLIENTE" autocomplete="off">
            </div>
          </div>
          {{-- <input type="text" name="cod_ruc2" id="cod_ruc2">
          <input type="text" name="proyectos_id" id="proyectos_id"> --}}
        </div>

        <div class="col-sm-12 col-md-2">
          <div class="form-group">
            <label for="tipo_c">Tipo Comprobante <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
              <select class="form-control" name="tipo_c" id="tipo_c">
                <option value="">SELECCIONE</option>
                <option value="B" @if($proyectos->tipocomp=="B") selected @endif>BOLETA</option>
                <option value="F" @if($proyectos->tipocomp=="F") selected @endif>FACTURA</option>
                <option value="T" @if($proyectos->tipocomp=="T") selected @endif>TICKET</option>
              </select>
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-md-3">
          <div class="form-group">
            <label for="numerodoc">Número <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input type="text" name="numerodoc" id="numerodoc" class="form-control text-uppercase" value="{{ $proyectos->numerodoc }}" placeholder="INGRESE NÚMERO" autocomplete="off">
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        @if($datos->doc_tipo == 'H')
        <div class="col-sm-12 col-md-2">
          <div class="form-group">
            <label for="doc_estado">Estado <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
              <select class="form-control" required="" name="doc_estado" id="doc_estado">
                <option value="">SELECCIONE</option>
                <option value="P" @if($datos->doc_estado == 'P') selected="" @endif>PENDIENTE</option>
                <option value="E" @if($datos->doc_estado == 'E') selected="" @endif>ENTREGADO</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-9">
          <div class="form-group">
            <label for="obs">Observación</label>
            <div class="input-group mb-2">
              <textarea class="form-control" name="obs" id="obs" cols="30" rows="4" placeholder="OBSERVACIÓN DE LOS MATERIALES">{{$datos->obs}}</textarea>
            </div>
          </div>
        </div>
        @endif
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
          <div class="input-group mb-2">
            <input type="text" name="producto" id="producto" class="form-control" value="{{ old('producto') }}" placeholder="INGRESAR PRODUCTO">
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
            <input type="number" name="cant" id="cant" class="form-control text-right" value="{{ old('cant') }}" placeholder="0.00" step="any">
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
        <div id="save_form" class="alert alert-success " role="alert">
          <strong>
            Registro guardado!!
          </strong>
        </div>
        <button id="actionSubmit_salida2" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
        <a href="{{ route('salidas.comprobante', ['id'=>$datos->id])}}" class="btn btn-danger" target="_blank"><i class='mdi mdi-file-pdf'></i>Imprimir</a>
        {{-- <a href="#" onclick="window.print();" class="btn btn-danger view_print"><i class="mdi mdi-file-pdf"></i>PDF</a> --}}
        <a href="{{ route('salidas.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
        <a href="{{ route('salidas.create')}}" class="btn btn-light"><i class='mdi mdi-plus'></i>Crear Nuevo</a>
      </div>

    </div>
