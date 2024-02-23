@extends('layouts.app')
@extends('users.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Usuarios</b></h3>
        </div>
        <div class="section-body">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-xl-4">
                                    <div class="card bg-primary order-card">
                                        <div class="card-block">
                                            <h5>Total de Usuarios</h5>
                                            <h3 class="text-left"><span>{{$countUsers}}</span></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="card bg-success order-card">
                                        <div class="card-block">
                                            <h5>Usuarios Activos</h5>
                                            <h3 class="text-left"><span>{{$countUsersActive}}</span></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="card bg-danger order-card">
                                        <div class="card-block">
                                            <h5>Usuarios Inactivos</h5>
                                            <h3 class="text-left"><span>{{$countUsersInactive}}</span></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'users.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', [
                                            '' => 'Ver todos',
                                            'cedula' => 'Cédula',
                                            'usuario' => 'Usuario', 
                                            'estatus' => 'Estatus',
                                            'nombre' => 'Primer Nombre',
                                            'apellido' => 'Primer Apellido',
                                        ], 
                                        'Seleccionar', ['class' => 'form-control select2']) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        {!! Form::text('buscador', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    {!! Form::button('<i class="fa fa-search"> Buscar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}

                                <div class="row">
                                    <div class="col-xs-9 col-sm-9 col-md-9">
                                        <div class="form-group">
                                            {{-- @can('users.create') --}}
                                                <a class="btn btn-success" href="{{ route('users.create') }}"><i class="fas fa-plus"></i> Crear</a>                        
                                            {{-- @endcan --}}
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 text-right"> 
                                        <div class="form-group">
                                            <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#modifyStatus"><i class="fa fa-spinner"></i> Modificar Estatus</a>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 text-right">
                                        <div class="form-group">
                                            {{-- @can('users.excel') --}}
                                                <a class="btn btn-success" href="{{ route('users.export.excel') }}"><i class="fas fa-file-excel"></i></a>                        
                                            {{-- @endcan --}}
                                        </div>
                                    </div>
                                </div>
                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Persona</th>
                                                <th>Usuario</th>
                                                <th>Estatus</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($Users as $user)
                                                <tr role="row" class="odd">
                                                    <td class="sorting_1">{{ $user->person->primer_nombre.' '.$user->person->primer_apellido }}</td>
                                                    <td class="sorting_1">{{ $user->users }}</td>
                                                    <td class="sorting_1">
                                                        {{-- @can('users.update_status') --}}
                                                            {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update_status', $user->id], 'class' => 'estatus']) !!}
                                                                {!! Form::button($user->status ? 'Activo' : 'Inactivo', ['type' => 'submit', 'class' => $user->status ? 'btn btn-info' : 'btn btn-danger', 'Title' => 'Modificar Estatus']) !!}
                                                            {!! Form::close() !!} 
                                                        {{-- @elsecan('users.index')
                                                            <span class="badge badge-pill badge-{{$user->status ? 'info' : 'danger' }}">{{$user->status ? 'Activo' : 'Inactivo' }}</span>
                                                        @endcan --}}
                                                    </td>
                                                    <td align="center">
                                                        {{-- @can('users.show') --}}
                                                            <a class="btn btn-info" Title="Mostrar" href="{{ route('users.show', $user->id) }}"><i class='fa fa-eye'></i></a>
                                                        {{-- @endcan
                                                        @can('users.edit') --}}
                                                            <a class="btn btn-primary" Title="Editar" href="{{ route('users.edit', $user->id) }}"><i class='fa fa-edit'></i></a>
                                                        {{-- @endcan --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $Users->appends(request()->input())->links() }}
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    @include('users.modals.estatus', ['estatus' => 'users.update_status.all'])
@endsection

@section('scripts')
    <script src="{{ asset('public/js/users/index.js') }}"></script>
@endsection