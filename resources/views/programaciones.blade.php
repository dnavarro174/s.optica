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
        
        <div class="content-wrapper pt-0">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Listado de Programaciones</h4>
              <div class="row" id="capBusqueda" style="display: block;">
                <div class="col-12 form-inline">
                  
                  <form action="{{ route('programaciones.index') }}" method="GET" class="form-inline pull-right">
                    <div class="form-group mb-2">
                      <input type="text" class="form-control text-uppercase" id="s" name="s" placeholder="Buscar" value="">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary mb-2" id="buscar" {{-- onclick="buscarusuario()" --}} >Buscar</button>

                    <?php
                     if (isset($_GET['s'])){ ?>
                     <a class="ml-2 small" href=' {{route('programaciones.index')}} '>Borrar busqueda</a>
                    <?php } ?>
                  </form>
                </div>
              </div>


              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('programaciones.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}
                  <div class="row">
                    
                    <div class="col-xs-12  col-sm-8 text-left mb-4">

                      @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                      <a href="#" onclick="eximForm()" class="btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                      @endif
                      {{-- data-target="#Modal_estudiantes" --}}

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"  >Borrar Seleccionados</button>
                      @endif

                      {{--  --}}
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                      <a href="{{ route('programaciones.create') }}" class="btn btn-outline-success">Agregar Nuevo</a>
                      @endif

                    </div> {{-- end derecha --}}
                    <div class="col-xs-12 col-sm-4 text-right mb-4">
                      <span class="small pull-left">
                        <strong>Mostrando</strong>
                        {{ $programaciones_datos->firstItem() }} - {{ $programaciones_datos->lastItem() }} de
                        {{ $programaciones_datos->total() }}
                      </span>

                    </div>{{-- end izq --}}
                    
                  </div> {{-- end row --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead>
                            <tr role="row">
                              <th style="width: 3%;" class="sinpadding"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" style="width: 2%;"></th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Código #: orden ascendente" style="width: 5%;">Código</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased On: activate to sort column ascending" style="width: 20%;">Nombre</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Customer: activate to sort column ascending" style="width: 10%;">Tipo</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 25%;">Curso</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Base Price: activate to sort column ascending" style="width: 13%;">N° Sesiones</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased Price: activate to sort column ascending" style="width: 10%;">Fecha Desde</th>
                              <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased Price: activate to sort column ascending" style="width: 10%;">Fecha Hasta</th>
                              {{-- <th class="sorting_desc" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" aria-sort="descending" style="width: 61px;">Estado</th> --}}
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($programaciones_datos as $datos)
                            <tr role="row" class="odd">
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('programaciones.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif

                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('programaciones.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif

                                  <a href="{{ route('actividades.create', 'act')}}" class="">
                                    <i class="mdi mdi-alarm text-primary icon-md" title="Agregar una Actividad"></i>
                                  </a>

                                  {{-- <form style="display: inline;" method="POST" action="{{ route('programaciones.destroy', $datos->id)}}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="button_submit" title="Eliminar"><img src="images/ico/trash.png" class="acciones" width="14" alt="edit icono"></button>
                                  </form> --}}
                                </td>
                                <td>{{ $datos->codigo }}</td>
                                <td>{{ $datos->nombre }}</td>
                                <td>{{ $datos->tipo }}</td>
                                <td>{{ $datos->nombre_curso }}</td>
                                <td>{{ $datos->nsesiones }}</td>
                                <td>{{ $datos->fecha_desde }}</td>
                                <td>{{ $datos->fecha_hasta }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $programaciones_datos->appends(request()->query())->links() !!}

                      </div>
                    </div>
                  </div>

                  {{ Form::close() }} {{-- end close form --}}

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


{{-- form importar --}}
<div class="modal fade ass" id="Modal_estudiantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form  id="f_cargar_datos_programaciones" name="f_cargar_datos_programaciones" method="post"  action="{{ route('programaciones.import') }}" class="formarchivo" enctype="multipart/form-data" >
          {!! csrf_field() !!}
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Importar / Exportar - Excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span> 
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group row">
            <h4 class="col-md-3 mt-1">Export</h4>
            <div class="col-md-9">
              <a href="{{ route('programaciones.export') }}" class="btn btn-secondary btn-block">Exportar</a>
              <span class="help-block with-errors"></span>

            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <h4>Import</h4>
              <div class="dropify-wrapper"><div class="dropify-message"><span class="file-icon"></span> <p>Seleccione el archivo .xls o .csv</p><p class="dropify-error">Ooops, nose ha adjuntado</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div>

                <input type="file" name="file" id="archivo" class="dropify" required>
                <button type="button" class="dropify-clear">Quitar</button>

                <div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Clic para reemplazar archivo</p></div></div></div></div>

              <span class="help-block with-errors"></span>

            </div>
          </div>
        <div style="display:none;" id="cargador_excel" class="content-wrapper p-0" align="center">  {{-- msg cargando --}}
          <div class="card bg-white" style="background:#f3f3f3 !important;" >
            <div class="">
              <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
              <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
            </div>
          </div>
        </div>{{-- msg cargando --}}



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="btnImport1">Importar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade ass" id="Modal_organizar_programaciones" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width: 95%; margin-top:2%; ">
    <div class="modal-content" style="max-height: 600px">
    

      <div class="card">
        <div class="card-body" style=" overflow: scroll; ">
          <iframe src="{{ route('programaciones.importresults') }}" frameborder="1" width="100%" height="400" id="iframePrev" style="display:none;    border: 1px solid #e6e6e6;"></iframe>

          <form class="form-inline"  id="programacionesImportSave" name="programacionesImportSave" action="{{ route('programaciones.importsave') }}" method="post" >
            {!! csrf_field() !!}
          <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="rnr-is-control checkbox">
                  <label><input class="rnr-checkbox" id="chkPrimeraFila" name="chkPrimeraFila" type="checkbox" value="1" checked> Cabeceras de columnas en la primera línea</label>
                </div>
              </div>
            <div class="col-xs-12 col-sm-4">
                <div id="dateFormatSettings1" class="rnr-is-control form-group">
                  
                    <label class="pr-2" style="font-size: 15px">Formato de fecha: </label>
                 
                  
                    <input id="txtFormatoF" name="txtFormatoF" type="text" value="dd/mm/yyyy" class="form-control border-primary">
                  
                </div>
              </div>
              <div style="display:none;" id="cargador_excel2" class="content-wrapper p-0" align="center">{{-- end div cargando --}}
                <div class="card bg-white text-center p-3 border0" style="background:#fff !important;" >
                  <div class="row col-12">
                    <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                    <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                  </div>
                </div>
              </div> {{-- end div cargando --}}
          </div>
            
          <div class="row">
              <table id="tbl_estudiantes_imp_ord" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
              </table>
              <input type="hidden" name="totCol" id="totCol">
              <input type="hidden" name="hdnTabla" id="hdnTabla">
          </div>

          </form>
        </div>
      </div>
      <div class="modal-footer">
        <div id="resultado" style="display:none;">Cargando...</div>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="btnRegresar" >Regresar</button>

        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCerrarIf_programaciones">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnSumImport_programaciones">Importar Datos</button>
      </div>

    </div>

  </div>
</div>
{{-- form importar --}}

@endsection