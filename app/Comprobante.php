<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $table = 'comprobantes';
    protected $fillable = [
        "tpo_doc","cod_empresa","cod_almacen","serie_doc","nro_doc",
        "cod_cliente","igv_total","total","cod_usuario","descuento_valor","descuento_porc",
        "forma_pago","observacion","monto","razon_social","direccion","importe","tpo_com"
    ];

    public function cliente()
    {
        return $this->belongsTo(CtaCorrientes::class,'cod_cliente','cod_ruc');
    }
    public function scopeFecha($query, $text_fecha)
    {
        if($text_fecha !== "" && strlen($text_fecha) == 7){
            $text_partido = explode("-", $text_fecha);
            $m = str_replace(0, "", $text_partido[0]);
            $query->where(DB::raw("DATE_FORMAT(comprobantes.created_at, '%m-%Y')"), "=", $text_fecha );
        }
    }
}
