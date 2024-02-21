<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Resenna;
use App\Traits\APITrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResennaController extends Controller
{
    use APITrait;

    private $resenna;
    
    public function __construct(Resenna $resenna)
    {
        $this->resenna = $resenna;
    }

    public function SearchResennado(Request $request)
    {
        $this->setStartTime();
        $this->setUrl($request->path());
        $this->setBearerToken($request->bearerToken());

        $rules = [
            'cedula' => 'required|numeric',
        ];
        
        $validateRequest = [
            'cedula' => $request->cedula
        ];
        
        if (!$this->validateRequest($validateRequest, $rules)) {

            $result = [];
            $cedula = $request->cedula;
            $parametersRequested = [
                'ip' => $request->ip ?? '192.168.1.101',
                'mac' => $request->mac ?? '00:00:00:00:00:00',
                'idUser' => $request->idUser,
                'idDependencia' => $request->idDependencia,
                'request' => 'Cedula: '.$cedula
            ];

            $this->setService(__FUNCTION__);
            $this->setMultipleRequest($parametersRequested);
            $this->setServicesAllowed($request->servicesAllowed);

            if($this->validateServicePermission()) {
                $result['count'] = $this->resenna->join('persons', 'persons.id', '=', 'resenna_detenido.id_person')
                ->Where('persons.cedula', $cedula)->count();
        
                $i = 0;
                if($result['count'] > 0) {
                    $person = $this->resenna->join('persons', 'persons.id', '=', 'resenna_detenido.id_person')
                    ->join('nomenclador.geografia as estado', 'estado.id', '=', 'persons.id_estado_nacimiento')
                    ->join('nomenclador.geografia as municipio', 'municipio.id', '=', 'persons.id_municipio_nacimiento')
                    ->join('nomenclador.tipo_documentacion', 'tipo_documentacion.id', '=', 'persons.id_tipo_documentacion')
                    ->join('nomenclador.genero', 'genero.id', '=', 'persons.id_genero')
                    ->select(
                        'persons.id', 'persons.letra_cedula AS letraCedula', 'persons.cedula', 'persons.primer_nombre AS primerNombre', 
                        'persons.segundo_nombre AS segundoNombre', 'persons.primer_apellido AS primerApellido', 
                        'persons.segundo_apellido AS segundoApellido', 'persons.fecha_nacimiento AS fechaNacimiento', 
                        'tipo_documentacion.valor AS documentacion', 'estado.valor AS estadoNacimiento', 
                        'municipio.valor AS municipioNacimiento', 'genero.valor as genero'
                    )
                    ->Where('persons.cedula', '=', $cedula)->first();
        
                    $resenna = $this->resenna->join('nomenclador.caracteristicas_resennado AS Motivo_Resenna', 'Motivo_Resenna.id', '=', 'resenna_detenido.id_motivo_resenna')
                    ->join('nomenclador.caracteristicas_resennado AS Tez', 'Tez.id', '=', 'resenna_detenido.id_tez')
                    ->join('nomenclador.caracteristicas_resennado AS Contextura', 'Contextura.id', '=', 'resenna_detenido.id_contextura')
                    ->join('nomenclador.caracteristicas_resennado as profesion', 'profesion.id', '=', 'resenna_detenido.id_profesion')
                    ->join('nomenclador.caracteristicas_resennado as estado_civil', 'estado_civil.id', '=', 'resenna_detenido.id_estado_civil')
                    ->join('funcionarios as funcionario_aprehensor', 'funcionario_aprehensor.id', '=', 'resenna_detenido.id_funcionario_aprehensor')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionario_aprehensor.id_jerarquia')
                    ->join('persons as person_funcionario_aprehensor', 'person_funcionario_aprehensor.id', '=', 'funcionario_aprehensor.id_person')
                    ->select(
                    'resenna_detenido.fecha_resenna AS fechaResenna', 'resenna_detenido.direccion', 
                    'Tez.valor as tez', 'Contextura.valor as contextura', 'Motivo_Resenna.valor as motivoResenna', 'profesion.valor AS profesion',
                    'jerarquia.valor AS jerarquia', 'estado_civil.valor AS estadoCivil',
                    'person_funcionario_aprehensor.primer_nombre', 'person_funcionario_aprehensor.primer_apellido',
                    'funcionario_aprehensor.credencial', 'resenna_detenido.observaciones',
                    )
                    ->Where('resenna_detenido.id_person', '=', $person['id'])->get();

                    $idUser = $this->getRequest('idUser');
                    if($idUser != null) {
                        $id_Accion = Constants::BUSQUEDA; 
                        $valores_modificados = 'Tipo de Búsqueda: Reseñado. Valor Buscado: '.$cedula;
                        event(new TrazasEvent($idUser, $id_Accion, $valores_modificados, 'Traza_Resenna'));
                    }
        
                    $person['edad'] = Carbon::parse($person['fecha_nacimiento'])->age;
                    $person['fecha_nacimiento'] = date('d/m/Y', strtotime($person['fecha_nacimiento']));
                    $result['persona'] = $person;
        
                    while($i < $result['count']) {
                        //dd($resenna[$i]['fecha_resenna']);die;
                        //$resenna[$i]['fecha_resenna'] = date('d/m/Y', strtotime($resenna[$i]['fecha_resenna']));
                        $resenna[$i]['funcionarioAprehensor'] = $resenna[$i]['primer_nombre'].' '.$resenna[$i]['primer_apellido'];
                        $resenna[$i]['credencialFuncionarioAprehensor'] = $resenna[$i]['credencial'];
                        unset($resenna[$i]['primer_nombre'], $resenna[$i]['primer_apellido'], $resenna[$i]['credencial']);
                        $result['resenna'][$i] = $resenna[$i];
                        $i++;
                    }
                    $this->setMessage(Constants::HTTP_MESSAGE_OK);
                }else{
                    $this->setMessage('El Ciudadano no posee Reseñas');
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
