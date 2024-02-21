<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Token_Dependencias;
use App\Models\Traza_Token;
use App\Models\Dependencias;
use Illuminate\Http\Request;
use Alert;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Token_Historial;
use App\Traits\TokenJWTTrait;
use DateTime;
use Illuminate\Support\Facades\Auth;

class TokensController extends Controller
{
    use TokenJWTTrait;

    private $token_dependencias;
    private $dependencias;
    private $token_historial;

    function __construct(Token_Dependencias $token_dependencias, Dependencias $dependencias, Token_Historial $token_historial)
    {
        $this->middleware('can:tokens.index')->only('index');
        $this->middleware('can:tokens.create')->only('create');
        $this->middleware('can:tokens.show')->only('show');
        $this->middleware('can:tokens.edit')->only('edit', 'update');
        $this->middleware('can:tokens.update_status')->only('update_status');
        $this->token_dependencias = $token_dependencias;
        $this->token_historial = $token_historial;
        $this->dependencias = $dependencias;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();

        if($request->tipo_busqueda == 'dependencia') {
            $tokens = $this->token_dependencias->join('dependencias', 'dependencias.id', '=', 'token_dependencias.id_dependencia')
            ->Where('dependencias.Nombre', 'ilike', '%'.$request->buscador.'%')->paginate(10);
        }else{
            $tokens = $this->token_dependencias->paginate(10);
        }

        if(isset($request->tipo_busqueda) && isset($request->buscador)) {
            $id_user = Auth::user()->id;
            $id_Accion = Constants::BUSQUEDA;
            $valores_modificados = 'Tipo de Búsqueda: '.$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador;
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Token'));
        }
        
        return view('tokens.index', ['tokens' => $tokens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $dependencias = $this->dependencias->orderBy('nombre', 'asc')
        ->select('dependencias.id', 'dependencias.nombre', 'dependencias.ministerio', 'dependencias.organismo')
        ->leftjoin('token_dependencias', 'token_dependencias.id_dependencia', '=', 'dependencias.id')
        ->where('token_dependencias.id_dependencia', '=', null)
        ->get();
        $fecha_hoy = date('Y-m-d');

        return view('tokens.create', compact('fecha_hoy', 'dependencias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $existsDependencia = $this->token_dependencias->where('id_dependencia', $request->dependencia)->exists();
        if($existsDependencia) {
            Alert()->error('Esta Dependencia ya posee un Token Asignado');
            return redirect()->route('tokens.index');
        }else{

            $JWT = $this->generateTokenJWT($request->duracion_token, $request->dependencia);
            $fecha_expire = date('Y-m-d H:i:s', $this->time_expire);
            $fecha_created = date('Y-m-d H:i:s', $this->time);

            $this->token_dependencias->create([
                'id_dependencia' => $request->dependencia, 
                'token' => $JWT, 
                'created_at' => $fecha_created, 
                'expired_at' => $fecha_expire,
                'duracion_token' => $request->duracion_token,
                'estatus' => true
            ]);

            $token = $this->dependencias->Where('id', $request->dependencia)->first();
            $dependencia = $token['nombre'];
            $organismo = $token['organismo'];
            $ministerio = $token['ministerio'];

            $id_user = Auth::user()->id;
            $id_Accion = Constants::REGISTRO;
            $valores_modificados = 'Datos del Token - Fecha de generacion: '.$fecha_created.' || Fecha de Expiración: '.$fecha_expire.
            ' || Duración del Token(días): '.$request->duracion_token.' || Token: '.$JWT.
            ' || Dependencia: '.$dependencia.' || Organismo: '.$organismo.' || Ministerio: '.$ministerio;
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Token'));

            Alert()->success('Token Creado Satisfactoriamente','Su Token es: '.$JWT.'  ||  Su Token expirará el: '.$fecha_expire);
            return redirect()->route('tokens.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Token_Dependencias $token)
    {
        $fecha_hoy = new DateTime();
        $generacion = new DateTime($token->created_at);
        $expiracion = new DateTime($token->expired_at);
        $ultimo = new DateTime($token->last_used_at);
        $fecha_generacion = $fecha_hoy->diff($generacion)->days;
        $fecha_expiracion = $fecha_hoy->diff($expiracion)->days;
        $ultimo_uso = $fecha_hoy->diff($ultimo)->h;
        $edit = false;

        $id_user = Auth::user()->id;
        $id_Accion = Constants::VISUALIZACION;
        $valores_modificados = 'Datos del Token - Fecha de generacion: '.$token->created_at.' || Fecha de Expiración: '.$token->expired_at.
        ' || Fecha de Último Uso: '.$token->last_used_at.' || Duración del Token(días): '.$token->duracion_token.' || Token: '.$token->token.
        ' || Estatus del Token: '.$token->estatus ? 'Activo' : 'Inactivo';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Token'));

        return view('tokens.show', compact('token', 'fecha_generacion', 'fecha_expiracion', 'ultimo_uso', 'edit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Token_Dependencias $token)
    {
        $dependencias = $this->dependencias->orderBy('nombre', 'asc')->select('id', 'nombre', 'ministerio', 'organismo')->get();
        $fecha_hoy = new DateTime();
        $generacion = new DateTime($token->created_at);
        $expiracion = new DateTime($token->expired_at);
        $ultimo = new DateTime($token->last_used_at);
        $fecha_generacion = $fecha_hoy->diff($generacion)->days;
        $fecha_expiracion = $fecha_hoy->diff($expiracion)->days;
        $ultimo_uso = $fecha_hoy->diff($ultimo)->h;
        $edit = true;

        return view('tokens.edit', compact('token', 'dependencias', 'fecha_generacion', 'fecha_expiracion', 'ultimo_uso', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $token_actual = $this->token_dependencias->Where('id', $id)->select('id_dependencia', 'token', 'created_at', 'expired_at', 'last_used_at', 'duracion_token')->first();
        $this->token_historial->create([
            'id_dependencia' => $token_actual['id_dependencia'],
            'token' => $token_actual['token'],
            'created_at' => $token_actual['created_at'],
            'expired_at' => $token_actual['expired_at'],
            'last_used_at' => $token_actual['last_used_at'], 
            'duracion_token' => $token_actual['duracion_token']
        ]);

        $JWT = $this->generateTokenJWT($request->duracion_token, $token_actual['id_dependencia']);
        $fecha_expire = date('Y-m-d H:i:s', $this->time_expire);
        $fecha_created = date('Y-m-d H:i:s', $this->time);

        $tokens = $this->token_dependencias->find($id, ['id']);
        $tokens->update([
            'token' => $JWT,
            'duracion_token' => $request->duracion_token,
            'created_at' => $fecha_created,
            'expired_at' => $fecha_expire,
            'last_used_at' => null
        ]);

        $token = $this->dependencias->Where('id', $token_actual['id_dependencia'])->first();
        $dependencia = $token['nombre'];
        $organismo = $token['organismo'];
        $ministerio = $token['ministerio'];

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION;
        $valores_modificados = 'Datos del Token - Fecha de generacion: '.$fecha_created.' || Fecha de Expiración: '.$fecha_expire.
        ' || Duración del Token(días): '.$request->duracion_token.' || Token: '.$JWT.
        ' || Dependencia: '.$dependencia.' || Organismo: '.$organismo.' || Ministerio: '.$ministerio;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Token'));

        Alert()->success('Token Actualizado Satisfactoriamente','Su Token es: '.$JWT.'  ||  Su Token expirará el: '.$fecha_expire);
        return redirect()->route('tokens.index');
    }

    public function update_status($id)
    {
        $token = $this->token_dependencias->Where('id', $id)->first();
        $status = $token['estatus'];
        $id_dependencia = $token['id_dependencia'];

        if($status == true) {
            $estatus = false;
            $notificacion = 'Inactivo';
            $estatus_previo = 'Activo';
        }else{
            $estatus = true;
            $notificacion = 'Activo';
            $estatus_previo = 'Inactivo';
        }
        $tokens = $this->token_dependencias->find($id, ['id']);
        $tokens->update(['estatus' => $estatus]);

        $token = $this->dependencias->Where('id', $id_dependencia)->first();
        $dependencia = $token['nombre'];
        $organismo = $token['organismo'];
        $ministerio = $token['ministerio'];

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION;
        $valores_modificados = 'Datos del Token - Estatus previo: '.$estatus_previo.' || Estatus nuevo: '.$notificacion.
        ' || Token: '.$token.' || Dependencia: '.$dependencia.' || Organismo: '.$organismo.' || Ministerio: '.$ministerio;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Token'));

        Alert()->success('Estatus de Token Actualizado', 'Nuevo Estatus: '.$notificacion);
        return redirect()->route('tokens.index');
    }
}
