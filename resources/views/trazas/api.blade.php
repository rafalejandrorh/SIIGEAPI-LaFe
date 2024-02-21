@extends('layouts.app')
@extends('trazas.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Trazas de API</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <a href="{{ route('trazas.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                </div>
                            </div>
                            <br>

                            {!! Form::open(array('route' => 'traza_api.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'consulta' => 'Consulta',
                                        'token' => 'Token',
                                        ], 'Seleccionar', array('class' => 'form-control select2')) !!}
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
                                <div class="col-xs-2 col-sm-2 col-md-2">
                                    <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#filtrar"><i class="fa fa-filter"></i> Filtro</a>
                                </div>
                            </div>
                            {!! Form::close() !!}

                                    <table class="table table-striped mt-2 display dataTable table-hover">
                                        <thead>
                                            <tr role="row">
                                                <th>Fecha de Consulta</th>
                                                <th>Consulta</th>
                                                <th>Tipo de Consulta</th>
                                                <th>Organismo</th>
                                                <th>Dependencia</th>
                                                <th>IP</th>
                                                <th>MAC</th>
                                                <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($apis as $api)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$api->fecha_request}}</td>
                                                <td class="sorting_1">{{$api->request}}</td>
                                                <td class="sorting_1">{{$api->action}}</td>
                                                <td class="sorting_1">{{$api->dependencia->organismo ?? 'N/A'}}</td>
                                                <td class="sorting_1">{{$api->dependencia->nombre ?? 'N/A'}}</td>
                                                <td class="sorting_1">{{$api->ip}}</td>
                                                <td class="sorting_1">{{$api->mac}}</td>
                                                <td>
                                                    <a class="btn btn-info" href="{{ route('traza_api.show', $api->id) }}"><i class='fa fa-eye'></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $apis->appends(request()->input())->links() }}
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Filtro --}}
    <div class="modal fade" id="filtrar" tabindex="-1" aria-labelledby="filtro" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="page__heading text-white"><b>Filtro</b></h3>
                    <span aria-hidden="true" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</span>
                </div>
            {!! Form::open(array('route' => 'traza_api.index','method' => 'GET')) !!}
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="email">Desde (Fecha de Consulta)</label>
                            {!! Form::date('fecha_inicio', null, array('class' => 'form-control datepicker')) !!}
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="email">Hasta (Fecha de Consulta)</label>
                            {!! Form::date('fecha_fin', null, array('class' => 'form-control datepicker')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Tipo de Consulta</label>
                            {!! Form::select('action', $accion, [], 
                            array('class' => 'form-control', 'placeholder'=>'Seleccione')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Usuario</label>
                            {!! Form::select('usuario', $user, [], 
                            array('class' => 'form-control', 'placeholder'=>'Seleccione')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Dependencia</label>
                            {!! Form::select('dependencia', $dependencia, [], 
                            array('class' => 'form-control', 'placeholder'=>'Seleccione')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Organismo</label>
                            {!! Form::select('organismo', $organismo, [], 
                            array('class' => 'form-control', 'placeholder'=>'Seleccione')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Ministerio</label>
                            {!! Form::select('ministerio', $ministerio, [], 
                            array('class' => 'form-control', 'placeholder'=>'Seleccione')) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        {!! Form::hidden('filtro', 1, array('class' => 'form-control datepicker')) !!}
                        {!! Form::button('<i class="fa fa-check"> Aplicar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                    </div>
                </div>

            </div>
            {!! Form::close() !!}
        </div>
        </div>
    </div>
    
</section>
@endsection