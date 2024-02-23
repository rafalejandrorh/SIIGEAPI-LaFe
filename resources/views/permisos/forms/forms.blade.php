{{-- Formulario para Registrar y Editar Permisos --}}
@if (isset($permiso))
{!! Form::model($permiso, array('method' => 'PATCH', 'route' => ['permisos.update', $permiso->id])) !!}
@else
{!! Form::open(array('route' => 'permisos.store','method' => 'POST')) !!}
@endif
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="name">Nomenclatura</label>
                {!! Form::text('nomenclatura', isset($permiso->name) ? $permiso->name : null, [
                    'class' => 'form-control', 
                    'required' => 'required',
                    'id' => 'nomenclatura'
                    ]) 
                !!}
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="email">Descripción</label>
                {!! Form::text('descripcion', isset($permiso->description) ? $permiso->description : null, [
                    'class' => 'form-control', 
                    'required' => 'required',
                    'id' => 'descripcion'
                    ]) 
                !!}
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="email">Tipo de Permiso</label>
                {!! Form::text('tipo_permiso', isset($permiso->guard_name) ? $permiso->guard_name : null, [
                    'class' => 'form-control', 
                    'required' => 'required',
                    'id' => 'tipo_permiso'
                    ]) 
                !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit']) !!}
        </div>
    </div>
{!! Form::close() !!}
