@extends('layouts.app')
@extends('configuraciones.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Editar Ruta de Almacenamiento</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">                    
                        @include('partials.alerts')

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                            </div>
                        </div>
                        <br>
 
                        @include('configuraciones.forms.RutasAlmacenamiento')
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('public/js/configuraciones/forms.rutasAlmacenamiento.js')}}"></script>
@endsection