{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update.password']]) !!}
<div class="row">
    <div class="col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            <label for="password">Contraseña Actual</label>
            {!! Form::password('curr_password', array('class' => 'form-control', 'id' => 'current_password')) !!}
            <span for="" id="message_password" class="text-danger"></span>
        </div>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 PasswordCollapse collapse">
        <div class="form-group">
            <label for="password">Contraseña Nueva</label>
            {!! Form::password('password', array('class' => 'form-control', 'id' => 'new_password')) !!}
        </div>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 PasswordCollapse collapse">
        <div class="form-group">
            <label for="confirm-password">Confirmar Contraseña</label>
            {!! Form::password('confirm-password', array('class' => 'form-control', 'id' => 'new_password_confirm')) !!}

        </div>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 mt-4">
        {!! Form::button('<i class="fa fa-save"> Guardar</i>', ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'save', 'disabled']) !!}
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <span for="" id="message_new_password" class="text-danger"></span>
    </div>
</div>
{!! Form::close() !!}
<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4 mt-4">
        <div class="form-group">
            <div class="custom-control custom-switch">
                {{ Form::checkbox('security_questions', isset($user->security_questions) && $user->security_questions ? 1 : 0, 
                    isset($user->security_questions) && $user->security_questions ? true : false, [
                    'class' => 'custom-control-input', 
                    'id' => 'security_questions',
                    isset($user->security_questions) && $user->security_questions ? 'checked' : ''
                    ]) 
                }}
                <label for="security_questions" class="custom-control-label">Preguntas de Seguridad</label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    {!! Form::model($user, ['method' => 'DELETE','route' => ['questions.destroy', $user->id], 'class' => 'confirmationQuestions']) !!}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {!! Form::button('<i class="fa fa-trash"> Eliminar Preguntas de Seguridad</i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
            </div>
        </div>
    {!! Form::close() !!}
</div>