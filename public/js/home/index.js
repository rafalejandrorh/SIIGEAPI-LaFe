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
        updateCoordinates(coordinates);
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

        clearInterval(intervalLoader);
        showLoader();
        
        /*
        if(err.code === timeoutExpired) {
            interval = setInterval(() => {
                console.log('Expulsando de la Aplicación');
                window.location.replace('https://google.com');
            }, 3000); // 0.3 segundos
        }
        */
	}

    const optionsRequest = {
		enableHighAccuracy: true, // Alta precisión
		maximumAge: true, // Caché
		timeout: 5000 // Esperar solo 5 segundos
	};

	const updateCoordinates = location => {
        const token = $('meta[name="csrf-token"]').attr('content');
		const data = {
            coordinates: `${location.latitude}, ${location.longitude}`,
        };

        $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            type: 'PUT',
            url: '/coordinates/user',
            data: data,
            cache: false,
            success: function (result) {
                console.log(result.message);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                if(xhr.status > "299") {
                    console.log(xhr);
                    console.log(thrownError);
                    window.location.href = '/home';
                }
            }
        })
    };

    id = navigator.geolocation.watchPosition(getLocation, ErrorInLocation, optionsRequest)
    intervalGeolocation = setInterval(() => {
        navigator.geolocation.clearWatch(id);
        clearInterval(intervalGeolocation);
    }, 1000); //0.1 segundos
});