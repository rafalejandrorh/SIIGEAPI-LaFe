<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;

    protected $table = 'nomenclador.servicios';

    protected $fillable = ['id', 'nombre', 'metodo', 'estatus'];

    public function scopeEstatus($query, $status)
    {  
        return $query->Where('estatus', $status);
    }

    public function search($request, $paginate = 10)
    {
        if($request->tipo_busqueda == 'nombre'){
            $result = $this->Where('nombre', 'ilike', '%'.$request->buscador.'%')->paginate($paginate);

        }else if($request->tipo_busqueda == 'metodo'){
            $result = $this->Where('metodo', 'ilike', '%'.$request->buscador.'%')->paginate($paginate);
        }else{
            $result = $this->paginate($paginate);
        }
        return $result;
    }
}
