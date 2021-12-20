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
                  {{-- @if(Session::has('success'))
                  <p class="alert alert-warning">{{ Session::get('success') }}</p>
                  @endif --}}

                  @if (session('success'))
                      <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class='mdi mdi-alert-box'></i></strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                  @endif

                  
                  <h4 class="card-title">Usuarios</h4>

                  <form class="forms-sample" id="cursosForm" action="{{ route('usuarios.store') }}" method="post">
                    {!! csrf_field() !!}
                    @include ('usuarios.form')

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