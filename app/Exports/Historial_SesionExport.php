<?php

namespace App\Exports;

use App\Models\Historial_Sesion;
use Maatwebsite\Excel\Concerns\FromCollection;

class Historial_SesionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $historial = Historial_Sesion::join('users', 'users.id', '=', 'historial_sesion.id_user')
        ->select('users.users', 'historial_sesion.login', 'historial_sesion.logout', 'historial_sesion.MAC', 'historial_sesion.tipo_logout')
        ->get();

        $i = count($historial) - 1;
        while($i >= 0)
        {
            $e = $i + 1;
            $historial[$e] = $historial[$i];
            $i--;
        }

        $historial[0] = [
            'Usuario', 
            'Inicio', 
            'Cierre', 
            'MAC', 
            'Tipo de Cierre'
        ];

        $i = 1;
        while($i < count($historial))
        {
            if($historial[$i]['tipo_logout'] == 1){
                $historial[$i]['tipo_logout'] = 'Finalizada por el Usuario';
            }else if($historial[$i]['tipo_logout'] == 2){
                $historial[$i]['tipo_logout'] = 'Finalizada por el Sistema';
            }else if($historial[$i]['tipo_logout'] == 3){
                $historial[$i]['tipo_logout'] = 'Finalizada por un Tercero';
            }else{
                $historial[$i]['tipo_logout'] = 'Sin Finalizar';
            }
            $i++;
        }

        return $historial;
    }
}
