<?php

namespace App\Repositories;
use App\Producto;

class ProductRepositorie{
	private $model;

	public function __construct(){
		$this->model = new Producto();
	}

	public function findByName($q){
		// busqueda tab: art y medida
		/*return $this->model->join('unidad_medida as u','articulos.cod_umedida','=','u.id')
					->select('articulos.nombre','articulos.cod_artic','u.cod_umedida','articulos.costo_mn','articulos.marca','articulos.precio_venta')
					->where('cod_almacen',session('cod_almacen'))
					->where('articulos.nombre', 'like', "%$q%")
					->orderBy('articulos.nombre', 'ASC')
					->get();*/

    	//return $this->model->where('nombre', 'like', "%$q%")->get();

		// busqueda tab: articulos, medida y stock_articulos_alm
		return $this->model->join('unidad_medida as u','articulos.cod_umedida','=','u.id')
					->join('stock_articulos_alm as s', 's.cod_artic','=','articulos.cod_artic')
					->select('articulos.nombre','articulos.cod_artic','u.cod_umedida','articulos.costo_mn','articulos.marca','articulos.precio_venta','s.stock_alm')
					->where('s.cod_almacen',session('cod_almacen'))
					->where('articulos.nombre', 'like', "%$q%")
					->orderBy('articulos.nombre', 'ASC')
					->get();

    }
}