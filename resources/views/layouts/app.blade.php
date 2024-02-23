<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'SIIGEAPI')</title>
    <link rel="icon" href="{{ asset('public/img/logo-seguros-la-fe.jpg')}}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 4.1.1 -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet"> --}}
    <link href="{{ asset('public/assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/assets/css/iziToast.min.css') }}">
    <link href="{{ asset('public/assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('public/css/personalized.css')}}" type="text/css">
    <link href="{{ asset('public/assets/js/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('public/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('public/css/jquery-confirm.min.css')}}" type="text/css">

    @yield('page_css')
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/web/css/components.css')}}">
    
    @yield('page_css')

    @yield('css')
</head>
<body>

<div id="app">

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            @include('layouts.header')

        </nav>
        <div class="main-sidebar main-sidebar-postion">
            @include('layouts.sidebar')
        </div>

        <!-- Main Content -->
        <div class="main-content">
            {{-- Notificaciones Emergentes / Por acomodar en cuanto a posición  --}}
            {{-- <div class="toast">
                <div class="toast-header">
                    <div class="rounded mr-2" style="height: 16px;width: 16px;background-color: red;"></div>
                    <strong class="mr-auto">Título de la notificación</strong>
                    <small>Hace 2 segundos</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Cuerpo de la notificación
                </div>
            </div> --}}
            @yield('content')
        </div>
        
        <footer class="main-footer">
            @include('layouts.footer')
        </footer>
    </div>
</div>

<div class="lds-ripple-container loader">
    <div class="lds-ripple">
        <div></div>
        <div></div>
    </div>
    <h3 class="lds-ripple-message"></h3>
</div>

</body>

<script type="text/javascript">
    const urlMain = "{{ url("") }}";
</script>

{{-- <script src="{{ asset('public/js/app.js') }}"></script> --}}
<script src="{{ asset('public/assets/js/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/assets/js/iziToast.min.js') }}"></script>
<script src="{{ asset('public/assets/js/select2.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('public/assets/js/moment/min/moment.min.js') }}"></script>

{{-- JavaScript --}}
<script src="{{ asset('public/js/globals/funciones.js') }}"></script>
{{-- Validaciones de Formulario y Carga de Archivos --}}
<script src="{{ asset('public/js/globals/validations.js') }}"></script>

{{-- Scripts Generales --}}

<!-- Template JS File -->
<script src="{{ asset('public/web/js/stisla.js') }}"></script>
<script src="{{ asset('public/web/js/scripts.js') }}"></script>
<script src="{{ asset('public/js/jquery-confirm.min.js')}}"></script>
{{--  --}}
<script src="{{ asset('public/js/profile.js') }}"></script>
{{--  --}}

{{-- Socket.io --}}
{{-- <script> window.laravelEchoPort = '{{ env("LARAVEL_ECHO_PORT") }}'; </script> --}}
{{-- <script src="//{{request()->getHost() }}:{{ env("LARAVEL_ECHO_PORT") }}/socket.io/socket.io.js"></script> --}}

@include('sweetalert::alert')

@yield('page_js')
@yield('scripts')

{{-- Notificaciones en Tiempo real con Redis y Socket.io --}}
 <script>
    //const userId = '{{ auth()->id() }}'

    // window.Echo.channel('public-notification-channel')
    // .listen('.NotificationEvent', (data) => {
    //     showNotification(data);
    // });

    // window.Echo.private('notification-channel'+userId)
    // .listen('.NotificationEvent', (data) => {
    //     showNotification(data);
    // });

    // function showNotification(data) {
    //     $("#notification").append('<div class="alert alert-success">' + data.message + '</div>');
    //     Swal.fire({
    //     position: 'top-end',
    //     icon: data.icon,
    //     title: data.message,
    //     showConfirmButton: false,
    //     timer: 3000
    //     })
    // }
</script> 

<script>
    let loggedInUser =@json(\Illuminate\Support\Facades\Auth::user());
    let loginUrl = '{{ route('login') }}';
    const userUrl = '{{url('users')}}';
    // Loading button plugin (removed from BS4)
    (function ($) {
        $.fn.button = function (action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));

</script>

<script>
    //hideLoader();

    intervalLoader = setInterval(() => {
        hideLoader();
    }, 500);

    function hideLoader() {
        $('.loader').fadeOut('fast');
    }

    function showLoader() {
        $('.loader').fadeIn(1000);
    }

    $('.toast').toast({
        autohide: true,
        delay: 5000
    });
</script>
</html>
