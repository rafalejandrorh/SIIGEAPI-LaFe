<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Funcionario;
use App\Models\User;
use App\Traits\APITrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PolizasController extends Controller
{
    use APITrait;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function broadcast(Request $request)
    {
        $this->setStartTime();
        $this->setUrl($request->path());
        $this->setBearerToken($request->bearerToken());

        $rules = [
            'tipo' => 'required|string|max:10',
            'valor' => 'required|string|max:10',
        ];
        
        $validateRequest = [
            'tipo' => $request->tipo,
            'valor' => $request->valor
        ];
        
        if (!$this->validateRequest($validateRequest, $rules)) {

            $tipo = $request->tipo;
            $valor = $request->valor;
            $parametersRequested = [
                'ip' => $request->ip ?? ':::1',
                'mac' => $request->mac ?? '00:00:00:00:00',
                'idEmpresa' => $request->idEmpresa,
                'request' => $request->getContent()
            ];

            $this->setService(__FUNCTION__);
            $this->setMultipleRequest($parametersRequested);
            $this->setServicesAllowed($request->servicesAllowed);

            if($this->validateServicePermission()) {
                $exists = true;

                $data = [];
                $this->setMessage(Constants::HTTP_MESSAGE_NOK);
                $this->setCode(Constants::HTTP_CODE_NOT_FOUND);
                $this->setDescription(Constants::HTTP_DESCRIPTION_NOT_FOUND);
                if($exists) {
                    $data = json_decode($request->getContent());
                    $this->setMessage(Constants::HTTP_MESSAGE_OK);
                    $this->setCode(Constants::HTTP_CODE_OK);
                    $this->setDescription(Constants::HTTP_DESCRIPTION_OK);
                }
                $this->setData($data);
            }
            $this->calculateTimeExecution();
            $this->saveTrazas();
        }

        return response()->json($this->getResponse(), $this->getCode(), $this->getHeader());
    }
}
