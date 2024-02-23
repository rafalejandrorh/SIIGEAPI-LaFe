<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Servicios;
use App\Models\Traza_Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiciosController extends Controller
{
    private $servicios;

    function __construct(Servicios $servicios)
    {
        // $this->middleware('can:servicios.index')->only('index');
        // $this->middleware('can:servicios.create')->only('create');
        // $this->middleware('can:servicios.edit')->only('edit', 'update');
        // $this->middleware('can:servicios.destroy')->only('destroy');
        // $this->middleware('can:servicios.update_status')->only('update_status');
        $this->servicios = $servicios;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();

        $servicios = $this->servicios->search($request);
        
        if(isset($request->tipo_busqueda) && isset($request->buscador)) {
            $id_user = Auth::user()->id;
            $id_Accion = Constants::BUSQUEDA;
            $valores_modificados = 'Tipo de Búsqueda: '.$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador;
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Servicios'));
        }

        return view('servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $exists = $this->servicios->Where('nombre', $request->nombre)->exists();
        if($exists) {
            Alert()->warning('Este nombre ya es usado por otro Servicio registrado en el Sistema');
            return back(); 
        }

        $this->servicios->create([
            'nombre' => $request->nombre,
            'metodo' => $request->metodo,
            'estatus' => true
        ]);
        $id_user = Auth::user()->id;
        $id_Accion = Constants::REGISTRO;
        $valores_modificados = 'Datos del Servicio: '.$request->nombre.' || Activo || '.$request->metodo;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Servicios'));
        
        Alert()->success('Servicio Creado Satisfactoriamente');
        return redirect()->route('servicios.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Servicios $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servicios $servicio)
    {
        $exists = $this->servicios->Where('nombre', $request->nombre)->where('id', '!=', $servicio->id)->exists();
        if($exists) {
            Alert()->warning('Este nombre ya es usado por otro Servicio registrado en el Sistema');
            return back(); 
        }

        $servicio->update($request->all());
        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION;
        $valores_modificados = 'Datos del Servicio: '.$request->nombre.' || '.$request->metodo;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Servicios'));
    
        Alert()->success('Servicios Actualizado Satisfactoriamente');
        return redirect()->route('servicios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio = $this->servicios->Where('id', $id)->first();
        $id_user = Auth::user()->id;
        $id_Accion = Constants::ELIMINACION;
        $valores_modificados = 'Datos del Servicio: '.$servicio['nombre'].' || '.$servicio['metodo'];
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Servicios'));

        $servicios = $this->servicios->find($id, ['id']);
        $servicios->delete();
        Alert()->success('El servicio ha sido Eliminado');
        return redirect()->route('servicios.index');
    }

    public function update_status($id)
    {
        $servicios = $this->servicios->Where('id', $id)->first();

        $id = $servicios['id'];
        $estatus = $servicios['estatus'];
        $metodo = $servicios['metodo'];
        $nombre = $servicios['nombre'];

        if($estatus == true)
        {
            $status = false;
            $notificacion = 'Inactivo';
        }else if($estatus == false){
            $status = true;
            $notificacion = 'Activo';
        }else{
            Alert()->error('No se actualizó el Estatus del Usuario', 'El Funcionario no se encuentra Activo, por lo que no se puede activar su Usuario');
            return back();
        }
        $servicios = Servicios::find($id, ['id']);
        $servicios->update(['estatus' => $status]);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION;
        $valores_modificados = 'Datos del Servicio: '.$nombre.' || '.$metodo.' || Estatus Previo: '.$estatus.' || Estatus Nuevo: '.$notificacion;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Servicios'));

        Alert()->success('Estatus de Servicio Actualizado', 'Nuevo Estatus: '.$notificacion);
        return redirect()->route('servicios.index');
    }
}
