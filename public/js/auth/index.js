$(function() {

    if (!"geolocation" in navigator) {
		Swal.fire({
            title: 'Atención',
            text: 'Tú navegador no permite el acceso a la Ubicación',
            icon: 'warning',
        });
	}

    const getLocation = location => {
		const coordinates = location.coords;
        validateCoordinates(coordinates);
    }

    const ErrorInLocation = err => {
		console.log("Error obteniendo ubicación: ", err);
        timeoutExpired = 3;
        if(err.code === timeoutExpired) {
            text = 'Estás experimentando problemas de Red o estás intentando ingresar con una VPN Extranjera. Serás expulsado de la Aplicación'
        }else{
            text = 'Tú navegador no permite el acceso a la Ubicación, cambia esta configuración y refresca la página';
        }
        
        Swal.fire({
            title: 'Atención',
            text: text,
            icon: 'warning',
            showCancelButton: false,
            showConfirmButton: false
        });

        showLoader();

        if(err.code === timeoutExpired) {
            interval = setInterval(() => {
                console.log('Expulsando de la Aplicación');
                window.location.replace('https://google.com');
            }, 3000); // 0.3 segundos
        }
	}

    const optionsRequest = {
		enableHighAccuracy: true, // Alta precisión
		maximumAge: true, // Caché
		timeout: 5000 // Esperar solo 5 segundos
	};

	const validateCoordinates = location => {
        const token = $('meta[name="csrf-token"]').attr('content');
		const data = {
            buscador: `${location.latitude}, ${location.longitude}`,
        }; //

        $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            type: 'GET',
            url: '/georeference/search',
            data: data,
            cache: false,
            success: function (result) {
                console.log(result);
                if(result.country != 'Venezuela') {
                    Swal.fire({
                        title: 'Atención',
                        text: 'No te encuentras en Venezuela, por tanto serás expulsado de la Aplicación',
                        icon: 'warning',
                        showCancelButton: false,
                        showConfirmButton: false
                    });

                    interval = setInterval(() => {
                        console.log('Expulsando de la Aplicación');
                        window.location.replace('https://google.com');
                    }, 1000); // 0.3 segundos

                    navigator.geolocation.clearWatch(id);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                if(xhr.status > "299") {
                    console.log(xhr);
                    console.log(thrownError);
                    window.location.href = '/';
                }
            }
        })
    };

    //id = navigator.geolocation.watchPosition(getLocation, ErrorInLocation, optionsRequest)
});