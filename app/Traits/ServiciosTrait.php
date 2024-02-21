<?php

namespace App\Traits;

use App\Models\Servicios;
use Illuminate\Support\Facades\Cache;

trait ServiciosTrait
{
    public function pluckServicios()
    {
        if(Cache::has('pluckServicios')) {
            return Cache::get('pluckServicios');
        }else{
            $result = Servicios::pluck('nombre', 'nombre')->all();
            Cache::put('pluckServicios', $result, 3600);
            return $result;
        }
    }

    public function getServices($status = true)
    {
        return Servicios::Where('estatus', $status)->get()->toArray();
    }
}

?>