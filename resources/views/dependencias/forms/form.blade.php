{{-- Formulario para Registrar y Editar Funcionarios --}}

        @if (isset($dependencia))
        {!! Form::model($dependencia, ['method' => 'PUT','route' => ['dependencias.update', $dependencia->id]]) !!}
        @else
        {!! Form::open(array('route' => 'dependencias.store','method'=>'POST')) !!}
        @endif
        <div class="card card-primary">
            <div class="card-header">
                <h5 class="text-primary"><i class="fa fa-building"></i> Datos de la Dependencia</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="name">Organismo</label>
                            {!! Form::text('organismo', isset($dependencia->organismo) ? $dependencia->organismo : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="name">Dependencia</label>
                            {!! Form::text('dependencia', isset($dependencia->nombre) ? $dependencia->nombre : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="name">Ministerio</label>
                            {!! Form::text('ministerio', isset($dependencia->ministerio) ? $dependencia->ministerio : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="name">Seudónimo</label>
                            {!! Form::text('seudonimo', isset($dependencia->seudonimo) ? $dependencia->seudonimo : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Número de Contacto</label>
                            {!! Form::text('telefono', isset($dependencia->telefono) ? $dependencia->telefono : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            {!! Form::email('correo', isset($dependencia->correo) ? $dependencia->correo : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h5 class="text-primary"><i class="fa fa-user"></i> Datos del Representante</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Letra</label>
                            {!! Form::select('letra_cedula', ['V' => 'V', 'E' => 'E'], isset($dependencia->person->letra_cedula) ? $dependencia->person->letra_cedula : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Cédula</label>
                            {!! Form::text('cedula', isset($dependencia->person->cedula) ? $dependencia->person->cedula : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Primer Nombre</label>
                            {!! Form::text('primer_nombre', isset($dependencia->person->primer_nombre) ? $dependencia->person->primer_nombre : null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Segundo Nombre</label>
                            {!! Form::text('segundo_nombre', isset($dependencia->person->segundo_nombre) ? $dependencia->person->segundo_nombre : null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Primer Apellido</label>
                            {!! Form::text('primer_apellido', isset($dependencia->person->primer_apellido) ? $dependencia->person->primer_apellido : null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Segundo Apellido</label>
                            {!! Form::text('segundo_apellido', isset($dependencia->person->segundo_apellido) ? $dependencia->person->segundo_apellido : null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Género</label>
                            {!! Form::select('id_genero', $genero, isset($dependencia->person->id_genero) ? $dependencia->person->id_genero : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h5 class="text-primary"><i class="fa fa-server"></i> Servicios a Consumir</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            @foreach($servicios as $servicio)
                                <div class="custom-control custom-checkbox">
                                    {{ Form::checkbox('id_servicios[]', $servicio->id, isset($dependencias_servicios) ? (in_array($servicio->nombre, $dependencias_servicios) ? true : false) : false, 
                                    ['class' => 'custom-control-input', 'tabindex' => '3', 'id' => 'servicio'.$servicio->id]) }}
                                    <label class="custom-control-label" for="servicio{{ $servicio->id }}">{{ $servicio->nombre }} - {{ $servicio->metodo }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>