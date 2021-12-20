<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BOLETA DE VENTA ELECTRONICA B001 - 1</title>
</head>
<style>
    body{
        font-family: Roboto, 'Segoe UI', Tahoma, sans-serif
    }
    .logo-imagen{
        width: 130px;
        height: 100px;
        float: left;
    }
    .parrafo-no-margen{
        margin-top: 0!important;
        margin-bottom: 0!important;
    }

    .div-derecha-top{
        margin-top: -110px;
        float: right
    }

    .parrafo-documento{
        border-top: 1px solid;
        border-bottom: 1px solid;
        font-size: 18px;
    }
    .div-fecha{
        border: 1px solid;
        border-radius: 10px;
    }

    .div-proveedor{
        float: left;
        margin-top: 40px;
    }

    .div-tabla{
        width: 100%;
        margin-top: 30px;
        display: inline-block;
    }

    .productos-tabla{
        width: 100%!important;
        border-collapse: collapse;
        border-spacing: 0;

    }


    .productos-tabla thead tr th{
        font-size: 13px;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
    }

    .productos-tabla tbody tr td{
        font-size: 14px;
    }

    .productos-tabla tbody tr:not(:last-child) {
        border-bottom: 1px solid black;
    }


    .div-monto-texto{
        border: 1px solid black;
        padding: 3px;
        border-radius: 10px;
        width: 60%;
        margin-top: 30px;
        float: left;
    }


    .div-totales{
        border-top: 1px solid black;
        margin-top: 0;
        text-align: right;
    }



</style>
<body>
<div style="text-align: center">
    {{--<img style="width: 160px;height: 120px;" src="{{asset('storage/imagenes/empresa/'.$empresa->logo)}}">--}}
    <p class="parrafo-no-margen" style="font-weight: bold">FARMA SALUD Y VIDA S.A.C</p>
    <p class="parrafo-no-margen">RUC: 20606533943</p>
    <p class="parrafo-no-margen">Dirección:  Lima, Calle los sauces 235</p>
    <p class="parrafo-no-margen">Teléfono: 925 316 984</p>
    <p class="parrafo-no-margen">Email: farma@gmail.com</p>
</div>

<div >
    <p style="text-align: center" class="parrafo-documento">BOLETA DE VENTA ELECTRÓNICA <br> B001 - 36</p>
</div>


<div>
    <p class="parrafo-no-margen">Fecha Emisión: 31/08/2021</p>
    <p class="parrafo-no-margen">Hora Emisión: 16:06</p>
    <p class="parrafo-no-margen">Cliente: JUAN PEREZ</p>
    <p class="parrafo-no-margen">DNI: 12345678</p>
    <p class="parrafo-no-margen">Domicilio: AV 123455</p>
</div>


<div class="div-tabla">
    <table align="center" class="productos-tabla">
        <thead>
        <tr>
            <th>CANT.</th>
            <th>U.M</th>
            <th>DESCRIPCIÓN</th>
            <th>P.UNIT</th>
            <th>TOTAL</th>
        </tr>
        </thead>
        <tbody>
       {{--  @foreach ($detalleVenta as $det)
            <tr>
                <td align="center">{{$det->cantidad}}</td>
                <td align="center">{{$det->unidadMedida->nombre}}</td>
                <td width="30%">{{$det->txt_nombre_producto}}</td>
                <td align="center">{{$det->precio_unitario}}</td>
                <td align="center">{{$det->importe_total}}</td>
            </tr>
        @endforeach --}}
        <tr>
                <td align="center">1</td>
                <td align="center">UND.</td>
                <td width="30%">Jarabe mucovit 100 ml</td>
                <td align="center">123</td>
                <td align="center">123</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="div-totales">
    <p style="font-weight: bold" class="parrafo-no-margen">OP. GRAVADAS: <span >S/ 100</span></p>
    <p style="font-weight: bold" class="parrafo-no-margen">IGV(18%): <span >S/ 100</span></p>
    <p style="font-weight: bold" class="parrafo-no-margen">OP.EXONERADAS: <span >S/ 100</span></p>
    <p style="font-weight: bold" class="parrafo-no-margen">OP.INAFECTAS: <span >S/ 100</span></p>
    <p style="font-weight: bold" class="parrafo-no-margen">TOTAL: <span >S/ 100</span></p>
</div>

<div>
    @php
      $formato = new NumberFormatter("es", NumberFormatter::SPELLOUT);

        $arrCantidad = explode(".", '1500.20');
        $entero = $arrCantidad[0];
        $decimal = $arrCantidad[1];

        $texto = mb_strtoupper($formato->format($entero)) . ' CON ' . $decimal . '/100 soles';
    @endphp
    <p>SON: <b>{{$texto}}</b></p>
</div>

<div style="display: block;margin-left: 10%">
    <p class="parrafo-no-margen">
        {!! DNS2D::getBarcodeHTML('$empresa->ruc'.' | '.'$venta->tipoComprobante->idtipo_comprobante' .' | '.'$venta->serie_comprobante'.' | '.'$venta->num_comprobante'.' | '.'$venta->igv'.' | '.'$venta->total'.' | '.'now()->parse($venta->creado)->format("Y-m-d")'.' | '.'$venta->cliente->tipoDocumento->idtipo_documento'.' | '.'$venta->cliente->num_documento', 'QRCODE',4,4) !!}
    </p>
</div>

<div style="display: block;text-align: center">
    <p class="parrafo-no-margen">
        hash: 123fswefghb===xvreee
    </p>
</div>

<div style="display: block;margin-top: 40px">
    <p style="margin: 0"><b>CONDICIÓN DE PAGO: </b>CONTADO</p>
    <p style="margin: 0"><b>VENDEDOR:</b> Vendedor 1</p>
</div>




</body>
</html>
