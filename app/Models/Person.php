<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MegaCreativo\API\CedulaVE;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $dates = ['fecha_nacimiento'];
    protected $fillable = [
        'letra_cedula', 
        'cedula', 
        'primer_nombre', 
        'segundo_nombre', 
        'primer_apellido', 
        'segundo_apellido', 
        'id_genero', 
        'fecha_nacimiento', 
        'id_estado_nacimiento', 
        'id_municipio_nacimiento', 
        'id_pais_nacimiento'
    ];

    public function user()
    {
        return $this->hasone(User::class);
    }

    public function genero()
    {
        return $this->belongsto(Genero::class,'id_genero');
    }

    public function pais_nacimiento()
    {
        return $this->belongsto(Geografia::class,'id_pais_nacimiento');
    }

    public function estado_nacimiento()
    {
        return $this->belongsto(Geografia::class,'id_estado_nacimiento');
    }

    public function municipio_nacimiento()
    {
        return $this->belongsto(Geografia::class,'id_municipio_nacimiento');
    }

}
