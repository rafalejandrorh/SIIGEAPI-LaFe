@extends('layouts.app')
@extends('trazas.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Detallado de Trazas de API</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <a href="{{ route('traza_api.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>
 
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="name">Fecha de Consulta</label>
                                    {!! Form::text('user', $apis->fecha_request, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Consulta</label>
                                    {!! Form::text('accion', $apis->request, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Tipo de Consulta</label>
                                    {!! Form::text('fecha', $apis->action, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="email">Token Utilizado</label>
                                    {!! Form::textarea('fecha', $apis->token, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="email">Respuesta a la Consulta</label>
                                    {!! Form::textarea('fecha', $apis->response, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">Usuario</label>
                                    {!! Form::text('fecha', $apis->user->users ?? 'N/A', array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">IP</label>
                                    {!! Form::text('fecha', $apis->ip, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="email">MAC</label>
                                    {!! Form::text('fecha', $apis->mac, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Organismo</label>
                                    {!! Form::text('fecha', $apis->dependencia->organismo ?? 'N/A', array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Dependencia</label>
                                    {!! Form::text('fecha', $apis->dependencia->nombre ?? 'N/A', array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="email">Ministerio</label>
                                    {!! Form::text('fecha', $apis->dependencia->ministerio ?? 'N/A', array('class' => 'form-control', 'readonly')) !!}
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
