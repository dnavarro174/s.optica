<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Optica - JD System</title>
  <link rel="stylesheet" href="{{ asset('iconfonts/mdi/css/materialdesignicons.min.css?v=1.1')}}">
  {{-- <link rel="stylesheet" href="{{ asset('iconfonts/puse-icons-feather/feather.css?v=1.1')}}"> --}}
  <link rel="stylesheet" href="{{ asset('css/vendor.bundle.base.css?v=1.1')}}">
  <link rel="stylesheet" href="{{ asset('css/vendor.bundle.addons.css?v=1.1')}}">
  <link rel="stylesheet" href="{{ asset('css/style.css?v=1.1')}}">
  <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css?v=1.1"> --}}
  @yield('header')
  <style>
    .sidebar .nav .nav-item.active > a.active{color: #fff;text-decoration: none;}
    a.active{color:red;text-decoration: underline;}
    .error{color: red;font-size: 12px;}
  </style>
</head>
<body class="horizontal-menu-2">

@yield('content')

<div style="display: none;" id="cargador_empresa" class="content-wrapper pt-0" align="center">
  <div class="card">
    <div class="card-body">
      <label style="color:#FFF;background-color:#ABB6BA; text-align:center;display: inline-block;">&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
      <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Realizando tarea solicitada ...</label><br><hr style="color:#003" width="50%">
    </div>
  </div>
</div>

  
<script src="{{ asset('js/vendor.bundle.base.js?v=1.1')}}"></script>  
  <script src="{{ asset('js/vendor.bundle.addons.js?v=1.1')}}"></script>

  <script src="{{ asset('js/off-canvas.js?v=1.1')}}"></script>
  <script src="{{ asset('js/hoverable-collapse.js?v=1.1')}}"></script>
  <script src="{{ asset('js/misc.js?v=1.1')}}"></script>
  <script src="{{ asset('js/settings.js?v=1.1')}}"></script>
  <script src="{{ asset('js/todolist.js?v=1.1')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('js/dashboard.js?v=1.1')}}"></script>
  <script src="{{ asset('js/horizontal-menu.js') }}"></script>
  <!-- End custom js for this page-->
  <script src="{{ asset('js/formpickers.js?v=1.1')}}"></script>
  <!-- End custom js for this page-->
  <script src="{{ asset('js/data-table.js?v=1.1')}}"></script>
  <script src="{{ asset('js/funciones.js?v=1.1')}}"></script>
  <script src="{{ asset('js/form-validation.js?v=1.1')}}"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script>
  function baseURL(url){
    return '{{url('')}}/'+url;
  }
  </script>

  @yield('footer')
  
  @include('sweet::alert')
  {{-- <script src="{{ asset('js/alerts.js?v=1.1')}}"></script> --}}
  {{-- <script src="{{ asset('js/bt-maxLength.js?v=1.1')}}"></script> --}}
  <!-- end footer_js
  THEME: DASHBOARD IPRESMA
  Desarrollado por: Dany Navarro 
  dnavarro174@gmail.com 
  -->
  
</body>
</html>