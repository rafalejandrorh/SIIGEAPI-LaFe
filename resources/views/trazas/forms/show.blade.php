<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <label for="name">Usuario</label>
            {!! Form::text('user', $data->user->users, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <label for="email">Acción</label>
            {!! Form::text('accion', $data->acciones->valor, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <label for="email">Fecha</label>
            {!! Form::text('fecha', $data->created_at, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <label for="email">Valores Modificados</label>
            {!! Form::textarea('valores_modificados', $data->valores_modificados, array('class' => 'form-control', 'readonly')) !!}
        </div>
    </div>
</div>
