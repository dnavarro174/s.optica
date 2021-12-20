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
      <div class="main-panel">
        <div class="content-wrapper pt-3">
          <div class="row justify-content-center">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12 ">{{-- offset-md-1 --}}
                  
                      <div class="d-flex justify-content-between mb-4">
                        <h6 class="card-title mb-0">
                          {{session('empresa')['nom_empresa']}}  <br>
                          <strong>RUC: </strong> {{session('empresa')['ruc']}}
                        </h6>
                        <div class="col-xs-12 col-sm-4 text-right pr-0">
                          <a href="#" onclick='window.print();' class="btn btn-danger view_print"><i class="mdi mdi-file-pdf"></i>PDF</a>
                          {{-- <a href="{{route('kardex_excel.index',['id'=>1])}}" class="btn btn-success view_print"><i class="mdi mdi-file-excel"></i>EXCEL</a> --}}
                        </div>
                      </div>
                      <div class="col-xl-12 row">
                        <h6><a href="{{route('kardex_va.create')}}" class="btn btn-link view_print p-0"><i class="mdi mdi-reply-all"></i>Regresar</a></h6>
                      </div>
                      
                      @if (session('warning'))
                          <div class="alert alert-warning ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('warning') }}</strong>
                          </div>
                      @endif

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('kardex.create') }}" class="btn btn-success">Regresar</a>

                      @else
                        <form class="forms-sample" id="ingresosForm2" action="{{ route('gt.store') }}" method="post">
                          {!! csrf_field() !!}

                          <style type="text/css">
                          .textos{max-width:45px;text-align:center}.textos2{max-width:80px;text-align:center}
                          </style>

                          <input type="hidden" name="tot_reg" id="tot_reg" value="0">
                          <input type="hidden" name="ruta_alm" id="ruta_alm" value="{{ route('gt.index') }}">
                              
                              <div class="row">

                                <div class="col-sm-12 col-md-7"></div>
                                <div class="col-sm-12 col-md-5">
                                  <div class="form-group">
                                    <h3 class="h6 text-right"> {{ \Carbon\Carbon::now()->format('d.m.Y')}} </h3>
                                  </div>
                                </div>
                              </div>
                              
                              {{-- ocultado --}}
                              <div class="row mostrar_1">
                                <div id="msg_stock" class="msg_stock col-xs-12 col-md-12"></div>
                                  <div class="col-xs-12 col-md-12 ">{{-- table-striped --}}
                                      <table class="table table-hover table-sm table-responsive" id="ma_detalle" width="100%">
                                        <thead class="thead-">
                                          <tr>
                                            <th scope="col" class="text-center tab_v" width="2%" rowspan="2">Itém</th>
                                            <th scope="col" class="text-center tab_v" width="5%" rowspan="2">Fecha</th>
                                            <th scope="col" class="text-center tab_v" width="4%" rowspan="2">Tipo<br> Doc</th>
                                            <th scope="col" class="text-center tab_v" width="5%" rowspan="2">Documento</th>
                                            <th class="table-info text-center" colspan="3" scope="col" width="5%">INGRESO</th>
                                            <th class="table-danger text-center" colspan="3" scope="col" width="5%">SALIDA</th>
                                            <th class="table-success text-center" colspan="3" scope="col" width="5%">SALDO</th>
                                            <th scope="col" class="tab_v" width="10%" rowspan="2">Observaciones</th>
                                          </tr>
                                          <tr>
                                            <th class="table-info text-center" scope="col" width="5%">Cant</th>
                                            <th class="table-info text-center" scope="col" width="5%">C.U.</th>
                                            <th class="table-info text-center" scope="col" width="5%">Costo</th>
                                            <th class="table-danger text-center" scope="col" width="5%">Cant</th>
                                            <th class="table-danger text-center" scope="col" width="5%">C.U.</th>
                                            <th class="table-danger text-center" scope="col" width="5%">Costo</th>
                                            <th class="table-success text-center" scope="col" width="5%">Cant</th>
                                            <th class="table-success text-center" scope="col" width="5%">C.U.</th>
                                            <th class="table-success text-center" scope="col" width="5%">Costo</th>
                                          </tr>
                                        </thead>

                                        {{-- <tbody id="filas_contenedor"> --}}
                                          @foreach($articulos as $i => $kar)
                                          {{-- <tr class="reg_ejm"><td colspan="8">No hay datos</td></tr> --}}
                                          {{-- @if($kar['tot_i']>0) --}}
                                          <tr>
                                            <td colspan="2" class="text-left">CÓDIGO: {{$kar['cod_artic']}}{{-- cod_sunat --}}</td>
                                            <td colspan="10" class="text-left">DESCRIPCIÓN: {{$kar['desc']}}</td>
                                            <td colspan="2" class="text-left">U.M.: {{$kar['um']}}</td>
                                          </tr>
                                          {{-- @endif --}}

                                          @foreach($kar['data'] as $j => $k)
                                          <tr>
                                            <td align="center">{{$j+1}} </td>{{-- $k['id'] --}}
                                            <td>{{$k['fecha']}}</td>
                                            <td>{{$k['tipo_doc'] }}</td>
                                            <td class="text-center">{{$k['documento'] }}</td>

                                            <td class="text-right">{{$k['icant']}}</td>
                                            <td class="text-right">{{$k['icosto']}}</td>
                                            <td class="text-right">{{$k['icostot']}}</td>

                                            <td class="text-right">{{$k['ecant']}}</td>
                                            <td class="text-right">{{$k['ecosto']}}</td>
                                            <td class="text-right">{{$k['ecostot']}}</td>

                                            <td class="text-right">{{$k['scant']}}</td>
                                            <td class="text-right">{{$k['scosto']}}</td>
                                            <td class="text-right">{{$k['scostot']}}</td>

                                            <td class="text-left">{{$k['obs']}}</td>{{-- 8 col --}}
                                          </tr>
                                          @endforeach
                                          
                                        {{-- </tbody>
                                        <tfoot> --}}
                                          {{-- @if($kar['tot_i']>0) --}}
                                          <tr id="td_totales">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right totales">Total en:</td>
                                            <td align="right"><strong class="tot1">{{$kar['tot_i']}}</strong></td>
                                            <td> </td>
                                            <td align="right"><strong class="tot1">{{$kar['tot_icosto']}}</strong></td>

                                            <td align="right"><strong class="tot1">{{$kar['tot_e']}}</strong></td>
                                            <td> </td>
                                            <td align="right"><strong class="tot1">{{$kar['tot_ecosto']}}</strong></td>

                                            <td align="right"></td>
                                            <td> </td>
                                            <td align="right"></td>
                                          </tr>
                                          {{-- @endif --}}
                                        {{-- </tfoot> --}}
                                        @endforeach
                                      </table>


                                      
                                  </div>
                              </div>

                              <div class="form-group row view_print">
                                <div class="col-sm-12 text-center mt-4">
                                  <div id="save_form" class="alert alert-success " role="alert">
                                    <strong>
                                      Registro guardado!!
                                    </strong>
                                  </div>
                                  {{-- <button id="btnEnviar" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button> --}}
                                  <a href="{{ route('kardex_va.create')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Regresar</a>
                                </div>

                              </div>
                          

                        </form>
                      @endif

                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
          
          
        </div>
        

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
@section('footer')
<style>
#filas_contenedor{text-align: center;}
.tab_v{vertical-align: middle ;}
.totales{font-weight: bold;}
</style>

{{-- <script src="{{ asset('js/salidas.js')}}"></script>

<link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.min.css?id=1') }}">
<script src="{{ asset('js_auto/jquery.easy-autocomplete.js')}}"></script>
<script src="{{ asset('js/autocomplete.js')}}"></script> --}}
<script>
$('document').ready(function(){

  $('#almacen_d').change(function(){
    $('.mostrar_1').fadeIn('slow');
  });


});
</script>
@endsection