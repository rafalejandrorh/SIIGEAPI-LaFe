<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class DataController extends Controller
{
    /*
    private function errorCodeService()
    {
        $response = [
            'code' => "".Constants::ERROR_CODE_SERVICE."",
            'status' => Constants::ERROR_DESCRIPTION_SERVICE,
            'description' => 'El Servicio  que intenta consultar no existe o no se encuentra disponible',
        ];
        return $response;
    }

    public function errorCodeUnauthorizedService()
    {
        $response = [
            'code' => "".Constants::ERROR_CODE_UNAUTHORIZED_SERVICE."",
            'status' => Constants::ERROR_DESCRIPTION_UNAUTHORIZED_SERVICE,
            'Message' => 'No posee Autorizacion para consultar este servicio',
        ];
        return $response;
    }

    public function errorUnauthorizedAction()
    {
        $response = [
            'code' => "".Constants::ERROR_UNAUTHORIZED_ACTION."",
            'status' => Constants::ERROR_DESCRIPTION_UNAUTHORIZED_ACTION,
            'description' => 'La Accion que pretende realizar no se encuentra permitida en este servicio. El incidente sera reportado.'
        ];
        return $response;
    }

    private function errorCodeInactiveService($data)
    {
        $response = [
            'code' => "".Constants::ERROR_CODE_INACTIVE_SERVICE."",
            'status' => Constants::ERROR_DESCRIPTION_INACTIVE_SERVICE,
            'description' => 'El Servicio que intenta Consultar se encuentra Inactivo',
            'Request' => $data
        ];
        return $response;
    }

    private function okWelcome()
    {
        $response = [
            'code' => "".Constants::OK_CODE_SERVICE."",
            'status' => Constants::OK_DESCRIPTION_SERVICE,
            'description' => 'Revisa la Documentacion para utilizar el Servicio.'
        ];
        return $response;
    }
    */
}
