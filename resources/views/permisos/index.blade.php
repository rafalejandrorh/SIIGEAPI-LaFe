@extends('layouts.app')
@extends('permisos.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Permisos</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    {!! Form::open(array('route' => 'permisos.index','method' => 'GET')) !!}
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', [
                                            '' => 'Ver todos',
                                            'nomenclatura' => 'Nomenclatura',
                                            'descripcion' => 'Descripción',
                                            'permiso' => 'Tipo de Permiso', 
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
                            <br>

                                <div class="col-xs-2 col-sm-2 col-md-2">
                                    <div class="form-group">
                                        {{-- @can('permisos.create') --}}
                                        <a class="btn btn-success" href="{{ route('permisos.create') }}"><i class="fas fa-plus"></i> Crear</a>                        
                                        {{-- @endcan --}}
                                    </div>
                                </div>
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Nomenclatura</th>
                                                <th>Descripción</th>
                                                <th>Tipo de Permiso</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($permissions as $permission)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$permission->name}}</td>
                                                <td class="sorting_1">{{$permission->description}}</td>
                                                <td class="sorting_1">{{$permission->guard_name}}</td>
                                                <td align="center">
                                                    {{-- @can('permisos.edit') --}}
                                                        <a class="btn btn-primary" href="{{ route('permisos.edit', $permission->id) }}"><i class='fa fa-edit'></i></a>
                                                    {{-- @endcan
                                                    @can('permisos.destroy') --}}
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['permisos.destroy', $permission->id], 'style'=>'display:inline', 'class' => 'eliminar']) !!}
                                                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                        {!! Form::close() !!}  
                                                    {{-- @endcan --}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $permissions->appends(request()->input())->links() }}
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