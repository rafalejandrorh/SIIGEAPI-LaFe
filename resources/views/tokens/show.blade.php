@extends('layouts.app')
@extends('tokens.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Detallado de Tokens</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-10 col-sm-10 col-md-10">
                                    <a href="{{ route('tokens.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                </div>
                            </div>
                            <br>
                            @include('tokens.forms.edit')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection