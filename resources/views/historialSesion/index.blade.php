@extends('layouts.app')
@extends('historialSesion.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Historial de Sesión</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <a class="btn btn-success" href="{{ route('historial_sesion.export.excel') }}"><i class="fas fa-file-excel"></i></a>                        
                                    </div>
                                </div>
                            </div>
                            <br>

                            {!! Form::open(array('route' => 'historial_sesion.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'cedula' => 'Cédula',
                                        'credencial' => 'Credencial',
                                        'jerarquia' => 'Jerarquía', 
                                        'usuario' => 'Usuario', 
                                        'nombre' => 'Primer Nombre del Funcionario',
                                        'apellido' => 'Primer Apellido del Funcionario',], 
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
                                <div class="col-xs-2 col-sm-2 col-md-2 text-right">
                                    <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#filtrar"><i class="fa fa-filter"></i> Filtro</a>
                                </div>
                            </div>
                            {!! Form::close() !!}

                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Usuario</th>
                                                <th>Funcionario Asignado</th>
                                                <th>Inicio de Sesión</th>
                                                <th>Cierre de Sesión</th>
                                                <th>Tipo de Cierre</th>
                                                <th>MAC</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($historial_sesion as $historial)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$historial->user->users}}</td>
                                                <td class="sorting_1">{{$historial->user->funcionario->person->primer_nombre.' '.$historial->user->funcionario->person->primer_apellido}}</td>
                                                <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($historial->login)) }}</td>
                                                @if ($historial->logout)
                                                    <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($historial->logout)) }}</td>
                                                @else
                                                    <td class="sorting_1"> - </td>
                                                @endif
                                                
                                                @if ($historial->tipo_logout == 1)
                                                    <td class="sorting_1">Finalizada por el Usuario</td>
                                                @elseif ($historial->tipo_logout == 2)
                                                    <td class="sorting_1">Finalizada por el Sistema</td>
                                                @elseif ($historial->tipo_logout == 3)
                                                    <td class="sorting_1">Finalizada por un Tercero</td>
                                                @else
                                                    <td class="sorting_1">Sin Finalizar</td>
                                                @endif
                                                <td class="sorting_1">{{$historial->MAC}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $historial_sesion->appends(request()->input())->links() }}
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('trazas.modals.filtro', ['filtro' => 'historial_sesion.index'])
</section>
@endsection