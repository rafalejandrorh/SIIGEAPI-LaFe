$(function() {
    $('#nomenclatura').on('keyup', function() {
        validateWriting($('#nomenclatura').val(), '#nomenclatura');
    });
    $('#descripcion').on('keyup', function() {
        validateWriting($('#descripcion').val(), '#descripcion');
    });
    $('#tipo_permiso').on('keyup', function() {
        validateWriting($('#tipo_permiso').val(), '#tipo_permiso');
    });
});