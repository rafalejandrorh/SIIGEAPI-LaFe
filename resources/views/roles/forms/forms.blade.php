{{-- Formulario para Registrar y Editar Roles --}}
@if (isset($role))
    {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
@else
    {!! Form::open(['route' => 'roles.store','method' => 'POST']) !!}
@endif


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <label for="">Nombre del Rol:</label>      
            {!! Form::text('name', isset($role->name) ? $role->name : null, [
                'class' => 'form-control',
                'id' => 'name'
                ]) 
            !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <label for="">Permisos para este Rol:</label>
            @foreach($permission as $value)
                <div class="custom-control custom-checkbox">
                    {{ Form::checkbox('permission[]', $value->id, isset($rolePermissions) ? in_array($value->id, $rolePermissions) ? true : false : false, 
                    ['class' => 'custom-control-input', 'tabindex' => '3', 'id' => 'permission'.$value->id]) }}
                    <label class="custom-control-label" for="permission{{ $value->id }}">{{ $value->description }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
    </div>
</div>
{!! Form::close() !!}