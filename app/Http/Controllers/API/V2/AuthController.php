<?php

namespace App\Http\Controllers\API\V2;

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
        $this->setStartTime();
        $this->setUrl($request->path());

        $request->validate([
            'user'    => 'required|max:20',
            'password' => 'required',
        ]);

        $this->setData([]);
        $this->setMessage('');
        $this->setCode(Constants::HTTP_CODE_UNAUTHORIZED);
        $this->setDescription(Constants::DESCRIPTION_ERROR_AUTH);

        $validateUser = $this->user::where('users', $request->user)->exists();
        if($validateUser) {

            $validateStatusUser = $this->user::where('users', $request->user)
            ->where('status', true)
            ->exists();

            if($validateStatusUser) {
                
                $user = $this->user->with('person')->where('users', $request->user)->first();
                $primerNombre = $user->person->primer_nombre;
                $primerApellido = $user->person->primer_apellido;
                $validatePassword = Hash::check($request->password, $user->password);

                if($validatePassword) {

                    $dateNow = date('d-m-Y H:i:s');
                    $dateExpireToken = date('d-m-Y H:i:s', strtotime($dateNow."+ 12 hour"));
                    $token = $user->createToken('APPSIIGEAPI', ['*'], $dateExpireToken)->plainTextToken;
                    $data = [
                        'userId' => $user->id,
                        'fullName' => $primerNombre.' '.$primerApellido,
                        'accessToken' => $token
                    ];

                    $this->setMessage('Inicio de Sesión Exitoso');
                    $this->setData($data);
                    $this->setCode(Constants::HTTP_CODE_OK);
                    $this->setDescription(Constants::DESCRIPTION_OK_AUTH);
                }else{
                    $this->setMessage('Contraseña Incorrecta');
                }
            }else{
                $this->setMessage('Usuario Inactivo o Sin Acceso a la Aplicación Móvil');
            }
        }else{
            $this->setMessage('Usuario Incorrecto o No Registrado');
        }
        $this->calculateTimeExecution();

        return response()->json($this->getResponse(), $this->getCode(), $this->getHeader());
    }

    public function logout(Request $request)
    {
        $this->setStartTime();
        $this->setUrl($request->path());

        $bearerToken = $request->bearerToken() ?? null;
        $validateToken = PersonalAccessToken::findToken($bearerToken);
        if($validateToken) {

            $validateToken->delete();
            $this->setMessage('Sesión Finalizada Exitosamente');
            $this->setCode(Constants::HTTP_CODE_OK);
            $this->setDescription(Constants::HTTP_DESCRIPTION_OK);
        }else{
            $this->setMessage('No se encontró el Token');
            $this->setCode(Constants::HTTP_CODE_NOT_FOUND);
            $this->setDescription(Constants::HTTP_DESCRIPTION_NOT_FOUND);
        }
        $this->calculateTimeExecution();

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
