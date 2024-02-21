@extends('layouts.app')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Mi Perfil</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="text-primary"><i class="fa fa-id-badge"></i> Datos</h5>
                        </div>
                        <div class="card-body">    
                            @if (!isset($password_status) || $password_status == false)
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <a href="{{ route('home') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                    </div>
                                </div>  
                            @endif
                            <br>
                            @include('users.forms.profile', ['user' => $user, 'roles' => $role])
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="text-primary"><i class="fa fa-cog"></i> Ajustes</h5>
                        </div>
                        <div class="card-body">    
                            @include('users.forms.settings', ['user' => $user])
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="text-primary"><i class="fa fa-lock"></i> Seguridad</h5>
                        </div>
                        <div class="card-body">    
                            @include('users.forms.security', ['user' => $user])
                        </div>
                    </div>
                

                    @if (!isset($password_status) || $password_status == false)
                        @include('sessions.tables.tables', ['userIgnore' => true])
                    @else
                    
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('public/js/users/settings.js')}}"></script>
@endsection