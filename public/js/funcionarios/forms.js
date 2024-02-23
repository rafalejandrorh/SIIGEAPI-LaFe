$(function () {

    $('#credencial').on('keyup', function() {
        validateWriting($('#credencial').val(), '#credencial');
    });
    $('#cedula').on('keyup', function() {
        validateWriting($('#cedula').val(), '#cedula');
    });
    $('#primer_nombre').on('keyup', function() {
        validateWriting($('#primer_nombre').val(), '#primer_nombre');
    });
    $('#segundo_nombre').on('keyup', function() {
        validateWriting($('#segundo_nombre').val(), '#segundo_nombre');
    });
    $('#primer_apellido').on('keyup', function() {
        validateWriting($('#primer_apellido').val(), '#primer_apellido');
    });
    $('#segundo_apellido').on('keyup', function() {
        validateWriting($('#segundo_apellido').val(), '#segundo_apellido');
    });
    $('#fecha_nacimiento').on('keyup', function() {
        validateWriting($('#fecha_nacimiento').val(), '#fecha_nacimiento');
    });
    $('#telefono').on('keyup', function() {
        validateWriting($('#telefono').val(), '#telefono');
    });

    url = window.location;

    $("#organismo").on('change', function() {
    	getSelectJerarquiaByOrganismo()
    });

    function getSelectJerarquiaByOrganismo() {
        var idOrganismo = $('#organismo').val();
        var dataform = new FormData();
        $.ajax({
            type: 'GET',
            url: urlMain+"/jerarquia/"+idOrganismo,
            data: dataform,
            processData: false,
            contentType: false,
            success: function (result) {
                select = $('#jerarquia');
                $(select).empty();
                for (var i = 0; i < result.length; i++) {
                    select.append('<option value="'+result[i].id+'">'+result[i].valor+'</option>');
                };
            },
            error: function (xhr, ajaxOptions, thrownError) {
                if(xhr.status > "299") {
                    window.location.href = url.origin;
                }
            }
        })
    };

})