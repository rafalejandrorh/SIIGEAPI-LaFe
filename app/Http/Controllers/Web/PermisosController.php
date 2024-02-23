<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermisosController extends Controller
{
    private $permissions;

    function __construct(Permissions $permissions)
    {
        // $this->middleware('can:permisos.index')->only('index_permisos');
        // $this->middleware('can:permisos.create')->only('create_permisos');
        // $this->middleware('can:permisos.edit')->only('edit_permisos', 'update_permisos');
        // $this->middleware('can:permisos.destroy')->only('destroy_permisos');
        $this->permissions = $permissions;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();
        $permissions = $this->permissions->search($request);

        $id_user = Auth::user()->id;
        if(isset($request->tipo_busqueda) && isset($request->buscador))
        {
            $id_Accion = Constants::BUSQUEDA; 
            $valores_modificados = 'Tipo de Búsqueda: '.$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador;
        }else{
            $id_Accion = Constants::INGRESO_MODULO; 
            $valores_modificados = 'N/A';
        }
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Permisos'));

        return view('permisos.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permisos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->permissions->create([
            'name' => $request->nomenclatura,
            'description' => $request->descripcion,
            'guard_name' => $request->tipo_permiso
        ]);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::REGISTRO; 
        $valores_modificados = 'Datos de Permiso: '.$request->nomenclatura.' || '.$request->descripcion.' || '.$request->tipo_permiso;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Permisos'));

        Alert()->success('Permiso registrado Satisfactoriamente');
        return redirect()->route('permisos.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permissions $permiso)
    {
        return view('permisos.edit', compact('permiso'));
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
        $permissions = $this->permissions->find($id, ['id']);
        $permissions->update([
            'name' => $request->nomenclatura,
            'description' => $request->descripcion,
            'guard_name' => $request->tipo_permiso
        ]);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION; 
        $valores_modificados = 'Datos de Permiso: '.$request->nomenclatura.' || '.$request->descripcion.' || '.$request->tipo_permiso;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Permisos'));

        Alert()->success('Permiso Actualizado Satisfactoriamente');
        return redirect()->route('permisos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permissions = $this->permissions->where('id', $id)->first();
        $id_user = Auth::user()->id;
        $id_Accion = Constants::ELIMINACION; 
        $valores_modificados = 'Datos de Permiso: '.$permissions['name'].' || '.$permissions['description'].' || '.$permissions['guard_name'];
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Permisos'));
        
        $permissions = $this->permissions->find($id, ['id']);
        $permissions->delete();

        Alert()->success('El Permiso ha sido Eliminada');
        return redirect()->route('permisos.index');
    }
}
