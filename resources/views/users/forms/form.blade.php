{{-- Formulario para Registrar y Editar Usuarios --}}
@if (isset($user))
    {!! Form::model($user, ['method' => 'PUT','route' => ['users.update', $user->id]]) !!}
    <div class="row">
        <div class="col-xs-5 col-sm-5 col-md-5">
            <div class="form-group">
                <label for="">Funcionario Asignado</label>
                <input type="text" class="form-control" value="{{$user->funcionario->jerarquia->valor.'. '.$user->funcionario->person->primer_nombre.' '.$user->funcionario->person->primer_apellido}}" readonly>
            </div>
        </div>
@else
    {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
    <div class="row">
        <div class="col-xs-5 col-sm-5 col-md-5">
            <div class="form-group">
                <label for="">Asignar Funcionario</label>
                <select name="id_funcionario" id="" class="form-control select2" required>
                    <option value="">Seleccione</option>
                @foreach ($funcionarios as $funcionario)
                    <option value="{{ $funcionario->id }}"> {{$funcionario->valor.'. '.$funcionario->primer_nombre.' '.$funcionario->primer_apellido }}</option>
                @endforeach
                </select>
            </div>
        </div>
@endif
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <label for="">Roles</label>
                {!! Form::select('roles[]', $roles, isset($user->roles) ? $user->roles : [], [
                    'class' => 'form-control select2', 
                    'required' => 'required', 
                    'multiple' => 'multiple',
                    'placeholder' => 'Seleccione',
                    'id' => 'roles'
                    ])
                !!}
            </div>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="email">Usuario</label>
                {!! Form::text('users', isset($user->users) ? $user->users : null, [
                    'class' => 'form-control', 
                    'required' => 'required',
                    'id' => 'users'
                    ])
                !!}
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                {!! Form::email('email', isset($user->email) ? $user->email : null, [
                    'class' => 'form-control', 
                    'id' => 'email'
                    ])
                !!}
            </div>
        </div>
    @if (!isset($user))
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <label for="password">Password</label>
                {!! Form::password('password', [
                    'class' => 'form-control', 
                    'required' => 'required',
                    'id' => ''
                    ])
                !!}
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <label for="confirm-password">Confirmar Password</label>
                {!! Form::password('confirm-password', [
                    'class' => 'form-control', 
                    'required' => 'required',
                    'id' => ''
                    ])
                !!}
            </div>
        </div>
    @endif
    </div>
    <div class="row">
        <div class="col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                <div class="custom-control custom-switch">
                    {{ Form::checkbox('acceso_app', null, isset($user->acceso_app) && $user->acceso_app ? true : false, [
                        'class' => 'custom-control-input', 
                        'id' => 'darkSwitch',
                        isset($user->acceso_app) && $user->acceso_app ? 'checked' : ''
                        ]) 
                    }}
                    <label for="darkSwitch" class="custom-control-label">Acceso APP</label>
                </div>
            </div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                <div class="custom-control custom-switch">
                    {{ Form::checkbox('two_factors_auth', null, 
                        isset($user->two_factors_auth) && $user->two_factors_auth ? true : false, [
                        'class' => 'custom-control-input', 
                        'id' => 'two_factors_auth',
                        isset($user->two_factors_auth) && $user->two_factors_auth ? 'checked' : ''
                        ]) 
                    }}
                    <label for="two_factors_auth" class="custom-control-label">Autenticación de dos factores (2FA)</label>
                </div>
            </div>
        </div>
        <div class="col-xs-8 col-sm-8 col-md-8">
            <div class="form-group">
                <div class="custom-control custom-switch">
                    {{ Form::checkbox('security_questions', null, 
                        isset($user->security_questions) && $user->security_questions ? true : false, [
                        'class' => 'custom-control-input', 
                        'id' => 'security_questions',
                        isset($user->security_questions) && $user->security_questions ? 'checked' : ''
                        ]) 
                    }}
                    <label for="security_questions" class="custom-control-label">Preguntas de Seguridad</label>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}

    @if (isset($user))
        {!! Form::model($user, ['method' => 'PATCH','route' => ['users.reset', $user->id], 'class' => 'contrasenna']) !!}
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    {!! Form::button('<i class="fa fa-reply"> Reestablecer Contraseña</i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                </div>
            </div>
        {!! Form::close() !!}

        {!! Form::model($user, ['method' => 'DELETE','route' => ['questions.destroy', $user->id], 'class' => 'confirmation']) !!}
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    {!! Form::button('<i class="fa fa-trash"> Eliminar Preguntas de Seguridad</i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    @endif
    </div>