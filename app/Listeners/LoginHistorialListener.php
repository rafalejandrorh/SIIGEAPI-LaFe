<?php

namespace App\Listeners;

use App\Models\Historial_Sesion;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginHistorialListener
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
        $mac = $event->getMac();

        $users = User::find($id_user, ['id']);
        $users->update(['last_login' => now()]);

        $sesion = new Historial_Sesion();
        $sesion->id_user = $id_user;
        $sesion->login = now();
        $sesion->MAC = $mac;
        //$sesion->IP = $request->ip();
        $sesion->save();
        return $sesion->id;
    }
}
