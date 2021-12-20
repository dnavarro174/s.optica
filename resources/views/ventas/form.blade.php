<style type="text/css">
    .textos{max-width:45px;text-align:center}.textos2{max-width:80px;text-align:center}
</style>

<input type="hidden" name="tot_reg" id="tot_reg" value="{{old("tot_reg",$comprobante->tot_reg,0)}}">
<input type="hidden" name="ruta_alm" id="ruta_alm" value="{{ route('ventas.index') }}">
<input type="hidden" name="importe" id="importe" value="{{old("importe",$comprobante->importe,0)}}">

<div class="row">
    <div class="col-sm-12 d-flex justify-content-between mb-4">
        <h5 class="card-title mb-0">Cliente </h5>
    </div>

    <div class="col-sm-12 col-md-2">
        <div class="form-group">
            <label for="nro_preing">Tipo documento <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <select class="form-control" required="" name="nro_preing" id="nro_preing-">
                    <option value="0">OTRO DOCUMENTO (COD 0)</option>
                    <option value="1" selected>D.N.I.</option>
                    <option value="4">CARNET DE EXTRANJERIA (COD 4)</option>
                    <option value="6">R.U.C.</option>
                    <option value="7">PASAPORTE (COD 7)</option>
                    <option value="A">CED. DIPLOMATICA DE IDENTIDAD (COD A)</option>
                    <option value="B">OC.IDENT.PAIS.RESIDENCIA-NO.D (COD B)</option>
                    <option value="C">TIN (COD C)</option>
                    <option value="D">IN (COD D)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-2 d-none">
        <div class="form-group">
            <label for="nro_doc">Nro Documento <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input type="text" name="nro_doc" id="nro_doc" class="form-control text-uppercase" placeholder="Número" value="{{$numbers}}" autofocus="" required="">
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-2">
        <div class="form-group">
            <label for="cod_ruc">Nro Documento <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input readonly="" required="" type="text" name="cod_ruc" id="cod_ruc" class="form-control" value="{{ old('cod_ruc',$comprobante->cod_cliente) }}" placeholder="">
                <input type="hidden" name="cod_ruc2" id="cod_ruc2">
                <input type="hidden" name="cod_emp2" id="cod_emp2">
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="form-group ">
            <label for="razon_social">Nombre Cliente <span class="text-danger">*</span> <a href="#" id="AddProveedor" class="btn btn-link py-0" style="font-size: 12px;" onclick="formCliente('1','cliente','{{ url('') }}')">Registrar Nuevo Cliente</a></label>

            <div class="mb-2 col-md-12 p-0">
                <input required="" type="text" name="razon_social" id="razon_social" class="form-control text-uppercase" value="{{ old('razon_social',$comprobante->cliente?$comprobante->cliente->razon_social:'') }}" placeholder="Nombre">
            </div>
        </div>
    </div>
    
    
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label for="direccion">Dirección <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input type="text" name="direccion" id="direccion" class="form-control text-uppercase" placeholder="Dirección" value="{{old('direccion', $comprobante->direccion)}} ddd" >
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-12 d-flex justify-content-between mb-4">
        <h5 class="card-title mt-4 mb-0">Lista de Productos </h5>
    </div>
</div>

<div id="xcliente" class="row" style="display: none;">
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label for="cliente_doc" class="tit_proyecto"><span id="xtit">Cliente</span> <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input type="text" name="cliente_doc" id="cliente_doc" class="form-control text-uppercase" value="{{ old('cliente_doc',$comprobante->cod_cliente) }}" placeholder="INGRESE CLIENTE" title="INGRESE CLIENTE" autocomplete="off">
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
                    <option value="B">BOLETA</option>
                    <option value="F">FACTURA</option>
                    <option value="T">TICKET</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label for="numerodoc">Número <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input type="text" name="numerodoc" id="numerodoc" class="form-control text-uppercase" value="{{ old('numerodoc',$comprobante->nro_doc) }}" placeholder="INGRESE NÚMERO" autocomplete="off">
            </div>
        </div>
    </div>
</div>



<div class="row mostrar_1" >{{--style="display: none;" add productos --}}

    <div class="col-sm-12 col-md-1">
        <div class="form-group">
            <label for="cod_artic">Código </label>
            <div class="input-group mb-2">
                <input readonly="" type="text" maxlength="10" name="cod_artic" id="cod_artic" class="form-control" value="{{ old('cod_artic') }}" placeholder="00001">
                <input type="hidden" name="cod_artic2" id="cod_artic2">
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label for="producto">Producto <span class="text-danger">*</span></label>
            <div class="input-group mb-2">
                <input type="text" name="producto" id="producto" class="form-control" value="{{ old('producto') }}" placeholder="INGRESAR PRODUCTO" onkeyup="saltar(event,'cant')" autocomplete="off">
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

    <div class="col-sm-12 col-md-2">
        <div class="form-group">
            <label for="costo_mn">Precio </label>
            <div class="input-group mb-2">
                <input type="text" name="costo_mn" id="costo_mn" class="form-control text-right" value="{{ old('costo_mn') }}" placeholder="0.00" onkeyup="saltar(event,'subtotal');">
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
    <div class="col-sm-12 col-md-1 d-none">
        <div class="form-group">
            <label for="cod_umedida">UM </label>
            <div class="input-group mb-2">
                <input readonly="" type="text" name="cod_umedida" id="cod_umedida" class="form-control">
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-1 px-0">
        <div class="form-group">
            <label for="subtotal"> </label>
            <div class="input-group mb-2">
                <button type="button" class="btn btn-success" id="add_producto2" title="Agregar Producto">
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
                <th scope="col" class="text-center">U.M</th>
                {{-- <th scope="col" class="text-center">Precio</th> --}}
                <th scope="col" class="text-center">Cant.</th>
                <th scope="col" >Precio</th>
                <th cope="col" >Subtotal</th>
                <th cope="col" >Igv</th>
                <th cope="col" >Importe</th>
                <th cope="col" class="text-center" width="10%">Acciones</th>
            </tr>
            </thead>
            <tbody id="filas_contenedor">
            @if($comprobante->tot_reg)
                @foreach($details as $i=>$d)
                    <tr id='td_{{$i+1}}'>
                        <td style='text-align: center'>{{$i+1}}</td>
                        <td style='text-align: center'><input type='text' class='textos' readonly name='cod_art_{{$i+1}}' value='{{$d->cod_articulo}}' ></td>
                        <td>{{$d->nombre}} -- STOCK: {{round($d->stock_alm,2)}}</td>
                        <td style='text-align: center'><input type='text' class='textos' readonly name='uni_med_{{$i+1}}' value='{{$d->cod_umedida}}' ></td>
                        <td style='text-align: center'><input type='text' class='textos cant_{{$i+1}} impocal' readonly name='cant_{{$i+1}}' value='{{$d->cantidad}}' ></td>
                        <td style='text-align: center'><input type='text' class='textos costo_{{$i+1}} impocal' readonly name='costo_{{$i+1}}' value='{{$d->precio}}' ></td>

                        <td style='text-align: center'><input type='text' class='textos importes_{{$i+1}}' readonly name='importes_{{$i+1}}' value='{{number_format($d->cantidad*$d->precio/1.18,2,".","")}}' ></td>
                        <td style='text-align: center'><input type='text' class='textos igves_{{$i+1}}' readonly name='igves_{{$i+1}}' value='{{number_format(($d->cantidad*$d->precio)-($d->cantidad*$d->precio/1.18),2,".","")}}' ></td>

                        <td style='text-align: center'><input type='text' class='textos subt_{{$i+1}}' readonly name='subt_{{$i+1}}' value='{{number_format($d->cantidad*$d->precio,2,".","")}}' ></td>

                        <td style='text-align: center'>
                            <i class='mdi mdi-pencil text-dark icon-md btnAdd pr-1' num='{{$i+1}}' title='Agregar'></i>
                            <i class='mdi mdi-content-save text-dark icon-md btnSave save_{{$i+1}} pr-1' style='display:none;' num='{{$i+1}}' title='Guardar'></i>
                            <i class='mdi mdi-delete text-danger icon-md btnQuitar' num='{{$i+1}}' title='Quitar'></i>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="reg_ejm"><td colspan="8">No hay datos</td></tr>
            @endif
            </tbody>
            <tfoot>
            <tr id="td_totales">
                <td colspan="5" rowspan="5">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="forma_pago">Forma de Pago: <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                    <select class="form-control" required name="forma_pago" id="forma_pago">
                                        <option value="0" {{$comprobante->forma_pago==0?'selected':''}}>EFECTIVO</option>
                                        <option value="1" {{$comprobante->forma_pago==1?'selected':''}}>CHEQUE</option>
                                        <option value="4" {{$comprobante->forma_pago==4?'selected':''}}>TARJETA DE CRÉDITO</option>
                                        <option value="6" {{$comprobante->forma_pago==6?'selected':''}}>TRANSFERENCIA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="total_recibido">Total Recibido: </label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="total_recibido" id="total_recibido" value="{{old('total_recibido',$comprobante->monto)}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="descuento">Descuento en %</label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="descuento" id="descuento" value="{{old('descuento',$comprobante->descuento_porc)}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="vuelto">Vuelto: </label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="vuelto" id="vuelto" value="{{old('vuelto',$comprobante->vuelto)}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="obsercion">Observación</label>
                                <div class="input-group mb-2">
                                    <textarea name="obsercion" id="obsercion" cols="30" rows="2" class="form-control">{{old('obsercion',$comprobante->observacion)}}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </td>
                <td></td>
                <td colspan="2">Resumen:</td>
                <td align="center">{{-- <strong id="tot1"></strong> --}}</td>
                {{-- <td align="center"><strong id="tot2"></strong></td>
                <td align="center"><strong id="tot3"></strong>

                </td> --}}
            </tr>
            <tr id="td_subtotal">
                <td></td>
                <td colspan="2">SubTotal:</td>
                <td align="right"><strong id="xsubtotal_text"></strong><input type="hidden" id="xsubtotal" name="xsubtotal" value="{{old('xsubtotal',$comprobante->total)}}"></td>
            </tr>
            <tr id="td_desc">
                <td></td>
                <td colspan="2">Descuento:</td>
                <td align="right"><strong id="xdesc_text"></strong><input type="hidden" id="xdesc" name="xdesc"  value="{{old('xsubtotal',$comprobante->descuento_valor)}}"></td>
            </tr>
            <tr id="td_desc">
                <td></td>
                <td colspan="2">IGV:</td>
                <td align="right"><strong id="xigv_text"></strong><input type="hidden" id="xigv" name="xigv" value="{{old('xsubtotal',$comprobante->igv_total)}}"></td>
            </tr>
            <tr id="td_total">
                <td></td>
                <td colspan="2"><h6>Total:</h6></td>
                <td align="right"><h5 id="xtotal_text"></h5><input type="hidden" id="xtotal" name="xtotal" value="{{old('xsubtotal',$comprobante->total)}}"></td>
            </tr>
            </tfoot>
        </table>


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
        <a href="{{ route('ventas.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
    </div>

</div>
