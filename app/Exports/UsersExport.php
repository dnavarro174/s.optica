<?php

namespace App\Exports;

use App\User;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {	
    	//SELECT * FROM users u INNER JOIN almacen a ON a.cod_empresa=u.cod_empresa WHERE u.cod_empresa=1;

        return User::join('almacen','almacen.cod_empresa','=','users.cod_empresa')
        			->WHERE('users.cod_empresa',1)->get();
        //return User::all();
        //return DB::select("select a.cod_artic,a.nombre, sum(m.cant_mov) AS suma  FROM movimientos m INNER JOIN articulos a ON m.cod_artic=a.cod_artic WHERE m.tpo_doc=04 AND m.nro_linea<>0 GROUP BY a.cod_artic, a.nombre ORDER BY suma DESC LIMIT 5");
    }
}
