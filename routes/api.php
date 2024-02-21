<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FuncionarioController;
use App\Http\Controllers\API\ResennaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function() {
    $code = App\Http\Constants::HTTP_CODE_UNAUTHORIZED;
    $status = App\Http\Constants::DESCRIPTION_ERROR_UNAUTHORIZED;
    return response()->json([
            'code' => $code,
            'status' => $status,
            'message' => 'Contacte a la Unidad de Tecnologia de la Policia del Municipio Cristobal Rojas para solicitar acceso'
        ],
        $code
    );
});

// Servicios a Externos Autenticados por Bearer Token
Route::middleware(['token'])->group(function() {
    Route::prefix('/v1')->group(function() {
        Route::prefix('/funcionario')->group(function() {
            //Route::post('/consulta/{tipo}/{valor}', [FuncionarioController::class, 'SearchFuncionario']);
        });

        Route::prefix('/resenna')->group(function() {
            //Route::post('/consulta/{cedula}', [ResennaController::class, 'SearchResennado']);
        });
    });
});

// Servicios para la APP autenticados por Auth Sanctum
Route::prefix('/v2')->group(function() {
    Route::prefix('/auth')->group(function() {
        //Route::post('/login', [AuthController::class, 'login']);
        //Route::post('/logout', [AuthController::class, 'logout']);
    });
    
    Route::middleware(['auth:sanctum', 'access.app', 'status.user'])->group(function() {
        Route::prefix('/funcionario')->group(function() {
            //Route::post('/consulta/{tipo}/{valor}', [FuncionarioController::class, 'SearchFuncionario']);
        });

        Route::prefix('/resenna')->group(function() {
            //Route::post('/consulta/{cedula}', [ResennaController::class, 'SearchResennado']);
        });
    });
});