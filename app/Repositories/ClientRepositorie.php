<?php

namespace App\Repositories;
use App\CtaCorrientes;

class ClientRepositorie{
	private $model;

	public function __construct(){
		$this->model = new Client();
	}

	public function findByName($q){
    	return $this->model->where('razon_social', 'like', "%emsag%")
    	->orderBy('razon_social', 'ASC')
    	->get();
    }
}