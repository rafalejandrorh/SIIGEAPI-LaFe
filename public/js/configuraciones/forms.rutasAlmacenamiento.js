$(function() {
    $('#ruta').on('keyup', function() {
        validateWriting($('#ruta').val(), '#ruta');
    });
    $('#tipo_archivo').on('keyup', function() {
        validateWriting($('#tipo_archivo').val(), '#tipo_archivo');
    });
    $('#nomenclatura').on('keyup', function() {
        validateWriting($('#nomenclatura').val(), '#nomenclatura');
    });
    $('#modulo').on('keyup', function() {
        validateWriting($('#modulo').val(), '#modulo');
    });
    $('#descripcion').on('keyup', function() {
        validateWriting($('#descripcion').val(), '#descripcion');
    });
});