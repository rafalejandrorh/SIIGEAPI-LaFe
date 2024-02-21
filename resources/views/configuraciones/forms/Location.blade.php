{{-- Formulario para Editar Ubicación --}}
{!! Form::model($location, array('method' => 'PATCH', 'route' => ['location.update'])) !!}
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="email">Latitud</label>
                {!! Form::text('latitude', isset($location->latitude) ? $location->latitude : null, [
                    'class' => 'form-control', 
                    'required' => 'required',
                    'id' => 'latitude'
                    ]) 
                !!}
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <label for="name">Longitud</label>
                {!! Form::text('longitude', isset($location->longitude) ? $location->longitude : null, [
                    'class' => 'form-control', 
                    'required' => 'required',
                    'id' => 'longitude'
                    ]) 
                !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit']) !!}
        </div>
    </div>
{!! Form::close() !!}
