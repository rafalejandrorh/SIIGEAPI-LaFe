@extends('layouts.auth_app')
@extends('auth.partials.header')
@section('content')
<div class="login-main-text">
    <div class="title text-center">
        <h2 style="color:#000000"><b>Sistema de Reseña Policial</b></h2>
        <h2 style="color:#000000"><b>Servicio de Investigación Penal</b></h2>
    </div>  
</div>

<div class="card card-primary">
    <div class="card-header"><h4>Reestablecer Contraseña</h4></div>

    <div class="card-body">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group mb-3">
                <input id="email" 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ $email ?? old('email') }}" 
                    placeholder="Correo Electronico"
                    required 
                    autocomplete="email" 
                    autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input id="password" 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    name="password" 
                    placeholder="Contraseña"
                    required 
                    autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input id="password-confirm" 
                    type="password" 
                    class="form-control" 
                    name="password_confirmation" 
                    placeholder="Confirmar Contraseña"
                    required 
                    autocomplete="new-password">
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Reestablecer</button>
                </div>
            </div>
            
        </form>
    </div>
</div>
@endsection
