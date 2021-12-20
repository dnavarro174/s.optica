<?php

namespace App\Repositories;
use App\CtaCorrientes;
use App\Proyecto;
use App\Laboratorio;

class ProveedorRepositorie{
	private $model;

	public function __construct(){
		$this->model = new CtaCorrientes();
	}

	public function findByName($q){
    	return $this->model->
    				orWhere('razon_social', 'like', "%$q%")
    				->orWhere('cod_ruc', 'like', "%$q%")
                    ->orderBy('razon_social', 'ASC')
    				->get();
    }

    public function findByNameClient($q){
    	return $this->model->
    				where('flag_tipo',1)
    				->where('razon_social', 'like', "%$q%")
                    ->orderBy('razon_social', 'ASC')
    				->get();
    }

    public function findByNameProy($q){
    	return Proyecto::where('flag_activo',1)
    				->where('nom_proy', 'like', "%$q%")
                    ->orderBy('nom_proy', 'ASC')
    				->get();
    }

    public function findByNameLaboratorio($q){
        return Laboratorio::where('laboratorio', 'like', "%$q%")
                    ->orderBy('laboratorio', 'ASC')
                    ->get();
    }

    
}