<nav class="navbar horizontal-layout-2 col-lg-12 col-12 p-0 d-flex flex-row align-items-start mb-4">
      <div class="container">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
          <h5 class="ml-4 pl-3"><a class="navbar-brand brand-logo" href="{{ route('home')}}">
            JMV Óptica
          </a></h5>
          <a class="navbar-brand brand-logo-mini" href="{{ route('home')}}"><img src="{{URL::route('home')}}/images/emsag.svg" alt="logo tkt mini"></a>

        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center pr-0">
          {{-- <ul class="navbar-nav header-links">
            <li class="nav-item">
              <a href="#" class="nav-link">Tareas <span class="badge badge-success ml-1">New</span></a>
            </li>
            <li class="nav-item active">
              <a href="#" class="nav-link"><i class="mdi mdi-elevation-rise"></i>Reportes</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="mdi mdi-bookmark-plus-outline"></i>Metas</a>
            </li>
          </ul> --}}
          <ul class="navbar-nav ml-auto dropdown-menus">
            
          @guest
            <li class="nav-item dropdown d-none d-xl-inline-block">
              <a class="nav-link" href="{{ route('login') }}"><span class="mr-3">{{ __('Login') }}</span></a>
            </li>
            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
          @else

          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="mr-3">Hola, {{ Auth::user()->name }} !</span><img class="img-xs rounded-circle" src="{{ asset('images/logo_ticketing_1.png')}}" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <a class="dropdown-item p-0">
                <div class="d-flex border-bottom">
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center"><i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i></div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right"><i class="mdi mdi-account-outline mr-0 text-gray"></i></div>
                  <div class="py-3 px-4 d-flex align-items-center justify-content-center"><i class="mdi mdi-alarm-check mr-0 text-gray"></i></div>
                </div>
              </a>
              <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
              </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
          </li>
          <li class="d-block d-md-none">
            <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                 <i class="mdi mdi-logout text-white"></i>
            </a>
          </li>

          @endguest

          <!-- Authentication Links -->
          </ul>
          <button type="button" class="navbar-toggler d-block d-md-none"><i class="mdi mdi-menu"></i></button>
        </div>
      </div>
      <div class="container">
        <div class="nav-bottom border-bottom border-secondary">
          <ul class="navbar-nav">
            <li class="nav-item dropdown active">
              <a class="nav-link count-indicator" href="{{ route('home') }}">
                Inicio
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" href="{{route('contratos.index')}}">
                Contratos
                {{-- Guía de Transferencia --}}
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('maestros.index')}}" class="nav-link count-indicator">Maestros</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" href="{{route('productos.index')}}" >{{-- id="finance-dropdown"  --}}
                Productos
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" href="{{route('ingresos.index')}}" >{{-- id="finance-dropdown"  --}}
                Ingresos
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" href="{{route('salidas.index')}}" >{{-- id="finance-dropdown"  --}}
                Salidas
              </a>
            </li>
            {{-- <li class="nav-item dropdown">
              <a class="nav-link count-indicator" href="{{route('guia_devolucion.index')}}">
                Guía de Devolución
              </a>
            </li> --}}
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" href="{{route('ventas.index')}}">
                Ventas
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" href="{{route('menu_almacen.index')}}">
                Reportes
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" target="_blank" href="{{route('comprobante.pdf',['tipoImpresion'=>'a4'])}}">A4</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" target="_blank" href="{{route('comprobante.pdf',['tipoImpresion'=>'ticket'])}}">Tick</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="usuarios" href="#" data-toggle="dropdown">
               <i class="mdi mdi-settings"></i>
              </a>
              <div class="dropdown-menu dropdown-left navbar-dropdown" aria-labelledby="usuarios">
                <ul>
                  <li class="dropdown-item"><a href="{{ route('usuarios.index') }}" class="dropdown-link">Usuarios</a></li>
                  <li class="dropdown-item"><a href="{{ route('roles.index') }}" class="dropdown-link">Roles</a></li>
                  
                </ul>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto d-none d-lg-block">
            <li class="nav-item mr-4">
              {{-- @if(session('almacen')<>"") --}}
              @if(session()->has('almacen'))
              <span class="badge badge-dark">{{-- Cod: {{session('cod_almacen')}} -  --}}
                {{session('almacen')['nombre']}}
              </span>
              @endif
            </li>
            {{-- <li class="nav-item mr-4">
              <form action="#">
                <div class="form-group mb-0">
                  <div class="input-group search-field">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search">
                  </div>
                </div>
              </form>
            </li> --}}
          </ul>
        </div>
      </div>
    </nav>