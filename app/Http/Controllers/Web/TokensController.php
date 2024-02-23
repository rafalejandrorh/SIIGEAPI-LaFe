<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alert;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Empresas;
use App\Models\Token_Empresas;
use App\Models\Token_Historial;
use App\Traits\TokenJWTTrait;
use DateTime;
use Illuminate\Support\Facades\Auth;

class TokensController extends Controller
{
    use TokenJWTTrait;

    private $token_empresas;
    private $empresas;
    private $token_historial;

    function __construct(Token_Empresas $token_empresas, Empresas $empresas, Token_Historial $token_historial)
    {
        // $this->middleware('can:tokens.index')->only('index');
        // $this->middleware('can:tokens.create')->only('create');
        // $this->middleware('can:tokens.show')->only('show');
        // $this->middleware('can:tokens.edit')->only('edit', 'update');
        // $this->middleware('can:tokens.update_status')->only('update_status');
        $this->token_empresas = $token_empresas;
        $this->token_historial = $token_historial;
        $this->empresas = $empresas;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();

        if($request->tipo_busqueda == 'empresa') {
            $tokens = $this->token_empresas->join('empresas', 'empresas.id', '=', 'token_empresas.id_empresa')
            ->Where('empresas.Nombre', 'ilike', '%'.$request->buscador.'%')->paginate(10);
        }else{
            $tokens = $this->token_empresas->paginate(10);
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
        $empresas = $this->empresas->orderBy('nombre', 'asc')
        ->select('empresas.id', 'empresas.nombre', 'empresas.departamento')
        ->leftjoin('token_empresas', 'token_empresas.id_empresa', '=', 'empresas.id')
        ->where('token_empresas.id_empresa', '=', null)
        ->get();
        $fecha_hoy = date('Y-m-d');

        return view('tokens.create', compact('fecha_hoy', 'empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $existsEmpresa = $this->token_empresas->where('id_empresa', $request->id_empresa)->exists();
        if($existsEmpresa) {
            Alert()->error('Esta Empresa ya posee un Token Asignado');
            return redirect()->route('tokens.index');
        }else{

            $JWT = $this->generateTokenJWT($request->duracion_token, $request->id_empresa);
            $fecha_expire = date('Y-m-d H:i:s', $this->time_expire);
            $fecha_created = date('Y-m-d H:i:s', $this->time);

            $this->token_empresas->create([
                'id_empresa' => $request->id_empresa, 
                'token' => $JWT, 
                'created_at' => $fecha_created, 
                'expired_at' => $fecha_expire,
                'duracion_token' => $request->duracion_token,
                'estatus' => true
            ]);

            $token = $this->empresas->Where('id', $request->id_empresa)->first();
            $empresa = $token['nombre'];
            $departamento = $token['departamento'];

            $id_user = Auth::user()->id;
            $id_Accion = Constants::REGISTRO;
            $valores_modificados = 'Datos del Token - Fecha de generacion: '.$fecha_created.' || Fecha de Expiración: '.$fecha_expire.
            ' || Duración del Token(días): '.$request->duracion_token.' || Token: '.$JWT.
            ' || Empresa: '.$empresa.' || Departamento: '.$departamento;
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
    public function show(token_empresas $token)
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
    public function edit(token_empresas $token)
    {
        $empresas = $this->empresas->orderBy('nombre', 'asc')->select('id', 'nombre', 'departamento')->get();
        $fecha_hoy = new DateTime();
        $generacion = new DateTime($token->created_at);
        $expiracion = new DateTime($token->expired_at);
        $ultimo = new DateTime($token->last_used_at);
        $fecha_generacion = $fecha_hoy->diff($generacion)->days;
        $fecha_expiracion = $fecha_hoy->diff($expiracion)->days;
        $ultimo_uso = $fecha_hoy->diff($ultimo)->h;
        $edit = true;

        return view('tokens.edit', compact('token', 'empresas', 'fecha_generacion', 'fecha_expiracion', 'ultimo_uso', 'edit'));
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
        $token_actual = $this->token_empresas->Where('id', $id)->select('id_empresa', 'token', 'created_at', 'expired_at', 'last_used_at', 'duracion_token')->first();
        $this->token_historial->create([
            'id_empresa' => $token_actual['id_empresa'],
            'token' => $token_actual['token'],
            'created_at' => $token_actual['created_at'],
            'expired_at' => $token_actual['expired_at'],
            'last_used_at' => $token_actual['last_used_at'], 
            'duracion_token' => $token_actual['duracion_token']
        ]);

        $JWT = $this->generateTokenJWT($request->duracion_token, $token_actual['id_empresa']);
        $fecha_expire = date('Y-m-d H:i:s', $this->time_expire);
        $fecha_created = date('Y-m-d H:i:s', $this->time);

        $tokens = $this->token_empresas->find($id, ['id']);
        $tokens->update([
            'token' => $JWT,
            'duracion_token' => $request->duracion_token,
            'created_at' => $fecha_created,
            'expired_at' => $fecha_expire,
            'last_used_at' => null
        ]);

        $token = $this->empresas->Where('id', $token_actual['id_empresa'])->first();
        $empresa = $token['nombre'];
        $departamento = $token['departamento'];

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION;
        $valores_modificados = 'Datos del Token - Fecha de generacion: '.$fecha_created.' || Fecha de Expiración: '.$fecha_expire.
        ' || Duración del Token(días): '.$request->duracion_token.' || Token: '.$JWT.
        ' || Empresa: '.$empresa.' || Departamento: '.$departamento;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Token'));

        Alert()->success('Token Actualizado Satisfactoriamente','Su Token es: '.$JWT.'  ||  Su Token expirará el: '.$fecha_expire);
        return redirect()->route('tokens.index');
    }

    public function update_status($id)
    {
        $token = $this->token_empresas->Where('id', $id)->first();
        $status = $token['estatus'];
        $id_empresa = $token['id_empresa'];

        if($status == true) {
            $estatus = false;
            $notificacion = 'Inactivo';
            $estatus_previo = 'Activo';
        }else{
            $estatus = true;
            $notificacion = 'Activo';
            $estatus_previo = 'Inactivo';
        }
        $tokens = $this->token_empresas->find($id, ['id']);
        $tokens->update(['estatus' => $estatus]);

        $token = $this->empresas->Where('id', $id_empresa)->first();
        $empresa = $token['nombre'];
        $departamento = $token['departamento'];

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION;
        $valores_modificados = 'Datos del Token - Estatus previo: '.$estatus_previo.' || Estatus nuevo: '.$notificacion.
        ' || Token: '.$token.' || Empresa: '.$empresa.' || Departamento: '.$departamento;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Token'));

        Alert()->success('Estatus de Token Actualizado', 'Nuevo Estatus: '.$notificacion);
        return redirect()->route('tokens.index');
    }
}
