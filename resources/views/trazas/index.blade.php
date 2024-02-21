@extends('layouts.app')
@extends('trazas.partials.header')
@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading text-primary"><b>Trazas</b></h3>
    </div>
    <div class="section-body">
        <div class="row">

            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <a class="btn btn-danger" href="{{ route('home') }}"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_historialSesion.index') }}"><i class='fa fa-history'> Historial de Sesión</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_resenna.index') }}"><i class='fa fa-balance-scale'> Reseñas</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_bitacoraLlamadas.index') }}"><i class='fa fa-phone'> Bitácora de Llamadas</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_funcionarios.index') }}"><i class='fa fa-users'> Funcionarios</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_user.index') }}"><i class='fa fa-user'> Usuarios</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_roles.index') }}"><i class='fa fa-key'> Roles</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_sesiones.index') }}"><i class='fa fa-clock'> Sesiones</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_permisos.index') }}"><i class='fa fa-lock'> Permisos</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_rutasAlmacenamiento.index') }}"><i class='fa fa-server'> Rutas de Almacenamiento</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_backup.index') }}"><i class='fa fa-upload'> Respaldos</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="form-group">
                                <a class="btn btn-primary" href="{{ route('traza_apk.index') }}"><i class='fa fa-mobile'> APK</i></a>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <center><a class="btn btn-primary" href="{{ route('traza_historial_tokens.index') }}"><i class='fa fa-history'> Historial de Tokens</i></a></center>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <center><a class="btn btn-primary" href="{{ route('traza_tokens.index') }}"><i class='fa fa-key'> Tokens</i></a></center>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <center><a class="btn btn-primary" href="{{ route('traza_servicios.index') }}"><i class='fa fa-server'> Servicios</i></a></center>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <center><a class="btn btn-primary" href="{{ route('traza_api.index') }}"><i class='fa fa-database'> API´s</i></a></center>
                                <hr>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <center><a class="btn btn-primary text-center" href="{{ route('traza_dependencias.index') }}"><i class='fa fa-building'> Dependencias</i></a></center>
                                <hr>
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