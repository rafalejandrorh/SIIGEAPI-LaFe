<div class="card card-primary">
    <div class="card-header">
        <h5 class="text-primary"><i class="fa fa-id-badge"></i> Datos de Usuario</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <label for="email">Funcionario Asignado</label>
                    {!! Form::text('user', $data['user']->funcionario->jerarquia->valor.'. '.$data['user']->funcionario->person->primer_nombre.' '.$data['user']->funcionario->person->primer_apellido, [
                        'class' => 'form-control', 
                        'id' => 'user',
                        'readonly', 'readonly'
                        ])
                    !!}
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    <label for="email">Usuario</label>
                    {!! Form::text('user', $data['user']->users, [
                        'class' => 'form-control', 
                        'id' => 'user',
                        'readonly', 'readonly'
                        ])
                    !!}
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <label for="email">Último Inicio de Sesión</label>
                    {!! Form::text('last_login', $data['user']->last_login, [
                        'class' => 'form-control', 
                        'id' => 'last_login',
                        'readonly', 'readonly'
                        ])
                    !!}
                </div>
            </div>
            @can('users.update_status')
                {!! Form::model($data['user'], ['method' => 'PATCH','route' => ['users.update_status', $data['user']->id]]) !!}
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label for="email">Estatus</label>
                            {!! Form::button($data['user']->status ? 'Activo' : 'Inactivo', ['type' => 'submit', 'class' => $data['user']->status ? 'btn btn-info' : 'btn btn-danger']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
            @endcan
        </div>
    </div>
</div>
    