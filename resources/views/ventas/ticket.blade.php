<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{$titulo}}</title>
</head>
<style>
    @page { size: 7.8cm 26.2cm; margin:0 1px; padding:0;}
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
    <p class="parrafo-no-margen" style="font-weight: bold">{{$empresa->nombre}}</p>
    <p class="parrafo-no-margen">RUC: {{$empresa->ruc}}</p>
    <p class="parrafo-no-margen">Dirección:  {{$empresa->direccion}}</p>
    <p class="parrafo-no-margen">Teléfono: {{$empresa->telefono}}</p>
    <p class="parrafo-no-margen">Email: {{$empresa->email}}</p>
</div>

<div >
    <p style="text-align: center" class="parrafo-documento">{{$tipo}} <br> {{$serie}} - {{$nro}}</p>
</div>


<div>
    <p class="parrafo-no-margen">Fecha Emisión: {{$fecha}}</p>
    <p class="parrafo-no-margen">Hora Emisión: {{$hora}}</p>
    <p class="parrafo-no-margen">Cliente: {{$cliente->nombre}}</p>
    <p class="parrafo-no-margen">DNI: {{$cliente->nro}}</p>
    <p class="parrafo-no-margen">Domicilio: {{$cliente->direccion}}</p>
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
        @foreach ($detalle as $det)
        <tr>
            <td align="center">{{$det->cantidad}}</td>
            <td align="center">{{$det->cod_umedida}}</td>
            <td width="30%">{{$det->nombre}}</td>
            <td align="center">{{$det->precio}}</td>
            <td align="center">{{number_format($det->cantidad*$det->precio,2)}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="div-totales">
    <p style="font-weight: bold" class="parrafo-no-margen">OP. GRAVADAS: <span >S/ {{$monto}}</span></p>
    <p style="font-weight: bold" class="parrafo-no-margen">IGV(18%): <span >S/ {{$igv}}</span></p>
    <p style="font-weight: bold" class="parrafo-no-margen">OP.EXONERADAS: <span >S/ {{$descuento}}</span></p>
    <p style="font-weight: bold" class="parrafo-no-margen">OP.INAFECTAS: <span >S/ {{$subtotal}}</span></p>
    <p style="font-weight: bold" class="parrafo-no-margen">TOTAL: <span >S/ {{$total}}</span></p>
</div>

<div>
    @php
        $formato = new NumberFormatter("es", NumberFormatter::SPELLOUT);

          $arrCantidad = explode(".", $total0);
          $entero = $arrCantidad[0];
          $decimal = $arrCantidad[1];

          $texto = mb_strtoupper($formato->format($entero)) . ' CON ' . $decimal . '/100 soles';
    @endphp
    <p>SON: <b>{{$texto}}</b></p>
</div>

<div style="display: block;margin-left: 10%">
    <p class="parrafo-no-margen">
        {!! $codigo !!}
    </p>
</div>

<div style="display: block;text-align: center">
    <p class="parrafo-no-margen">
        {{$hash}}
    </p>
</div>

<div style="display: block;margin-top: 40px">
    <p style="margin: 0"><b>CONDICIÓN DE PAGO: </b>{{$condicion}}</p>
    <p style="margin: 0"><b>VENDEDOR:</b> Vendedor{{$vendedor}}</p>
</div>




</body>
</html>
