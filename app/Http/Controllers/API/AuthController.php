<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Constants;
use App\Models\User;
use App\Traits\APITrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use APITrait;

    private $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $request->validate([
            'user'    => 'required|max:20',
            'password' => 'required',
        ]);

        $message = '';
        $data = [];
        $code = Constants::HTTP_CODE_UNAUTHORIZED;
        $description = Constants::DESCRIPTION_ERROR_AUTH;

        $validateUser = $this->user::where('users', $request->user)->exists();
        if($validateUser) {

            $validateStatusUser = $this->user::where('users', $request->user)
            ->where('acceso_app', true)
            ->where('status', true)
            ->exists();

            if($validateStatusUser) {
                
                $user = $this->user->with('funcionario.person')->with('funcionario.jerarquia')->where('users', $request->user)->first();
                $jerarquia = $user->funcionario->jerarquia->valor;
                $primerNombre = $user->funcionario->person->primer_nombre;
                $primerApellido = $user->funcionario->person->primer_apellido;
                $validatePassword = Hash::check($request->password, $user->password);

                if($validatePassword) {

                    $dateNow = date('d-m-Y H:i:s');
                    $dateExpireToken = date('d-m-Y H:i:s', strtotime($dateNow."+ 12 hour"));
                    $token = $user->createToken('APPSIIGEAPI', ['*'], $dateExpireToken)->plainTextToken;
                    
                    $message = 'Inicio de Sesión Exitoso';
                    $data = [
                        'userId' => $user->id,
                        'funcionario' => $jerarquia.' '.$primerNombre.' '.$primerApellido,
                        'accessToken' => $token
                    ];
                    $code = Constants::HTTP_CODE_OK;
                    $description = Constants::DESCRIPTION_OK_AUTH;
                }else{
                    $message = 'Contraseña Incorrecta';
                }
            }else{
                $message = 'Usuario Inactivo o Sin Acceso a la Aplicación Móvil';
            }
        }else{
            $message = 'Usuario Incorrecto o No Registrado';
        }
        
        $this->setMessage($message);
        $this->setCode($code);
        $this->setDescription($description);
        $this->setData($data);
        return response()->json($this->getResponse(), $this->getCode(), $this->getHeader());
    }

    public function logout(Request $request)
    {
        $bearerToken = $request->bearerToken() ?? null;
        PersonalAccessToken::findToken($bearerToken)->delete();
        $this->setMessage('Sesión Finalizada Exitosamente');
        $this->setCode(Constants::HTTP_CODE_OK);
        $this->setDescription(Constants::HTTP_DESCRIPTION_OK);
        return response()->json($this->getResponse(), $this->getCode(), $this->getHeader());
    }

    // Determinar si el Token tiene la capacidad determinada
    // if ($user->tokenCan('server:update')) {
    //     //
    // }

    // El middleware se puede asignar a una ruta para verificar que el token de la solicitud tiene las capacidades
    // Route::get('/orders', function () {
    //     // Token has both "check-status" and "place-orders" abilities...
    // })->middleware(['auth:sanctum', 'abilities:check-status,place-orders']);

    // Route::get('/orders', function () {
    //     // Token has the "check-status" or "place-orders" ability...
    // })->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
}
