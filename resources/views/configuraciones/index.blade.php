@extends('layouts.app')
@extends('configuraciones.partials.header')
@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading text-primary"><b>Configuraciones</b></h3>
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

            @can('permisos.index')
                <div class="col-lg-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <div class="form-group">
                                    <a class="btn btn-primary" href="{{ route('permisos.index') }}"><i class='fa fa-lock'> Permisos</i></a>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('rutasAlmacenamiento.index')
                <div class="col-lg-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <div class="form-group">
                                    <a class="btn btn-primary" href="{{ route('rutasAlmacenamiento.index') }}"><i class='fa fa-server'> Rutas de Almacenamiento</i></a>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('backup.index')
                <div class="col-lg-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <div class="form-group">
                                    <a class="btn btn-primary" href="{{ route('backup.index') }}"><i class='fa fa-upload'> Respaldos</i></a>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('location.edit')
                <div class="col-lg-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <div class="form-group">
                                    <a class="btn btn-primary" href="{{ route('location.edit') }}"><i class='fa fa-globe'> Ubicación</i></a>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('location.edit')
                <div class="col-lg-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <div class="form-group">
                                    <a class="btn btn-primary" href="{{ route('apk.index') }}"><i class='fa fa-mobile'> APK</i></a>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</section>
@endsection