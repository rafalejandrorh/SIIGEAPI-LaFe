<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios_Empresas extends Model
{
    use HasFactory;

    protected $table = 'servicios_empresas';

    protected $fillable = ['id_empresa', 'id_servicio'];

    public function empresas()
    {
        return $this->belongsto(Empresas::class, 'id_empresa');
    }

    public function servicios()
    {
        return $this->belongsto(Servicios::class, 'id_servicio');
    }
}
