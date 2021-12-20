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
        border: 1px solid;
        border-radius: 10px;
        padding: 20px;
        font-weight: bold;
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
        border: 1px solid black;
        padding: 10px;
        border-radius: 10px;
        margin-top: 30px;
        width: 30%;
        float: right
    }



</style>
<body>
<div>
    <img class="logo-imagen" src="{{asset('images/logo_ticketing_2.png')}}">
    <p class="parrafo-no-margen" style="font-weight: bold">FARMA SALUD Y VIDA S.A.C</p>
    <p class="parrafo-no-margen">RUC: 20606533943</p>
    <p class="parrafo-no-margen">Dirección:  Lima, Calle los sauces 235</p>
    <p class="parrafo-no-margen">Teléfono: 925 316 984</p>
    <p class="parrafo-no-margen">Email: farma@gmail.com</p>
</div>

<div class="div-derecha-top">
    <p style="text-align: center" class="parrafo-documento">BOLETA DE VENTA ELECTRÓNICA <br> B001 - 36</p>
    <div class="div-fecha">
        <p style="text-align: center;margin-bottom: 0">FECHA</p>
        <hr>
        <p style="text-align: center;margin-top: 0">31/08/2021</p>
    </div>
</div>


    <div class="div-proveedor">
        <p class="parrafo-no-margen" style="font-weight: bold">CLIENTE</p>
        <p class="parrafo-no-margen">JUAN PEREZ</p>
        <p class="parrafo-no-margen">Domicilio: AV 123455</p>
        <p class="parrafo-no-margen">DNI: 12345678</p>
        <p class="parrafo-no-margen">Email: jonny47169@gmail.com</p>
        <p class="parrafo-no-margen">Telefono: 910252611</p>
    </div>


<div class="div-tabla">
    <table align="center" class="productos-tabla">
        <thead>
        <tr>
            <th>CANT.</th>
            <th>U.M</th>
            <th>DESCRIPCIÓN</th>
            <th>LOTE</th>
            <th>SERIE</th>
            <th>P.UNIT</th>
            <th>TOTAL</th>
        </tr>
        </thead>
        <tbody>
            {{-- @foreach ($detalleVenta as $det)
            <tr>
                <td align="center">{{$det->cantidad}}</td>
                <td align="center">{{$det->unidadMedida->nombre}}</td>
                <td width="30%">{{$det->txt_nombre_producto}}</td>
                <td align="center">{{$det->num_lote}}</td>
                <td align="center">{{$det->serie}}</td>
                <td align="center">{{$det->precio_unitario}}</td>
                <td align="center">{{$det->importe_total}}</td>
            </tr>
        @endforeach  --}}
        @foreach(range(0, 10) as $key)
        <tr>
                <td align="center">1</td>
                <td align="center">UND.</td>
                <td width="30%">Jarabe mucovit 100 ml</td>
                <td align="center">666</td>
                <td align="center">12</td>
                <td align="center">123</td>
                <td align="center">123</td>
            </tr>

        @endforeach
        
        </tbody>
    </table>
</div>

<div class="div-monto-texto">
    <p style="margin: 0"><b>VENDEDOR:</b> Vendedor1</p>
    <hr style="border: 1px solid black;border-top: 0">
    <p style="margin: 0"><b>CONDICIÓN DE PAGO:</b> CONTADO</p>
    <hr style="border: 1px solid black;border-top: 0">
    @php
      $formato = new NumberFormatter("es", NumberFormatter::SPELLOUT);

        $arrCantidad = explode(".", '1500.20');
        $entero = $arrCantidad[0];
        $decimal = $arrCantidad[1];

        $texto = mb_strtoupper($formato->format($entero)) . ' CON ' . $decimal . '/100 soles';
    @endphp
    <p style="margin: 0"><b>SON:</b> {{$texto}}</p>
    <hr style="border: 1px solid black;border-top: 0">
    <p style="margin: 0">
        <b>REPRESENTACIÓN IMPRESA DE LA:</b> BOLETA DE VENTA ELECTRONICA
    </p>
    <hr style="border: 1px solid black;border-top: 0">
    <p style="margin: 0">
        <b>HASH:</b> 123fswefghb===xvreee
    </p>
    <hr style="border: 1px solid black;border-top: 0">
    <p style="margin: 0">
    	
        {!! DNS2D::getBarcodeHTML('$empresa->ruc'.' | '.'$venta->tipoComprobante->idtipo_comprobante' .' | '.'$venta->serie_comprobante'.' | '.'$venta->num_comprobante'.' | '.'$venta->igv'.' | '.'$venta->total'.' | '.'now()->parse($venta->creado)->format("Y-m-d")'.' | '.'$venta->cliente->tipoDocumento->idtipo_documento'.' | '.'$venta->cliente->num_documento', 'QRCODE',4,4) !!}
    </p>
</div>

<div class="div-totales">
    <p style="font-weight: bold;margin: 0;font-size: 14px;text-align: center">TOTALES</p>
    <p>OP. GRAVADAS: <span style="float: right">S/. 100.00</span></p>
    <p>IGV(18%): <span style="float: right">S/. 100.00</span></p>
    <p>OP.EXONERADAS: <span style="float: right">S/. 100.00</span></p>
    <p>OP.INAFECTAS: <span style="float: right">S/. 100.00</span></p>
    <p>TOTAL: <span style="float: right">S/. 100.00</span></p>

</div>

</body>
</html>
