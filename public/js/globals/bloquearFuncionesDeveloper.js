$(document).ready(function () {
    $(document).bind("contextmenu", function (e) {
        e.preventDefault();
        alert('Acción no permitida')
    })//Evitar click derecho que pueda ver el menu del click. 
    $(document).keydown(function (event) {
        if (event.keyCode == 123) {
            alert('Acción no permitida')
            return false; //Prevent from F12
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
            alert('Acción no permitida')
            return false; //Prevent from ctrl+shift+i
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 67) {
            alert('Acción no permitida')
            return false;
        } else if (event.ctrlKey && event.keyCode == 83) {
            alert('Acción no permitida')
            return false;
        } else if (event.ctrlKey && event.keyCode == 85) {
            alert('Acción no permitida')
            return false;
        } else if (event.ctrlKey && event.keyCode == 65) {
            alert('Acción no permitida')
            return false;
        } 
    })
    function click(){
        if(event.button==0 && event.altKey){
            alert('Acción no permitida');
        }
    }
    document.onmousedown=click 
})