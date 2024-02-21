@extends('layouts.app')
@section('title', 'SIREPOL | Inicio')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>SIIGEAPI</b></h3>
        </div>
            
        <div id="notification"></div>
        
        <div class="section-body">
            <div class="row">
                <div class="col-lg-8 offset-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="text-center text-primary"><b>Bienvenido al Sistema Integrado de Gestión de API´s</b></h3>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12 col-xl-12">
                                <div class="login-brand">
                                    <center><img src="{{ asset('public/img/logo-seguros-la-fe.jpg') }}" alt="logo" width="430" height="200" class="shadow-light"></center>
                                </div>
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
                @can('home.report')
                    <div class="col-lg-12">
                        @include('sessions.tables.tables', ['userIgnore' => false])
                    </div>
                @endcan
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('public/js/home/index.js')}}"></script> --}}
@endsection



