@extends('layouts.app')
@extends('configuraciones.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Configuraciones - Rutas de Almacenamiento</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <a href="{{ route('configuraciones.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                </div>
                            </div>
                            <br>
                                <div class="col-xs-2 col-sm-2 col-md-2">
                                    <div class="form-group">
                                        @can('rutasAlmacenamiento.create')
                                        <a class="btn btn-success" href="{{ route('rutasAlmacenamiento.create') }}"><i class="fas fa-plus"></i> Registrar</a>                        
                                        @endcan
                                    </div>
                                </div>
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Ruta</th>
                                                <th>Tipos de Archivo</th>
                                                <th>Nomenclatura</th>
                                                <th>Módulo</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($rutasAlmacenamiento as $almacenamiento)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$almacenamiento->ruta}}</td>
                                                <td class="sorting_1">{{$almacenamiento->tipo_archivo}}</td>
                                                <td class="sorting_1">{{$almacenamiento->nomenclatura}}</td>
                                                <td class="sorting_1">{{$almacenamiento->modulo}}</td>
                                                <td class="sorting_1">{{$almacenamiento->descripcion}}</td>
                                                <td align="center">
                                                    @can('rutasAlmacenamiento.edit')
                                                        <a class="btn btn-primary" href="{{ route('rutasAlmacenamiento.edit', $almacenamiento->id) }}"><i class='fa fa-edit'></i></a>
                                                    @endcan
                                                    @can('rutasAlmacenamiento.destroy')
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['rutasAlmacenamiento.destroy', $almacenamiento->id], 'style'=>'display:inline', 'class' => 'eliminar']) !!}
                                                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                        {!! Form::close() !!}  
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $rutasAlmacenamiento->appends(request()->input())->links() }}
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