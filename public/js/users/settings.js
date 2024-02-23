$(function () {
    $('#current_password').on('keyup', function() {
        verifyCurrentPassword();
    });

    $('#new_password_confirm').on('change', function() {
        verifyNewPassword();
    });

    $('#new_password').on('change', function() {
        verifyNewPassword();
    });

    $('#email').on('keyup', function() {
        document.getElementById('saveEmail').disabled = false
    });

    $('#two_factors_auth').on('click', function(){
        update2FA();
    });

    $('#security_questions').on('click', function(){
        updateSecurityQuestions();
    });

    $('.confirmationQuestions').on('click', function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, estoy seguro!'
        }).then((result) => {
            if (result.value) {
                this.submit();
            }
        })
    })

    $('.confirmation2FA').on('click', function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, estoy seguro!'
        }).then((result) => {
            if (result.value) {
                this.submit();
            }
        })
    })

    function update2FA() {
        const token = $('meta[name="csrf-token"]').attr('content');
        let two_factors_auth = $('#two_factors_auth').val(); 
        $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            url: urlMain+'/profile/user/2FA',
            type: 'POST',
            data: {two_factors_auth:two_factors_auth},
            cache: false,
            success: function(data) {
                if(two_factors_auth == 1) {
                    $('#two_factors_auth').val(0);
                }else{
                    $('#two_factors_auth').val(1)
                };
                Swal.fire({
                    title: data.title,
                    icon: data.icon,
                });
            }
        });
    }

    function updateSecurityQuestions() {
        const token = $('meta[name="csrf-token"]').attr('content');
        let security_questions = $('#security_questions').val(); 
        $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            url: urlMain+'/profile/user/questions',
            type: 'POST',
            data: {security_questions:security_questions},
            cache: false,
            success: function(data) {
                if(security_questions == 1) {
                    $('#security_questions').val(0);
                }else{
                    $('#security_questions').val(1)
                };
                Swal.fire({
                    title: data.title,
                    icon: data.icon,
                });
            }
        });
    }

    function verifyCurrentPassword() {
        current_password = $('#current_password').val();

        if(current_password != ''){
            document.getElementById('save').disabled = false;
            $('#message_password').html('');
            $('.PasswordCollapse').removeClass("collapse");
        }else{
            document.getElementById('save').disabled = true;
            $('#message_password').html('Ingrese su Contraseña Actual');
            $('#message_new_password').html('');
            $('.PasswordCollapse').addClass("collapse");
        }
    }

    function verifyNewPassword() {
        messagePassword = null;
        messageLengthPassword = null;
        current_password = $('#current_password').val();
        new_password = $('#new_password').val();
        new_password_confirm = $('#new_password_confirm').val();

        if(new_password != new_password_confirm && new_password_confirm != '') {
            //alert('Las Contraseñas no coinciden');
            messagePassword = 'Las Contraseñas no coinciden';
        }else{
            messagePassword = '';
        }

        if(new_password.length < 8){
            messageLengthPassword = 'Debe tener al menos 8 caracteres';
        }else{
            messageLengthPassword = '';
        }

        if(messagePassword != '' && messageLengthPassword == '') {
            document.getElementById('save').disabled = true;
            $('#message_new_password').html(messagePassword);
        }else if(messagePassword != '' && messageLengthPassword != '') {
            document.getElementById('save').disabled = true;
            $('#message_new_password').html(messagePassword+' y '+messageLengthPassword);
        }else if(messagePassword == '' && messageLengthPassword != '') {
            document.getElementById('save').disabled = true;
            $('#message_new_password').html(messageLengthPassword);
        }else{
            document.getElementById('save').disabled = false;
            $('#message_new_password').html('');
        }

        if(current_password == ''){
            document.getElementById('save').disabled = true;
            $('#message_password').html('Ingrese su Contraseña Actual');
        }
        
    }
});