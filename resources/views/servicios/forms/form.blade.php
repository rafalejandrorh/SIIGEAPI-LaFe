{{-- Formulario para Registrar y Editar Funcionarios --}}
@if (isset($servicio))
{!! Form::model($servicio, ['method' => 'PUT','route' => ['servicios.update', $servicio->id]]) !!}
@else
    {!! Form::open(array('route' => 'servicios.store','method'=>'POST')) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h4 class="page__heading"><b>Datos del Servicio</b></h4>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <label for="name">Nombre</label>
            {!! Form::text('nombre', isset($servicio->nombre) ? $servicio->nombre : null, array('class' => 'form-control', 'required' => 'required')) !!}
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <label for="name">Método</label>
            {!! Form::text('metodo', isset($servicio->valor) ? $servicio->valor : null, array('class' => 'form-control', 'required' => 'required')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
    </div>
</div>
{!! Form::close() !!}