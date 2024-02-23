{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <label for="">Persona Asignada</label>
            <input type="text" class="form-control" value="{{$user->person->primer_nombre.' '.$user->person->segundo_nombre}}" readonly>
        </div>
    </div>
    
    <div class="col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            <label for="">Roles</label>
            {!! Form::select('roles', $roles, $user->role, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>

    <div class="col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            <label for="email">Estatus</label>
            {!! Form::text('id_estatus', $user->status ? 'Activo' : 'Inactivo', array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>
    
    <div class="col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            <label for="email">Usuario</label>
            {!! Form::text('users', $user->users, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div> 

    <div class="col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            {!! Form::text('email', $user->email, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div> 

    <div class="col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            <label for="email">Último Inicio de Sesión</label>
            {!! Form::text('users', $user->last_login, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}