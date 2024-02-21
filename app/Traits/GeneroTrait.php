<?php

namespace App\Traits;

use App\Models\Genero;
use Illuminate\Support\Facades\Cache;

trait GeneroTrait
{
    public function getGeneroById($id)
    {
        if(Cache::has('genero-'.$id)) {
            return Cache::get('genero-'.$id);
        }else{
            $result = Genero::where('id', $id)->first();
            Cache::put('genero-'.$id, $result, 3600);
            return $result;
        }
    }

    public function pluckGenero()
    {
        if(Cache::has('pluckGenero')) {
            return Cache::get('pluckGenero');
        }else{
            $result = Genero::pluck('valor', 'id')->all();
            Cache::put('pluckGenero', $result, 3600);
            return $result;
        }
    }
}

?>