@extends('layouts.app')
@extends('servicios.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Servicios</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'servicios.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-3 col-sm-3 col-md-5">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'nombre' => 'Nombre',
                                        'metodo' => 'Método',
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
                                        {{-- @can('servicios.create') --}}
                                            <a class="btn btn-success" href="{{ route('servicios.create') }}"><i class="fa fa-plus"></i> Registrar</a>                        
                                        {{-- @endcan --}}
                                    </div>
                                </div>
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Nombre</th>
                                                <th>Método</th>
                                                <th>Estatus</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($servicios as $servicio)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$servicio->nombre}}</td>
                                                <td class="sorting_1">{{$servicio->metodo}}</td>
                                                <td class="sorting_1">
                                                    {{-- @can('servicios.update_status') --}}
                                                        {!! Form::model($servicio, ['method' => 'PATCH','route' => ['servicios.update_status', $servicio->id]]) !!}
                                                            {!! Form::button($servicio->estatus ? 'Activo' : 'Inactivo', ['type' => 'submit', 'class' => $servicio->estatus ? 'btn btn-info' : 'btn btn-danger']) !!}
                                                        {!! Form::close() !!} 
                                                    {{-- @elsecan('servicios.index') --}}
                                                        {{-- <td class="sorting_1">
                                                            <span class="badge badge-pill badge-{{$servicio->estatus ? 'info' : 'danger'}}">{{ $servicio->estatus ? 'Activo' : 'Inactivo' }}</span>
                                                        </td> --}}
                                                    {{-- @endcan --}}
                                                </td>
                                                <td align="center">
                                                    {{-- @can('servicios.edit') --}}
                                                        <a class="btn btn-primary" href="{{ route('servicios.edit', $servicio->id) }}"><i class='fa fa-edit'></i></a>
                                                    {{-- @endcan --}}
                                                    {{-- @can('servicios.destroy') --}}
                                                        {!! Form::open(['method' => 'DELETE','route' => ['servicios.destroy', $servicio->id],'style'=>'display:inline', 'class' => 'eliminar']) !!}
                                                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                        {!! Form::close() !!}                                                  
                                                    {{-- @endcan --}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $servicios->appends(request()->input())->links() }}         
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