<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    public function cliente(){
    	return $this->belongsTo(CtaCorrientes::class,'cod_ruc','cod_ruc');
  	}
}
