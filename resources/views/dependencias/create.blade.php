@extends('layouts.app')
@extends('dependencias.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Registrar Dependencia</b></h3>
        </div>
        
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">    
                            @if ($errors->any())                                                
                                <div class="alert alert-dark alert-dismissible fade show" role="alert">
                                <strong>¡Revise los campos!</strong>                        
                                    @foreach ($errors->all() as $error)                                    
                                        <span class="badge badge-danger">{{ $error }}</span>
                                    @endforeach                        
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <a href="{{ route('dependencias.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('dependencias.forms.form')
                </div>
            </div>
        </div>

    </section>
@endsection
