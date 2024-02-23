@extends('layouts.app')
@extends('trazas.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Detallado de Historial de Token</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <a href="{{ route('traza_historial_tokens.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>
 
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Dependencia</label>
                                    {!! Form::text('user', $historial_token->empresas->nombre, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Organismo</label>
                                    {!! Form::text('user', $historial_token->empresas->organismo, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Ministerio</label>
                                    {!! Form::text('user', $historial_token->empresas->ministerio, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="email">Fecha de Creación</label>
                                    {!! Form::text('fecha_creacion', $historial_token->created_at, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="email">Fecha de Expiración</label>
                                    {!! Form::text('fecha_expiracion', $historial_token->expires_at, array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="email">Fecha de Último Uso</label>
                                    {!! Form::text('fecha_ultimo_uso', $historial_token->last_used_at, array('class' => 'form-control', 'readonly')) !!}
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
