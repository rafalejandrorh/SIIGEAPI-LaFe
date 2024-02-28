<?php

use App\Http\Controllers\API\V1\AuthController AS AuthControllerV1;
use App\Http\Controllers\API\V2\AuthController AS AuthControllerV2;
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

Route::get('/', function() {
    $code = App\Http\Constants::HTTP_CODE_UNAUTHORIZED;
    $status = App\Http\Constants::DESCRIPTION_ERROR_UNAUTHORIZED;
    return response()->json([
            'code' => $code,
            'status' => $status,
            'message' => 'Contacte a la Unidad de Tecnologia de Seguros La Fé para solicitar acceso'
        ],
        $code
    );
});

// Servicios a Externos Autenticados por Bearer Token
Route::middleware(['token'])->group(function() {
    Route::prefix('/v1')->group(function() {
        Route::prefix('/auth')->group(function() {
            Route::post('/', [AuthControllerV1::class, 'index']);
        });
    });
});

// Servicios para la APP autenticados por Auth Sanctum
Route::prefix('/v2')->group(function() {
    Route::prefix('/auth')->group(function() {
        Route::post('/login', [AuthControllerV2::class, 'login']);
        Route::post('/logout', [AuthControllerV2::class, 'logout']);
    });
    
    Route::middleware(['auth:sanctum', 'access.app', 'status.user'])->group(function() {
        Route::prefix('/polizas')->group(function() {
            //Route::post('/emision', [PolizasController::class, 'broadcast']);
        });
    });
});