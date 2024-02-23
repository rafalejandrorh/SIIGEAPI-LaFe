<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <label for="email">Último inicio de Sesión</label>
            {!! Form::text('last_login', $user->last_login, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <label for="email">Usuario</label>
            {!! Form::text('user', $user->users, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-8">
        <div class="form-group">
            <label for="email">Persona</label>
            {!! Form::text('funcionario_asignado', $user->person->primer_nombre.' '.$user->person->primer_apellido, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <label for="email">Roles</label>
            {!! Form::text('roles', $roles, [
                'class' => 'form-control', 
                'readonly' => 'readonly', 
                ])
            !!}
        </div>
    </div>
</div>
{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update.email']]) !!}
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            {!! Form::text('email', $user->email, array('class' => 'form-control', 'id' => 'email')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'saveEmail', 'disabled']) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}