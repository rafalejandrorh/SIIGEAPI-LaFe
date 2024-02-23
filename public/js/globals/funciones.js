$(function() {
    
    contadorSesion(); 

    var timeout;
    document.onmousemove = function(){ 
        clearTimeout(timeout); 
        contadorSesion(); //aqui cargamos la funcion de inactividad
    } 

    setInterval(function() {
        var fechaActual = new Date();
        var opciones = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        var momentNow = moment();
        $('#date').html(fechaActual.toLocaleDateString('ve-es', opciones));  
        $('#time').html(momentNow.format('hh:mm:ss A'));
    }, 100); 

    function contadorSesion() {
    timeout = setTimeout(function () {
            $.confirm({
                title: 'Alerta de Inactividad!',
                content: 'La sesión esta a punto de expirar.',
                autoClose: 'expirar|10000',//cuanto tiempo necesitamos para cerrar la sess automaticamente
                type: 'red',
                icon: 'fa fa-spinner fa-spin',
                buttons: {
                    expirar: {
                        text: 'Cerrar Sesión',
                        btnClass: 'btn-red',
                        action: function () {
                            salir();
                        }
                    },
                    permanecer: function () {
                        contadorSesion(); //reinicia el conteo
                        $.alert('La Sesión ha sido reiniciada!'); //mensaje
                    }
                }
            });
        }, 2100000);//2100000 son 35 minutos
    }

    function salir() {
        $("#logout-formactivar").on('click');
        //onclick="event.preventDefault(); document.getElementById('logout-form').submit();"  
        // window.location.href = "/login"; //esta función te saca
    }

    $('.mark-as-read').on('click', function() {
        markAsReadNotification($(this));
    });
});

$('.estados').on('change', function() {
    var tipo;
    var id;
    var campo;
    tipo = 108;
    campo = '.municipios';
    id = $('.estados').val();
    cargarGeografia(tipo, id, campo);
});

function cargarGeografia(tipo, id, campo) {
    getUrl = urlMain+'/geografia/venezuela/'+tipo+'/'+id;
    $.get(getUrl, function(data) {
        $(campo).empty();
        $(campo).append('<option value="">Seleccione</option>');
        for (var i = 0; i < data.length; i++) {
            $(campo).append('<option value="'+data[i].id+'">'+data[i].valor+'</option>');
        };
    });
}

function markAsReadNotification(data = null) {
    return $.ajax({
        url: urlMain+'/notifications/markAsRead/'+data.data('id'),
        type: 'GET',
        success: function(data) {
            $('.countNotification').html(data);
            id.parents('div.notification').remove();
            if(data == 0)
            {
                $('.dropdownNotification').append('<a class="dropdown-item text-primary text-center"> Sin Notificaciones</a>');
            }
        }
    });
}

function launchFullScreen(element) {
    if(element.requestFullScreen) {
        element.requestFullScreen();
    }else if(element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
    }else if(element.webkitRequestFullScreen) {
        element.webkitRequestFullScreen();
    }
}

function redirect(e) {
    window.location = e.value;
}

function initTheme() {
    const darkThemeSelected =
      localStorage.getItem('darkSwitch') !== null &&
      localStorage.getItem('darkSwitch') === 'dark';
    darkSwitch.checked = darkThemeSelected;
    darkThemeSelected ? document.body.setAttribute('data-theme', 'dark') :
      document.body.removeAttribute('data-theme');
}

function resetTheme() {
    if (darkSwitch.checked) {
      document.body.setAttribute('data-theme', 'dark');
      localStorage.setItem('darkSwitch', 'dark');
    } else {
      document.body.removeAttribute('data-theme');
      localStorage.removeItem('darkSwitch');
    }
}

const darkSwitch = document.getElementById('toggleDarkMode');

// this is here so we can get the body dark mode before the page displays
// otherwise the page will be white for a second... 
initTheme();

window.addEventListener('load', () => {
    if (darkSwitch) {
        initTheme();
        darkSwitch.addEventListener('change', () => {
        resetTheme();
        });
    }
});