<li class="side-menus {{ Request::is('home') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('home') }}">
        <i class=" fas fa-home"></i>
        <span> Inicio</span>
    </a>
</li>
@can('servicios.index') 
<li class="side-menus {{ Request::is('servicios') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('servicios.index') }}">
        <i class="fas fa-server"></i><span>Servicios</span>
    </a>
</li>
@endcan
@can('dependencias.index') 
<li class="side-menus {{ Request::is('dependencias') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('dependencias.index') }}">
        <i class="fas fa-building"></i><span>Dependencias</span>
    </a>
</li>
@endcan
@can('users.index') 
<li class="side-menus {{ Request::is('users') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('users.index') }}">
        <i class=" fas fa-user"></i>
        <span> Usuarios</span>
    </a>
</li>
@endcan
{{-- @can('roles.index')  --}}
<li class="side-menus {{ Request::is('roles') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('roles.index') }}">
        <i class=" fas fa-key"></i>
        <span> Roles</span>
    </a>
</li>
{{-- @endcan --}}
@can('tokens.index') 
<li class="side-menus {{ Request::is('tokens') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('tokens.index') }}">
        <i class="fas fa-unlock"></i><span>Tokens</span>
    </a>
</li>
@endcan
@can('sessions.index') 
<li class="side-menus {{ Request::is('sessions') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('sessions.index') }}">
        <i class=" fas fa-clock"></i>
        <span> Sesiones</span>
    </a>
</li>
@endcan
@can('historialSesion.index')
<li class="side-menus {{ Request::is('historialSesion') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('historial_sesion.index') }}">
        <i class=" fas fa-history"></i>
        <span> Historial de Sesión</span>
    </a>
</li>
@endcan
@can('trazas.index') 
<li class="side-menus {{ Request::is('trazas') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('trazas.index') }}">
        <i class=" fas fa-save"></i>
        <span> Trazas</span>
    </a>
</li>
@endcan
@can('logs.index') 
<li class="side-menus {{ Request::is('logs') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('logs') }}">
        <i class=" fas fa-file-code"></i>
        <span> Logs</span>
    </a>
</li>
@endcan
@can('configuraciones.index')
<li class="side-menus {{ Request::is('configuraciones') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('configuraciones.index') }}">
        <i class=" fas fa-cog"></i>
        <span> Configuración</span>
    </a>
</li>
@endcan
@can('documentacion.index')
<li class="side-menus">
    <a class="nav-link" href="#">
        <i class=" fas fa-book"></i>
        <span> Manual de Usuario</span>
    </a>
</li>
@endcan


