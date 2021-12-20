<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comprobante_Det extends Model
{
    protected $table = 'comprobantes_det';
    protected $fillable = [
        "cod_comprobante","cod_empresa","cod_usuario","cod_articulo","precio",
        "cantidad"
    ];
    public $timestamps = false;
    //
}
