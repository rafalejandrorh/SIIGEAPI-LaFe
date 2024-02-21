<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogoutHistorialEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id_historial_sesion;
    private $id_user;
    private $tipo_logout;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id_historial_sesion, $tipo_logout, $id_user)
    {
        $this->id_historial_sesion = $id_historial_sesion;
        $this->id_user = $id_user;
        $this->tipo_logout = $tipo_logout;
    }

    public function getIdHistorialSesion()
    {
        return $this->id_historial_sesion;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function getTipoLogout()
    {
        return $this->tipo_logout;
    }
}
