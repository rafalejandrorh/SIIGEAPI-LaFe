<?php

namespace App\Listeners;

use App\Models\Historial_Sesion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogoutHistorialListener
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
        $id_user = $event->getIdUser();
        $tipo_logout = $event->getTipoLogout();
        $id_historial_sesion = $event->getIdHistorialSesion();

        if($id_historial_sesion == null)
        {
            $sessions = Historial_Sesion::where('id_user', $id_user)->orderBy('id', 'DESC')->first();
            $sessions->logout = now();
            $sessions->tipo_logout = $tipo_logout;
            $sessions->save();
        }else{
            //$sesion = Historial_Sesion::find($id_historial_sesion, ['id']);
            $sessions = Historial_Sesion::where('id', $id_historial_sesion)->first();
            $sessions->logout = now();
            $sessions->tipo_logout = $tipo_logout;
            $sessions->save();
            /*
            $sesion->update([
                'logout' => now(),
                'tipo_logout' => $tipo_logout
            ]);
            */
        }
    }
}
