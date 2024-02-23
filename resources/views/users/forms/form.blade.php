{{-- Formulario para Registrar y Editar Usuarios --}}
@if (isset($user))
    {!! Form::model($user, ['method' => 'PUT','route' => ['users.update', $user->id]]) !!}
    <div class="row">
        <div class="col-xs-5 col-sm-5 col-md-5">
            <div class="form-group">
                <label for="">Persona Asignada</label>
                <input type="text" class="form-control" value="{{$user->person->primer_nombre.' '.$user->person->primer_apellido}}" readonly>
            </div>
        </div>
@else
    {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
    <div class="row">
        <div class="col-xs-5 col-sm-5 col-md-5">
            <div class="form-group">
                <label for="">Asignar Persona</label>
                <select name="id_person" id="" class="form-control select2" required>
                    <option value="">Seleccione</option>
                @foreach ($persons as $person)
                    <option value="{{ $person->id }}"> {{$person->primer_nombre.' '.$person->primer_apellido }}</option>
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