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
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12 ">{{-- offset-md-1 --}}
                      
                      <div class="d-flex justify-content-between mb-4">
                        <h4 class="card-title mb-0">Nuevo Parte de Ingreso Valorizado </h4>
                      </div>
                      
                      @if (session('warning'))
                          <div class="alert alert-warning ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('warning') }}</strong>
                          </div>
                      @endif

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('ingresos.index') }}" class="btn btn-success">Regresar</a>

                      @else
                        <form class="forms-sample" id="ingresosForm2" action="{{ route('ingresos.store') }}" method="post">
                          {!! csrf_field() !!}
                          @include ('ingresos.form')

                        </form>
                      @endif

                    </div>
                  </div>

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


  {{-- TC  --}}
  <div class="modal fade ass" id="Modal_add_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content"> 
        <form  id="f_actividad" name="f_actividad" method="post" action="{{ route('tc.store') }}" class="formarchivo" >
            {!! csrf_field() !!}
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="exampleModalLabel">Registrar Tipo de Cambio</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body pt-0 form-act">


        </div>
        <div class="modal-footer">
          <a href="{{route('tc.index')}}" target="_blank" class="btn btn-link">Ver listado</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-dark" id="saveActividades">Guardar</button>{{-- btnImport1 --}}
        </div>
        </form>
      </div>
    </div>
  </div>
  {{-- fin modal --}}
  {{-- PROVEEDORES --}}
  <div class="modal fade ass" id="Modal_add_provee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content"> 
        <form  id="f_proveedor" name="f_proveedor" method="post" action="{{ route('prov.store') }}" class="formarchivo" >
            {!! csrf_field() !!}
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="exampleModalLabel">Registrar Nuevo Proveedor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body pt-0 form-act">


        </div>
        <div class="modal-footer">
          <a href="{{route('ctas_corrientes.index', ['id'=>2])}}" target="_blank" class="btn btn-link">Ver listado</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-dark" id="saveProveedor">Guardar</button>{{-- btnImport1 --}}
        </div>
        </form>
      </div>
    </div>
  </div>
  {{-- fin modal --}}

@endsection
@section('footer')
<script src="{{ asset('js/ingresos.js')}}"></script>

<link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.min.css?id=1') }}">
{{-- <link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.themes.min.css') }}"> --}}

<script src="{{ asset('js_auto/jquery.easy-autocomplete.js')}}"></script>
<script src="{{ asset('js/autocomplete.js')}}"></script>

<script>
$(document).ready(function(){
  console.log('listo');

  $('form#f_actividad').submit(function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      console.log('Submit form ingresos');
      $('#saveActividades').attr('disabled');

      var actionformPar = $("#f_actividad").attr('action');
      //$("#saveActividades").attr("disabled","disabled");
      $.ajax({
          url: actionformPar,
          type:'POST',
          data: new FormData(this),
          processData: false,
          contentType: false,
            beforeSend: function(){
                //toastr.warning('Procesando su solicitud');
            },
          success: function(res){
              swal({
                    type: 'success',
                    title: 'Éxito...',
                    text: 'Registro Guardado',
                  })
                  .then((value) => {
                      if(res.rs!=0){
                        $('#Modal_add_actividad').modal('hide');  
                        //location.reload();
                      
                      }else{
                        $("#f_actividad")[0].reset();
                      }

                      if(res.monedas != 0){
                        $('#tipo_cambio').val('');
                        $('#moneda').empty();
                        $("#moneda").append("<option value='' selected>SELECCIONE</option>");
                        $('#add_TC').css('display','none');
                        for(var i=0;i<res.monedas.length;i++){
                          $("#moneda").append("<option val_dolares='"+res.monedas[i].TC_me+"' val_soles='"+res.monedas[i].TC_compra_mn+"' tipo='"+res.monedas[i].cod_moneda+"' val_compra_mn='"+res.monedas[i].TC_compra_mn+"' value='"+res.monedas[i].id+"' fecha='"+res.monedas[i].fecha+"'>"+res.monedas[i].nom_moneda+"</option>");
                        }
                      }else{
                        console.log('No monedas');
                      }
                      
                  });

            
          },
          error: function(xhr, status, error){
            $("#saveActividades").removeAttr("disabled");
            var err = JSON.parse(xhr.responseText);
            var tipo = err.tipo;
            console.log(err.error);
            console.log(status);
            alert(err.error);

          $("#btnGen").removeAttr("disabled");
          return false;
          
          }
      });
    });

  $('form#f_proveedor').submit(function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      console.log('Submit form proveedor');
      
      $('#saveProveedor').attr('disabled');

      var actionformPar = $("#f_proveedor").attr('action');
      //$("#saveProveedor").attr("disabled","disabled");
      $.ajax({
          url: actionformPar,
          type:'POST',
          data: new FormData(this),
          processData: false,
          contentType: false,
            beforeSend: function(){
                //toastr.warning('Procesando su solicitud');
            },
          success: function(res){
              swal({
                    type: 'success',
                    title: 'Éxito...',
                    text: 'Registro Guardado',
                  })
                  .then((value) => {
                    console.log('Save Proveedor');
                    console.log(res);
                      if(res.rs!=0){
                        $('#Modal_add_provee').modal('hide');  
                        //location.reload();
                      
                      }else{
                        $("#f_proveedor")[0].reset();
                      }

                      if(res.ok == "ok"){
                        $('#cod_ruc,#cod_ruc2,#cod_emp2').val(res.cod_ruc);
                        $('#razon_social').val(res.razon_social);
                        $('.mostrar_1').removeAttr('style');
                        $('.easy-autocomplete').css('width','100%');
                      }else{
                        console.log('Ok = no');
                      }
                      
                  });

            
          },
          error: function(xhr, status, error){
            $("#saveProveedor").removeAttr("disabled");
            var err = JSON.parse(xhr.responseText);
            var tipo = err.tipo;
            console.log(err.error);
            console.log(status);
            alert(err.error);

          $("#btnGen").removeAttr("disabled");
          return false;
          
          }
      });
    });

});

function formActividad(dia, tc_id, url){ 
      
      event.preventDefault(); 
      event.stopImmediatePropagation();

      let fecha = $('#fecha_desde').val();
      console.log(fecha);
      fecha = fecha.replace('/','-');
      fecha = fecha.replace('/','-');

      if(tc_id == "proveedor"){
        $("#Modal_add_provee").modal('show');
        var url = url+"/prov_add/"+fecha+"/"+tc_id;
      }else{
        $("#Modal_add_actividad").modal('show');
        var url = url+"/tc_add/"+fecha+"/"+tc_id;
      }
      
      $.get(url, function (resp,resul){
        /*console.log(resp);
        console.log(resul);
        console.log('Ajaxs '+ url);*/
        console.log('form creado');
        $(".form-act").html(resp);
        
      });
}

</script>
@endsection