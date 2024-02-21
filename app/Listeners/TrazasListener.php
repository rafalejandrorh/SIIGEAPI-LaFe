<?php

namespace App\Listeners;

use App\Models\Traza_Apk_Movil;
use App\Models\Traza_Backup;
use App\Models\Traza_Bitacora_Llamadas;
use App\Models\Traza_Dependencias;
use App\Models\Traza_Funcionarios;
use App\Models\Traza_Historial_Sesion;
use App\Models\Traza_Permisos;
use App\Models\Traza_Resenna;
use App\Models\Traza_Roles;
use App\Models\Traza_Rutas_Almacenamiento;
use App\Models\Traza_Servicios;
use App\Models\Traza_Sessions;
use App\Models\Traza_Token;
use App\Models\Traza_User;

class TrazasListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $tabla = $event->getModule();
        $data = $event->getData();
        switch ($tabla) {
            case 'Traza_User':
                Traza_User::create($data);
                break;
            case 'Traza_Funcionarios':
                Traza_Funcionarios::create($data);
                break;
            case 'Traza_Resenna':
                Traza_Resenna::create($data);
                break;
            case 'Traza_Roles':
                Traza_Roles::Create($data);
                break;
            case 'Traza_Sessions':
                Traza_Sessions::Create($data);
                break;
            case 'Traza_Permisos':
                Traza_Permisos::Create($data);
                break;
            case 'Traza_RutasAlmacenamiento':
                Traza_Rutas_Almacenamiento::Create($data);
                break;
            case 'Traza_HistorialSesion':
                Traza_Historial_Sesion::Create($data);
                break;
            case 'Traza_Backup':
                Traza_Backup::Create($data);
                break;
            case 'Traza_ApkMovil':
                Traza_Apk_Movil::Create($data);
                break;
            case 'Traza_BitacoraLlamadas':
                Traza_Bitacora_Llamadas::Create($data);
                break;
            case 'Traza_Dependencias':
                Traza_Dependencias::Create($data);
                break;
            case 'Traza_Servicios':
                Traza_Servicios::Create($data);
                break;
            case 'Traza_Token':
                Traza_Token::Create($data);
                break;
            default:
                break;
        }
        
    }
}
