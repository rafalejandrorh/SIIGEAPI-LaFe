<?php

namespace App\Traits;

use App\Http\Constants;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;

trait TokenJWTTrait
{
    public $time_expire;
    public $time;

    public function generateTokenJWT($duracionToken, $id_dependencia)
    {
        date_default_timezone_set('America/Caracas');
        $this->time = time();
        $this->time_expire = $this->time + (60*60*24*$duracionToken);
        $token = array(
            "iat" => $this->time, //Tiempo en que inicia el Token
            "exp" => $this->time_expire, //Tiempo de expiración del Token
            "data" => [
                "id_dependencia" => $id_dependencia,
            ]
        );

       return JWT::encode($token, Constants::JWT_KEY, 'HS256');
    }
}

?>