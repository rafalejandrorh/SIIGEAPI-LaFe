<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Events\TrazasEvent;
use App\Exports\Historial_SesionExport;
use App\Http\Constants;
use App\Models\Historial_Sesion;
use App\Models\Traza_Acciones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class HistorialSesionController extends Controller
{
    private $historial;

    function __construct(Historial_Sesion $historial_Sesion)
    {
        // $this->middleware('can:historialSesion.index')->only('index');
        // $this->middleware('can:historialSesion.excel')->only('exportExcel');
        $this->historial = $historial_Sesion;
    }

    public function index(Request $request)
    {
        $request->all();
        $historial_sesion[] = null;
        if(isset($request->filtro) && $request->filtro == 1)
        {
            $historial_sesion = $this->historial->filtro($request);
        }else{
            $historial_sesion = $this->historial->search($request);
        }

        $id_user = Auth::user()->id;
        if(isset($request->tipo_busqueda) && isset($request->buscador))
        {
            $id_Accion = Constants::BUSQUEDA; 
            $valores_modificados = 'Tipo de Búsqueda: '.$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador;
        }else{
            $id_Accion = Constants::INGRESO_MODULO; 
            $valores_modificados = 'N/A';
        }
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_HistorialSesion'));

        $accion = Traza_Acciones::pluck('valor', 'id')->all();
        $user = User::pluck('users', 'id')->all();

        return view('historialSesion.index', compact('historial_sesion', 'user', 'accion'));
    }

    public function exportExcel()
    {
        $id_user = Auth::user()->id;
        $id_Accion = Constants::DESCARGA; 
        $valores_modificados = 'Descarga de Excel con Historial de Sesión';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_HistorialSesion'));

        return Excel::download(new Historial_SesionExport, 'historial_sesion_'.date('Ymd-his').'.xlsx');
    }
}
