<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrazasEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id_user;
    private $id_accion;
    private $valores_modificados;
    private $modulo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id_user, $id_accion, $valores_modificados, $modulo)
    {
        $this->id_user = $id_user;
        $this->id_accion = $id_accion;
        $this->valores_modificados = $valores_modificados;
        $this->modulo = $modulo;
    }

    public function getData()
    {
        return [
            'id_user' => $this->id_user,
            'id_accion' => $this->id_accion,
            'valores_modificados' => $this->valores_modificados 
        ];
    }

    public function getModule()
    {
        return $this->modulo;
    }

}
