@extends('layouts.app')
@extends('sessions.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Detallado de Sesión</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">
   
                            <div class="row">
                                <div class="col-xs-10 col-sm-10 col-md-10">
                                    <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                </div>
                            </div>
                            <br>
                        </div>
                </div>
                <div class="col-lg-12">
                    @include('sessions.forms.show')
                </div>
                <div class="col-lg-12">
                    @include('sessions.tables.tables', ['userIgnore' => true])
                </div>
            </div>
        </div>
    </section>
@endsection
