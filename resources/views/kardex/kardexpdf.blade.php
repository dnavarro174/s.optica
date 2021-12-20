<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/vendor.bundle.base.css?v=2')}}">
    <link rel="stylesheet" href="{{ asset('css/vendor.bundle.addons.css?v=2')}}"> -->
    <link rel="stylesheet" href="{{ asset('css/style.css?v=2')}}">
    <style>
      .page-break {page-break-after: always;page-break-before:always;}
    </style>
  </head>
  <body class="bg-blanco">

  <div class="container-scroller">
      <div class="row mostrar_1">
                                <div id="msg_stock" class="msg_stock col-xs-12 col-md-12"></div>
                                  <div class="col-xs-12 col-md-12 ">
                                      <table class="table" id="ma_detalle" width="100%">
                                        <thead class="thead-">
                                          <tr>
                                            <th scope="col" class="text-center tab_v" width="2%" rowspan="2">Itém</th>
                                            <th scope="col" class="text-center tab_v" width="5%" rowspan="2">Fecha</th>
                                            <th scope="col" class="text-center tab_v" width="5%" rowspan="2">Tipo</th>
                                            <th scope="col" class="text-center tab_v" width="5%" rowspan="2">Documento</th>
                                            <th scope="col" class="text-center" width="5%">INGRESO</th>
                                            <th scope="col" class="text-center" width="5%">SALIDA</th>
                                            <th scope="col" class="text-center" width="5%">SALDO</th>
                                            <th scope="col" class="tab_v" width="10%" rowspan="2">Obs</th>
                                          </tr>
                                          <tr>
                                            <th scope="col" class="text-center" width="5%">ICant</th>
                                            <th scope="col" class="text-center" width="5%">ECant</th>
                                            <th scope="col" class="text-center" width="5%">Saldo</th>
                                          </tr>
                                        </thead>

                                        {{-- <tbody id="filas_contenedor"> --}}
                                          @foreach($articulos as $i => $kar)
                                          {{-- <tr class="reg_ejm"><td colspan="8">No hay datos</td></tr> --}}
                                          <tr>
                                            <td colspan="2" class="text-left">CÓDIGO: {{$kar['cod_artic']}}</td>
                                            <td colspan="6" class="text-left">DESCRIPCIÓN: {{$kar['desc']}}</td>
                                          </tr>

                                          @foreach($kar['data'] as $j => $k)
                                          <tr>
                                            <td>{{$k['id']}} Cod: {{$k['codigo']}}</td>
                                            <td>{{$k['fecha']}}</td>
                                            <td>{{$k['tipo_doc'] }}</td>
                                            <td class="text-center">{{$k['documento'] }}</td>
                                            <td class="text-right">{{$k['icant']}}</td>
                                            <td class="text-right">{{$k['ecant']}}</td>
                                            <td class="text-right">{{$k['scant']}}</td>
                                            <td class="text-left">{{$k['obs']}}</td>{{-- 8 col --}}
                                          </tr>
                                          @endforeach
                                          
                                        {{-- </tbody>
                                        <tfoot> --}}
                                          <tr id="td_totales">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right totales">Totales:</td>
                                            <td align="right"><strong id="tot1">{{$kar['tot_i']}}</strong></td>
                                            <td align="right"><strong id="tot1">{{$kar['tot_e']}}</strong></td>
                                          </tr>
                                        {{-- </tfoot> --}}
                                        @endforeach
                                      </table>


                                      
                                  </div>
                              </div>
  </div>

</body>
</html>