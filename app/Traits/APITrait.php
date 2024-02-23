<?php

namespace App\Traits;

use App\Http\Constants;
use App\Models\Traza_API;
use Illuminate\Support\Facades\Validator;

trait APITrait
{
    private $code = 500;
    private $startTime = 0;
    private $endTime = 0;
    private $timeExecution = 0;
    private $bearerToken = null;
    private $description = null;
    private $service = null;
    private $url = '/api/v2';
    private $message = '';
    private $data = [];
    private $request = [];
    private $servicesAllowed = [];
    private $headers = [
        'Content-Type' => 'application/json', 
        'accept' => 'application/json'
    ];
   
    public function setHeader($key, $value) 
    {
        $this->headers[$key] = $value;
    }

    public function getHeader()
    {
        return $this->headers;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setServicesAllowed($servicesAllowed)
    {
        $this->servicesAllowed = $servicesAllowed;
    }

    public function getServicesAllowed()
    {
        return $this->servicesAllowed ?? [];
    }

    public function setService($service)
    {
        $this->service = $service ?? null;
    }

    public function getService()
    {
        return $this->service;
    }

    public function setMultipleRequest($array = [])
    {
        foreach($array as $key => $value) {
            $this->request[$key] = $value ?? null;
        }
    }

    public function setRequest($key = '', $value = null)
    {
        $this->request[$key] = $value ?? null;
    }

    public function getRequest($key = null)
    {
        $request = $this->request;
        if(isset($key)) {
            $request = $this->request[$key];
        }
        return $request;
    }

    public function setUrl($url)
    {
        $splitUrl = str_split($url);
        if($splitUrl[0] !== '/') {
            $url = '/'.$url;
        }
        $this->url = $url ?? $this->url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setBearerToken($bearerToken)
    {
        $this->bearerToken = $bearerToken ?? null;
    }

    public function getBearerToken()
    {
        return $this->bearerToken;
    }

    public function setStartTime()
    {
        $this->startTime = microtime(true);
    }

    public function getTimeExecution()
    {
        return $this->timeExecution;
    }

    public function calculateTimeExecution()
    {
        $this->endTime = microtime(true);
        $this->timeExecution = $this->endTime - $this->startTime;
    }

    public function getResponse()
    {
        return [
            'description' => $this->description,
            'message' => $this->message,
            'data' => $this->data,
            'timeExecution' => $this->timeExecution,
            'href' => $this->url
        ];
    }

    public function rebuildArrayServices($services)
    {
        $servicesAllowed = [];
        foreach($services as $value) {
            $servicesAllowed[] = $value['metodo'];
        }
        return $servicesAllowed;
    }

    public function validateServicePermission()
    {
        $permission = in_array($this->service, $this->servicesAllowed, true) ? true : false;
        if(!$permission) {
            $this->setCode(Constants::HTTP_CODE_FORBIDDEN);
            $this->setDescription(Constants::HTTP_DESCRIPTION_FORBIDDEN);
            $this->setMessage(Constants::HTTP_MESSAGE_FORBIDDEN);
        }
        return $permission;
    }

    public function validateRequest($request, $rules) 
    {
        $validator = Validator::make($request, $rules);
        $fails = $validator->fails();
        if ($fails) {
            $this->setCode(Constants::HTTP_CODE_BAD_REQUEST);
            $this->setDescription(Constants::HTTP_DESCRIPTION_BAD_REQUEST);
            $this->setMessage($validator->errors()->all());
        }
        return $fails;
    }

    public function saveTrazas()
    {
        if(isset($this->service) && is_array($this->service)){
            $this->service = print_r($this->service, true);
        }

        if(isset($this->request['request']) && is_array($this->request['request'])){
            $this->request['request'] = print_r($this->request['request'], true);
        }
        
        $trazaAPI = new Traza_API();
        if(isset($this->request['idUser'])) {
            $trazaAPI->id_user = $this->request['idUser'];
        }

        if(isset($this->request['idEmpresa'])) {
            $trazaAPI->id_empresa = $this->request['idEmpresa'];
        }

        $trazaAPI->ip = $this->request['ip'];
        $trazaAPI->mac = $this->request['mac'];
        $trazaAPI->fecha_request = date('Y-m-d H:i:s');
        $trazaAPI->action = $this->service ?? null;
        $trazaAPI->response = json_encode($this->data, true);
        $trazaAPI->request = $this->request['request'];
        $trazaAPI->token = $this->bearerToken;
        $trazaAPI->code = $this->code;
        $trazaAPI->description = $this->description;
        $trazaAPI->time_execution = $this->timeExecution;
        $trazaAPI->save();
    }
}

?>