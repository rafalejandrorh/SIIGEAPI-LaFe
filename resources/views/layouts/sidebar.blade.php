<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <img class="navbar-brand-full app-header-logo" src="{{ asset('public/img/logo-seguros-la-fe.jpg') }}" width="55"
             alt="Logo Seguros La Fé">
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <img class="navbar-brand-full" src="{{ asset('public/img/logo_pmcr_sin_fondo.png') }}" width="55px" alt=""/>
        </a>
    </div>
    @if (!isset($password_status) || $password_status == false)
        <ul class="sidebar-menu">
            @include('layouts.menu')
        </ul>
    @endif
</aside>
