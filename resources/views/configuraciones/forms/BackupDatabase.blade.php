    {!! Form::open(array('route' => 'backup.store.database','method' => 'POST', 'files' => true)) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h5><i class="fa fa-database"></i> Base de Datos</h5>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="card card-primary">
                <div class="card-block border-bottom">
                    {!! Form::file('urlFile', ['class' => 'form-control-file upload', 'id'=>'url']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit']) !!}
    </div>
    {!! Form::close() !!}
