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
        <div class="card-header"><h4>Olvidé mi Contraseña</h4></div>

        <div class="card-body">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="email"
                           name="email"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                           placeholder="Correo Electronico"
                           required>
                    @if ($errors->has('email'))
                        <span class="error invalid-feedback">{{ $errors->first('password') }}</span>
                    @endif

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection