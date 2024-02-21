{{-- Modal de Filtro --}}
<div class="modal fade" id="cargarApk" tabindex="-1" aria-labelledby="apk" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="page__heading text-white"><b>Filtro</b></h3>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
        {!! Form::open(array('route' => 'apk.store', 'method' => 'POST', 'files' => true)) !!}
        <div class="modal-body">

            <div class="row">   
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="card card-primary">
                        <div class="card-block border-bottom">
                            {!! Form::file('apk', ['class' => 'form-control-file uploadAPK', 'id'=>'file', 'accept' => '.apk']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    {!! Form::hidden('filtro', 1, array('class' => 'form-control datepicker')) !!}
                    {!! Form::button('<i class="fa fa-check"></i> Aplicar', ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit']) !!}
                </div>
            </div>

        </div>
        {!! Form::close() !!}
    </div>
    </div>
</div>