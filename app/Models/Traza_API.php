<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traza_API extends Model
{
    use HasFactory;

    protected $table = 'trazas.api';
    protected $fillable = [
        'id',
        'ip',
        'mac',
        'fecha_request',
        'action',
        'response',
        'request',
        'token',
        'code',
        'description',
        'time_execution',
        'id_empresa',
        'id_user'
    ];

    public function user()
    {
        return $this->belongsto(User::class, 'id_user');
    }

    public function empresa()
    {
        return $this->belongsto(Empresas::class, 'id_empresa');
    }
}
