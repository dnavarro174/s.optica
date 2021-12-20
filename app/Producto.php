<?php

namespace App;
use App\UMedida;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'articulos';
    /*protected $fillable = ['codigo','nombre', 'descripcion','stock','unidad_med','marca','modelo','tipo'];*/

    public function medida(){
    	return $this->belongsTo(UMedida::class,'cod_umedida','id');
  	}

  	public function Cat(){
    	return $this->belongsTo(Categoria::class,'cod_categoria','id');
  	}
}
