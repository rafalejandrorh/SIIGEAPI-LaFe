<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\PublicNotification;
use App\Events\TrazasEvent;
use App\Exports\UsersExport;
use App\Http\Constants;
use App\Models\Funcionario;
use App\Traits\FuncionariosTrait;
use App\Traits\HistorialAccionesTrait;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use UserTrait;
    use FuncionariosTrait;
    use HistorialAccionesTrait;

    private $user;
    private $funcionario;

    function __construct(User $user, Funcionario $funcionario)
    {
        $this->middleware('can:users.index')->only('index');
        $this->middleware('can:users.create')->only('create');
        $this->middleware('can:users.show')->only('show');
        $this->middleware('can:users.edit')->only('edit', 'update');
        $this->middleware('can:users.update_status')->only('updateStatus');
        $this->middleware('can:users.excel')->only('exportExcel');

        $this->user = $user;
        $this->funcionario = $funcionario;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Users = $this->user->search($request->all());

        $id_user = Auth::user()->id;
        if(isset($request->tipo_busqueda) && isset($request->buscador))
        {
            $id_Accion = Constants::BUSQUEDA; 
            $valores_modificados = 'Tipo de Búsqueda: '.$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador;
        }else{
            $id_Accion = Constants::INGRESO_MODULO; 
            $valores_modificados = 'N/A';
        }
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

        $dateYM = date('Y-m');
        $dateY = date('Y');
        $dateYMD = date('Y-m-d');
        $countUsers = $this->user->count();
        $countUsersActive = $this->user->Where('status', true)->count();
        $countUsersInactive = $this->user->Where('status', false)->count();
        
        return view('users.index', compact('Users', 'countUsers', 'countUsersActive', 'countUsersInactive'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','id')->all();
        $funcionarios = $this->pluckFuncionario();
        return view('users.create',compact('roles', 'funcionarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->all();
        Validator::make($request,$this->user->returnValidations(),$this->user->returnMessages())->validate();
        
        $query = $this->user->where('id_funcionario', $request['id_funcionario']);
        $validateUser = $query->exists();
        if($validateUser){
            Alert()->info('El funcionario ya posee un Usuario.');
            return redirect()->route('users.index');
        }else{
            $funcionario = $this->funcionario->where('id', $request['id_funcionario'])->first();
            if($funcionario['id_estatus'] == 1310000 || $funcionario['id_estatus'] == 1310005) {
                $request['password'] = bcrypt($request['password']);
                
                $user = $this->user->create([
                    'id_funcionario' => $funcionario['id'],
                    'users' => $request['users'],
                    'password' => $request['password'],
                    'status' => 'true',
                    'email' => $request['email'],
                    'acceso_app' => isset($request['acceso_app']) ? true : false,
                    'two_factors_auth' => isset($request['two_factors_auth']) ? true : false,
                    'security_questions' => isset($request['security_questions']) ? true : false,
                    'password_status' => true
                ]);
                $user->roles()->sync($request['roles']);
                $rol = $this->splitArrayWithComma($request['roles']);

                $request['acceso_app'] = isset($request['acceso_app']) ? 'Acceso a Aplicación Móvil' : 'Sin Acceso a Aplicación Móvil';
                $request['two_factors_auth'] = isset($request['two_factors_auth']) ? 'Con Autenticación de dos factores' : 'Sin Autenticación de dos factores';
                $request['security_questions'] = isset($request['security_questions']) ? 'Con Preguntas de Seguridad' : 'Sin Preguntas de Seguridad';
        
                $id_user = Auth::user()->id;
                $id_Accion = Constants::REGISTRO; 
                $valores_modificados = 'Datos de Usuario: '.$request['users'].' || Activo || '.$rol.' || '.$request['email'].
                ' || '.$request['acceso_app'].' || '.$request['two_factors_auth'].' || '.$request['security_questions'];
                event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

                event(new PublicNotification(Auth::user()->users, $request['users'], 'Usuarios', $id_Accion));
                Alert()->success('Usuario Creado Satisfactoriamente'); 
                return redirect()->route('users.index');

            }else{
                Alert()->error('No se creó el Usuario', 'El Funcionario no se encuentra Activo, por lo que no se puede asignar un Usuario');
                return back();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $id_user = Auth::user()->id;
        $roles = $user->roles;
        $role = $this->splitArrayWithComma($roles);

        $estatus = $user->status ? 'Activo' : 'Inactivo';
        $id_Accion = Constants::VISUALIZACION; 
        $valores_modificados ='Datos de Usuario: '.
        $user->funcionario->jerarquia->valor.'. '.$user->funcionario->person->primer_nombre.' '.
        $user->funcionario->person->primer_apellido.' || '.$estatus.' || '.$user->users.' || '.$role.' || '.$user->email;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

        $roles = Role::pluck('name','id')->all();
        return view('users.show', compact('user', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name','id')->all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request = $request->all();
        $request['acceso_app'] = isset($request['acceso_app']) ? true : false;
        $request['two_factors_auth'] = isset($request['two_factors_auth']) ? true : false;
        $request['security_questions'] = isset($request['security_questions']) ? true : false;

        $user->update($request);
        $user->id;
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $user->roles()->sync($request['roles']);
        $rol = $this->splitArrayWithComma($request['roles']);

        $estatus = $user->status ? 'Activo' : 'Inactivo';
        $request['acceso_app'] = $request['acceso_app'] ? 'Acceso a Aplicación Móvil' : 'Sin Acceso a Aplicación Móvil';
        $request['two_factors_auth'] = $request['two_factors_auth'] ? 'Con Autentcación de dos factores' : 'Sin Autenticación de dos factores';
        $request['security_questions'] = $request['security_questions'] ? 'Con Preguntas de Seguridad' : 'Sin Preguntas de Seguridad';

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION; 
        $valores_modificados = 'Datos de Usuario: '.$request['users'].' || '.$estatus.' || '.$rol.' || '.$request['email'].
        ' || '.$request['acceso_app'].' || '.$request['two_factors_auth'].' || '.$request['security_questions'];
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));
    
        Alert()->success('Usuario Actualizado Satisfactoriamente');
        return redirect()->route('users.index');
    }

    public function ResetPassword($id){
        
        $user = $this->user->with('funcionario')->Where('id', $id)->first();
        $cedula = $user->funcionario->person->cedula;
        $password = 'pm*'.$cedula.'..';
        $usuario = Auth::user()->users;

        $bcrypt = bcrypt($password);
        $reset_password = $this->user->find($id, ['id']);
        $reset_password->update([
            'password' => $bcrypt,
            'password_status' => true
        ]);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION; 
        $valores_modificados = 'Se reseteó la contraseña del Usuario: '.$usuario.'. Se colocó la contraseña genérica';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

        Alert()->success('Reinicio de Contraseña realizado', 'Nueva Contraseña: '.$password);
        return back(); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id)
    {
        $user = $this->user->with('funcionario')->Where('id', $id)->first();
        $id_estatus = $user->funcionario->id_estatus;

        if($user['status']) {
            $estatus = false;
            $notificacion = 'Inactivo';
        }else if($user['status'] == false && $id_estatus == 1310000 || $id_estatus == 1310005){
            $estatus = true;
            $notificacion = 'Activo';
        }else{
            Alert()->error('No se actualizó el Estatus del Usuario', 'El Funcionario no se encuentra Activo, por lo que no se puede activar su Usuario');
            return back();
        }
        $users = $this->user->find($id, ['id']);
        $users->update(['status' => $estatus]);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION; 
        $valores_modificados = 'Datos de Usuario: '.$user['users'].' || '.$notificacion;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

        Alert()->success('Estatus de Usuario Actualizado', 'Nuevo Estatus: '.$notificacion);
        return back();
    }

    public function updateStatusAll(Request $request)
    {
        $request = $request->all();
        $i = 0;
        $countUsers = $request['user'];
        $dataUsers = null;
        while($i < count($countUsers)) {
            $user = $this->user->with('funcionario')->Where('id', $countUsers[$i])->first();
            $id_estatus = $user->funcionario->id_estatus;

            if($user['status']) {
                $estatusFuncionario = true;
                $estatus = false;
                $notificacion = 'Inactivo';
            }else if($user['status'] == false && $id_estatus == 1310000 || $id_estatus == 1310005){
                $estatusFuncionario = true;
                $estatus = true;
                $notificacion = 'Activo';
            }else{
                $estatusFuncionario = false;
                $notificacion = 'El Funcionario no se encuentra Activo, por lo que no se pudo activar el Usuario';
            }

            if($estatusFuncionario) {
                $users = $this->user->find($countUsers[$i], ['id']);
                $users->update(['status' => $estatus]);
            }

            $dataUsers .= $user['users'].', '.$notificacion.' || ';

            $i++;
        }

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION; 
        $valores_modificados = 'Datos de Usuario: '.$dataUsers;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

        Alert()->success('Estatus de Usuario Actualizado', 'Usuarios Modificados: '.$dataUsers);
        return redirect()->route('users.index');
    }

    public function exportExcel()
    {
        $id_user = Auth::user()->id;
        $id_Accion = Constants::DESCARGA; 
        $valores_modificados = 'Descarga de Excel con Datos de Usuarios';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

        return Excel::download(new UsersExport, 'users_'.date('Ymd-his').'.xlsx');
    }

    public function profile($password_status = null)
    {
        $idUser = Auth::user()->id;
        $roles = Auth::user()->roles;
        $role = $this->splitArrayWithComma($roles);
        $user = $this->user->Where('id', $idUser)->first();
        $data = $this->getHistorialAcciones($idUser);

        return view('users.profile', compact('user', 'role', 'idUser', 'password_status', 'data'));
    }

    public function updatePassword(Request $request)
    {
        $id = Auth::user()->id;
        $query = $this->user->where('id', $id);
        $person = $query->where('status', true)->exists();
        if($person) {

            $persona = $query->where('id', $id)->first();
            $validacion_password = Hash::check(request('curr_password'), $persona->password);
            if($validacion_password) {

                $validacion_password_new = Hash::check(request('password'), $persona->password);
                if($validacion_password_new == false) {
                    $request['password'] = bcrypt($request['password']);
                    $user = $this->user->find($id, ['id']);
                    $user->update([
                        'password' => $request['password'],
                        'password_status' => false
                    ]);

                    $id_user = Auth::user()->id;
                    $id_Accion = Constants::ACTUALIZACION; 
                    $valores_modificados = 'El Usuario actualizó su contraseña';
                    event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

                    Alert()->success('Cambio de Contraseña Exitoso');
                    return redirect()->route('home');
                }else{
                    Alert()->warning('Atención', 'La nueva Contraseña coincide con la Actual. Por favor, inserta una Contraseña distinta.');
                    return back();
                }
            }else{
                Alert()->error('La Contraseña Actual indicada no coincide con nuestros registros.');
                return back()->with('error', 'Ok');
            }
        }else{
            Alert()->warning('Lo sentimos', 'No puedes realizar esta acción.');
            return back();
        }
    }

    public function updateEmail(Request $request)
    {
        $id = Auth::user()->id;
        $query = $this->user->where('id', $id);
        $person = $query->where('status', true)->exists();
        if($person) {

            $dataPerson = $query->select('email')->first();
            if($dataPerson['email'] != $request['email']) {

                $user = $this->user->find($id, ['id']);
                $user->update([
                    'email' => $request['email'],
                ]);

                $id_user = Auth::user()->id;
                $id_Accion = Constants::ACTUALIZACION; 
                $valores_modificados = 'El Usuario actualizó su email';
                event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

                Alert()->success('Cambio de Email Exitoso');
                return redirect()->route('home');
            }else{
                Alert()->warning('Atención', 'El nuevo Email coincide con el Email Actual. Por favor, inserta un Email distinto.');
                return back();
            }
        }else{
            Alert()->warning('Lo sentimos', 'No puedes realizar esta acción.');
            return back();
        }
    }

    public function update2FA(Request $request)
    {
        $id = Auth::user()->id;
        $query = $this->user->where('id', $id);
        $person = $query->where('status', true)->exists();
        if($person) {

            $user = $this->user->find($id, ['id']);
            $user->update([
                'two_factors_auth' => $request->two_factors_auth ? false : true
            ]);

            $id_user = Auth::user()->id;
            $id_Accion = Constants::ACTUALIZACION; 
            $valores_modificados = $request->two_factors_auth ? 'El Usuario deshabilitó la Autenticación de dos factores' : 'El Usuario habilitó la Autenticación de dos factores';
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

            $icon = $request->two_factors_auth ? 'info' : 'success';
            $title = $request->security_questions ? 'Autenticación de dos factores deshabilitada' : 'Autenticación de dos factores habilitada';
            $result = [
                'icon' => $icon,
                'title' => $title
            ];
            return response()->json($result);
        }else{
            $result = [
                'icon' => 'warning',
                'title' => 'No puedes realizar esta acción'
            ];
            return response()->json($result);
        }
    }

    public function updateRequestSecurityQuestions(Request $request)
    {
        $id = Auth::user()->id;
        $query = $this->user->where('id', $id);
        $person = $query->where('status', true)->exists();

        if($person) {
            $user = $this->user->find($id, ['id']);
            $user->update([
                'security_questions' => $request->security_questions ? false : true
            ]);

            $id_user = Auth::user()->id;
            $id_Accion = Constants::ACTUALIZACION; 
            $valores_modificados = $request->security_questions ? 'El Usuario deshabilitó las Preguntas de Seguridad' : 'El Usuario Habilitó las Preguntas de Seguridad';
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

            $icon = $request->security_questions ? 'info' : 'success';
            $title = $request->security_questions ? 'Preguntas de Seguridad deshabilitadas' : 'Preguntas de Seguridad habilitadas';
            $result = [
                'icon' => $icon,
                'title' => $title
            ];
            return response()->json($result);
        }else{
            $result = [
                'icon' => 'warning',
                'title' => 'No puedes realizar esta acción'
            ];
            return response()->json($result);
        }
    }

    public function updateCoordinates(Request $request)
    {
        $id = Auth::user()->id;

        $user = $this->user->find($id, ['id']);
        $user->update([
            'coordinates' => $request->coordinates
        ]);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION; 
        $valores_modificados = 'Actualización de Ubicación del Usuario';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

        return response()->json(['message' => 'Coordenadas Actualizadas']);
    }

    public function markAsReadNotification(Request $request)
    {
        auth()->user()->unreadNotifications
        ->when($request, function($query) use ($request) {
            return $query->where('id', $request->id);
        })->markAsRead();
        
        return response(count(auth()->user()->unreadNotifications));
    }

    public function restart2FA()
    {
        $id_user = Auth::user()->id;
        $user = $this->user->find($id_user, ['id']);
        $user->update([
            'token_login' => null
        ]);

        $id_Accion = Constants::ACTUALIZACION; 
        $valores_modificados = 'Se reinició la Autenticación de dos Factores';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));
        
        Alert()->success('Se reinició la Autenticación de dos factores Satisfactoriamente');
        return back();
    }
}
