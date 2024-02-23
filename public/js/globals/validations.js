$('.numero').on('input', function () { 
    this.value = this.value.replace(/[^0-9]/g,'');
});

$('.letras').on('input', function () { 
    this.value = this.value.replace(/[^a-zA-Z ]+$/,'');
});

$('.mail').on('input', function (){ 
    if($(".mail").val().indexOf('@', 0) == -1 || $(".mail").val().indexOf('.', 0) == -1) {
        Swal.fire({
            title: 'Atención',
            text: "El correo electrónico introducido no es válido",
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        });
    }
});

$(".uploadImage").on('change', function() {
    var file = this.files[0];
    var size = this.files[0].size;
    var typefile = file.type;
    var match= ["image/jpg", "image/jpeg", "image/png"];
    document.getElementById('submit').disabled = false;
    if(!((typefile == match[0] || typefile == match[1] || typefile == match[2] || typefile == null || typefile == "")) && size > 10000000) {
        Swal.fire({
            title: 'Atención',
            text: "Por favor, ingresa un formato de Imágen válido (jpg, jpeg, png) y con un tamaño menor a 10MB",
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        });
        document.getElementById('submit').disabled = true;
        return false;
    }
});

$(".uploadAPK").on('change', function() {
    var file = document.getElementById('file');
    var extensionfile = file.value;
    var size = file.files[0].size;
    var match= /(.apk)$/i;
    document.getElementById('submit').disabled = false;
    if(!match.exec(extensionfile) && size > 30000000) {
            Swal.fire({
            title: 'Atención',
            text: "Por favor, ingresa un formato de Aplicación válido (apk) y con un tamaño menor a 30MB",
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        });
        document.getElementById('submit').disabled = true;
        return false;
    }
});

function mayus(e){
    e.value = e.value.toUpperCase();
};

function minus(e){
    e.value = e.value.toLowerCase();
};

function validateWriting(value, className) {
    if(value != ''){
        $(className).removeClass("is-invalid").addClass("is-valid");
    }else{
        $(className).removeClass("is-valid").addClass("is-invalid");
    }
}

function validateCountry(value, className) {
    if(value == 1060223)
    {
        $(className).removeClass("collapse");
    }else{
        $(className).addClass("collapse");
    }
}

function validateDocumentation(value, className) {
    if(value != 37) {
        $(className).addClass("collapse");
        $(className).addClass("collapse");
    }else{
        $(className).removeClass("collapse");
        $(className).removeClass("collapse");
    }
}
