<style type="text/css">
.textos{max-width:45px;text-align:center}.textos2{max-width:80px;text-align:center}
</style>

<input type="hidden" name="tot_reg" id="tot_reg" value="0">
<input type="hidden" name="ruta_alm" id="ruta_alm" value="{{ route('gt.index') }}">
    
    <div class="row">
      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="fecha">Fecha <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <div id="datepicker-popup" class="input-group date datepicker">
              <input type="text" name="fecha_desde" id="fecha_desde" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ old('fecha_entrega', date('d/m/Y') ) }}" required="">
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
              <input type="text" name="nro_doc" id="nro_doc" class="form-control" value="{{$numbers}}" autofocus="" required="">
          </div>          
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="referencia">Referencia </label>
          <div class="input-group mb-2">
              <input type="text" name="referencia" id="referencia" class="form-control text-uppercase" value="{{ old('referencia') }}" placeholder="INGRESE SERIE" title="INGRESE SERIE" autocomplete="off">
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
            <select class="form-control" required="" name="almacen_d" id="almacen_d">
              <option value="">SELECCIONE</option>
              @foreach($almacen_d as $dest)
              <option value="{{ $dest->id }}">{{ $dest->almacen }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- <div class="col-sm-12 col-md-3">
        <div class="form-group">
          <label for="cod_ruc">RUC <span class="text-danger">*</span></label>
          <div class="input-group mb-2">
            <input readonly="" required="" type="text" name="cod_ruc" id="cod_ruc" class="form-control" value="{{ old('cod_ruc') }}" placeholder="">
            <input type="hidden" name="cod_ruc2" id="cod_ruc2">
            <input type="hidden" name="proyectos_id" id="proyectos_id">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-4">
        <div class="form-group">
          <label for="proyecto">Proyecto <span class="text-danger">*</span> <a href="#" id="AddProveedor" class="btn btn-link py-0" style="font-size: 12px;" onclick="formActividad('1','proyecto','{{ url('') }}')">Registrar Proyecto</a></label>
          <div class="input-group mb-2">
              <input required="" type="text" name="proyecto" id="proyecto" class="form-control" value="{{ old('proyecto') }}" placeholder="PROYECTO" title="PROYECTO" autocomplete="off">
          </div>
        </div>
      </div> --}}
      

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

      <div class="col-sm-12 col-md-6">
        <div class="form-group">
          <label for="producto">Producto <span class="text-danger">*</span></label>
          <div class="col-sm-12 mb-2 p-0">
            <input type="text" name="producto" id="producto" class="form-control" value="{{ old('producto') }}" placeholder="INGRESAR PRODUCTO" onkeyup="saltar(event,'cant')" autocomplete="off">
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-2">
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
            <input type="number" name="cant" id="cant" class="form-control text-right" value="{{ old('cant') }}" placeholder="0.00" onkeyup="saltar(event,'add_producto2')" step="any">
          </div>
        </div>
      </div>

      {{-- <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="costo_mn">Costo Unitario </label>
          <div class="input-group mb-2">
            <input type="text" name="costo_mn" id="costo_mn" class="form-control" value="{{ old('costo_mn') }}" placeholder="0.00" onkeyup="saltar(event,'subtotal');">
          </div>
        </div>
      </div> 

      <div class="col-sm-12 col-md-2">
        <div class="form-group">
          <label for="subtotal">Subtotal </label>
          <div class="input-group mb-2">
            <input type="text" name="subtotal" id="subtotal" class="form-control" value="{{ old('subtotal') }}" placeholder="0.00" onkeyup="saltar(event,'add_producto2')">
          </div>
        </div>
      </div>--}}

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
                  <th scope="col" width="70%">Producto</th>
                  <th scope="col" class="text-center">U.M</th>
                  <th scope="col" class="text-center">Cant.</th>
                  {{-- <th scope="col" >Costo</th>
                  <th cope="col" >Subtotal</th> --}}
                  <th cope="col" class="text-center" width="20%">Acciones</th>
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
                  {{-- <td align="center"><strong id="tot2"></strong></td>
                  <td align="center"><strong id="tot3"></strong>
                    <input type="hidden" id="tot3_a" value="" name="tot3_a">
                  </td> --}}
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
        <button id="btnEnviar" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
        <a href="{{ route('gt.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
      </div>

    </div>
