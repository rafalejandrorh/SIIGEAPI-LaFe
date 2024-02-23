$(function () {
    $('#name').on('keyup', function() {
        validateWriting($('#name').val(), '#name');
    });
});