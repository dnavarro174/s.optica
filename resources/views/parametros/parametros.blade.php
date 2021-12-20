@extends('layouts.theme')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layouts.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial -->
      <div class="main-panel">
      <style>
      .bloque_login:hover{background:#f1f1f1;transition:all ease-out .5s;border: 1px solid #dee2e6!important}
      </style> 
        <div class="content-wrapper p-4 pt-2">
                  @if (session('alert'))
                      <div class="alert alert-success">
                          {{ session('alert') }}
                      </div>
                  @endif
          <div class="row">
            @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics text-center">
                
                
                <div class="card-body bloque_login">
                  <a href="{{ route('almacen.create') }}">
                  <div class="highlight-icon bg-info  mr-3  m-auto">
                    <i class="mdi mdi-plus text-white icon-lg"></i>
                  </div></a>

                  <h4 class="mt-4"><a href="{{ route('almacen.create') }}">Crear almacen</a></h4>
                  
                  {{-- <button class="btn btn-info btn-sm mt-3 mb-4">click</button> --}}
                </div>

              </div>
            </div>
            @endif

            @foreach ($almacen_datos as $datos)

            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="{{ route('menu_almacen.index', array('cod_empresa'=>$datos->id)) }}">{{ $datos->almacen}}</a>
                  </h5>
                   <p class="card-text">
                     {{$datos->descripcion}}
                   </p>

                  {{-- <a href="" target="_blank" class="btn btn-link"><i class="mdi mdi-link"></i> Preinscritos</a>  --}}
                  

                  <div class="dropdown float-right">
                    <button class="btn btn-white dropdown-toggle pr-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones">
                      <i class="mdi mdi-chevron-down h3"></i>{{-- mdi-dots-vertical --}}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                      @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                      <a class="dropdown-item" href="{{route('almacen.edit', $datos->id)}}"><i class="mdi mdi-brush"></i> Editar Almacen</a>
                      @endif 
                      
                      <form style="display: inline;" method="POST" action="{{ route('almacen.destroy', $datos->id)}}">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button class="dropdown-item" type="submit"><i class="mdi mdi-delete"></i> Borrar</button>
                        
                      </form>

                      {{-- @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                      <a class="dropdown-item" href="{{route('almacen.edit', $datos->id)}}"><i class="mdi mdi-brush"></i> Editar Evento</a>
                      @endif
                      <a class="dropdown-item addActividades" data-id="{{$datos->id}}" href="{{ route('actividades.index')}}" data-toggle="modal"><i class="mdi mdi-message-processing"></i> Crear Actividades</a>
                      <a class="dropdown-item" href="{{route('caii_plantilla.edit', $datos->id)}}"><i class="mdi mdi-brush"></i> Editar Plantillas</a>
                      <a class="dropdown-item" href="{{route('caii_form.edit', $datos->id)}}"><i class="mdi mdi-plus-circle"></i> Editar Formulario</a>
                      <a class="dropdown-item" href="#"><i class="mdi mdi-delete"></i> Borrar</a> --}}

        

                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
            
          </div>
          <div class="row">
            {!! $almacen_datos->appends(request()->query())->links() !!}
          </div>
          
        </div> <!-- end listado table -->

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layouts.footer')
        <!-- end footer.php -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->


@endsection