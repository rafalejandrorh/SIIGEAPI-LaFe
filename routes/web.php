<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\DependenciasController;
use App\Http\Controllers\Web\PermisosController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\TrazasController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\GeografiaVenezuelaController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ServiciosController;
use App\Http\Controllers\Web\SessionsController;
use App\Http\Controllers\Web\TokensController;
use App\Http\Controllers\Web\UsersQuestionsController;
use App\Http\Controllers\Web\HistorialSesionController;
use App\Http\Controllers\Web\ConfiguracionesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'status.user', 'password.status.user'])->group(function() {
    Route::resource('geografiaVenezuela', GeografiaVenezuelaController::class);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('trazas', TrazasController::class);
    Route::resource('sessions', SessionsController::class);
    Route::resource('tokens', TokensController::class);
    Route::resource('empresas', DependenciasController::class);
    Route::resource('servicios', ServiciosController::class);

    // Rutas Principales (Login y Home)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Rutas Adicionales de Servicios
    Route::patch('/servicios/{servicio}/status', [ServiciosController::class, 'update_status'])->name('servicios.update_status');

    // Rutas Adicionales de Tokens
    Route::patch('/tokens/{token}/status', [TokensController::class, 'update_status'])->name('tokens.update_status');

    // Rutas para consulta Vía Ajax (Response JSON)
    Route::get('geografia/venezuela/{id}/{id_hijo}', [GeografiaVenezuelaController::class, 'get']);
    Route::get('notifications/markAsRead/{id}', [UserController::class, 'markAsReadNotification']);

    // Rutas adicionales para exportables
    Route::prefix('/export')->group(function() {
        Route::get('/roles', [RoleController::class, 'exportExcel'])->name('roles.export.excel');
        Route::get('/users', [UserController::class, 'exportExcel'])->name('users.export.excel');
    });

    Route::patch('/reset/{user}', [UserController::class, 'ResetPassword'])->name('users.reset');
    Route::get('/status/user', [UserController::class, 'updateStatusAll'])->name('users.update_status.all');
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update_status');

    Route::prefix('/profile')->group(function() {
        Route::withoutMiddleware('password.status.user')->group(function() {
            Route::get('user', [UserController::class, 'profile'])->name('users.profile');
            Route::patch('user/password', [UserController::class, 'updatePassword'])->name('users.update.password');
            Route::patch('user/email', [UserController::class, 'updateEmail'])->name('users.update.email');
            Route::post('user/questions', [UserController::class, 'updateRequestSecurityQuestions'])->name('users.update.request.security_questions');
        });
    });

    Route::prefix('questions')->group(function() {
        Route::post('/', [UsersQuestionsController::class, 'store'])->name('questions.store');
        Route::get('/validation/{user}', [UsersQuestionsController::class, 'index'])->name('questions.index');
        Route::patch('/{user}', [UsersQuestionsController::class, 'update'])->name('questions.update');;
        Route::post('/validation', [UsersQuestionsController::class, 'validation'])->name('questions.validation');
        Route::delete('/{user}', [UsersQuestionsController::class, 'destroy'])->name('questions.destroy');
    });

    // Módulo de Trazas
    Route::get('/trazasHistorialSesion', [TrazasController::class, 'indexHistorialSesion'])->name('traza_historialSesion.index');
    Route::get('/trazasUsers', [TrazasController::class, 'indexUsuarios'])->name('traza_user.index');
    Route::get('/trazasRoles', [TrazasController::class, 'indexRoles'])->name('traza_roles.index');
    Route::get('/trazasSesiones', [TrazasController::class, 'indexSesiones'])->name('traza_sesiones.index');
    Route::get('/trazasPermisos', [TrazasController::class, 'indexPermisos'])->name('traza_permisos.index');
    Route::get('/trazaEmpresas', [TrazasController::class, 'index_dependencias'])->name('traza_dependencias.index');
    Route::get('/trazaTokens', [TrazasController::class, 'index_tokens'])->name('traza_tokens.index');
    Route::get('/trazaServicios', [TrazasController::class, 'index_servicios'])->name('traza_servicios.index');
    Route::get('/trazaApi', [TrazasController::class, 'index_api'])->name('traza_api.index');
    Route::get('/trazaHistorialTokens', [TrazasController::class, 'index_historial_tokens'])->name('traza_historial_tokens.index');
    
    // Rutas Adicionales del módulo de Trazas
    Route::prefix('/trazas')->group(function() {
        Route::get('/users/{data}', [TrazasController::class, 'showUsuarios'])->name('traza_user.show');
        Route::get('/roles/{data}', [TrazasController::class, 'showRoles'])->name('traza_roles.show');
        Route::get('/sesiones/{data}', [TrazasController::class, 'showSesiones'])->name('traza_sesiones.show');
        Route::get('/permisos/{data}', [TrazasController::class, 'showPermisos'])->name('traza_permisos.show');
        Route::get('/empresas/{data}', [TrazasController::class, 'show_dependencias'])->name('traza_dependencias.show');
        Route::get('/tokens/{data}', [TrazasController::class, 'show_tokens'])->name('traza_tokens.show');
        Route::get('/historialTokens/{historial_token}', [TrazasController::class, 'show_historial_tokens'])->name('traza_historial_tokens.show');
        Route::get('/api/{apis}', [TrazasController::class, 'show_api'])->name('traza_api.show');
        Route::get('/servicios/{data}', [TrazasController::class, 'show_servicios'])->name('traza_servicios.show');

        Route::patch('/users/{data}', [TrazasController::class, 'updateUsers'])->name('traza_user.update');
        Route::patch('/roles/{data}', [TrazasController::class, 'updateRoles'])->name('traza_roles.update');
    });

    // Módulo de Historial de Sesión
    Route::get('/historialSesion', [HistorialSesionController::class, 'index'])->name('historial_sesion.index');
    Route::get('export/historialSesion/', [HistorialSesionController::class, 'exportExcel'])->name('historial_sesion.export.excel');

    // Módulo de Configuraciones
    Route::prefix('/configuraciones')->group(function() {
        Route::get('/', [ConfiguracionesController::class, 'index'])->name('configuraciones.index');
        Route::get('/permisos', [PermisosController::class, 'index'])->name('permisos.index');
        Route::get('/permisos/create', [PermisosController::class, 'create'])->name('permisos.create');
        Route::get('/permisos/{permiso}/edit', [PermisosController::class, 'edit'])->name('permisos.edit');
        Route::patch('/permisos/{permiso}', [PermisosController::class, 'update'])->name('permisos.update');
        Route::post('/permisos', [PermisosController::class, 'store'])->name('permisos.store');
        Route::delete('/permisos/{permiso}', [PermisosController::class, 'destroy'])->name('permisos.destroy');
    });

    // Módulo de Logs
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');

    // Rutas Adicionales de Logout
    Route::withoutMiddleware('status.user')->group(function() {
        Route::get('logout/{id}', [LoginController::class, 'logout'])->name('logout.forced');
        Route::post('logout/{id}', [LoginController::class, 'logout'])->name('logout');
    });
});

// Rutas de Autenticación
Auth::routes();
Route::get('/', [LoginController::class, 'index']);