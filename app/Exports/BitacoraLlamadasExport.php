<?php

namespace App\Exports;

use App\Models\Bitacora_Llamadas;
use Maatwebsite\Excel\Concerns\FromCollection;

class BitacoraLlamadasExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $registros = Bitacora_Llamadas::leftjoin('persons', 'persons.id', '=', 'bitacora_llamadas.id_person')
        ->join('users', 'users.id', '=', 'bitacora_llamadas.id_user')
        ->join('funcionarios', 'funcionarios.id', '=', 'bitacora_llamadas.id_funcionario')
        ->join('persons AS funcionario', 'funcionario.id', '=', 'funcionarios.id_person')
        ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
        ->join('nomenclador.organismos_seguridad', 'organismos_seguridad.id', '=', 'funcionarios.id_organismo')
        ->join('nomenclador.llamada AS motivo', 'motivo.id', '=', 'bitacora_llamadas.id_motivo')
        ->join('nomenclador.llamada AS tipo', 'tipo.id', '=', 'bitacora_llamadas.id_tipo')
        ->join('nomenclador.llamada AS prioridad', 'prioridad.id', '=', 'bitacora_llamadas.id_prioridad')
        ->select(
            'bitacora_llamadas.fecha',
            'bitacora_llamadas.hora_inicio',
            'bitacora_llamadas.hora_fin',
            'bitacora_llamadas.telefono',
            'bitacora_llamadas.duracion',
            'bitacora_llamadas.descripcion',
            'motivo.valor as motivo',
            'tipo.valor as tipo',
            'prioridad.valor as prioridad',
            'persons.id as id_person',
            'persons.cedula', 
            'persons.primer_nombre', 
            'persons.segundo_nombre', 
            'persons.primer_apellido',
            'persons.segundo_apellido',
            'funcionarios.credencial',
            'funcionario.primer_nombre',
            'funcionario.primer_apellido',
            'jerarquia.valor as jerarquia',
            'organismos_seguridad.nombre as organismo',
            'users.users'
        )->get();

        $i = 0;
        while($i < count($registros))
        {
            $llamada[$i]['fecha'] = $registros[$i]['fecha'];
            $llamada[$i]['hora_inicio'] = $registros[$i]['hora_inicio'];
            $llamada[$i]['hora_fin'] = $registros[$i]['hora_fin'];
            $llamada[$i]['telefono'] = $registros[$i]['telefono'];
            $llamada[$i]['duracion'] = $registros[$i]['duracion'];
            $llamada[$i]['descripcion'] = $registros[$i]['descripcion'];
            $llamada[$i]['motivo'] = $registros[$i]['motivo'];
            $llamada[$i]['tipo'] = $registros[$i]['tipo'];
            $llamada[$i]['prioridad'] = $registros[$i]['prioridad'];

            $llamada[$i]['cedula'] = isset($registros[$i]['id_person']) ? $registros[$i]['cedula'] : 'Anonimo';
            $llamada[$i]['nombres_persona'] = isset($registros[$i]['id_person']) ? $registros[$i]['primer_nombre'].' '.$registros[$i]['segundo_nombre'].
            ', '.$registros[$i]['primer_apellido'].' '.$registros[$i]['segundo_apellido'] : 'Anonimo';

            $llamada[$i]['credencial'] = $registros[$i]['credencial'];
            $llamada[$i]['funcionario'] = $registros[$i]['jerarquia'].
            '. '.$registros[$i]['primer_nombre'].' '.$registros[$i]['primer_apellido'];
            $llamada[$i]['organismo'] = $registros[$i]['organismo'];
            $llamada[$i]['users'] = $registros[$i]['users'];

            $i++;
        }

        $i = count($llamada) - 1;
        while($i >= 0)
        {
            $e = $i + 1;
            $llamada[$e] = $llamada[$i];
            $i--;
        }

        $llamada[0] = [
            'Fecha',
            'Hora Inicio',
            'Hora Fin',
            'Telefono',
            'Duracion',
            'Descripcion',
            'Motivo',
            'Tipo', 
            'Prioridad', 
            'Cedula Persona que llama',
            'Nombres Persona que llama',
            'Credencial Funcionario que atiende llamada',
            'Funcionario que atiende',
            'Organismo de Seguridad que atiende llamada',
            'Usuario que registra llamada',
        ];
        
        return collect($llamada);
    }
}
