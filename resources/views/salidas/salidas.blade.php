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

              <div class="d-flex justify-content-between mb-4">
                <h4 class="card-title mb-0">Vale de Consumo 
                  <a href="{{ route('menu_almacen.index')}}" class="btn btn-link py-0"><i class="mdi mdi-reply-all"></i> Menú</a>
                </h4>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-5 col-xs-12">
                        <input type="text" autocomplete="off" class="form-control" placeholder="BUSCAR" name="s" value="@if($search){{$search}} @endif">
                        
                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar h4" href=' {{route('salidas.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                          <?php } ?>
                      </div>
                      <div class=" col-sm-2 col-xs-12">
                        <select class="form-control" name="sta">
                          <option value="">ESTADO</option>
                          <option value="P">PENDIENTE</option>
                          <option value="E">ENTREGADO</option>
                        </select>
                      </div>
               
                      <div class=" col-sm-2 col-xs-12">

                        <select class="form-control" name="m" id="filter-by-date" onchange="submit();">
                          <option selected="selected" value="0">Todas las fechas</option>
                          @foreach($fechas as $f)
                          <option value="{{$f->fecha}}">@if($f->mes == '01') Enero @elseif($f->mes == '02') Febrero @elseif($f->mes == '03') Marzo @elseif($f->mes == '04') Abril @elseif($f->mes == '05') Mayo @elseif($f->mes == '06') Junio @elseif($f->mes == '07') Julio @elseif($f->mes == '08') Agostro @elseif($f->mes == '09') Setiembre @elseif($f->mes == '10') Octubre @elseif($f->mes == '11') Noviembre @else  Diciembre  @endif {{$f->ano}}</option>
                          @endforeach
                          {{-- <option value="2019/11">Noviembre 2019 </option>
                          <option value="2019/10">Octubre 2019 </option>--}}
                        </select>

                      </div>
                      
                      <div class="col-xs-12 col-sm-1 text-right mb-4">
                        <select onchange="submit()" class="form-control" name="pag" id="pag">
                          @if(isset($_GET['pag']))
                          <option value="15" @if(($_GET['pag'] == 15)) selected @endif>15</option>
                          <option value="20" @if(($_GET['pag'] == 20)) selected @endif>20</option>
                          <option value="30" @if(($_GET['pag'] == 30)) selected @endif>30</option>
                          <option value="50" @if(($_GET['pag'] == 50)) selected @endif>50</option>
                          <option value="100" @if(($_GET['pag'] == 100)) selected @endif>100</option>
                          <option value="500" @if(($_GET['pag'] == 500)) selected @endif>500</option>
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

                  <form action="{{ route('salidas.eliminarVarios') }}" role='form' method="POST" id="form-delete">
                    {!! csrf_field() !!}

                    <div class="row">{{-- cap: opciones --}}
                      
                      <div class="col-xs-12  col-sm-10 text-right mb-4">
                        <div class="form-row ">
                          
                            @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                            <div class=" col-sm-2 col-xs-12">
                              <a href="#" onclick="eximForm()" class="form-control btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                            </div>
                            @endif
                            {{-- data-target="#Modal_estudiantes" --}}

                            @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                            <a href="{{ route('salidas.create') }}" class="btn btn-sm btn-dark mx-1"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                            @endif
                            <div class="btn-group" id="btn_2" role="group">
                              <button id="btnGroupDrop1" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Reportes
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="top-start">
                                <a class="dropdown-item" href="{{ route('pendientes.herramientas') }}">Herramientas pendientes</a>
                              </div>
                          </div>

                            @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                            <div class=" col-sm-2 col-xs-12">
                              <button type="submit" class="form-control btn btn-secondary " disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> BORRAR</button>
                            </div>
                            @endif

                        </div>


                      </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-2 text-right mb-4">
                        <span class="small pull-left ptt-3">
                          <strong>Mostrando</strong>
                          {{ $salidas_datos->firstItem() }} - {{ $salidas_datos->lastItem() }} de
                          {{ $salidas_datos->total() }}
                        </span>
                      </div>{{-- end izq --}}
                    </div> {{-- end cap: opciones --}}

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 3%;" class="sinpadding">
                                <input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1">
                              </th>
                              <th style="width: 2%;"></th>
                              {{-- <th style="width: 2%;">Item</th> --}}
                              <th style="width: 5%;">Periódo</th>
                              <th style="width: 5%;">Documento</th>
                              <th style="width: 10%;">Origen Doc.</th>
                              <th style="width: 5%;">Referencia</th>
                              <th style="width: 25%;">Descripción</th>
                              <th style="width: 15%;">Responsable</th>
                              <th style="width: 5%;text-align: center;">Tipo</th>
                              <th style="width: 5%;text-align: center;">Estado</th>
                              <th style="width: 5%;"></th>
                              <th style="width: 6%;">Fecha</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($salidas_datos)==0)
                              <th colspan="7">No existe registros</th>
                            @else
                            @foreach ($salidas_datos as $datos)
                            <tr role="row" class="odd">
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('salidas.edit',$datos->id)}}">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('salidas.show',$datos->id)}}">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                </td>
                                {{-- <td>{{ $datos->id }}</td> --}}
                                <td>{{ $datos->ano_doc."/".$datos->mes_doc }}</td>
                                <td><a href="{{ route('salidas.edit',$datos->id)}}">{{ $datos->nro_doc }}</a></td>
                                <td>@if($datos->nro_preing ==1) ORDEN TRABAJO @elseif($datos->nro_preing ==2) CONSUMO INTERNO @else VENTAS @endif</td>
                                <td>{{ $datos->nro_ref }}</td>
                                <td>{{ $datos->proyecto->nom_proy }}</td>
                                <td>{{ $datos->responsable }}</td>
                                <td align="center">
                                  <label class="badge btn-sm @if($datos->doc_tipo == 'H')badge-dark @else badge-primary @endif">@if($datos->doc_tipo == 'H') Herramienta @else Material @endif</label>
                                </td>
                                <td>
                                  <label class="badge btn-sm @if($datos->doc_estado == 'P')badge-info @elseif($datos->doc_estado == 'E') badge-secondary @else badge-dark @endif">@if($datos->doc_estado == 'P') Pendiente @elseif($datos->doc_estado == 'E') Entregado @else Entregado @endif</label>
                                </td>
                                <td>
                                  <a target="_blank" href="{{ route('salidas.comprobante', ['id'=>$datos->id])}}" class="btn btn-danger btn-xs">DESCARGAR</a>
                                </td>
                                <td>{!! \Carbon\Carbon::parse($datos->created_at)->format('d.m.Y') !!}</td>
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                        {!! $salidas_datos->appends(request()->query())->links() !!}

                      </div>
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

@endsection