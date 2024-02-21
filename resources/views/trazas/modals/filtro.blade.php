{{-- Modal de Filtro --}}
<div class="modal fade" id="filtrar" tabindex="-1" aria-labelledby="filtro" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="page__heading text-white"><b>Filtro</b></h3>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
            {!! Form::open(array('route' => $filtro,'method' => 'GET')) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="email">Desde</label>
                            {!! Form::date('fecha_inicio', null, ['class' => 'form-control datepicker']) !!}
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="email">Hasta</label>
                            {!! Form::date('fecha_fin', null, ['class' => 'form-control datepicker']) !!}
                        </div>
                    </div>
                    @if ($filtro != 'historial_sesion.index')
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="email">Acción</label>
                                {!! Form::select('id_accion', $accion, [], 
                                ['class' => 'form-control', 'placeholder'=>'Seleccione']) !!}
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="email">Usuario</label>
                            {!! Form::select('id_usuario', $user, [], 
                            ['class' => 'form-control', 'placeholder'=>'Seleccione']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::hidden('filtro', 1, ['class' => 'form-control']) !!}
                {!! Form::button('<i class="fa fa-check"> Aplicar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>