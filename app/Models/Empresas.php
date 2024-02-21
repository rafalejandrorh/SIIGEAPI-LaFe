<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'id',
        'nombre', 
        'departamento', 
        'seudonimo', 
        'telefono', 
        'correo', 
        'id_person'
    ];

    public function person()
    {
        return $this->belongsto(Person::class, 'id_person');
    }

    public function search($request, $paginate = 10)
    {
        if($request->tipo_busqueda == 'empresa'){
            $result = $this->Where('nombre', 'ilike', '%'.$request->buscador.'%')->paginate($paginate);

        }else if($request->tipo_busqueda == 'departamento'){
            $result = $this->Where('departamento', 'ilike', '%'.$request->buscador.'%')->paginate($paginate);

        }else{
            $result = $this->paginate($paginate);
        }
        return $result;
    }
}
