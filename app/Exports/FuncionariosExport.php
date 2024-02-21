<?php

namespace App\Exports;

use App\Models\Funcionario;
use App\Models\Resenna;
use Maatwebsite\Excel\Concerns\FromCollection;

class FuncionariosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $funcionarios = Funcionario::join('persons', 'persons.id', '=', 'funcionarios.id_person')
        ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
        ->join('nomenclador.estatus_funcionario', 'estatus_funcionario.id', '=', 'funcionarios.id_estatus')
        ->join('nomenclador.organismos_seguridad', 'organismos_seguridad.id', '=', 'funcionarios.id_organismo')
        ->select(
            'funcionarios.id',
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
        )->get();

        $i = 0;
        while($i < count($funcionarios))
        {
            $resennas = Resenna::join('funcionarios AS funcionarioResenna', 'funcionarioResenna.id', '=', 'resenna_detenido.id_funcionario_resenna')
            ->Where('funcionarioResenna.id', $funcionarios[$i]['id'])
            ->count();
            $aprehensiones = Resenna::join('funcionarios AS funcionarioAprehensor', 'funcionarioAprehensor.id', '=', 'resenna_detenido.id_funcionario_aprehensor')
            ->Where('funcionarioAprehensor.id', $funcionarios[$i]['id'])
            ->count();

            $funcionarios[$i]['resennas'] =  $resennas != null ? $resennas : 0;
            $funcionarios[$i]['aprehensiones'] = $aprehensiones != null ? $aprehensiones : 0;

            unset($funcionarios[$i]['id']);

            $i++;
        }

        $i = count($funcionarios) - 1;
        while($i >= 0)
        {
            $e = $i + 1;
            $funcionarios[$e] = $funcionarios[$i];
            $i--;
        }

        $funcionarios[0] = [
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
            'Reseñas Realizadas',
            'Aprehensiones Realizadas'
        ];
        
        return $funcionarios;
    }
}
