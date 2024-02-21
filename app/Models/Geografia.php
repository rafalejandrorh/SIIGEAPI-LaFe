<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geografia extends Model
{
    use HasFactory;

    protected $table = 'nomenclador.geografia';

    protected $fillable = ['id', 'valor', 'id_padre', 'id_hijo'];

    public function nomencladores() {
        return $this->hasMany(Geografia::class, 'id_hijo', 'id');
    }

    public function nomenclador() {
        return $this->belongsTo(Geografia::class, 'id_hijo', 'id');
    }

    public function scopeIdPadre($query, $id)
    {
        return $query->where('id_padre', $id);
    }

    public function scopeIdHijo($query, $id)
    {
        return $query->where('id_hijo', $id);
    }
}
