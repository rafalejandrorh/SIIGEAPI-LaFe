<form class="form-inline mr-auto" action="#">
    <ul class="navbar-nav mr-3">
        <li>
            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li>
            <a href="#" onclick="launchFullScreen(document.documentElement);" class="text-white nav-link nav-link-lg">
                <i class="fas fa-expand"></i>
            </a>
        </li>
        <li class="dropdown">
            <b href="#" id="date" class="nnav-link nav-link-lg md-4 text-white"></b>
            <b href="#" id="time" class="nnav-link nav-link-lg md-4 text-white"></b>
        </li>
    </ul>
</form>
<ul class="nav navbar-nav navbar-right">
    @if(\Illuminate\Support\Facades\Auth::user())
        @if (!isset($password_status) || $password_status == false)

            @can('chat.index')
                <li class="dropdown">
                    <a href="{{ route('chat') }}" class="nav-link nav-link-lg" title="Ver Mensajes">
                        <i class="far fa-envelope"></i>
                    </a>
                </li>
            @endcan
        @endif    
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"
               class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('public/img/profile.jpg') }}"
                     class="rounded-circle mr-1 thumbnail-rounded user-thumbnail ">
                <div class="d-sm-none d-lg-inline-block">
                    {{\Illuminate\Support\Facades\Auth::user()->users}}
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                @can('users.password')
                    @if (!isset($password_status) || $password_status == false)
                        <a class="dropdown-item" href="{{ route('users.profile') }}">
                            <i class="fa fa-user"></i>  Mi Perfil
                        </a>
                    @endif
                @endcan
                <a class="dropdown-item text-danger" onclick="document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out-alt"></i>  Cerrar Sesión
                </a>

                <form id="logout-form" action="{{ url('logout/1') }}" method="POST" class="d-none">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>

        <a id="logout-formactivar" 
            onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-expire').submit();">
        </a>
        <form id="logout-expire" action="{{ url('logout/2') }}" method="POST" class="d-none">
            {{ csrf_field() }}
        </form>
    @else
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-sm-none d-lg-inline-block">{{ __('Sin Inicio de Sesión') }}</div>
            </a>
        </li>
    @endif
</ul>