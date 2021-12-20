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
        
        <div class="content-wrapper pt-0">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Contratos 
                <a href="{{ route('menu_almacen.index')}}" class="btn btn-link"><i class="mdi mdi-reply-all"></i> Menú</a>
                <a href="{{ route('almacen.index')}}" class="btn btn-link"><i class="mdi mdi-reply-all"></i> Almacen</a></h4>
              
                <div class="row" id="capBusqueda" style="display: block;">
                <div class="col-xs-12 col-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-7 col-xs-12">
                        <input type="text" class="form-control text-uppercase" placeholder="BUSCAR" name="s" value="@if($text_search){{$text_search}} @endif">

                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar h4" href=' {{route('contratos.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                          <?php } ?>
                      </div>
                      <div class="col-xs-12 col-sm-2 text-right mb-4">
                        <select onchange="submit()" class="form-control" name="e" id="e">
                          @if(isset($_GET['e']))
                          <option value="1" @if(($_GET['e'] ==1)) selected="" @endif>PENDIENTE</option>
                          <option value="2" @if(($_GET['e'] == 2)) selected="" @endif>ENTREGADO</option>
                          @else
                          <option value="">SELECCIONE</option><option value="1">PENDIENTE</option><option value="2">ENTREGADO</option>
                          @endif
                        </select>
                      </div>
                      
                      <div class="col-xs-12 col-sm-1 text-right mb-4">
                        <select onchange="submit()" class="form-control" name="pag" id="pag">
                          @if(isset($_GET['pag']))
                          <option value="15" @if(($_GET['pag'] == 15)) selected="" @endif>15</option>
                          <option value="20" @if(($_GET['pag'] == 20)) selected="" @endif>20</option>
                          <option value="30" @if(($_GET['pag'] == 30)) selected="" @endif>30</option>
                          <option value="50" @if(($_GET['pag'] == 50)) selected="" @endif>50</option>
                          <option value="100" @if(($_GET['pag'] == 100)) selected="" @endif>100</option>
                          <option value="500" @if(($_GET['pag'] == 500)) selected="" @endif>500</option>
                          @else
                          <option value="15">15</option><option value="20">20</option><option value="30" >30</option><option value="50" >50</option><option value="100">100</option><option value="500">500</option>{{-- <option value="-1" >Todos</option> --}}
                          @endif
                        </select>
                      </div>
                     
                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" ><i class="mdi mdi-magnify text-white icon-md"></i>BUSCAR</button>
                      </div>
                    </div>
                  </form>
                  
                  
                </div>
              </div>
              <div class="row d-none" id="nuevo" >{{-- style="display:none;" --}}
                <div class="d-flex">
                  <div class="d-block px-3">
                    <form class="form" id="contratoForm" action="{{ route('contratos.store') }}" method="post">
                          {!! csrf_field() !!}
                          <input type="hidden" name="opt" id="opt" value="">
                          <input type="hidden" name="_id" id="_id" value="">

                          <div class="row">
                            
                            <div class="col-sm-12 col-md-2">
                              <div class="form-group">
                                <label for="cod_ruc">DNI <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input disabled="" required="" type="text" maxlength="15" name="cod_ruc" id="cod_ruc" class="form-control" value="{{ old('cod_ruc') }}" placeholder="">
                                  <input type="hidden" name="cod_ruc2" id="cod_ruc2">
                                  <input type="hidden" name="cod_emp2" id="cod_emp2">
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-3">
                              <div class="form-group">
                                <label for="cliente">Cliente <span class="text-danger">*</span> <a href="#" id="AddProveedor" class="btn btn-link py-0" style="font-size: 12px;" onclick="formCliente('1','cliente','{{ url('') }}')">Registrar Nuevo Cliente</a></label>
                                <div class="input-group mb-2">
                                  <input required type="text" autocomplete="off" class="form-control text-uppercase" name="cliente" id="razon_social" placeholder="Cliente"  value="{{ old('cliente') }}" >
                                  {!! $errors->first('cliente', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1">
                              <div class="form-group">
                                  <label for="edad">Edad <span class="text-danger">*</span></label>
                                  <div class="input-group mb-2">
                                      <input type="text" name="edad" id="edad" class="form-control text-uppercase" placeholder="" value="{{old('edad')}} ddd" >
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-2">
                              <div class="form-group">
                                  <label for="tele">Teléfono <span class="text-danger">*</span></label>
                                  <div class="input-group mb-2">
                                      <input type="text" name="tele" id="tele" class="form-control text-uppercase" placeholder="" value="{{old('tele')}} ddd" >
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                              <div class="form-group">
                                <label for="descripcion">Descripción <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <textarea required name="descripcion" id="descripcion" class="form-control text-uppercase"  cols="30" rows="3" placeholder="DESCRIPCIÓN">{{ old('descripcion') }}</textarea>
                                  {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            
                            
                            <style>
                            .form-group .has-danger label.error {
                                display: flex;
                                width: 100%;
                            }
                            </style>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 col-md-2 pr-1">
                              <div class="form-group">
                                <label for="laboratorio">Laboratorio <span class="text-danger">*</span> <a href="#" id="AddLaboratorio" title="Registrar Laboratorio" class="btn btn-link py-0" style="font-size: 12px;" onclick="formCliente('1','laboratorio','{{ url('') }}')">Registrar </a></label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" autocomplete="off" name="laboratorio" id="laboratorio" placeholder="Nombre de Laboratorio"  value="{{ old('laboratorio') }}" >
                                  <input type="hidden" id="laboratorio_id" name="laboratorio_id" value="0">
                                  {!! $errors->first('laboratorio', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pr-1">
                              <div class="form-group">
                                <label for="medida_od">Medida OD</label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" name="medida_od" id="medida_od" placeholder=""  value="{{ old('medida_od') }}" >
                                  {!! $errors->first('medida_od', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_oi">Medida OI</label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" name="medida_oi" id="medida_oi" placeholder=""  value="{{ old('medida_oi') }}" >
                                  {!! $errors->first('medida_oi', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_add">Med. ADD</label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" name="medida_add" id="medida_add" placeholder=""  value="{{ old('medida_add') }}" >
                                  {!! $errors->first('medida_add', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_dip">Medida DIP</label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" name="medida_dip" id="medida_dip" placeholder=""  value="{{ old('medida_dip') }}" >
                                  {!! $errors->first('medida_dip', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_dip">Precio <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input id="precio" type="number" autocomplete="off" placeholder="0.00" step="any" required  class="form-control text-uppercase text-right" name="precio"   value="{{ old('precio_total') }}" >
                                  {!! $errors->first('precio', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="acuenta">A cuenta <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input id="acuenta" type="number" autocomplete="off" placeholder="0.00" step="any" required class="form-control text-uppercase text-right" name="acuenta" value="{{ old('acuenta') }}" >
                                  {!! $errors->first('acuenta', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="saldo">Saldo</label>
                                <div class="input-group mb-2">
                                  <input id="saldo"  type="text" autocomplete="off" placeholder="0.00" step="any" readonly class="form-control text-uppercase text-right" name="saldo"  value="{{ old('saldo') }}" >
                                  {!! $errors->first('saldo', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="estado">Estado </label>
                                <div class="input-group mb-2">
                                  <select name="estado" id="estado" class="form-control text-uppercase" required="">
                                    {{-- <option value="">SELECCIONE</option> --}}
                                    <option value="1">PENDIENTE</option>
                                    <option value="2">ENTREGADO</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1 text-center mt-4">
                              <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                            </div>
                            

                          </div> {{-- end row --}}


                          

                        </form>
                    
                  </div>
                </div>
              </div>


              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              @if (session('danger'))
                  
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class='mdi mdi-delete-sweep'></i></strong> {{ session('danger') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
              @endif
              
              <div class="row">
                <div class="col-12">

                  <form action="{{ route('contratos.eliminarVarios') }}" role='form' method="POST" id="form-delete">
                    {!! csrf_field() !!}

                    <div class="row">

                      <div class="col-xs-12  col-sm-8  mb-4">

                        @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                        <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                        @endif

                        @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                          <a href="#" id="btnNuevo" class="btn btn-sm btn-dark"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                          <a href="#" id="btnCancelar" class="btn btn-sm btn-secondary d-none">Cancelar</a>
                        @endif

                        @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                        <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                        @endif

                      </div> {{-- end derecha --}}
                      
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $contratos_datos->firstItem() }} - {{ $contratos_datos->lastItem() }} de
                          {{ $contratos_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                      
                    </div> {{-- end row --}}



                    <div class="row">
                      <div class="table-responsive fixed-height" style="height: 400px; padding-bottom: 49px;">
                        <table id="order-listing" class="table table-hover table-sm ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 3%;" class="sinpadding">
                                <input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1">
                              </th>
                              <th style="width: 2%;"></th>
                              {{-- <th style="width: 5%;">Código</th> --}}
                              <th style="width: 8%;">DNI</th>
                              <th style="width: 30%;">Cliente</th>
                              <th style="width: 30%;">Descripción</th>
                              <th style="width: 5%;">PDF</th>

                              <th class="text-center" style="width: 5%;">Med.OD</th>
                              <th class="text-center" style="width: 5%;">Med.OI</th>
                              <th class="text-center" style="width: 5%;">Med.ADD</th>
                              <th class="text-center" style="width: 5%;">Med.DIP</th>
                              <th class="text-center" style="width: 5%;">Estado</th>
                              <th class="text-center" style="width: 12%;">Precio</th>
                              <th class="text-center" style="width: 12%;">Acuenta</th>
                              <th class="text-center" style="width: 12%;">Saldo</th>
                              <th class="text-center" style="width: 5%;">Fecha</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($contratos_datos)==0)
                              <th colspan="12">No existe registros</th>
                            @else
                            @foreach ($contratos_datos as $datos)
                            <tr role="row" class="odd" @if($datos->stock_total < $datos->stock_min) style="background: #ffbebe;" @endif>
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-iidd="{{ $datos->id }}"></td>
                              <td nowrap>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  {{-- {{ route('contratos.edit',$datos->id)}} --}}
                                  <a href="#" onclick="editContrato('{{$datos->id}}', '{{url('')}}')" class="_editarC">
                                    <i class="mdi mdi-pencil text-dark icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                </td>
                                <td align="center">{{ $datos->cod_ruc }}</td>
                                <td>{{ str_limit($datos->razon_social,20) }}</td>
                                <td title="{{$datos->descripcion }}">{{ str_limit($datos->descripcion,50) }}</td>
                                <td>
                                  <a href="#" class="my-2" title="Formato ticket">
                                    <img src="{{asset('images/menu_icons/ticket_cpe.svg')}}" alt="ticket" width="25">
                                  </a><br>
                                  <a href="#" class="my-2" title="Formato A4">
                                    <img src="{{asset('images/menu_icons/pdf_cpe.svg')}}" alt="pdf" width="25">
                                  </a>
                                  
                                </td>
                                <td class="text-center">{{ $datos->medida_od }}</td>
                                <td class="text-center">{{ $datos->medida_oi }}</td>
                                <td class="text-center">{{ $datos->medida_add }}</td>
                                <td class="text-center">{{ $datos->medida_dip }}</td>
                                <th class="text-center">
                                  <div class="badge @if($datos->estado==1) badge-danger @else badge-success @endif  badge-pill">{{$datos->estado==1?'PENDIENTE':'ENTREGADO'}}</div>
                                </th>
                                <th class="text-center">{{ $datos->precio_total }}</th>
                                <th class="text-center">{{ $datos->acuenta }}</th>
                                <th class="text-center">{{ $datos->saldo }}</th>
                                <td class="text-center">{{ \Carbon\Carbon::parse($datos->created_at)->format('d-m-Y') }} / <br>{{ \Carbon\Carbon::parse($datos->created_at)->format('h:m A') }}</td>
                                
                                
                                {{-- <td align="center">
                                  @if($datos->flag_activo == 'S')
                                  <i class="mdi mdi-map-marker-circle text-success" title="Activo"></i>
                                  @else
                                  <i class="mdi mdi-map-marker-circle text-secondary" title="Activo"></i>
                                  @endif
                                </td> --}}
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                        {!! $contratos_datos->appends(request()->query())->links() !!}

                      </div>
                    </div>
                  
  
                  </form>
                  {{-- {{ Form::close() }} --}} {{-- end close form --}}

                </div>
              </div>
            </div>
           
          </div>
        </div> <!-- end listado table -->

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

  {{-- PROVEEDORES --}}
  <div class="modal fade ass" id="Modal_add_provee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog h-100 my-0 mx-auto d-flex flex-column justify-content-center" role="document">
      <div class="modal-content m-2"> 
        <form  id="f_proveedor" name="f_proveedor" method="post" action="{{ route('cli.clienteStore') }}" class="formarchivo" >
            {!! csrf_field() !!}
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="exampleModalLabel">Registrar Nuevo Cliente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body pt-0 form-act">


        </div>
        <div class="modal-footer">
          <a href="{{route('ctas_corrientes.index', ['id'=>2])}}" target="_blank" class="btn btn-link">Ver listado</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-dark" id="saveProveedor">Guardar</button>{{-- btnImport1 --}}
        </div>
        </form>
      </div>
    </div>
  </div>
  {{-- fin modal --}}
  {{-- LABORATORIO --}}
  <div class="modal fade ass" id="Modal_add_laboratorio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog h-100 my-0 mx-auto d-flex flex-column justify-content-center" role="document">
      <div class="modal-content m-2"> 
        <form  id="f_laboratorio" name="f_laboratorio" method="post" action="{{ route('cli.laboratorioStore') }}" class="formarchivo" >
            {!! csrf_field() !!}
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="exampleModalLabel">Registrar Nuevo Laboratorio</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body pt-0 form-act">


        </div>
        <div class="modal-footer">
          {{-- <a href="{{route('ctas_corrientes.index', ['id'=>2])}}" target="_blank" class="btn btn-link">Ver listado</a> --}}
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-dark" id="saveLaboratorio">Guardar</button>{{-- btnImport1 --}}
        </div>
        </form>
      </div>
    </div>
  </div>
  {{-- Comprobante Ticket --}}
  <div class="modal fade ass" id="Modal_Comprobante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog h-100 my-0 mx-auto d-flex flex-column justify-content-center" role="document">
      <div class="modal-content m-2"> 
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="exampleModalLabel">
           <i class="mdi mdi-checkbox-marked-circle"></i> Proceso Completo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body pt-0 form-act">
          <div class="row">
            <div class="col-12">
              <div class="alert alert-success" role="alert">
                Se guardó correctamente el contrato: <strong>#1</strong>
              </div>
            </div>
            <div class="col-12 text-center">
              <a href="#" class="mx-3 my-4">
                <img src="{{asset('images/menu_icons/ticket_cpe.svg')}}" alt="ticket" width="25">
              </a>
              <a href="#" class="mx-3 my-4">
                <img src="{{asset('images/menu_icons/pdf_cpe.svg')}}" alt="pdf" width="25">
              </a>
              
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <a class="btn btn-success btn-rounded btn-fw" href="{{route('contratos.index')}}" class="btn btn-link">Ver listado</a>
          <a class="btn btn-dark btn-rounded btn-fw" href="{{route('contratos.index')}}" class="btn btn-link">Crear nuevo contrato</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  {{-- fin modal --}}

@endsection
@section('footer')
<link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.min.css?id=1') }}">
<script src="{{ asset('js_auto/jquery.easy-autocomplete.js')}}"></script>
<script src="{{ asset('js/autocomplete.js')}}"></script>

<script>
$(document).ready(function(){
  let precio = 0;
  let acuenta = 0;
  let saldo = 0;
  console.log('listo');

  $( "#precio,#acuenta" ).blur(function() {
    this.value = parseFloat(this.value).toFixed(2);
    console.log('slll');
  });


  $('#btnNuevo').click((e)=>{
    mostrarForm();
    $("#opt").val('nuevo');
  });

  $('#btnCancelar').click((e)=>{
    ocultarbtnCancelar();
    $('#_id').val('');
    //$("#contratoForm")[0].reset();
    $("#contratoForm").trigger('reset'); 
  });

  /*$('#btnCancelar').click((e)=>{
    $('#nuevo').addClass('d-none').removeClass('d-block');
    $('#capBusqueda').addClass('d-block').removeClass('d-none');
    $('#btnCancelar').addClass('d-none').removeClass('d-inline-block');
    $('#btnNuevo').addClass('d-inline-block').removeClass('d-none');
  });*/

  // calc saldo
  $('#acuenta').keyup((e)=>{
    precio = parseFloat($('#precio').val());
    acuenta = parseFloat(e.target.value);

    if(precio=="" ){
        alert("Ingrese precio");
        $("#precio").focus().val('');
        return;
      }
    if(precio=="" || precio==0){
        //alert("El precio tiene que ser mayor a 0");
        swal("Advertencia","El precio tiene que ser mayor a 0", 'warning');
        $("#precio").focus().val('');
        return; 
      }

    if(precio<acuenta){
        //alert("Error: El campo Acuenta tiene que ser menor al PRECIO");
        swal("Advertencia","El campo Acuenta tiene que ser menor al PRECIO", 'warning');
        $("#acuenta").focus().val('');
        return; 
      }
    if(acuenta==0){
        //alert("Error: El campo Acuenta tiene que ser mayor a 0");
        swal('Advertencia', "El campo Acuenta tiene que ser mayor a 0", 'warning');
        $("#acuenta").focus().val('');
        return; 
      }

    saldo = precio - acuenta;
    saldo = saldo.toFixed(2);

    $('#saldo').val(saldo);
  });


  

  // Save Contrato
  $('form#contratoForm').submit(function (e) {
  //$('#actionSubmit').click(function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    console.log("Submit contrato");
    $('#actionSubmit').attr('disabled');

    var actionformPar = $("#contratoForm").attr('action');
    console.log("Submit contrato ajax");
    $.ajax({
          url: actionformPar,
          type:'POST',
          data: new FormData(this),
          processData: false,
          contentType: false,
            beforeSend: function(){
                //toastr.warning('Procesando su solicitud');
            },
          success: function(res){

            if(res==2){ //2: edit
              swal('Correcto', 'Registro modificado', 'success')
              .then((value) => {
                clearForm();
                location.reload();
              });
                  
            }else{ // 1: new
              swal('Correcto','Registro guardado correctamente','success')
              .then((value) => {
                clearForm();
                $("#Modal_Comprobante").modal('show');  
                console.log("Modal_Comprobante");
                return false;
                //$('form#contratoForm').submit(); 
              });  
            }
            

          },
          error: function(xhr, status, error){
            $("#saveProveedor").removeAttr("disabled");
            var err = JSON.parse(xhr.responseText);
            var tipo = err.tipo;
            console.log(err.error);
            console.log(status);
            swal("Advertencia", err.error, "warning");
            $("#btnGen").removeAttr("disabled");
            return false;
          
          }
      });
  })

});


//editar_usuario(7575)
function editContrato (id,url){
  event.preventDefault(); 
  var url = url +"/contratos/edit/"+id;
  $.get(url, function (resp,resul){
      console.log(resp);
    //if(resp.length > 0){razon_social
      $('#_id').val(resp.id);
      $('#cod_ruc,#cod_ruc2').val(resp.cod_ruc);
      $('#razon_social,#cod_emp2').val(resp.razon_social);
      $('#descripcion').val(resp.descripcion);
      $('#medida_od').val(resp.medida_od);
      $('#medida_oi').val(resp.medida_oi);
      $('#medida_add').val(resp.medida_add);
      $('#medida_dip').val(resp.medida_dip);
      $('#precio').val(resp.precio_total);
      $('#acuenta').val(resp.acuenta);
      $('#saldo').val(resp.saldo);
      $('#estado').val(resp.estado);
      $('#actionSubmit').text('Modificar');

      $('#nuevo').addClass('d-block').show();
      $("#opt").val('editar');
      mostrarForm();

  });
};

// Muestra form new y edit
function mostrarForm(){
    $('#nuevo').addClass('d-block').removeClass('d-none');
    $('#capBusqueda').addClass('d-none').removeClass('d-block');
    $('#btnCancelar').addClass('d-inline-block').removeClass('d-none');
    $('#btnNuevo').addClass('d-none').removeClass('d-inline-block');
}

// Oculta Btn
function ocultarbtnCancelar(){
  $('#nuevo').addClass('d-none').removeClass('d-block');
  $('#capBusqueda').addClass('d-block').removeClass('d-none');
  $('#btnCancelar').addClass('d-none').removeClass('d-inline-block');
  $('#btnNuevo').addClass('d-inline-block').removeClass('d-none');
}

function clearForm(){
  $("#contratoForm")[0].reset();
  //location.reload();
}

</script>

@endsection