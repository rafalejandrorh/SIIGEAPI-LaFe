<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traza_Historial_Sesion extends Model
{
    use HasFactory;

    protected $table = 'trazas.historial_sesion';

    protected $fillable = ['id_user','id_accion', 'valores_modificados'];

    public function acciones()
    {
        return $this->belongsto(Traza_Acciones::class, 'id_accion');
    }

    public function user()
    {
        return $this->belongsto(User::class, 'id_user');
    }
}
