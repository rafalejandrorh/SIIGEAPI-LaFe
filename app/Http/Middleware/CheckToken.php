<?php

namespace App\Http\Middleware;

use App\Http\Constants;
use App\Models\Dependencias_Servicios;
use Closure;
use Illuminate\Http\Request;
use App\Models\Token_Dependencias;
use App\Traits\APITrait;
use App\Traits\DependenciaServiciosTrait;

class CheckToken
{
    use APITrait;
    use DependenciaServiciosTrait;

    private $dependencias_servicios;
    private $token;

    public function __construct(Dependencias_Servicios $dependencias_servicios, Token_Dependencias $token)
    {
        $this->dependencias_servicios = $dependencias_servicios;
        $this->token = $token;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $bearerToken = $request->bearerToken() ?? null;
        $headers = $request->header();
        $entity = $headers['x-entity'] ?? null;

        $code = Constants::HTTP_CODE_UNAUTHORIZED;
        $description = Constants::HTTP_DESCRIPTION_UNAUTHORIZED;

        $tokenDependencias = $this->token::join('dependencias', 'dependencias.id', '=', 'token_dependencias.id_dependencia');
        if(isset($bearerToken) && $bearerToken != null) {
            if(isset($entity)) {
                
                $dependencia = $this->token::join('dependencias', 'dependencias.id', '=', 'token_dependencias.id_dependencia')->Where('seudonimo', $headers['x-entity']);
                $existsEntity = $dependencia->exists();
                
                if($existsEntity) {
                    
                    $dependencia = $dependencia->first();
                    if($dependencia['token'] === $bearerToken) {

                        $token = $tokenDependencias->Where('token', $bearerToken);
                        $dataToken = $token->first();
                        $today = date('Y-m-d H:i:s');

                        if($today < $dataToken['expired_at'] && $dataToken['estatus']) {
                            $servicesAllowed = $this->rebuildArrayServices($this->getServicesDependencia($dependencia['id_dependencia']));
                            $request['servicesAllowed'] = $servicesAllowed;
                            $request['idDependencia'] = $dependencia['id_dependencia'];
                            $code = Constants::HTTP_CODE_OK;
                            $description = Constants::DESCRIPTION_OK_TOKEN;
                            
                        }else if($today > $dataToken['expired_at']) {
                            $description = Constants::DESCRIPTION_ERROR_TOKEN_EXPIRE;
                        }else if($today < $dataToken['expired_at'] && !$dataToken['estatus']){
                            $description = Constants::DESCRIPTION_ERROR_INACTIVE_TOKEN;
                        }
                        $token->update(['last_used_at' => $today]);

                    }else{
                        $description = Constants::DESCRIPTION_ERROR_NOT_TOKEN_MATCH;
                    }
                }else{
                    $description = Constants::DESCRIPTION_ERROR_NOT_ENTITY_EXISTS;
                }
            }else{
                $description = Constants::DESCRIPTION_ERROR_NOT_HEADER_ENTITY;
            }
        }else{
            $description = Constants::DESCRIPTION_ERROR_NOT_TOKEN;
        }

        if($code != Constants::HTTP_CODE_OK) {
            $this->setCode($code);
            $this->setDescription($description);
            return response()->json($this->getResponse(), $this->getCode());
        }

        return $next($request);
    }
}
