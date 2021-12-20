<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;

class FacturaController extends Controller
{
    public function impresion(Request $request)
    {
        //$idventa = $request->input('idventa');
        $tipoImpresion = $request->input('tipoImpresion');

        /*$empresa = Empresa::query()->with(['ubigeoDepartamento', 'ubigeoProvincia', 'ubigeoDistrito'])->first();
        $venta = Venta::query()->with(['usuario', 'cliente', 'tipoMoneda', 'tipoComprobante'])->where('idventa', $idventa)->first();
        $detalleVenta = DetalleVenta::query()->with(['producto'])->where('idventa', $idventa)->get();*/

        if ($tipoImpresion == 'a4') {
           // $pdf = SnappyPdf::loadView('panel.reportes.comprobantes.a4', compact('empresa', 'venta', 'detalleVenta'));
            $pdf = SnappyPdf::loadView('factura.comprobantes.a4');
        } else {
            /*$pdf = SnappyPdf::loadView('panel.reportes.comprobantes.ticket', compact('empresa', 'venta', 'detalleVenta'))
                ->setOption('margin-bottom', '0')
                ->setOption('margin-left', '0')
                ->setOption('margin-right', '0')
                ->setOption('margin-top', '0')
                ->setOption('page-height', '320')
                ->setOption('page-width', '70');*/
                $pdf = SnappyPdf::loadView('factura.comprobantes.ticket')
                ->setOption('margin-bottom', '0')
                ->setOption('margin-left', '0')
                ->setOption('margin-right', '0')
                ->setOption('margin-top', '0')
                ->setOption('page-height', '320')
                ->setOption('page-width', '70');
        }

        return $pdf->inline();
    }
}
