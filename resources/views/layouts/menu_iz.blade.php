
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="profile-image"> 

                <img src="{{URL::route('home')}}/images/logo_ticketing_1.png" alt="Logo Ticketing" />
                <span class="online-status online"></span> 
              </div>
              <div class="profile-name">
                <p class="name">Gestión de Inventario</p>
                <!-- <p class="designation">UI/UX Designer</p> -->
              </div>
              <!-- <div class="notification-panel mt-4">
                <span><i class="mdi mdi-settings"></i></span>
                <span class="count-wrapper"><i class="mdi mdi-bell"></i><span class="count top-right bg-warning">4</span></span>
                <span><i class="mdi mdi-email"></i></span>
              </div> -->
            </div>
          </li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('home')}}"> 
            
            <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/01.png" alt="menu icon"> <span class="menu-title">Inicio</span></a> </li>

          
          <li class="nav-item">
            <a class="nav-link {{ request()->is('productos') ? 'active' : '' }}" data-toggle="collapse" href="#page-layouts" aria-expanded="false" aria-controls="page-layouts"> 
              <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/02.png" alt="menu icon"> 
              <span class="menu-title">Productos</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="page-layouts">
              <ul class="nav flex-column sub-menu">
                @if(in_array('productos',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link {{ request()->is('productos.index') ? 'active' : '' }}" href="{{ route('productos.index') }}">Productos</a></li>

                @endif
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                @if(isset( session("permisosTotales")["productos"]["permisos"]["reportes"]["permiso"]   ) && session("permisosTotales")["productos"]["permisos"]["reportes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
                @endif
                
                
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('salidas') ? 'active' : '' }}" data-toggle="collapse" href="#salidas" aria-expanded="false" aria-controls="salidas"> 
              <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/20.png" alt="menu icon"> 
              <span class="menu-title">Movimientos</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="salidas">
              <ul class="nav flex-column sub-menu">
                @if(in_array('salidas',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link {{ request()->is('salidas.index') ? 'active' : '' }}" href="{{ route('salidas.index') }}">Salidas</a></li>

                @endif
                <li class="nav-item"> <a class="nav-link" href="#">Consultas</a></li>
                @if(isset( session("permisosTotales")["salidas"]["permisos"]["reportes"]["permiso"]   ) && session("permisosTotales")["salidas"]["permisos"]["reportes"]["permiso"]== 1 ) 
                <li class="nav-item"> <a class="nav-link" href="#">Reportes</a></li>
                @endif
                
                
              </ul>
            </div>
          </li>
          @if(in_array('usuarios',session('permisosMenu')) or in_array('roles',session('permisosMenu')) )
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#users-roles" aria-expanded="false" aria-controls="lead-advanced"> <img class="menu-icon" src="{{URL::route('home')}}/images/menu_icons/05.png" alt="menu icon"> <span class="menu-title">Usuarios / Roles</span><i class="menu-arrow"></i></a>
            <div class="collapse" id="users-roles">
              <ul class="nav flex-column sub-menu">
                @if(in_array('usuarios',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('usuarios.index')}}">Usuarios</a></li>
                @endif
                @if(in_array('roles',session('permisosMenu')))
                <li class="nav-item"> <a class="nav-link" href="{{ route('roles.index')}}">Roles</a></li>
                @endif

              </ul>
            </div>
          </li>
          @endif
          
        </ul>
      </nav>