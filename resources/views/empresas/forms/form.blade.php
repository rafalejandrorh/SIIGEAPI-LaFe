{{-- Formulario para Registrar y Editar Funcionarios --}}

        @if (isset($empresa))
        {!! Form::model($empresa, ['method' => 'PUT','route' => ['empresas.update', $empresa->id]]) !!}
        @else
        {!! Form::open(array('route' => 'empresas.store','method'=>'POST')) !!}
        @endif
        <div class="card card-primary">
            <div class="card-header">
                <h5 class="text-primary"><i class="fa fa-building"></i> Datos de la Empresa</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            {!! Form::text('nombre', isset($empresa->nombre) ? $empresa->nombre : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="name">Departamento</label>
                            {!! Form::text('departamento', isset($departamento->nombre) ? $departamento->nombre : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="name">Seudónimo</label>
                            {!! Form::text('seudonimo', isset($empresa->seudonimo) ? $empresa->seudonimo : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Número de Contacto</label>
                            {!! Form::text('telefono', isset($empresa->telefono) ? $empresa->telefono : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            {!! Form::email('correo', isset($empresa->correo) ? $empresa->correo : null, array('class' => 'form-control', 'required' => 'required')) !!}
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
                            {!! Form::select('letra_cedula', ['V' => 'V', 'E' => 'E'], isset($empresa->person->letra_cedula) ? $empresa->person->letra_cedula : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Cédula</label>
                            {!! Form::text('cedula', isset($empresa->person->cedula) ? $empresa->person->cedula : null, array('class' => 'form-control', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Primer Nombre</label>
                            {!! Form::text('primer_nombre', isset($empresa->person->primer_nombre) ? $empresa->person->primer_nombre : null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Segundo Nombre</label>
                            {!! Form::text('segundo_nombre', isset($empresa->person->segundo_nombre) ? $empresa->person->segundo_nombre : null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Primer Apellido</label>
                            {!! Form::text('primer_apellido', isset($empresa->person->primer_apellido) ? $empresa->person->primer_apellido : null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Segundo Apellido</label>
                            {!! Form::text('segundo_apellido', isset($empresa->person->segundo_apellido) ? $empresa->person->segundo_apellido : null, array('class' => 'form-control', 'onkeyup'=>'mayus(this);')) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Género</label>
                            {!! Form::select('id_genero', $genero, isset($empresa->person->id_genero) ? $empresa->person->id_genero : null, array('class' => 'form-control', 'required' => 'required')) !!}
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
                                    {{ Form::checkbox('id_servicios[]', $servicio->id, isset($servicios_empresas) ? (in_array($servicio->nombre, $servicios_empresas) ? true : false) : false, 
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