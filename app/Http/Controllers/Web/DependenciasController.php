<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Genero;
use App\Models\Person;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Dependencias;
use App\Models\Dependencias_Servicios;
use App\Models\Servicios;
use App\Traits\GeneroTrait;

class DependenciasController extends Controller
{
    use GeneroTrait;

    private $dependencias;
    private $servicios;
    private $dependencias_servicios;
    private $person;
    private $genero;

    function __construct(Dependencias $dependencias, Servicios $servicios, Dependencias_Servicios $dependencias_servicios,
    Person $person, Genero $genero)
    {
        $this->middleware('can:dependencias.index')->only('index');
        $this->middleware('can:dependencias.create')->only('create');
        $this->middleware('can:dependencias.show')->only('show');
        $this->middleware('can:dependencias.edit')->only('edit', 'update');
        $this->dependencias_servicios = $dependencias_servicios;
        $this->dependencias = $dependencias;
        $this->servicios = $servicios;
        $this->person = $person;
        $this->genero = $genero;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->all();
        $dependencias = $this->dependencias->search($request);
        if(isset($request->tipo_busqueda) && isset($request->buscador)) {
            $id_user = Auth::user()->id;
            $id_Accion = Constants::BUSQUEDA;
            $valores_modificados = 'Tipo de Búsqueda: '.$request->tipo_busqueda.'. Valor Buscado: '.$request->buscador;
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Dependencias'));
        }
        
        return view('dependencias.index', compact('dependencias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $genero = $this->pluckGenero();
        $servicios = $this->servicios->Estatus(true)->get();
        return view('dependencias.create',compact('genero', 'servicios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cedula = $request['cedula'];
        $exists = $this->person->where('cedula','=',$cedula)->exists();
        if(!$exists) {
            $this->person->create([
                'letra_cedula' => 'V',
                'cedula' => $request->cedula,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'id_genero' => $request->id_genero,
            ]);
        }
        $person = $this->person->where('cedula', $cedula)->first();

        $dependencia = $this->dependencias->create([
            'nombre' => $request->dependencia,
            'ministerio' => $request->ministerio,
            'organismo' => $request->organismo,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'seudonimo' => $request->seudonimo,
            'id_person' => $person['id']
        ]);

        $i = 0;
        $nombre_servicio = null;
        while($i < count($request->id_servicios))
        {
            $this->dependencias_servicios->create([
                'id_dependencia' => $dependencia->id,
                'id_servicio' => $request->id_servicios[$i]
            ]);

            $nombre = $this->servicios->where('id', $request->id_servicios[$i])->first();
            $nombre_servicio .= $nombre['nombre'];
            $u = $i + 1;
            if($u < count($request->id_servicios)) {
                $nombre_servicio .= ', ';
            }
            $i++;
        }

        $genero = $this->genero->Where('id', $request['id_genero'])->first();
        $id_user = Auth::user()->id;
        $id_Accion = Constants::REGISTRO;
        $valores_modificados = 'Datos de la Dependencia: '.$request->organismo.', '.$request->dependencia.', Adscrito al: '.$request->ministerio.
        '|| Datos del Representante: '.$person['letra_cedula'].$person['cedula'].
        ' || '.$person['primer_nombre'].', '.$person['segundo_nombre'].' || '.
        $person['primer_apellido'].', '.$person['segundo_apellido'].' || '.
        $genero['valor'].' || '.$person['telefono'].' || '.$person['correo_electronico'].' || Servicios a Consumir: '.$nombre_servicio;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Dependencias'));

        Alert()->success('Dependencia Creada Satisfactoriamente');
        return redirect()->route('dependencias.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Dependencias $dependencia)
    {
        $genero = $this->pluckGenero();
        $servicios = $this->servicios->Estatus(true)->get();
        $dependencias_servicios = $this->dependencias_servicios->join('nomenclador.servicios', 'servicios.id', 'dependencias_servicios.id_servicio')
        ->where('dependencias_servicios.id_dependencia', $dependencia->id)->pluck('servicios.nombre', 'servicios.id')->all();
        return view('dependencias.edit', compact('dependencia', 'genero', 'servicios', 'dependencias_servicios'));
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
        $dependencias = $this->dependencias->find($id, ['id']);
        $dependencias->update([
            'nombre' => $request->dependencia,
            'organismo' => $request->organismo,
            'ministerio' => $request->ministerio,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'seudonimo' => $request->seudonimo
        ]);

        $person = $dependencias->Where('id', $id)->select('id_person')->first();
        $id_person = $person['id_person'];

        $personas = $this->person->find($id_person);
        $personas->update([
            'letra_cedula' => $request->letra_cedula, 
            'cedula' => $request->cedula, 
            'primer_nombre' => $request->primer_nombre, 
            'segundo_nombre' => $request->segundo_nombre, 
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido, 
            'id_genero' => $request->id_genero, 
        ]);

        $ExistsDependenciaServicio = $this->dependencias_servicios->where('id_dependencia', $id)->exists();
        if($ExistsDependenciaServicio) {
            $dependenciaServicio = $this->dependencias_servicios->where('id_dependencia', $id);
            $dependenciaServicio->delete();  
        }

        $i = 0;
        $nombre_servicio = null;
        while($i < count($request->id_servicios))
        {
            $this->dependencias_servicios->create([
                'id_dependencia' => $id,
                'id_servicio' => $request->id_servicios[$i]
            ]);

            $servicios = $this->servicios->where('id', $request->id_servicios[$i])->first();
            $nombre_servicio .= $servicios['nombre'];
            $u = $i + 1;
            if($u < count($request->id_servicios)) {
                $nombre_servicio .= ', ';
            }
            $i++;
        };

        $genero = $this->genero->Where('id', $request->id_genero)->first();

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION;
        $valores_modificados = 'Datos de la Dependencia: '.$request->organismo.', '.$request->dependencia.', Adscrito al: '.$request->ministerio.
        '|| Datos del Representante: '.$request->letra_cedula.$request->cedula.' || '.
        $request->primer_nombre.', '.$request->segundo_nombre.' || '.$request->primer_apellido.', '.
        $request->segundo_apellido.' || '.$genero['valor'].' || '.$request->fecha_nacimiento.' || '.$request->telefono.' || '.$request->correo.' || Servicios a Consumir: '.$nombre_servicio;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Dependencias'));

        Alert()->success('Dependencia Actualizada Satisfactoriamente');
        return redirect()->route('dependencias.index');
    }
}
