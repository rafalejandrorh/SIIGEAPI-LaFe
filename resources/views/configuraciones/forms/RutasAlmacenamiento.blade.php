{{-- Formulario para Registrar y Editar Rutas de Almacenamiento --}}
@if (isset($almacenamiento))
{!! Form::model($almacenamiento, array('method' => 'PATCH', 'route' => ['rutasAlmacenamiento.update', $almacenamiento->id])) !!}
@else
{!! Form::open(array('route' => 'rutasAlmacenamiento.store','method' => 'POST')) !!}
@endif
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="name">Ruta</label>
                {!! Form::text('ruta', isset($almacenamiento->ruta) ? $almacenamiento->ruta : null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ejm: imagenes/resennados', 
                    'required' => 'required', 
                    'id' => 'ruta'
                    ])
                !!}
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="email">Tipos de Archivo</label>
                {!! Form::text('tipo_archivo', isset($almacenamiento->tipo_archivo) ? $almacenamiento->tipo_archivo : null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ejm: jpeg, png, jpg', 
                    'required' => 'required', 
                    'id' => 'tipo_archivo'
                    ])
                !!}
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="email">Nomenclatura</label>
                {!! Form::text('nomenclatura', isset($almacenamiento->nomenclatura) ? $almacenamiento->nomenclatura : null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ejm: resenna.resennado.imagen', 
                    'required' => 'required', 
                    'id' => 'nomenclatura'
                    ])
                !!}
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="email">Módulo</label>
                {!! Form::text('modulo', isset($almacenamiento->modulo) ? $almacenamiento->modulo : null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ejm: Reseñas', 
                    'required' => 'required', 
                    'id' => 'modulo'
                    ])
                !!}
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label for="email">Descripción</label>
                {!! Form::text('descripcion', isset($almacenamiento->descripcion) ? $almacenamiento->descripcion : null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ejm: Imagen correspondiente del Reseñado', 
                    'required' => 'required', 
                    'id' => 'descripcion'
                    ])
                !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit']) !!}
        </div>
    </div>
{!! Form::close() !!}
