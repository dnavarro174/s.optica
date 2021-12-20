<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    protected $table = 'movimientos';
    protected $dates = ['fecha_entrega',];

    public function scopeProveedor($query, $proveedor)
    {
        if(trim($proveedor) !== ""){
            $query->orWhere('cta_cte', 'like', "%$proveedor%")->where("nro_linea",0);
        }
    }

    public function scopePeriodo($query, $periodo)
    {
        if(trim($periodo) !== ""){
            $text = explode('/',$periodo);
            $query->orWhere('ano_doc', $text[0])->orWhere('mes_doc', $text[1]);
        }
    }

    public function scopeMotivo($query, $motivo)
    {
        //dd("Scope: $motivo");
        if(trim($motivo !== "")){
            $query->orWhere('nro_doc', 'like', "%$motivo%")->orWhere('nro_ref', 'like', "%$motivo%");
        }   
    }

    public function scopeFecha($query, $text_fecha)
    {
        if($text_fecha !== "" && strlen($text_fecha) == 7){
            $text_partido = explode("-", $text_fecha);
            $m = str_replace(0, "", $text_partido[0]);

            $query->where("mes_doc", "=", $m)
                  ->where('ano_doc', "=", $text_partido[1]);
        }
    }

    public function ruc(){
        return $this->belongsTo(CtaCorrientes::class,'cta_cte','cod_ruc');
    }

    public function almacen_o(){
        return $this->belongsTo(Almacen::class,'cod_almacen','id');
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class,'proyectos_id','id');
    }

    public function articulo(){
        return $this->belongsTo(Producto::class,'cod_artic','cod_artic');
    }
   
}
