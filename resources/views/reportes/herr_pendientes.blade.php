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
                <h4 class="card-title mb-0">Herramientas Pendientes
                  <a href="{{ URL::previous() }}" class="btn btn-link py-0"><i class="mdi mdi-reply-all"></i> Regresar</a>
                </h4>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-2 col-xs-12">
                        <span class="small pull-right ptt-3">
                          <strong>Mostrando</strong>
                          {{ $salidas_datos->firstItem() }} - {{ $salidas_datos->lastItem() }} de
                          {{ $salidas_datos->total() }}
                        </span>
                      </div>

                      <div class=" col-sm-8 col-xs-12"></div>
    
                      <div class="col-xs-12 col-sm-2 text-right mb-4">
                        <select onchange="submit()" class="form-control" name="pag" id="pag">
                          @if(isset($_GET['pag']))
                          <option value="15" @if(($_GET['pag'] == 15)) selected @endif>15</option>
                          <option value="20" @if(($_GET['pag'] == 20)) selected @endif>20</option>
                          <option value="30" @if(($_GET['pag'] == 30)) selected @endif>30</option>
                          <option value="50" @if(($_GET['pag'] == 50)) selected @endif>50</option>
                          <option value="100" @if(($_GET['pag'] == 100)) selected @endif>100</option>
                          <option value="500" @if(($_GET['pag'] == 500)) selected @endif>500</option>
                          @else
                          <option value="15">15</option><option value="20">20</option><option value="30" >30</option><option value="50" >50</option><option value="100">100</option><option value="500">500</option>
                          @endif
                        </select>
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

                  <div id="order-listing_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12 table-responsive-lg">
                        <table id="order-listing" class="table ">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 5%;">Fecha</th>
                              <th style="width: 5%;">Referencia</th>
                              <th style="width: 20%;">Responsable</th>
                              <th style="width: 5%;">Código</th>
                              <th style="width: 20%;">Artículo</th>
                              <th style="width: 5%;">Cantidad</th>
                              <th style="width: 6%;">Estado</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($salidas_datos)==0)
                              <th colspan="7">No existe registros</th>
                            @else
                            @foreach ($salidas_datos as $datos)
                            <tr role="row" class="odd">
                                <td>{!! \Carbon\Carbon::parse($datos->created_at)->format('d.m.Y') !!}</td>
                                <td>{{ $datos->nro_ref }}</td>
                                <td>{{ $datos->responsable }}</td>
                                <td>{{ $datos->cod_sunat }}</td>
                                <td>{{ $datos->nombre }}</td>
                                <td class="text-right">{{ $datos->cant_mov }}</td>
                                <td><label class="badge btn-sm @if($datos->doc_estado == 'P')badge-info @endif @if($datos->doc_estado == 'E') badge-secondary @endif">@if($datos->doc_estado == 'P') Pendiente @endif @if($datos->doc_estado == 'E') Entregado @endif</label></td>
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