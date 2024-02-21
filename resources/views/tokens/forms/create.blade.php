{!! Form::open(array('route' => 'tokens.store','method' => 'POST','files' => true)) !!}
<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="form-group">
                <label for="name">Fecha de Generación</label>
                {!! Form::date('fecha_resenna', $fecha_hoy, array('class' => 'form-control datepicker', 'required' => 'required', 'readonly' => 'readonly')) !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <label for="">Dependencia</label>
                <select name="dependencia" id="" class="form-control select2" required>
                    <option value="">Seleccione</option>
                @foreach ($dependencias as $dependencia)
                    <option value="{{ $dependencia->id }}"> {{$dependencia->nombre.' ('.$dependencia->organismo.') - '.$dependencia->ministerio }}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="form-group">
                <label for="email">Duración del Token (En Días)</label>
                {!! Form::number('duracion_token', null, array('class' => 'form-control datepicker', 'onkeyup'=>'mayus(this);',)) !!}
            </div>
        </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
    </div>
</div>
{!! Form::close() !!}