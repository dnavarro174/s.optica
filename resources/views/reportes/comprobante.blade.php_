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

    <div class="">
      <div id="details" class="col-ms-12 col-xs-12">
        <div class="card bg-blanco">
          <div class="card-body">
            <h2 class="h4 text-center mb-4">ORDEN DE SALIDA Nº {{ $salidas[0]->id }}</h2>
            <!-- <h5 class="card-title">Card title</h5> -->

            <table class="table ">
                <tr>
                  <th><strong>Responsable:</strong> {{ $salidas[0]->responsable }}</th>
                  <td></td>
                  <td colspan="2"><strong>Fecha:</strong> {!! \Carbon\Carbon::parse($salidas[0]->fecha_entrega)->format('d-m-Y') !!}</td>
                </tr>
                <tr>
                  <th><strong>Motivo:</strong> {{ $salidas[0]->motivo }}</th>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
            </table>
            <div class="row mt-4">
              <table class="table ">
                <thead class="thead-dark">
                  <tr>
                    <th style="width: 2%;" scope="col">#</th>
                    <th style="width: 33%;" scope="col">Producto</th>
                    <th style="width: 15%;" scope="col">Cantidad</th>
                    <th class="page-break" style="width: 50%;" scope="col">Descripción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($detalles as $detalle)
                  <tr>
                    <th scope="row">{{ $detalle->idproducto }}</th>
                    <td class="page-break">{{ $detalle->nombre }}</td>
                    <td class="page-break">{{ $detalle->cantidad }}</td>
                    <td class="page-break" >{{ $detalle->descripcion }}</td>
                  </tr>
                  @endforeach
                  
                </tbody>
                <!-- <tfoot>
                <tr>
                  <td colspan="2"></td>
                  <td >TOTAL</td>
                  <td>$6,500.00</td>
                </tr>
              </tfoot> -->
              </table>
            </div>

          </div>
        </div>



        
      </div>
      
    </div>
      
      <!-- <script src="{{ asset('js/dashboard.js?v=2')}}"></script> -->
  </body>
</html>