<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Constants;
use App\Models\Token;
use App\Models\Token_Empresas;
use App\Models\User;
use App\Traits\APITrait;
use App\Traits\EmpresaServiciosTrait;
use Illuminate\Http\Request;
use DateTime;

class AuthController extends Controller
{
    use APITrait;
    use EmpresaServiciosTrait;

    private $user;
    private $token;
    private $tokenEmpresas;
    
    public function __construct(User $user, Token_Empresas $tokenEmpresas, Token $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->tokenEmpresas = $tokenEmpresas;
    }

    public function index(Request $request)
    {
        $this->setStartTime();
        $this->setUrl($request->path());
        $this->setBearerToken($request->bearerToken());

        $this->setMessage(Constants::HTTP_MESSAGE_NOK);
        $this->setCode(Constants::HTTP_CODE_NOT_FOUND);
        $this->setDescription(Constants::HTTP_DESCRIPTION_NOT_FOUND);
        $this->setData([]);

        $rules = [
            'service' => 'required|string|max:30',
        ];
        
        $validateRequest = [
            'service' => $request->service,
        ];
        
        if (!$this->validateRequest($validateRequest, $rules)) {
            $parametersRequested = [
                'ip' => $request->ip ?? ':::1',
                'mac' => $request->mac ?? '00:00:00:00:00',
                'idEmpresa' => $request->idEmpresa,
                'request' => $request->getContent()
            ];

            $this->setService($request->service);
            $this->setMultipleRequest($parametersRequested);
            $this->setServicesAllowed($request->servicesAllowed);

            if($this->validateServicePermission()) {
                $this->setMessage(Constants::HTTP_MESSAGE_OK);
                $this->setCode(Constants::HTTP_CODE_OK);
                $this->setDescription(Constants::HTTP_DESCRIPTION_OK);
                $this->setData(json_decode($request->getContent()));
            }
            $this->calculateTimeExecution();
            $this->saveTrazas();
        }
        return response()->json($this->getResponse(), $this->getCode(), $this->getHeader());
    }

    public function check(Request $request)
    {
        $this->setStartTime();
        $this->setUrl($request->path());
        $this->setBearerToken($request->bearerToken());

        $this->setMessage(Constants::HTTP_MESSAGE_NOK);
        $this->setCode(Constants::HTTP_CODE_NOT_FOUND);
        $this->setDescription(Constants::HTTP_DESCRIPTION_NOT_FOUND);
        $this->setData([]);
        
        $parametersRequested = [
            'ip' => $request->ip ?? ':::1',
            'mac' => $request->mac ?? '00:00:00:00:00',
            'idEmpresa' => $request->idEmpresa,
            'request' => $request->getContent()
        ];
        $this->setMultipleRequest($parametersRequested);

        $token = $this->tokenEmpresas::join('empresas', 'empresas.id', '=', 'token_empresas.id_empresa')
        ->Where('seudonimo', $request->header('x-entity'));
        $existsToken = $token->exists();
        
        if($existsToken) {
            $token = $token->first();
            
            $dateToday = new DateTime();
            $createdAtInDays = $dateToday->diff(new DateTime($token->created_at))->days.' días';
            $expiredAtInDays = $dateToday->diff(new DateTime($token->expired_at))->days.' días';
            $lastUsedAtInHours = $dateToday->diff(new DateTime($token->last_used_at))->days.' días';
            if($lastUsedAtInHours === '0 días') {
                $lastUsedAtInHours = $dateToday->diff(new DateTime($token->last_used_at))->h.' horas';

                if($lastUsedAtInHours === '0 horas') {
                    $lastUsedAtInHours = $dateToday->diff(new DateTime($token->last_used_at))->i.' minutos';

                    if($lastUsedAtInHours === '0 minutos') {
                        $lastUsedAtInHours = $dateToday->diff(new DateTime($token->last_used_at))->s.' segundos';
                    }
                }
            }

            $createdAt = date('d-m-Y H:i:s', strtotime($token->created_at)).'. Hace '.$createdAtInDays;
            $expiredAt = date('d-m-Y H:i:s', strtotime($token->expired_at)).'. En '.$expiredAtInDays;
            $lastUsedAt = date('d-m-Y H:i:s', strtotime($token->last_used_at)).'. Hace '.$lastUsedAtInHours;

            $availableServices = $this->rebuildArrayServices($this->getServiciosEmpresa($token['id_empresa']));

            $data = [
                'token' => [
                    'createdAt' => $createdAt,
                    'expiredAt' => $expiredAt,
                    'lastUsedAt' => $lastUsedAt
                ],
                'availableServices' => $availableServices
            ];

            $this->setMessage(Constants::HTTP_MESSAGE_OK);
            $this->setCode(Constants::HTTP_CODE_OK);
            $this->setDescription(Constants::HTTP_DESCRIPTION_OK);
            $this->setData($data);
        }
        $this->calculateTimeExecution();
        $this->saveTrazas();

        return response()->json($this->getResponse(), $this->getCode(), $this->getHeader());
    }

}
