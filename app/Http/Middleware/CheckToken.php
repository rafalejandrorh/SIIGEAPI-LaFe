<?php

namespace App\Http\Middleware;

use App\Http\Constants;
use App\Models\Servicios_Empresas;
use Closure;
use Illuminate\Http\Request;
use App\Models\Token_Empresas;
use App\Traits\APITrait;
use App\Traits\EmpresaServiciosTrait;

class CheckToken
{
    use APITrait;
    use EmpresaServiciosTrait;

    private $serviciosEmpresas;
    private $token;

    public function __construct(Servicios_Empresas $serviciosEmpresas, Token_Empresas $token)
    {
        $this->serviciosEmpresas = $serviciosEmpresas;
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

        $tokenEmpresa = $this->token::join('empresas', 'empresas.id', '=', 'token_empresas.id_empresa');
        if(isset($bearerToken) && $bearerToken != null) {
            if(isset($entity)) {
                
                $empresa = $this->token::join('empresas', 'empresas.id', '=', 'token_empresas.id_empresa')->Where('seudonimo', $headers['x-entity']);
                $existsEntity = $empresa->exists();
                
                if($existsEntity) {
                    
                    $empresa = $empresa->first();
                    if($empresa['token'] === $bearerToken) {

                        $token = $tokenEmpresa->Where('token', $bearerToken);
                        $dataToken = $token->first();
                        $today = date('Y-m-d H:i:s');

                        if($today < $dataToken['expired_at'] && $dataToken['estatus']) {
                            $servicesAllowed = $this->rebuildArrayServices($this->getServiciosEmpresa($empresa['id_empresa']));
                            $request['servicesAllowed'] = $servicesAllowed;
                            $request['idEmpresa'] = $empresa['id_empresa'];
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
