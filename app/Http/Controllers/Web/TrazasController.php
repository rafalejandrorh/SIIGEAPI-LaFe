<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dependencias;
use App\Models\Empresas;
use App\Models\Token_Historial;
use Illuminate\Http\Request;
use App\Models\Traza_Acciones;
use App\Models\Traza_API;
use App\Models\Traza_Apk_Movil;
use App\Models\Traza_Backup;
use App\Models\Traza_Bitacora_Llamadas;
use App\Models\Traza_Empresas;
use App\Models\Traza_Funcionarios;
use App\Models\Traza_Historial_Sesion;
use App\Models\Traza_Resenna;
use App\Models\Traza_Roles;
use App\Models\Traza_Sessions;
use App\Models\Traza_User;
use App\Models\Traza_Permisos;
use App\Models\Traza_Rutas_Almacenamiento;
use App\Models\Traza_Servicios;
use App\Models\Traza_Token;
use App\Models\User;
use App\Traits\ServiciosTrait;

class TrazasController extends Controller
{
    use ServiciosTrait;

    function __construct()
    {
        // $this->middleware('can:trazas.index')->only('index', 
        // 'indexUsuarios', 'indexResenna', 'indexFuncionarios', 
        // 'indexHistorialSesion', 'indexRoles', 'indexSesiones', 'indexPermisos', 'indexRutasAlmacenamiento',
        // 'showResenna', 'showUsuarios', 'showFuncionarios', 'showRoles', 'showSesiones', 'showPermisos', 'showRutasAlmacenamiento');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('trazas.index');
    }

    public function indexHistorialSesion(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Historial_Sesion::query();
            if($request->fecha_inicio != null && $request->fecha_fin != null)    
            {
                $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
            }
            if($request->id_accion != null)
            {
                $queryBuilder->Where('id_accion', $request->id_accion);
            }
            if($request->id_usuario != null)
            {
                $queryBuilder->Where('id_user', $request->id_usuario);
            }
            $historialSesion = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if(isset($request->buscador) && is_numeric($request->buscador)) {
                if($request->tipo_busqueda == 'cedula'){
                    $historialSesion = Traza_Historial_Sesion::join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $historialSesion = Traza_Historial_Sesion::select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')->
                    orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $historialSesion = Traza_Historial_Sesion::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $historialSesion = Traza_Historial_Sesion::join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $historialSesion = Traza_Historial_Sesion::join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $historialSesion = Traza_Historial_Sesion::join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $historialSesion = Traza_Historial_Sesion::join('trazas.acciones', 'acciones.id', '=', 'historial_sesion.id_accion')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')->orderBy('created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $historialSesion = Traza_Historial_Sesion::select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')->
                    orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $historialSesion = Traza_Historial_Sesion::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $historialSesion = Traza_Historial_Sesion::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('trazas.historialSesion', compact('historialSesion', 'user', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function indexUsuarios(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_User::query();
            if($request->fecha_inicio != null && $request->fecha_fin != null)    
            {
                $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
            }
            if($request->id_accion != null)
            {
                $queryBuilder->Where('id_accion', $request->id_accion);
            }
            if($request->id_usuario != null)
            {
                $queryBuilder->Where('id_user', $request->id_usuario);
            }
            $data = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if(isset($request->buscador) && is_numeric($request->buscador)) {
                if($request->tipo_busqueda == 'cedula'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('users.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('users.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_User::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('users.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('users.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('users.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->join('trazas.acciones', 'acciones.id', '=', 'users.id_accion')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('users.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('users.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_User::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_User::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Usuarios';
        $route = 'traza_user';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showUsuarios(Traza_User $data)
    {
        $title = 'Detallado de Trazas de Usuarios';
        return view('trazas.show', compact('data', 'title'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function indexRoles(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Roles::query();
            if($request->fecha_inicio != null && $request->fecha_fin != null)    
            {
                $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
            }
            if($request->id_accion != null)
            {
                $queryBuilder->Where('id_accion', $request->id_accion);
            }
            if($request->id_usuario != null)
            {
                $queryBuilder->Where('id_user', $request->id_usuario);
            }
            $data = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if(isset($request->buscador) && is_numeric($request->buscador)) {
                if($request->tipo_busqueda == 'cedula'){
                    $data = Traza_Roles::join('users AS usr', 'usr.id', '=', 'roles.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->Where('persons.cedula', '=', $request->buscador)
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Roles::select('roles.id', 'roles.id_user', 'roles.id_accion', 'roles.valores_modificados', 'roles.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Roles::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Roles::join('users AS usr', 'usr.id', '=', 'roles.id_user')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Roles::join('users AS usr', 'usr.id', '=', 'roles.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Roles::join('users AS usr', 'usr.id', '=', 'roles.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Roles::join('trazas.acciones', 'acciones.id', '=', 'roles.id_accion')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Roles::select('roles.id', 'roles.id_user', 'roles.id_accion', 'roles.valores_modificados', 'roles.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Roles::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Roles::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Roles';
        $route = 'traza_roles';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showRoles(Traza_Roles $data)
    {
        $title = 'Detallado de Trazas de Roles';
        return view('trazas.show', compact('data', 'title'));
    }

    public function indexSesiones(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Sessions::query();
            if($request->fecha_inicio != null && $request->fecha_fin != null)    
            {
                $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
            }
            if($request->id_accion != null)
            {
                $queryBuilder->Where('id_accion', $request->id_accion);
            }
            if($request->id_usuario != null)
            {
                $queryBuilder->Where('id_user', $request->id_usuario);
            }
            $data = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if(isset($request->buscador) && is_numeric($request->buscador))
            {
                if($request->tipo_busqueda == 'cedula'){
                    $data = Traza_Sessions::join('users AS usr', 'usr.id', '=', 'sessions.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Sessions::select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Sessions::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Sessions::join('users AS usr', 'usr.id', '=', 'sessions.id_user')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Sessions::join('users AS usr', 'usr.id', '=', 'sessions.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Sessions::join('users AS usr', 'usr.id', '=', 'sessions.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Sessions::join('trazas.acciones', 'acciones.id', '=', 'sessions.id_accion')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Sessions::select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Sessions::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Sessions::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Sesiones';
        $route = 'traza_sesiones';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showSesiones(Traza_Sessions $data)
    {
        $title = 'Detallado de Trazas de Sesiones';
        return view('trazas.show', compact('data', 'title'));
    }

    public function indexPermisos(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Permisos::query();
            if($request->fecha_inicio != null && $request->fecha_fin != null)    
            {
                $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
            }
            if($request->id_accion != null)
            {
                $queryBuilder->Where('id_accion', $request->id_accion);
            }
            if($request->id_usuario != null)
            {
                $queryBuilder->Where('id_user', $request->id_usuario);
            }
            $data = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if(isset($request->buscador) && is_numeric($request->buscador))
            {
                if($request->tipo_busqueda == 'cedula'){
                    $data = Traza_Permisos::join('users AS usr', 'usr.id', '=', 'permisos.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Permisos::select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Permisos::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Permisos::join('users AS usr', 'usr.id', '=', 'permisos.id_user')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Permisos::join('users AS usr', 'usr.id', '=', 'permisos.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Permisos::join('users AS usr', 'usr.id', '=', 'permisos.id_user')
                    ->join('persons', 'persons.id', '=', 'usr.id_person')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Permisos::join('trazas.acciones', 'acciones.id', '=', 'permisos.id_accion')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Permisos::select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Permisos::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Permisos::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Permisos';
        $route = 'traza_permisos';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showPermisos(Traza_Permisos $data)
    {
        $title = 'Detallado de Trazas de Permisos';
        return view('trazas.show', compact('data', 'title'));
    }
    
    public function index_empresas(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_Empresas::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                }
                if($request->id_accion != null)
                {
                    $queryBuilder->Where('id_accion', $request->id_accion);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $data = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if(isset($request->buscador) && is_numeric($request->buscador))
            {
                if($request->tipo_busqueda == 'cedula'){
                    $data = Traza_Empresas::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Empresas::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Empresas::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Empresas::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Empresas::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Empresas::join('traza_acciones', 'traza_acciones.id', '=', 'trazas_organismos.id_accion')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')->orderBy('created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Empresas::select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')->
                    orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Empresas::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Empresas::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Empresas';
        $route = 'Traza_Empresas';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function show_empresas(Traza_Empresas $data)
    {
        $title = 'Detallado de Trazas de Empresas';
        return view('trazas.show', compact('data', 'title'));
    }

    
    public function index_historial_tokens(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Token_Historial::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null && $request->tipo_filtro != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    if($request->tipo_filtro == 'creacion'){
                        $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                    }else if($request->tipo_filtro == 'expiracion'){
                        $queryBuilder->WhereBetween('expires_at', [$inicio, $fin]);
                    }else if($request->tipo_filtro == 'ultimo_uso'){
                        $queryBuilder->WhereBetween('last_used_at', [$inicio, $fin]);
                    }
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $historial_token = $queryBuilder->orderBy('updated_at', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'nombreEmpresa'){
                $historial_token = Token_Historial::join('empresas', 'empresas.id', '=', 'token_historial.id_empresa')
                ->Where('empresas.Nombre', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_empresa', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'departamentoEmpresa'){
                $historial_token = Token_Historial::join('empresas', 'empresas.id', '=', 'token_historial.id_empresa')
                ->Where('empresas.Organismo', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_empresa', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'ministerio'){
                $historial_token = Token_Historial::join('empresas', 'empresas.id', '=', 'token_historial.id_empresa')
                ->Where('empresas.Ministerio', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_empresa', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'token'){
                $historial_token = Token_Historial::join('empresas', 'empresas.id', '=', 'token_historial.id_empresa')
                ->Where('token_historial.token', '=', $request->buscador)->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_empresa', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else{
                $historial_token = Token_Historial::orderBy('token_historial.created_at', 'DESC')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Historial de Token';
        $route = 'traza_historial_tokens';
        return view('trazas.historialToken', compact('historial_token', 'user', 'accion'));
    }
    
    public function show_historial_tokens(Token_Historial $historial_token)
    {
        return view('trazas.forms.historialTokenShow', compact('historial_token'));
    }

    public function index_tokens(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_Token::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
                }
                if($request->id_accion != null)
                {
                    $queryBuilder->Where('id_accion', $request->id_accion);
                }
                if($request->id_usuario != null)
                {
                    $queryBuilder->Where('id_user', $request->id_usuario);
                }
                $data = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if(isset($request->buscador) && is_numeric($request->buscador))
            {
                if($request->tipo_busqueda == 'cedula'){
                    $data = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_token.created_at', 'desc')->paginate(10);
                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_token::orderBy('created_at', 'desc')->paginate(10);
                }

            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                    ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('trazas_token.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_token::join('traza_acciones', 'traza_acciones.id', '=', 'trazas_token.id_accion')
                    ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_token::select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_token::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_token::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Tokens';
        $route = 'traza_tokens';

        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function show_tokens(Traza_token $data)
    {
        $title = 'Detallado de Trazas de Tokens';
        return view('trazas.show', compact('data', 'title'));
    }

    public function index_api(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_API::query();
                if($request->fecha_inicio != null && $request->fecha_fin != null)    
                {
                    $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                    $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                    $queryBuilder->WhereBetween('fecha_request', [$inicio, $fin]);
                }
                if($request->action != null)
                {
                    $queryBuilder->Where('action', $request->action);
                }
                if($request->usuario != null)
                {
                    $queryBuilder->Where('usuario', $request->usuario);
                }
                if($request->nombreEmpresa != null)
                {
                    $queryBuilder->Where('nombre', $request->nombreEmpresa);
                }
                if($request->departamentoEmpresa != null)
                {
                    $queryBuilder->Where('departamento', $request->departamentoEmpresa);
                }
                $apis = $queryBuilder->orderBy('fecha_request', 'desc')->paginate(10);
        }else{

            if($request->tipo_busqueda == 'consulta'){
                $apis = Traza_API::Where('request', '=', $request->buscador)->orderBy('trazas_api.created_at', 'desc')->paginate(10);

            }else if($request->tipo_busqueda == 'token'){
                $apis = Traza_API::Where('token', '=', $request->buscador)->orderBy('trazas_api.created_at', 'desc')->paginate(10);

            }else{
                $apis = Traza_API::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = $this->pluckServicios();
        $user = User::pluck('users', 'id')->all();
        $nombreEmpresa = Empresas::pluck('nombre', 'nombre')->all();
        $departamentoEmpresa = Empresas::pluck('departamento', 'departamento')->all();

        return view('trazas.api', compact('apis', 'user', 'accion', 'nombreEmpresa', 'departamentoEmpresa'));
    }

    public function show_api(Traza_API $apis)
    {
        return view('trazas.forms.apishow', compact('apis'));
    }

    public function index_servicios(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Servicios::query();
            if($request->fecha_inicio != null && $request->fecha_fin != null)    
            {
                $inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
                $fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.' 23:59:59'));
                $queryBuilder->WhereBetween('created_at', [$inicio, $fin]);
            }
            if($request->id_accion != null)
            {
                $queryBuilder->Where('id_accion', $request->id_accion);
            }
            if($request->id_usuario != null)
            {
                $queryBuilder->Where('id_user', $request->id_usuario);
            }
            $data = $queryBuilder->orderBy('created_at', 'desc')->paginate(10);
        }else{

            if(isset($request->buscador) && is_numeric($request->buscador))
            {
                if($request->tipo_busqueda == 'cedula'){
                    $data = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Servicios::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                    ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                    ->join('persons', 'persons.id', '=', 'users.id_person')
                    ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Servicios::join('traza_acciones', 'traza_acciones.id', '=', 'trazas_servicios.id_accion')
                    ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Servicios::select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Servicios::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Servicios::orderBy('created_at', 'desc')->paginate(10);
            }

        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Servicios';
        $route = 'traza_servicios';

        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function show_servicios(Traza_Servicios $data)
    {
        $title = 'Detallado de Trazas de Servicios';
        return view('trazas.show', compact('data', 'title'));
    }
}
