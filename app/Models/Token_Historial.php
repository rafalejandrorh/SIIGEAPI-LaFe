<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token_Historial extends Model
{
    use HasFactory;

    protected $table = 'trazas.token_historial';

    protected $fillable = [
        'id',
        'id_empresa', 
        'token', 
        'created_at', 
        'expired_at', 
        'last_used_at'
    ];

    public function Dependencias()
    {
        return $this->belongsto(Empresas::class, 'id_empresa');
    }
}
