<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoginHistorialEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id_user;
    private $mac;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id_user, $mac)
    {
        $this->id_user = $id_user;
        $this->mac = $mac;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function getMac()
    {
        return $this->mac;
    }
}
