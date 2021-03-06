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
      .table td,.table th{font-size: 11px;color:#222;}
      h2.h6{color: black !important;}
      .table .thead-dark th{background: #999;border-color: #999}
      .foot{position: absolute;bottom: 0;background: yellow;display: block;left: 0;font-size: 10px;}
    </style>
  </head>
  <body class="bg-blanco">

    <div class="">{{-- container --}}
      <div id="details" class="col-ms-12 col-xs-12">
        {{-- <div class="container">
          <div class="navbar-brand-wrapper d-flex align-items-top ">
            <a class="navbar-brand brand-logo" href="{{ route('home')}}">
            <img width="125" height="25" src="{{URL::route('home')}}/images/emsag.svg" class="img-fluid" alt="logo emsag">
            </a>
          </div>
        </div> --}}


        <div class="card bg-blanco">
          <div class="card-body">

            <h2 class="h6 text-center mb-4 text-dark">ORDEN DE SALIDA Nº {{ $salidas->nro_doc }} - {{ $salidas->nro_ref }}</h2>

            <table class="table">
                @if($salidas->proyecto->tipocomp=="0")
                <tr>
                  <th><strong>Responsable:</strong> {{ $salidas->responsable }}</th>
                  <td></td>
                  <td colspan="2"><strong>Fecha:</strong> {!! \Carbon\Carbon::parse($salidas->fecha)->format('d-m-Y') !!}</td>
                </tr>
                <tr>
                  <td><strong>Proyecto:</strong> {{ $salidas->proyecto->nom_proy }}</td>
                  <td></td>
                  <td colspan="2"><strong>Almacén:</strong> {{ session('almacen')['nombre']}}</td>
                  <td></td>
                </tr>
                @else
                <tr>
                  <th><strong>{{ $salidas->responsable }}</strong></th>
                  <td></td>
                  <td colspan="2"><strong>Fecha:</strong> {!! \Carbon\Carbon::parse($salidas->fecha)->format('d-m-Y') !!}</td>
                </tr>
                <tr>
                  <td><strong>Cliente:</strong> {{ $salidas->proyecto->nom_proy }}</td>
                  <td></td>
                  <td colspan="2"><strong>Almacén:</strong> {{ session('almacen')['nombre']}}</td>
                  <td></td>
                </tr>
                @endif
                <tr>
                  <td><strong>Dirección:</strong> {{ $salidas->proyecto->direccion }}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
            </table>
            <div class="row mt-4">
              <table class="table ">
                <thead class="thead-dark">
                  <tr>
                    <th style="width: 5%;" scope="col">#</th>
                    <th style="width: 10%;" scope="col">Código</th>
                    <th style="width: 50%;" scope="col">Producto</th>
                    <th style="width: 10%;" scope="col">U.M.</th>
                    <th style="width: 15%;" scope="col">Cant</th>
                    {{-- <th class="page-break" style="width: 50%;" scope="col">Descripción</th> --}}
                  </tr>
                </thead>
                <tbody>
                  <?php $sum = 0; 
                  $tot_pag = 11;
                  $y = 0;
                  ?>
                  @foreach($detalles as $y => $detalle)
                  <tr>
                    <th scope="row">{{ $y+1 }}</th>
                    <th scope="row" class="text-center">{{ $detalle->articulo->cod_artic }}</th>
                    {{-- <th scope="row">{{ $detalle->articulo->cod_sunat }}</th> --}}
                    <td class="page-break">{{ $detalle->articulo->nombre }} @if($detalle->articulo->marca != "")/ Marca: {{$detalle->articulo->marca}}@endif</td>
                    <td class="page-break">{{ $detalle->cod_umedida }}</td>
                    <td class="page-break text-right">{{ $detalle->cant_mov }}</td>
                    <?php $sum += $detalle->cant_mov;
                    ?>
                  </tr>
                  @endforeach

                  <tr>
                    {{-- <td ></td> --}}
                    <th colspan="4" class="text-right">TOTAL:</th>
                    <th class="text-right">{{number_format($sum,2)}}</th>
                  </tr>

                  <?php
                  $restante = $tot_pag - ($y+1);
                  for ($i=0; $i <= $restante; $i++) { ?>
                  <?php
                    if($i == $restante){ ?>
                    <tr>
                      <td colspan="5" class="page-break border-0">
                        <table class="table">
                          <tr>
                            <td style="width: 30%;" class="page-break border-0"></td>
                            <td class="page-break text-center border-0">
                              ------------------------<br>SOLICITANTE
                            </td>
                            <td style="width: 5%;" class="page-break border-0"></td>
                            <td class="page-break text-center border-0">
                              ------------------------<br>DESPACHO
                            </td>
                            <td style="width: 30%;" class="page-break border-0 text-center"></td>
                          </tr>
                        </table>
                      </td>
                    </tr>

                  <?php
                    }else{ ?>
                    <tr>
                      <td colspan="5" class="page-break border-0">
                    </tr>
                  <?php

                    }
                  }
                  ?>
                  
                </tbody>
                {{-- <tfoot> --}}
                
              {{-- </tfoot> --}}
              </table>
            </div>

          </div>
        </div>


        <footer class="row foot">
          <div class="container-fluid clearfix">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2019 EMSAG. </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Desarrollado por <a href="http://www.jjdsystem.com" target="_blank">JJDSystem</a> </span>
            <!-- <p>Dany Navarro Manta </p>-->
          </div>
        </footer>

        
      </div>
      
    </div>

      
      <!-- <script src="{{ asset('js/dashboard.js?v=2')}}"></script> -->
  </body>
</html>