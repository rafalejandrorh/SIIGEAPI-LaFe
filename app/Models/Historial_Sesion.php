<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_Sesion extends Model
{
    use HasFactory;

    protected $table = 'public.historial_sesion';

    protected $fillable = ['logout', 'tipo_logout', 'id_user', 'id', 'MAC'];

    public function user()
    {
        return $this->belongsto(User::class, 'id_user');
    }

    public function filtro($request)
    {
        $queryBuilder = $this->query();
        if($request->fecha_inicio != null && $request->fecha_fin == null)
        {
            Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
            return $this->orderBy('login', 'DESC')->paginate(10);
        }
        if($request->fecha_inicio != null && $request->fecha_fin != null)    
        {
            $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
            $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
            $queryBuilder->WhereBetween('login', [$inicio, $fin]);
        }
        if($request->id_usuario != null)
        {
            $queryBuilder->Where('id_user', $request->id_usuario);
        }
        return $queryBuilder->orderBy('login', 'desc')->paginate(10);
    }

    public function search($request)
    {
        if(isset($request->buscador) && is_numeric($request->buscador))
        {
            if($request->tipo_busqueda == 'cedula'){
                $result = $this->join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.cedula', '=', $request->buscador)->orderBy('login', 'DESC')->paginate(10);
                
            }else if($request->tipo_busqueda == 'credencial'){
                $result = $this->join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('login', 'DESC')->paginate(10);
            }else{
                Alert()->warning('Búsqueda no permitida');
                $result = $this->orderBy('login', 'DESC')->paginate(10);
            }
        }else if(isset($request->buscador) && is_string($request->buscador)){

            if($request->tipo_busqueda == 'jerarquia'){
                $result = $this->join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
                ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')->orderBy('login', 'DESC')->paginate(10);

            }else if($request->tipo_busqueda == 'usuario'){
                $result = $this->join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('login', 'DESC')->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $result = $this->join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')->orderBy('login', 'DESC')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $result = $this->join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')->orderBy('login', 'DESC')->paginate(10);
            
            }else if($request->tipo_busqueda == 'jerarquia'){
                $roles = $this->join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funs.id_jerarquia')
                ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                ->orderBy('historial_sesion.created_at', 'DESC')->paginate(10);

            }else{
                Alert()->warning('Búsqueda no permitida');
                $result = $this->orderBy('login', 'DESC')->paginate(10);
            }
        }else{
            $result = $this->orderBy('login', 'DESC')->paginate(10);
        }
        return $result;
    }
}
