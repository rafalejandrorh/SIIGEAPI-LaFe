<?php

namespace App\Traits;

use App\Models\Historial_Sesion;
use App\Models\Traza_Roles;
use App\Models\Traza_User;
use App\Models\User;

trait HistorialAccionesTrait
{
    public function getHistorialAcciones($id = null)
    {
        $paginate = 10;
        $user = null;
        $users = new Traza_User();
        $roles = new Traza_Roles();
        $historialSesion = new Historial_Sesion();
        
        if($id != null)
        {
            $user = User::Where('id', $id)->first();
            $users = $users->Where('id_user', $id)->Where('created_at', '>', $user['last_login']);
            $roles = $roles->Where('id_user', $id)->Where('created_at', '>', $user['last_login']);
            $historialSesion = $historialSesion->Where('id_user', $id);
            $paginate = 5;
        }
        $users = $users->orderBy('created_at', 'DESC')->paginate($paginate);
        $roles = $roles->orderBy('created_at', 'DESC')->paginate($paginate);
        $historialSesion = $historialSesion->orderBy('created_at', 'DESC')->paginate($paginate);

        return [
            'user' => $user,
            'users' => $users,
            'roles' => $roles,
            'historialSesion' => $historialSesion 
        ];
    }
}

?>