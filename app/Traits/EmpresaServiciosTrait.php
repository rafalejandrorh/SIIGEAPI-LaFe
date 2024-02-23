<?php

namespace App\Traits;

use App\Models\Servicios_Empresas;

trait EmpresaServiciosTrait
{
    public function getServiciosEmpresa($id = null)
    {
        // Pendiente por simplificar relaciones
        return Servicios_Empresas::join('nomenclador.servicios', 'servicios.id', '=', 'servicios_empresas.id_servicio')
        ->Where('estatus', true)
        ->Where('id_empresa', $id)
        ->get()
        ->toArray();
    }
}

?>