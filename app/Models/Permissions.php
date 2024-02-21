<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;

    protected $table = 'permissions';
    protected $fillable = ['name', 'description', 'guard_name'];

    public function search($request)
    {
        if(isset($request->buscador) && is_string($request->buscador)){
            if($request->tipo_busqueda == 'nomenclatura'){
                $result = $this->Where('name', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            }else if($request->tipo_busqueda == 'descripcion'){
                $result = $this->Where('description', 'ilike', '%'.$request->buscador.'%')
                ->paginate(10);

            }else if($request->tipo_busqueda == 'permiso'){
                $result = $this->Where('guard_name', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            }else{
                Alert()->warning('Búsqueda no permitida');
                $result = $this->paginate(10);
            }
        }else{
            $result = $this->orderBy('created_at', 'desc')->paginate(10);
        }
        return $result;
    }
}
