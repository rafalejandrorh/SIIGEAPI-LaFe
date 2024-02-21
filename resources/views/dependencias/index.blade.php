@extends('layouts.app')
@extends('dependencias.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Dependencias</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'dependencias.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 col-md-5">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'dependencia' => 'Dependencia',
                                        'ministerio' => 'Ministerio',
                                        'organismo' => 'Organismo',
                                        'seudonimo' => 'Seudónimo' 
                                        ], 
                                        'Seleccionar', array('class' => 'form-control select2')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        {!! Form::text('buscador', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    {!! Form::button('<i class="fa fa-search"> Buscar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                                <div class="row">
                                    <div class="col-xs-9 col-sm-9 col-md-9">
                                        @can('dependencias.create')
                                            <a class="btn btn-success" href="{{ route('dependencias.create') }}"><i class="fa fa-plus"></i> Registrar</a>                        
                                        @endcan
                                    </div>
                                </div>
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Dependencia</th>
                                                <th>Organismo</th>
                                                <th>Ministerio</th>
                                                <th>Seudónimo</th>
                                                <th>Responsable</th>
                                                <th>Correo</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($dependencias as $dependencia)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$dependencia->nombre}}</td>
                                                <td class="sorting_1">{{$dependencia->organismo}}</td>
                                                <td class="sorting_1">{{$dependencia->ministerio}}</td>
                                                <td class="sorting_1">{{$dependencia->seudonimo}}</td>
                                                <td class="sorting_1">{{$dependencia->person->primer_nombre.' '.$dependencia->person->primer_apellido}}</td>
                                                <td class="sorting_1">{{$dependencia->correo}}</td>
                                                <td align="center">
                                                    @can('dependencias.edit')
                                                        <a class="btn btn-primary" href="{{ route('dependencias.edit', $dependencia->id) }}"><i class='fa fa-edit'></i></a>
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $dependencias->appends(request()->input())->links() }}
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')

    @if (session('eliminar') == 'Ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'La Reseña ha sido Eliminada.',
                'success'
            )
        </script>
    @endif

    <script>
        $('.eliminar').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
            if (result.value) {
                this.submit();
            }
            })

        });
    </script>

@endsection