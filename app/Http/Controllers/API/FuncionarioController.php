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

class FuncionarioController extends Controller
{
    use APITrait;

    private $user;
    private $funcionario;

    public function __construct(User $user, Funcionario $funcionario)
    {
        $this->user = $user;
        $this->funcionario = $funcionario;
    }

    public function SearchFuncionario(Request $request)
    {
        $this->setStartTime();
        $this->setUrl($request->path());
        $this->setBearerToken($request->bearerToken());

        $rules = [
            'tipo' => 'required|string|max:10',
            'valor' => 'required|numeric',
        ];
        
        $validateRequest = [
            'tipo' => $request->tipo,
            'valor' => $request->valor
        ];
        
        if (!$this->validateRequest($validateRequest, $rules)) {

            $result = [];
            $tipo = $request->tipo;
            $valor = $request->valor;
            $parametersRequested = [
                'ip' => $request->ip ?? '192.168.1.101',
                'mac' => $request->mac ?? '00:00:00:00:00:00',
                'idUser' => $request->idUser,
                'idDependencia' => $request->idDependencia,
                'request' => $tipo.': '.$valor
            ];

            $this->setService(__FUNCTION__);
            $this->setMultipleRequest($parametersRequested);
            $this->setServicesAllowed($request->servicesAllowed);

            if($this->validateServicePermission()) {
                if($tipo == 'cedula') {
                    $exists = $this->funcionario->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->Where('persons.cedula', $valor)->exists();
                    $message = 'La Cedula no pertenece a ningun Funcionario';
                }else if($tipo == 'credencial') {
                    $exists = $this->funcionario->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->Where('funcionarios.credencial', $valor)->exists();
                    $message = 'Credencial Inexistente';
                }else{
                    $exists = false;
                    $message = 'Búsqueda no válida';
                }
                
                if($exists) {
                    $funcionario = $this->funcionario->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->join('nomenclador.estatus_funcionario', 'estatus_funcionario.id', '=', 'funcionarios.id_estatus')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
                    ->select('persons.cedula', 'funcionarios.credencial', 'persons.primer_nombre', 
                    'persons.segundo_nombre', 'persons.primer_apellido', 'persons.segundo_apellido', 
                    'estatus_funcionario.valor as estatus_funcionario', 'jerarquia.valor as jerarquia');

                    if($tipo == 'cedula'){
                        $result = $funcionario->Where('persons.cedula', $valor)->first();
                    }else if($tipo == 'credencial'){
                        $result = $funcionario->Where('funcionarios.credencial', $valor)->first();
                    };
                    
                    $idUser = $this->getRequest('idUser');
                    if($idUser != null) {
                        $id_Accion = Constants::BUSQUEDA; 
                        $valores_modificados = 'Tipo de Búsqueda: '.$tipo.'. Valor Buscado: '.$valor;
                        event(new TrazasEvent($idUser, $id_Accion, $valores_modificados, 'Traza_Funcionarios'));
                    }
                    $this->setMessage(Constants::HTTP_MESSAGE_OK);
                }else{
                    $this->setMessage($message);
                }
                $this->setCode(Constants::HTTP_CODE_OK);
                $this->setDescription(Constants::HTTP_DESCRIPTION_OK);
                $this->setData($result);
            }
            $this->calculateTimeExecution();
            $this->saveTrazas();
        }

        return response()->json($this->getResponse(), $this->getCode(), $this->getHeader());
    }
}
