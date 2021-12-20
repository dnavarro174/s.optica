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
              <h4 class="card-title">Stock de Productos</h4>
              <div class="row">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-6 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">

                        {{-- @if($text_search){{$text_search}} @endif --}}

                        <?php
                           if (isset($_GET['s'])){ ?>
                           <a class="ml-2 small btn-cerrar h4" href=' {{route('reportes.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                          <?php } ?>
                      </div>
                      
                        <?php 
                            /*$v = array(); 
                            $i=0;
                            foreach ($fechas as $fec) { 
                                $v[] = $fec->format('Y/m');
                                $i++;
                            } 
                            $dates = array_unique($v);*/

                          ?>
                      <div class=" col-sm-2 col-xs-12">

                       
                        {{-- @foreach($fechas as $key => $fecha)--}}
                            <?php  
                            /*$valor[] = $fecha->format('Y/m'); 
                            $interseccion = array_intersect($valor, $dates)*/
                            ?>
                        {{-- @endforeach --}} 

                  
                          

                        <select class="form-control" name="m" id="filter-by-date" onchange="submit();">
                          <option selected="selected" value="0">Todas las fechas</option>
                          <option value="2019/12">Diciembre 2019 </option>
                          <option value="2019/11">Noviembre 2019 </option>
                          <option value="2019/10">Octubre 2019 </option>
                          <option value="2019/09">Setiembre 2019</option>
                          <option value="2019/08">Agosto 2019 </option>
                          <option value="2019/07">Julio 2019</option>
                          <option value="2019/06">Junio 2019</option>
                          <option value="2019/05">Mayo 2019</option>
                          <option value="2019/04">Abril 2019</option>
                          <option value="2019/03">Marzo 2019</option>
                          <option value="2019/02">Febrero 2019</option>
                          <option value="2019/01">Enero 2019</option>

                          
                        </select>
                       

                      </div>
                      <div class=" col-sm-2 col-xs-12">

                        <select class="form-control" name="st" id="filter-by-date" onchange="submit();">
                          <option selected="selected" value="">Estado</option>
                            <option value="1">Pendiente</option>
                            <option value="2">Entregado</option>
                            <option value="3">Cancelado</option>
                        </select>
                      </div>

                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" ><i class="mdi mdi-magnify text-white icon-md"></i>Buscar</button>
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

                  <form action="{{-- {{ route('reportes.eliminarVarios') }} --}}" role='form' method="POST" id="form-delete">
                    {!! csrf_field() !!}

                    <div class="row">
                      
                      
                      
                      <div class="col-xs-12  col-sm-8 text-right mb-4">
                        <div class="form-row">
                          
                            @if(@isset($permisos['exportar_importar']['permiso']) and  $permisos['exportar_importar']['permiso'] == 1)
                            <div class=" col-sm-2 col-xs-12">
                              <a href="#" onclick="eximForm()" class="form-control btn btn-outline-secondary" data-toggle="modal" >Exportar / Importar</a>
                            </div>
                            @endif
                            {{-- data-target="#Modal_estudiantes" --}}

                            
                            <div class=" col-sm-2 col-xs-12">
                              <a href="{{ route('salidas.create') }}" class="form-control btn btn-dark"><i class="mdi mdi-plus text-white icon-md"></i> Nuevo</a>
                            </div>
                            @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                            <div class=" col-sm-2 col-xs-12">
                              <button type="submit" class="form-control btn btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                            </div>
                            @endif

                        </div>

                      </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $reportes_datos->firstItem() }} - {{ $reportes_datos->lastItem() }} de
                          {{ $reportes_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                    </div> {{-- end row --}}

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
                              <th style="width: 30%;">Responsable</th>
                              <th style="width: 10%;">Fecha_Entrega</th>
                              <th style="width: 30%;" class="truncate">Motivo</th>
                              <th style="width: 10%;">Estado</th>
                              <th style="width: 5%;">PDF</th>
                              <th style="width: 13%;">Fecha_Registro</th>
                              {{-- <th class="sorting_desc aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" aria-sort="descending" style="width: 61px;">Estado</th> --}}
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($reportes_datos as $datos)
                            <tr role="row" class="odd">
                              <td class="sinpadding"><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap>
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                  <a href="{{ route('productos.edit',$datos->id)}}" class="">
                                    <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                  </a>
                                  @endif

                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('productos.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                  {{-- <form style="display: inline;" method="POST" action="{{ route('reportes.destroy', $datos->id)}}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="button_submit" title="Eliminar"><img src="images/ico/trash.png" class="acciones" width="14" alt="edit icono"></button>
                                  </form> --}}
                                </td>
                                <td>{{ $datos->responsable }}</td>
                                <td>{!! \Carbon\Carbon::parse($datos->fecha_entrega)->format('d-m-Y') !!}</td>{{-- ->format('d-m-Y') --}}
                                <td>
                                  @if(strlen($datos->motivo)>37)
                                  {{ 
                                    str_limit($datos->motivo,37,'...')
                                  }}
                                  @else
                                    {{$datos->motivo}}
                                  @endif
                                </td>
                                <td>
                                  @if(1 === $datos->estado)
                                  <label class="badge badge-success">Pendiente</label>
                                  @elseif(2 === $datos->estado)
                                  <label class="badge badge-danger">Entregado</label>
                                  @else
                                  <label class="badge badge-secondary">Cancelado</label>
                                  @endif

                                </td>
                                <td><a href="{{ route('reportes.comprobante', $datos->id) }}" class="btn btn-link"><i class="mdi mdi-file-pdf"></i>PDF</a></td>

                                <td>{{ $datos->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {!! $reportes_datos->appends(request()->query())->links() !!}

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