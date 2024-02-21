<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dependencias;
use App\Models\Token_Historial;
use Illuminate\Http\Request;
use App\Models\Traza_Acciones;
use App\Models\Traza_API;
use App\Models\Traza_Apk_Movil;
use App\Models\Traza_Backup;
use App\Models\Traza_Bitacora_Llamadas;
use App\Models\Traza_Dependencias;
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
        $this->middleware('can:trazas.index')->only('index', 
        'indexUsuarios', 'indexResenna', 'indexFuncionarios', 
        'indexHistorialSesion', 'indexRoles', 'indexSesiones', 'indexPermisos', 'indexRutasAlmacenamiento',
        'showResenna', 'showUsuarios', 'showFuncionarios', 'showRoles', 'showSesiones', 'showPermisos', 'showRutasAlmacenamiento');
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $historialSesion = Traza_Historial_Sesion::join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('historial_sesion.created_at', 'desc')->paginate(10);
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $historialSesion = Traza_Historial_Sesion::join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('historial_sesion.id', 'historial_sesion.id_user', 'historial_sesion.id_accion', 'historial_sesion.valores_modificados', 'historial_sesion.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('historial_sesion.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $historialSesion = Traza_Historial_Sesion::join('users AS usr', 'usr.id', '=', 'historial_sesion.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('historial_sesion.created_at', 'DESC')->paginate(10);

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexResenna(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Resenna::query();
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
                    $data = Traza_Resenna::join('users AS usr', 'usr.id', '=', 'resenna.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('resenna.id', 'resenna.id_user', 'resenna.id_accion', 'resenna.valores_modificados', 'resenna.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('resenna.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Resenna::join('users AS usr', 'usr.id', '=', 'resenna.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('resenna.id', 'resenna.id_user', 'resenna.id_accion', 'resenna.valores_modificados', 'resenna.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('resenna.created_at', 'desc')->paginate(10);
                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Resenna::select('resenna.id', 'resenna.id_user', 'resenna.id_accion', 'resenna.valores_modificados', 'resenna.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')->
                    orderBy('resenna.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Resenna::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Resenna::join('users AS usr', 'usr.id', '=', 'resenna.id_user')
                    ->select('resenna.id', 'resenna.id_user', 'resenna.id_accion', 'resenna.valores_modificados', 'resenna.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('resenna.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Resenna::join('users AS usr', 'usr.id', '=', 'resenna.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('resenna.id', 'resenna.id_user', 'resenna.id_accion', 'resenna.valores_modificados', 'resenna.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('resenna.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Resenna::join('users AS usr', 'usr.id', '=', 'resenna.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('resenna.id', 'resenna.id_user', 'resenna.id_accion', 'resenna.valores_modificados', 'resenna.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('resenna.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Resenna::join('users AS usr', 'usr.id', '=', 'resenna.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('resenna.created_at', 'DESC')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Resenna::join('trazas.acciones', 'acciones.id', '=', 'resenna.id_accion')
                    ->select('resenna.id', 'resenna.id_user', 'resenna.id_accion', 'resenna.valores_modificados', 'resenna.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')->orderBy('created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Resenna::select('resenna.id', 'resenna.id_user', 'resenna.id_accion', 'resenna.valores_modificados', 'resenna.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')->
                    orderBy('resenna.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Resenna::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Resenna::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Reseñas';
        $route = 'traza_resenna';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showResenna(Traza_Resenna $data)
    {
        $title = 'Detallado de Trazas de Reseñas';
        return view('trazas.show', compact('data', 'title'));
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('users.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('users.created_at', 'desc')->paginate(10);
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('users.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('users.id', 'users.id_user', 'users.id_accion', 'users.valores_modificados', 'users.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('users.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_User::join('users AS usr', 'usr.id', '=', 'users.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('users.created_at', 'DESC')->paginate(10);

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function indexFuncionarios(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Funcionarios::query();
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
                    $data = Traza_Funcionarios::join('users AS usr', 'usr.id', '=', 'funcionarios.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funs.id_person')
                    ->select('funcionarios.id', 'funcionarios.id_user', 'funcionarios.id_accion', 'funcionarios.valores_modificados', 'funcionarios.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)
                    ->orderBy('funcionarios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Funcionarios::join('users AS usr', 'usr.id', '=', 'funcionarios.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->select('funcionarios.id', 'funcionarios.id_user', 'funcionarios.id_accion', 'funcionarios.valores_modificados', 'funcionarios.created_at')
                    ->Where('funs.credencial', '=', $request->buscador)
                    ->orderBy('funcionarios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Funcionarios::select('funcionarios.id', 'funcionarios.id_user', 'funcionarios.id_accion', 'funcionarios.valores_modificados', 'funcionarios.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('funcionarios.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Funcionarios::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Funcionarios::join('users AS usr', 'usr.id', '=', 'funcionarios.id_user')
                    ->select('funcionarios.id', 'funcionarios.id_user', 'funcionarios.id_accion', 'funcionarios.valores_modificados', 'funcionarios.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('funcionarios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Funcionarios::join('users AS usr', 'usr.id', '=', 'funcionarios.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funs.id_person')
                    ->select('funcionarios.id', 'funcionarios.id_user', 'funcionarios.id_accion', 'funcionarios.valores_modificados', 'funcionarios.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('funcionarios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Funcionarios::join('users AS usr', 'usr.id', '=', 'funcionarios.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funs.id_person')
                    ->select('funcionarios.id', 'funcionarios.id_user', 'funcionarios.id_accion', 'funcionarios.valores_modificados', 'funcionarios.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('funcionarios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Funcionarios::join('users AS usr', 'usr.id', '=', 'funcionarios.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funs.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('funcionarios.created_at', 'DESC')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Funcionarios::join('trazas.acciones', 'acciones.id', '=', 'funcionarios.id_accion')
                    ->select('funcionarios.id', 'funcionarios.id_user', 'funcionarios.id_accion', 'funcionarios.valores_modificados', 'funcionarios.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('funcionarios.created_at', 'desc')->paginate(10);
                    
                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Funcionarios::select('funcionarios.id', 'funcionarios.id_user', 'funcionarios.id_accion', 'funcionarios.valores_modificados', 'funcionarios.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('funcionarios.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Funcionarios::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Funcionarios::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Funcionarios';
        $route = 'traza_funcionarios';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showFuncionarios(Traza_Funcionarios $data)
    {
        $title = 'Detallado de Trazas de Funcionarios';
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->Where('persons.cedula', '=', $request->buscador)
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Roles::join('users AS usr', 'usr.id', '=', 'roles.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->Where('funcionarios.credencial', '=', $request->buscador)
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Roles::join('users AS usr', 'usr.id', '=', 'roles.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Roles::join('users AS usr', 'usr.id', '=', 'roles.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funs.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('roles.created_at', 'DESC')->paginate(10);

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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Sessions::join('users AS usr', 'usr.id', '=', 'sessions.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('sessions.created_at', 'desc')->paginate(10);
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Sessions::join('users AS usr', 'usr.id', '=', 'sessions.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('sessions.id', 'sessions.id_user', 'sessions.id_accion', 'sessions.valores_modificados', 'sessions.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('sessions.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Sessions::join('users AS usr', 'usr.id', '=', 'sessions.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funs.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('sessions.created_at', 'DESC')->paginate(10);

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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Permisos::join('users AS usr', 'usr.id', '=', 'permisos.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('permisos.created_at', 'desc')->paginate(10);
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Permisos::join('users AS usr', 'usr.id', '=', 'permisos.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('permisos.id', 'permisos.id_user', 'permisos.id_accion', 'permisos.valores_modificados', 'permisos.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('permisos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Permisos::join('users AS usr', 'usr.id', '=', 'permisos.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funs.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('permisos.created_at', 'DESC')->paginate(10);

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

    public function indexRutasAlmacenamiento(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Rutas_Almacenamiento::query();
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
                    $data = Traza_Rutas_Almacenamiento::join('users AS usr', 'usr.id', '=', 'rutas_almacenamiento.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('rutas_almacenamiento.id', 'rutas_almacenamiento.id_user', 'rutas_almacenamiento.id_accion', 'rutas_almacenamiento.valores_modificados', 'rutas_almacenamiento.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('rutas_almacenamiento.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Rutas_Almacenamiento::join('users AS usr', 'usr.id', '=', 'rutas_almacenamiento.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('rutas_almacenamiento.id', 'rutas_almacenamiento.id_user', 'rutas_almacenamiento.id_accion', 'rutas_almacenamiento.valores_modificados', 'rutas_almacenamiento.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('rutas_almacenamiento.created_at', 'desc')->paginate(10);
                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Rutas_Almacenamiento::select('rutas_almacenamiento.id', 'rutas_almacenamiento.id_user', 'rutas_almacenamiento.id_accion', 'rutas_almacenamiento.valores_modificados', 'rutas_almacenamiento.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('rutas_almacenamiento.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Rutas_Almacenamiento::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Rutas_Almacenamiento::join('users AS usr', 'usr.id', '=', 'rutas_almacenamiento.id_user')
                    ->select('rutas_almacenamiento.id', 'rutas_almacenamiento.id_user', 'rutas_almacenamiento.id_accion', 'rutas_almacenamiento.valores_modificados', 'rutas_almacenamiento.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('rutas_almacenamiento.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Rutas_Almacenamiento::join('users AS usr', 'usr.id', '=', 'rutas_almacenamiento.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('rutas_almacenamiento.id', 'rutas_almacenamiento.id_user', 'rutas_almacenamiento.id_accion', 'rutas_almacenamiento.valores_modificados', 'rutas_almacenamiento.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('rutas_almacenamiento.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Rutas_Almacenamiento::join('users AS usr', 'usr.id', '=', 'rutas_almacenamiento.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('rutas_almacenamiento.id', 'rutas_almacenamiento.id_user', 'rutas_almacenamiento.id_accion', 'rutas_almacenamiento.valores_modificados', 'rutas_almacenamiento.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('rutas_almacenamiento.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Rutas_Almacenamiento::join('users AS usr', 'usr.id', '=', 'rutas_almacenamiento.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funs.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('rutas_almacenamiento.created_at', 'DESC')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Rutas_Almacenamiento::join('trazas.acciones', 'acciones.id', '=', 'rutas_almacenamiento.id_accion')
                    ->select('rutas_almacenamiento.id', 'rutas_almacenamiento.id_user', 'rutas_almacenamiento.id_accion', 'rutas_almacenamiento.valores_modificados', 'rutas_almacenamiento.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('rutas_almacenamiento.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Rutas_Almacenamiento::select('rutas_almacenamiento.id', 'rutas_almacenamiento.id_user', 'rutas_almacenamiento.id_accion', 'rutas_almacenamiento.valores_modificados', 'rutas_almacenamiento.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('rutas_almacenamiento.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Rutas_Almacenamiento::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Rutas_Almacenamiento::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Rutas de Almacenamiento';
        $route = 'traza_rutasAlmacenamiento';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showRutasAlmacenamiento(Traza_Rutas_Almacenamiento $data)
    {
        $title = 'Detallado de Trazas de Rutas de Almacenamiento';
        return view('trazas.show', compact('data', 'title'));
    }

    public function indexBackup(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Backup::query();
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
                    $data = Traza_Backup::join('users AS usr', 'usr.id', '=', 'backup.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('backup.id', 'backup.id_user', 'backup.id_accion', 'backup.valores_modificados', 'backup.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('backup.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Backup::join('users AS usr', 'usr.id', '=', 'backup.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('backup.id', 'backup.id_user', 'backup.id_accion', 'backup.valores_modificados', 'backup.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('backup.created_at', 'desc')->paginate(10);
                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Backup::select('backup.id', 'backup.id_user', 'backup.id_accion', 'backup.valores_modificados', 'backup.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('backup.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Backup::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Backup::join('users AS usr', 'usr.id', '=', 'backup.id_user')
                    ->select('backup.id', 'backup.id_user', 'backup.id_accion', 'backup.valores_modificados', 'backup.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('backup.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Backup::join('users AS usr', 'usr.id', '=', 'backup.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('backup.id', 'backup.id_user', 'backup.id_accion', 'backup.valores_modificados', 'backup.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('backup.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Backup::join('users AS usr', 'usr.id', '=', 'backup.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('backup.id', 'backup.id_user', 'backup.id_accion', 'backup.valores_modificados', 'backup.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('backup.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Backup::join('users AS usr', 'usr.id', '=', 'backup.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funs.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('backup.created_at', 'DESC')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Backup::join('trazas.acciones', 'acciones.id', '=', 'backup.id_accion')
                    ->select('backup.id', 'backup.id_user', 'backup.id_accion', 'backup.valores_modificados', 'backup.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('backup.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Backup::select('backup.id', 'backup.id_user', 'backup.id_accion', 'backup.valores_modificados', 'backup.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('backup.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Backup::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Backup::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Backup';
        $route = 'traza_backup';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showBackup(Traza_Backup $data)
    {
        $title = 'Detallado de Trazas de Backup';
        return view('trazas.show', compact('data', 'title'));
    }

    public function indexApk(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
            if($request->fecha_inicio != null && $request->fecha_fin == null)
            {
                Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                return back();
            }
            $queryBuilder = Traza_Apk_Movil::query();
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
                    $data = Traza_Apk_Movil::join('users AS usr', 'usr.id', '=', 'apk.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('apk.id', 'apk.id_user', 'apk.id_accion', 'apk.valores_modificados', 'apk.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('apk.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Apk_Movil::join('users AS usr', 'usr.id', '=', 'apk.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('apk.id', 'apk.id_user', 'apk.id_accion', 'apk.valores_modificados', 'apk.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('apk.created_at', 'desc')->paginate(10);
                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Apk_Movil::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Apk_Movil::join('users AS usr', 'usr.id', '=', 'apk.id_user')
                    ->select('apk.id', 'apk.id_user', 'apk.id_accion', 'apk.valores_modificados', 'apk.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('apk.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Apk_Movil::join('users AS usr', 'usr.id', '=', 'apk.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('apk.id', 'apk.id_user', 'apk.id_accion', 'apk.valores_modificados', 'apk.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('apk.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Apk_Movil::join('users AS usr', 'usr.id', '=', 'apk.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('apk.id', 'apk.id_user', 'apk.id_accion', 'apk.valores_modificados', 'apk.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('apk.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Apk_Movil::join('users AS usr', 'usr.id', '=', 'apk.id_user')
                    ->join('funcionarios AS funs', 'funs.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funs.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('apk.created_at', 'DESC')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Apk_Movil::join('trazas.acciones', 'acciones.id', '=', 'apk.id_accion')
                    ->select('apk.id', 'apk.id_user', 'apk.id_accion', 'apk.valores_modificados', 'apk.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('apk.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Apk_Movil::select('apk.id', 'apk.id_user', 'apk.id_accion', 'apk.valores_modificados', 'apk.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('apk.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Apk_Movil::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Apk_Movil::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de APK';
        $route = 'traza_apk';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showApk(Traza_Apk_Movil $data)
    {
        $title = 'Detallado de Trazas de APK';
        return view('trazas.show', compact('data', 'title'));
    }

    public function indexBitacoraLlamadas(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1) {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_Bitacora_Llamadas::query();
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
                    $data = Traza_Bitacora_Llamadas::join('users AS usr', 'usr.id', '=', 'bitacora_llamadas.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('bitacora_llamadas.id', 'bitacora_llamadas.id_user', 'bitacora_llamadas.id_accion', 'bitacora_llamadas.valores_modificados', 'bitacora_llamadas.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('bitacora_llamadas.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Bitacora_Llamadas::join('users AS usr', 'usr.id', '=', 'bitacora_llamadas.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->select('bitacora_llamadas.id', 'bitacora_llamadas.id_user', 'bitacora_llamadas.id_accion', 'bitacora_llamadas.valores_modificados', 'bitacora_llamadas.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('bitacora_llamadas.created_at', 'desc')->paginate(10);
                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Bitacora_Llamadas::select('bitacora_llamadas.id', 'bitacora_llamadas.id_user', 'bitacora_llamadas.id_accion', 'bitacora_llamadas.valores_modificados', 'bitacora_llamadas.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')->
                    orderBy('bitacora_llamadas.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Bitacora_Llamadas::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Bitacora_Llamadas::join('users AS usr', 'usr.id', '=', 'bitacora_llamadas.id_user')
                    ->select('bitacora_llamadas.id', 'bitacora_llamadas.id_user', 'bitacora_llamadas.id_accion', 'bitacora_llamadas.valores_modificados', 'bitacora_llamadas.created_at')
                    ->Where('users', 'ilike', '%'.$request->buscador.'%')->orderBy('bitacora_llamadas.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Bitacora_Llamadas::join('users AS usr', 'usr.id', '=', 'bitacora_llamadas.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('bitacora_llamadas.id', 'bitacora_llamadas.id_user', 'bitacora_llamadas.id_accion', 'bitacora_llamadas.valores_modificados', 'bitacora_llamadas.created_at')
                    ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('bitacora_llamadas.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Bitacora_Llamadas::join('users AS usr', 'usr.id', '=', 'bitacora_llamadas.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('bitacora_llamadas.id', 'bitacora_llamadas.id_user', 'bitacora_llamadas.id_accion', 'bitacora_llamadas.valores_modificados', 'bitacora_llamadas.created_at')
                    ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('bitacora_llamadas.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'jerarquia'){
                    $data = Traza_Bitacora_Llamadas::join('users AS usr', 'usr.id', '=', 'bitacora_llamadas.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'usr.id_funcionario')
                    ->join('nomenclador.jerarquia', 'jerarquia.id', '=', 'funcionarios.id_jerarquia')
                    ->Where('jerarquia.valor', 'ilike', '%'.$request->buscador.'%')
                    ->orderBy('bitacora_llamadas.created_at', 'DESC')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Bitacora_Llamadas::join('trazas.acciones', 'acciones.id', '=', 'bitacora_llamadas.id_accion')
                    ->select('bitacora_llamadas.id', 'bitacora_llamadas.id_user', 'bitacora_llamadas.id_accion', 'bitacora_llamadas.valores_modificados', 'bitacora_llamadas.created_at')
                    ->Where('acciones.valor', 'ilike', '%'.$request->buscador.'%')->orderBy('created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Bitacora_Llamadas::select('bitacora_llamadas.id', 'bitacora_llamadas.id_user', 'bitacora_llamadas.id_accion', 'bitacora_llamadas.valores_modificados', 'bitacora_llamadas.created_at')
                    ->Where('valores_modificados', 'ilike', '%'.$request->buscador.'%')->
                    orderBy('bitacora_llamadas.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Bitacora_Llamadas::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Bitacora_Llamadas::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Bitácora de Llamadas';
        $route = 'traza_bitacoraLlamadas';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function showBitacoraLlamadas(Traza_Bitacora_Llamadas $data)
    {
        $title = 'Detallado de Trazas de Bitácora de Llamadas';
        return view('trazas.show', compact('data', 'title'));
    }

    
    public function index_dependencias(Request $request)
    {
        $request->all();
        if(isset($request->filtro) && $request->filtro == 1)
        {
                if($request->fecha_inicio != null && $request->fecha_fin == null)
                {
                    Alert()->error('Error en el Filtrado','Atención: Al filtrar por fecha, debes colocar fecha de Inicio y Fin (Desde y Hasta)');
                    return back();
                }
                $queryBuilder = Traza_Dependencias::query();
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
                    $data = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);
                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Dependencias::orderBy('created_at', 'desc')->paginate(10);
                }
            }else if(isset($request->buscador) && is_string($request->buscador)){

                if($request->tipo_busqueda == 'usuario'){
                    $data = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('users', 'LIKE', '%'.$request->buscador.'%')->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'nombre'){
                    $data = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Dependencias::join('users', 'users.id', '=', 'trazas_organismos.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('persons.primer_apellido', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'accion'){
                    $data = Traza_Dependencias::join('traza_acciones', 'traza_acciones.id', '=', 'trazas_organismos.id_accion')
                    ->select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('traza_acciones.valor', 'LIKE', '%'.$request->buscador.'%')->orderBy('created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'valores_modificados'){
                    $data = Traza_Dependencias::select('trazas_organismos.id', 'trazas_organismos.id_user', 'trazas_organismos.id_accion', 'trazas_organismos.valores_modificados', 'trazas_organismos.created_at')
                    ->Where('valores_modificados', 'LIKE', '%'.$request->buscador.'%')->
                    orderBy('trazas_organismos.created_at', 'desc')->paginate(10);

                }else{
                    Alert()->warning('Búsqueda no permitida');
                    $data = Traza_Dependencias::orderBy('created_at', 'desc')->paginate(10);
                }
            }else{
                $data = Traza_Dependencias::orderBy('created_at', 'desc')->paginate(10);
            }
        }

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();
        $title = 'Trazas de Dependencias';
        $route = 'traza_dependencias';
        return view('trazas.list', compact('data', 'user', 'accion', 'title', 'route'));
    }

    public function show_dependencias(Traza_Dependencias $data)
    {
        $title = 'Detallado de Trazas de Dependencias';
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

            if($request->tipo_busqueda == 'dependencia'){
                $historial_token = Token_Historial::join('dependencias', 'dependencias.id', '=', 'token_historial.id_dependencias')
                ->Where('dependencias.Nombre', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_dependencias', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'organismo'){
                $historial_token = Token_Historial::join('dependencias', 'dependencias.id', '=', 'token_historial.id_dependencias')
                ->Where('dependencias.Organismo', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_dependencias', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'ministerio'){
                $historial_token = Token_Historial::join('dependencias', 'dependencias.id', '=', 'token_historial.id_dependencias')
                ->Where('dependencias.Ministerio', 'LIKE', '%'.$request->buscador.'%')->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_dependencias', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
                'token_historial.last_used_at', 'token_historial.id')->paginate(10);

            }else if($request->tipo_busqueda == 'token'){
                $historial_token = Token_Historial::join('dependencias', 'dependencias.id', '=', 'token_historial.id_dependencias')
                ->Where('token_historial.token', '=', $request->buscador)->orderBy('token_historial.created_at', 'DESC')
                ->select('token_historial.id_dependencias', 'token_historial.token', 'token_historial.updated_at', 'token_historial.created_at', 'token_historial.expires_at',
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_token.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('trazas_token.created_at', 'desc')->paginate(10);
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('trazas_token.id', 'trazas_token.id_user', 'trazas_token.id_accion', 'trazas_token.valores_modificados', 'trazas_token.created_at')
                    ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_token.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_token::join('users', 'users.id', '=', 'trazas_token.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
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
                if($request->dependencia != null)
                {
                    $queryBuilder->Where('dependencia', $request->dependencia);
                }
                if($request->organismo != null)
                {
                    $queryBuilder->Where('organismo', $request->organismo);
                }
                if($request->ministerio != null)
                {
                    $queryBuilder->Where('ministerio', $request->ministerio);
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
        $dependencia = Dependencias::pluck('nombre', 'nombre')->all();
        $organismo = Dependencias::pluck('organismo', 'organismo')->all();
        $ministerio = Dependencias::pluck('ministerio', 'ministerio')->all();

        return view('trazas.api', compact('apis', 'user', 'accion', 'dependencia', 'organismo', 'ministerio'));
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('persons.cedula', '=', $request->buscador)->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'credencial'){
                    $data = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('funcionarios.credencial', '=', $request->buscador)->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);
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
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
                    ->select('trazas_servicios.id', 'trazas_servicios.id_user', 'trazas_servicios.id_accion', 'trazas_servicios.valores_modificados', 'trazas_servicios.created_at')
                    ->Where('persons.primer_nombre', 'LIKE', '%'.$request->buscador.'%')
                    ->orderBy('trazas_servicios.created_at', 'desc')->paginate(10);

                }else if($request->tipo_busqueda == 'apellido'){
                    $data = Traza_Servicios::join('users', 'users.id', '=', 'trazas_servicios.id_user')
                    ->join('funcionarios', 'funcionarios.id', '=', 'users.id_funcionario')
                    ->join('persons', 'persons.id', '=', 'funcionarios.id_person')
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
