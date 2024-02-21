@extends('layouts.app')
@extends('trazas.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>{{ $title }}</b></h3>
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

                            {!! Form::open(array('route' => $route.'.index','method' => 'GET')) !!}
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                        'cedula' => 'Cédula',
                                        'credencial' => 'Credencial',
                                        'jerarquia' => 'Jerarquía', 
                                        'usuario' => 'Usuario', 
                                        'nombre' => 'Primer Nombre del Funcionario',
                                        'apellido' => 'Primer Apellido del Funcionario',
                                        'accion' => 'Acción del Usuario',
                                        'valores_modificados' => 'Valores Modificados'],
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
                                        @include('trazas.tables.table', ['data' => $data, 'route' => $route.'.show'])
                                    </table>
                                <div class="pagination justify-content-end">
                                    {{ $data->appends(request()->input())->links() }}
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('trazas.modals.filtro', ['filtro' => $route.'.index'])
</section>
@endsection