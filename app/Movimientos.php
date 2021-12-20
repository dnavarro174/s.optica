<?php

namespace App;
use App\Almacen;
use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    
    protected $table = 'movimientos';
    protected $primary_key 	= "id";
	//public 	$timestamps 	= false;	
    //protected $fillable = ['id','nom_modulo', 'alias'];

    public function Almacen(){
    	return $this->belongsTo(Almacen::class,'cod_almacen','id');
  	}
}
