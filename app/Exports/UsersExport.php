<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
        ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
        ->join('nomenclador.estatus_funcionario', 'estatus_funcionario.id', '=', 'funcionarios.id_estatus')
        ->join('nomenclador.organismos_seguridad', 'organismos_seguridad.id', '=', 'funcionarios.id_organismo')
        ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
        ->select(
            'users.users', 
            'users.status',
            'persons.cedula', 
            'persons.primer_nombre', 
            'persons.segundo_nombre', 
            'persons.primer_apellido',
            'persons.segundo_apellido',
            'funcionarios.credencial',
            'funcionarios.telefono',
            'jerarquia.valor as jerarquia',
            'estatus_funcionario.valor as estatus_laboral',
            'organismos_seguridad.nombre as organismo',
            'users.last_login'
        )->get();

        $i = count($users) - 1;
        while($i >= 0)
        {
            $e = $i + 1;
            $users[$e] = $users[$i];
            $i--;
        }

        $users[0] = [
            'Usuario', 
            'Estatus de Usuario', 
            'Cédula', 
            'Primer Nombre', 
            'Segundo Nombre',
            'Primer Apellido',
            'Segundo Apellido',
            'Credencial',
            'Teléfono',
            'Jerarquía',
            'Estatus Laboral',
            'Organismo de Seguridad',
            'Último Inicio de Sesión'
        ];

        $i = 1;
        while($i < count($users))
        {
            $users[$i]['status'] ? $users[$i]['status'] = 'Activo' : $users[$i]['status'] = 'Inactivo';
            $i++;
        }
        
        return $users;
    }
}
