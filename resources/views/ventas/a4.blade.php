<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{$titulo}}</title>
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
    <p class="parrafo-no-margen" style="font-weight: bold">{{$empresa->nombre}}</p>
    <p class="parrafo-no-margen">RUC: {{$empresa->ruc}}</p>
    <p class="parrafo-no-margen">Dirección:  {{$empresa->direccion}}</p>
    <p class="parrafo-no-margen">Teléfono: {{$empresa->telefono}}</p>
    <p class="parrafo-no-margen">Email: {{$empresa->email}}</p>
</div>

<div class="div-derecha-top">
    <p style="text-align: center" class="parrafo-documento">{{$tipo}} <br> {{$serie}} - {{$nro}}</p>
    <div class="div-fecha">
        <p style="text-align: center;margin-bottom: 0">FECHA</p>
        <hr>
        <p style="text-align: center;margin-top: 0">{{$fecha}}</p>
    </div>
</div>


<div class="div-proveedor">
    <p class="parrafo-no-margen" style="font-weight: bold">CLIENTE</p>
    <p class="parrafo-no-margen">{{$cliente->nombre}}</p>
    <p class="parrafo-no-margen">Domicilio: {{$cliente->direccion}}</p>
    <p class="parrafo-no-margen">DNI: {{$cliente->nro}}</p>
    <p class="parrafo-no-margen">Email: xxxjonny47169@gmail.com</p>
    <p class="parrafo-no-margen">Telefono: xxx910252611</p>
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
        @foreach ($detalle as $det)
            <tr>
                <td align="center">{{$det->cantidad}}</td>
                <td align="center">{{$det->cod_umedida}}</td>
                <td width="30%">J{{$det->nombre}}</td>
                <td align="center">ll</td>
                <td align="center">sse</td>
                <td align="center">{{$det->precio}}</td>
                <td align="center">{{number_format($det->cantidad*$det->precio,2)}}</td>
            </tr>

        @endforeach

        </tbody>
    </table>
</div>

<div class="div-monto-texto">
    <p style="margin: 0"><b>VENDEDOR:</b> Vendedor1{{$vendedor}}</p>
    <hr style="border: 1px solid black;border-top: 0">
    <p style="margin: 0"><b>CONDICIÓN DE PAGO:</b> {{$condicion}}</p>
    <hr style="border: 1px solid black;border-top: 0">
    @php
        $formato = new NumberFormatter("es", NumberFormatter::SPELLOUT);

          $arrCantidad = explode(".", $total0);
          $entero = $arrCantidad[0];
          $decimal = $arrCantidad[1];

          $texto = mb_strtoupper($formato->format($entero)) . ' CON ' . $decimal . '/100 soles';
    @endphp
    <p style="margin: 0"><b>SON:</b> {{$texto}}</p>
    <hr style="border: 1px solid black;border-top: 0">
    <p style="margin: 0">
        <b>REPRESENTACIÓN IMPRESA DE LA:</b> {{$tipo}}
    </p>
    <hr style="border: 1px solid black;border-top: 0">
    <p style="margin: 0">
        <b>HASH:</b> 123fswefghb===xvreee
    </p>
    <hr style="border: 1px solid black;border-top: 0">
    <p style="margin: 0">

        {!! $codigo !!}
    </p>
</div>

<div class="div-totales">
    <p style="font-weight: bold;margin: 0;font-size: 14px;text-align: center">TOTALES</p>
    <p>OP. GRAVADAS: <span style="float: right">S/. {{$monto}}</span></p>
    <p>IGV(18%): <span style="float: right">S/. {{$igv}}</span></p>
    <p>OP.EXONERADAS: <span style="float: right">S/. {{$descuento}}</span></p>
    <p>OP.INAFECTAS: <span style="float: right">S/. {{$subtotal}}</span></p>
    <p>TOTAL: <span style="float: right">S/. {{$total}}</span></p>

</div>

</body>
</html>
