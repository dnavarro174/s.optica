@extends('layouts.theme')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layouts.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      
      @include('layouts.menutop_setting_panel')
      <!-- end menu_user -->
      
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper pt-3">
          <div class="row justify-content-center">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                  <h4 class="card-title">Editar Usuarios</h4>
                  
                  <form class="forms-sample" id="cursosForm"  action="{{ route('usuarios.update', $usuarios_datos->id) }}" method="post">
                    {!! method_field('PUT') !!}
                    {!! csrf_field() !!}
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="usuario">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control " id="name" name="name" placeholder="Nombre" required="" value="{{ $usuarios_datos->name, old('name') }}" >
                        {!! $errors->first('name', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="email">Email</label>
                        <input type="email" class="form-control " id="email" name="email" required="" placeholder="Email" value="{{ $usuarios_datos->email, old('email')  }}" disabled="">
                        {!! $errors->first('email', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="password">Contraseña - <span class="text-danger">Rellenar sólo si desea cambiar de contraseña</span></label>
                        <input type="text" class="form-control " id="password" name="password" placeholder="Contraseña" value="" >
                        {{-- {!! $errors->first('password', '<span class=error>:message</span>') !!}
                        {{ $usuarios_datos->password or old('password') }} --}}
                      </div>
                    </div>
                    <div class="row">
                     
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control " id="cboEstado" name="cboEstado">
                          <option value="0">SELECCIONE</option>
                          <option value="1"
                            @if ('1' === $usuarios_datos->estado)
                              selected
                            @endif
                          >Activo</option>
                          <option value="2"
                            @if ('2' === $usuarios_datos->estado)
                              selected
                            @endif>Inactivo</option>
                        </select>
                      </div>
                    </div>

                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('usuarios.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
          
          
        </div>
        

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