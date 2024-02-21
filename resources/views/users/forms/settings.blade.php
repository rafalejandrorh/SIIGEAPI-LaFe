<div class="row">
    <div class="col-xs-8 col-sm-8 col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                {{ Form::checkbox('darkMode', null, false, [
                    'class' => 'custom-control-input', 
                    'id' => 'toggleDarkMode',
                    ]) 
                }}
                <label for="toggleDarkMode" class="custom-control-label"><i class="fas fa-moon"></i> Modo Oscuro</label>
            </div>
        </div>
    </div>
</div>