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
              <h4 class="card-title">Partes de Ingresos 
                <a href="{{ route('menu_almacen.index')}}" class="btn btn-link"><i class="mdi mdi-reply-all"></i> Menú</a>
              </h4>
              <div class="row">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-7 col-xs-12">
                        <input type="text" autocomplete="off" class="form-control" placeholder="BUSCAR" name="s" value="@if($search){{$search}} @endif">
                        
                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar h4" href=' {{route('ingresos.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                          <?php } ?>
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

                  {{-- {{ Form::open(array('route' => array('ingresos.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }} --}}
                  <form action="{{ route('ingresos.eliminarVarios') }}" role='form' method="POST" id="form-delete">
                    {!! csrf_field() !!}

                    <div class="row">{{-- cap: opciones --}}
                      
                      <div class="col-xs-12  col-sm-10 text-right mb-4">
                        <div class="form-row">
                          
                            @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                            <div class=" col-sm-2 col-xs-12">
                              <a href="#" onclick="eximForm()" class="form-control btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                            </div>
                            @endif
                            {{-- data-target="#Modal_estudiantes" --}}

                            @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                            {{-- <div class=" col-sm-2 col-xs-12">
                              <a href="{{ route('ingresos.create') }}" class="form-control btn btn-dark"><i class="mdi mdi-plus text-white icon-md"></i> NUEVO</a>
                            </div> --}}

                            <a href="{{ route('ingresos.create') }}" class="btn btn-sm btn-dark"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                            @endif
                            @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                            <div class=" col-sm-2 col-xs-12">
                              <button type="submit" class="form-control btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> BORRAR</button>
                            </div>
                            @endif

                        </div>

                      </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-2 text-right mb-4">
                        <span class="small pull-left ptt-3">
                          <strong>Mostrando</strong>
                          {{ $ingresos_datos->firstItem() }} - {{ $ingresos_datos->lastItem() }} de
                          {{ $ingresos_datos->total() }}
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
                              <th style="width: 5%;">Referencia</th>
                              <th style="width: 5%;">RUC</th>
                              <th style="width: 25%;">Proveedor</th>
                              <th class="text-center" style="width: 15%;">Tipo de Cambio</th>
                              <th class="text-center" style="width: 5%;">Moneda</th>
                              <th class="text-center" style="width: 7%;">Total S/</th>
                              <th class="text-center" style="width: 7%;">Total $</th>
                              <th style="width: 10%;">Fecha</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($ingresos_datos)==0)
                              <th colspan="12">No existe registros</th>
                            @else
                            @foreach ($ingresos_datos as $datos)
                            <tr role="row" class="odd">
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('ingresos.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('ingresos.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                </td>
                                {{-- <td>{{ $datos->id }}</td> --}}
                                <td>{{ $datos->ano_doc."/".$datos->mes_doc }}</td>
                                <td><a href="{{ route('ingresos.edit',$datos->id)}}">{{ $datos->nro_doc }}</a></td>
                                <td>{{ $datos->nro_ref }}</td>
                                <td>{{ $datos->cta_cte }}</td>
                                <td>{{ $datos->ruc->razon_social }}</td>
                                <td class="text-center">{{ number_format($datos->tc_mo_me, 2) }}</td>
                                <td class="text-center">{{ number_format($datos->tc_mn_me, 2) }}</td>
                                <td class="text-center">{{ number_format($datos->costo_tot_mn, 2) }}</td>
                                <td class="text-center">{{ number_format($datos->costo_tot_me, 2) }}</td>
                                <td>{!! \Carbon\Carbon::parse($datos->fecha_hora)->format('d.m.Y') !!}</td>
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                        {!! $ingresos_datos->appends(request()->query())->links() !!}

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