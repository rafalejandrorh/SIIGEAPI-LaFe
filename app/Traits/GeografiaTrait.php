<?php

namespace App\Traits;

use App\Models\Geografia;
use Illuminate\Support\Facades\Cache;

trait GeografiaTrait
{
    public function pluckPais()
    {
        if(Cache::has('pluckPais')) {
            return Cache::get('pluckPais');
        }else{
            $result = Geografia::orderBy('valor', 'asc')->IdPadre(106)->pluck('valor', 'id')->all();
            Cache::put('pluckPais', $result, 3600);
            return $result;
        }
    }

    public function pluckEstado()
    {
        if(Cache::has('pluckEstado')) {
            return Cache::get('pluckEstado');
        }else{
            $result = Geografia::orderBy('valor', 'asc')->IdPadre(107)->pluck('valor', 'id')->all();
            Cache::put('pluckEstado', $result, 3600);
            return $result;
        }
    }

    public function pluckMunicipio()
    {
        if(Cache::has('pluckMunicipio')) {
            return Cache::get('pluckMunicipio');
        }else{
            $result = Geografia::orderBy('valor', 'asc')->IdPadre(108)->pluck('valor', 'id')->all();
            Cache::put('pluckMunicipio', $result, 3600);
            return $result;
        }
    }

    public function getEstadoById($id = 0)
    {
        if(Cache::has('geografiaEstado-'.$id)) {
            return Cache::get('geografiaEstado-'.$id);
        }else{
            $result = Geografia::Where('id', $id)->first();
            Cache::put('geografiaEstado-'.$id, $result, 3600);
            return $result;
        }
    }
}

?>