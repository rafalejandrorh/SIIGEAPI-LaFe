$(function() {
    $('.eliminar').on('submit', function(e){
        e.preventDefault();
    
        Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
        }).then((result) => {
        if (result.value) {
            this.submit();
        }
        })
    
    });
});
