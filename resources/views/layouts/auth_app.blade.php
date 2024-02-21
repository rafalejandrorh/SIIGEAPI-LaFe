<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'SIREPOL')</title>

    <!-- General CSS Files -->
    <link rel="icon" href="{{ asset('public/img/logo-seguros-la-fe.jpg')}}">
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('public/css/font-awesome.min.css') }}">
    <link href="{{ asset('public/assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/web/css/components.css')}}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/iziToast.min.css') }}">
    <link href="{{ asset('public/assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/assets/js/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('public/assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
</head>

<body>
<div id="app">
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="login-brand">
                        <h6><a href="#" class="text-dark nav-link" id="date"></a> <a href="#" class="text-dark nav-link" id="time"></a></h6>
                        <img src="{{ asset('public/img/logo-seguros-la-fe.jpg') }}" alt="Seguros La Fé" width="180" class="shadow-light">
                    </div>
                    @yield('content')
                    <div class="simple-footer">
                       <hr>
                       Seguros La Fé
                       <br>
                       Desarrollado por: Rafael Rivero - rafalejandrorivero@gmail.com
                       <br>
                       Copyright &copy;{{ date('Y') }} <b>{{ App\Http\Constants::applicationName.' '.App\Http\Constants::applicationVersion }}</b> <i class="fa fa-code-branch"></i>
                       <br>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- General JS Scripts -->
<script src="{{ asset('public/assets/js/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('public/assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('public/assets/js/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('public/js/globals/funciones.js')}}"></script>
{{-- <script src="{{ asset('public/js/auth/index.js')}}"></script> --}}

<!-- JS Libraies -->

@include('sweetalert::alert')

<!-- Template JS File -->
<script src="{{ asset('public/web/js/stisla.js') }}"></script>
<script src="{{ asset('public/web/js/scripts.js') }}"></script>
<!-- Page Specific JS File -->

<script>
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
</body>
</html>

