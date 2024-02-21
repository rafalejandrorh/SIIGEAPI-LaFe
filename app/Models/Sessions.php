<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    protected $fillable = ['id','user_id', 'ip_address', 'user_agent', 'last_activity'];

    public function user()
    {
        return $this->belongsto(User::class, 'user_id');
    }

    public function search($request)
    {
        if(isset($request->buscador) && is_numeric($request->buscador))
        {
            if($request->tipo_busqueda == 'cedula'){
                $result = $this->join('users', 'users.id', '=', 'sessions.user_id')
                ->join('persons', 'persons.id', '=', 'users.id_person')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id', 'users.coordinates', 'users.users')
                ->orderBy('last_activity', 'DESC')
                ->Where('persons.cedula', '=', $request->buscador)->paginate(10);

            }else{
                Alert()->warning('Búsqueda no permitida');
                $result = $this->join('users', 'users.id', '=', 'sessions.user_id')
                ->orderBy('last_activity', 'DESC')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id', 'users.coordinates', 'users.users')
                ->paginate(10);
            }
        }else if(isset($request->buscador) && is_string($request->buscador)){
            
            if($request->tipo_busqueda == 'usuario'){
                $result = $this->join('users', 'users.id', '=', 'sessions.user_id')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id', 'users.coordinates', 'users.users')
                ->orderBy('last_activity', 'DESC')
                ->Where('users.users', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $result = $this->join('users', 'users.id', '=', 'sessions.user_id')
                ->join('persons', 'persons.id', '=', 'users.id_person')
                ->orderBy('last_activity', 'DESC')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id', 'users.coordinates', 'users.users')
                ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                ->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $result = $this->join('users', 'users.id', '=', 'sessions.user_id')
                ->join('persons', 'persons.id', '=', 'users.id_person')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id', 'users.coordinates', 'users.users')
                ->orderBy('last_activity', 'DESC')
                ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                ->paginate(10);
            }else{
                Alert()->warning('Búsqueda no permitida');
                $result = $this->join('users', 'users.id', '=', 'sessions.user_id')
                ->orderBy('last_activity', 'DESC')
                ->orderBy('last_activity', 'DESC')
                ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id', 'users.coordinates', 'users.users')
                ->paginate(10);
            }
        }else{
            $result = $this->join('users', 'users.id', '=', 'sessions.user_id')
            ->orderBy('last_activity', 'DESC')
            ->select('sessions.id AS session_id', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_id', 'users.coordinates', 'users.users')
            ->paginate(10);
        }
        return $result;
    }
}
