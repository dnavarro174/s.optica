@extends('layouts.theme')

@section('content')

  <div class="container-scroller bg_fondo2">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">


            <div class="auto-form-wrapper bg-transparent">
              <h3 class="h3 text-white text-center mb-4">Sistema de Óptica</h3>
              <form method="POST" action="{{ route('login') }}" class="sistema">
                
                @csrf

                <div class="form-group">
                  {{-- <label class="label">Usuario</label> --}}
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="mdi mdi-account-outline"></i></span>
                    </div>
                    <input id="email" type="email" class="form-control rounded-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
                  
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="mdi mdi-lock-outline"></i></span>
                    </div>
                    <input id="password" type="password" class="form-control rounded-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-dark submit-btn btn-block">
                    {{ __('Login') }}
                  </button>
                  {{-- @if (Route::has('password.request'))
                      <a class="btn btn-link" href="{{ route('password.request') }}">
                          {{ __('Forgot Your Password?') }}
                      </a>
                  @endif --}}
                </div>
                
              </form>
            </div>
            <ul class="auth-footer">
              <li><a href="https://jjdsystem.com/" target="_blank">Desarrollo de sistemas</a></li>
              <li><a href="https://jjdsystem.com/contacto" target="_blank">Contacto</a></li>
              <li><a href="#ayuda">Ayuda</a></li>
            </ul>
            <p class="footer-text text-center">Copyright © 2020 JD System. Todos los derechos reservados.</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
 


@endsection