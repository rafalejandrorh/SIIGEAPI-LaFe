<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Events\LogoutHistorialEvent;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Sessions;
use App\Traits\HistorialAccionesTrait;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    use HistorialAccionesTrait;

    private $sessions;

    function __construct(Sessions $sessions)
    {
        $this->middleware('can:sessions.index')->only('index');
        $this->middleware('can:sessions.destroy')->only('destroy');
        $this->sessions = $sessions;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sessions = $this->sessions->search($request);
        $id_user = Auth::user()->id;
        if(isset($request->tipo_busqueda) && isset($request->buscador))
        {
            $id_Accion = Constants::BUSQUEDA; 
            $valores_modificados = 'Tipo de Búsqueda: '.$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador;
        }else{
            $id_Accion = Constants::INGRESO_MODULO; 
            $valores_modificados = 'N/A';
        }
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Sessions'));

        // Mapa de Google para Georeferencias las Coordenadas del Lugar de Aprehensión
        // ROADMAP, TERRAIN o SATELLITE
        Mapper::map(10.216264, -66.859045, ['marker' => false, 'zoom' => 6.5, 'center' => true, 'type' => 'TERRAIN']);
        $i = 0;
        while($i < count($sessions))
        {
            if($sessions[$i]['coordinates'] != null)
            {
                $ex = explode(',', $sessions[$i]['coordinates']);
                $long = $ex[0];
                $lat = $ex[1];

                Mapper::marker($long, $lat, [
                    'animation' => 'DROP', 
                    'clickable' => true, 
                    'title' => $sessions[$i]['users'],
                    'content' => $sessions[$i]['users']
                ]); 
            }
            $i++;
        }

        $countSessions = $this->sessions->count();

        return view('sessions.index', compact('sessions', 'countSessions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sessions $session)
    {
        $id_user = Auth::user()->id;
        $id_Accion = Constants::VISUALIZACION; 
        $valores_modificados = 'Visualización de las Últimas Acciones del Usuario';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Sessions'));

        $data = $this->getHistorialAcciones($session->user_id);
        $session['session_id'] = $session->session_id;
        return view('sessions.show', compact('data', 'session'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->session == 'all')
        {
            $datoSesion = null;
            $sessions = $this->sessions->WhereNotNull('user_id')
            ->join('users', 'users.id', '=', 'sessions.user_id')
            ->select('users.users', 'sessions.ip_address')
            ->get();
            $i = 0;
            while($i < count($sessions))
            {
                $datoSesion .= $sessions[$i]['users'].' || '.$sessions[$i]['ip_address'].', ';
                $i++;
            }
        }else{
            $sessions = $this->sessions->Where('sessions.id', $request->session)
            ->join('users', 'users.id', '=', 'sessions.user_id')
            ->select('users.users', 'sessions.ip_address')
            ->first();
            $datoSesion = $sessions['users'].' || '.$sessions['ip_address'];
        }

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ELIMINACION; 
        $valores_modificados = 'Datos de Sesión: '.$datoSesion;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Sessions'));

        if($request->session == 'all')
        {
            $user = $this->sessions->WhereNotNull('user_id')->get();
            $session = $this->sessions->query()->delete();

            $i = 0;
            while($i < count($user))
            {
                event(new LogoutHistorialEvent(null, 3, $user[$i]['user_id']));
                $i++;
            } 
        }else{
            $session = $this->sessions->Where('id', $request->session);
            $user = $session->first();

            $session = $this->sessions->find($request->session, ['id']);
            $session->delete();

            event(new LogoutHistorialEvent(null, 3, $user['user_id']));
        }

        Alert()->success('La Sesión ha sido finalizada Exitosamente');
        return redirect()->route('sessions.index');
    }
}
