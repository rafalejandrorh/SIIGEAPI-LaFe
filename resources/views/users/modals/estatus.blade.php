        {{-- Modal de Filtro --}}
        <div class="modal fade" id="modifyStatus" tabindex="-1" aria-labelledby="filtro" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h3 class="page__heading text-white"><b>Modificar Estatus de Usuarios Masivamente</b></h3>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                {!! Form::model($Users, array('route' => $estatus, 'method' => 'GET')) !!}
                <div class="modal-body">
                    <div class="row">   
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="email">Usuarios</label>
                                <br>
                                @foreach ($Users as $user)
                                {!! Form::checkbox('user[]', $user->id, array('class' => 'form-control', 'id' => 'user'.$user->id)) !!}
                                {!! Form::label($user->funcionario->jerarquia->valor.'. '.$user->funcionario->person->primer_nombre.' '.$user->funcionario->person->primer_apellido.' || '.$user->users.' - '.($user->status ? 'Activo' : 'Inactivo')) !!}
                                <br>
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('<i class="fa fa-check"></i> Aplicar', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            </div>
        </div>