<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token_Empresas extends Model
{
    use HasFactory;

    protected $table = 'token_empresas';

    protected $fillable = [
        'id_empresa', 
        'token', 
        'last_used_at', 
        'created_at', 
        'expired_at', 
        'duracion_token', 
        'estatus'
    ];

    public function Dependencias()
    {
        return $this->belongsto(Empresas::class, 'id_empresa');
    }
}
