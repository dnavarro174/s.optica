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
      
      <div class="main-panel">
        <div class="content-wrapper pt-3">
          <div class="row justify-content-center">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12">
                  
                      <h4 class="card-title mb-4">Crear nuevo contrato</h4>
                      
                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('contratos.index') }}" class="btn btn-success">Regresar</a>

                      @else
                        <form class="forms-sample" id="estudiantesForm" action="{{ route('contratos.store') }}" method="post">
                          {!! csrf_field() !!}

                          <div class="row">
                            <div class="col-sm-12 col-md-2 pl-1 pr-1">
                              <div class="form-group">
                                <label for="descripcion">Descripción <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <textarea name="descripcion" class="form-control text-uppercase" id="" cols="30" rows="3" placeholder="DESCRIPCIÓN">{{ old('descripcion') }}</textarea>
                                  {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-2 pl-1 pr-1">
                              <div class="form-group">
                                <label for="cliente">Cliente <span class="text-danger">*</span></label>
                                <a href="#" class="btn btn-link btn-sm py-0 link-add" onclick="formActividad('categoria','{{ url('') }}', 'Crear Categoría')">Crear Categoría</a> 
                                <div class="input-group mb-2">
                                  <input required="" type="text" class="form-control text-uppercase" name="cliente" placeholder="Cliente"  value="{{ old('cliente') }}" >
                                  {!! $errors->first('cliente', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <style>
                            .form-group .has-danger label.error {
                                display: flex;
                                width: 100%;
                            }
                            </style>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_od">Medida OD</label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" name="medida_od" placeholder=""  value="{{ old('medida_od') }}" >
                                  {!! $errors->first('medida_od', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_oi">Medida OI</label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" name="medida_oi" placeholder=""  value="{{ old('medida_oi') }}" >
                                  {!! $errors->first('medida_oi', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_add">Med. ADD</label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" name="medida_add" placeholder=""  value="{{ old('medida_add') }}" >
                                  {!! $errors->first('medida_add', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_dip">Medida DIP</label>
                                <div class="input-group mb-2">
                                  <input  type="text" class="form-control text-uppercase" name="medida_dip" placeholder=""  value="{{ old('medida_dip') }}" >
                                  {!! $errors->first('medida_dip', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="medida_dip">Precio <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input required type="text" class="form-control text-uppercase" name="precio_total" placeholder=""  value="{{ old('precio_total') }}" >
                                  {!! $errors->first('precio_total', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="acuenta">A cuenta <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input required type="text" class="form-control text-uppercase" name="acuenta" placeholder=""  value="{{ old('acuenta') }}" >
                                  {!! $errors->first('acuenta', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-md-1 pl-1 pr-1">
                              <div class="form-group">
                                <label for="saldo">Saldo</label>
                                <div class="input-group mb-2">
                                  <input type="text" readonly class="form-control text-uppercase" name="saldo" placeholder=""  value="{{ old('saldo') }}" >
                                  {!! $errors->first('saldo', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-2 pl-1 pr-1">
                              <div class="form-group">
                                <label for="estado">Estado <span class="text-danger">*</span></label>
                                <a href="#" class="btn btn-link btn-sm py-0 link-add" onclick="formActividad('categoria','{{ url('') }}', 'Crear Categoría')">Crear Categoría</a> 
                                <div class="input-group mb-2">
                                  <select name="estado" id="cod_categoria" class="form-control text-uppercase" required="">
                                    <option value="">SELECCIONE</option>
                                    <option value="1">ACTIVO</option>
                                    <option value="0">INACTIVO</option>
                                  </select>
                                </div>
                              </div>
                            </div>

                          </div> {{-- end row --}}


                          <div class="form-group row">
                            <div class="col-sm-12 text-center mt-4">
                              <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
                              <a href="{{ route('contratos.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
                            </div>
                          </div>

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


  {{-- PROVEEDORES --}}
  <div class="modal fade ass" id="Modal_add_categoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content"> 
        <form  id="f_categoria" name="f_categoria" method="post" action="{{ route('categorias.store') }}" class="formarchivo" >
            {!! csrf_field() !!}
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="titleModal">Crear Categoría</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <div class="modal-body pt-0 form-act">

        </div>
        <div class="modal-footer">
          <a href="{{route('categorias.index', ['id'=>2])}}" target="_blank" class="btn btn-link">Ver listado</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-dark" id="saveCategoria">Guardar</button>{{-- btnImport1 --}}
        </div>
        </form>
      </div>
    </div>
  </div>
  {{-- fin modal --}}
@endsection

@section('footer')

<script>
$(document).ready(function(){
  console.log('listo');

  $('form#f_categoria').submit(function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      console.log('Submit form categorias');
      $('#saveCategorias').attr('disabled');

      var actionformPar = $("#f_categoria").attr('action');
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

              /*var html = "Se enviaran a: <span class='font-weight-bold'>"+1+"</span> usuarios la plantilla: <span class='font-weight-bold'>"+100+'</span>';
              const wrapper = document.createElement('div');
                wrapper.innerHTML = html;*/
                swal({
                    title: "Registro Guardado",
                    text: "Se guardo el registro.",
                    icon: "success",
                    button: "Aceptar",
                    //content: wrapper,
                    /*buttons: ["Aceptar","Cancelar"],
                    dangerMode: true,*/
                })
                  .then((value) => {
                    //console.log('Save Categoria');
                      if(res.rs!=0){
                        $('#Modal_add_categoria').modal('hide'); 
                        //location.reload();
                      }else{
                        $("#f_categoria")[0].reset();
                      }

                      if(res.ok == "ok"){
                        if(res.tipo == "categoria"){
                          $('#cod_categoria').html(res.categoria);
                        }else{
                          $('#cod_umedida').html(res.categoria);
                        }
                      }else{
                        //console.log('Ok = no');
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

function formActividad(tc_id, url, title){ 
      
      event.preventDefault(); 
      event.stopImmediatePropagation();

      console.log('formActividad: '+tc_id);

      $('#titleModal').html(title);
      $("#Modal_add_categoria").modal('show');
      var url = url+"/form_add/"+tc_id;

      $.get(url, function (resp,resul){
        $(".form-act").html(resp);
        
      });
}

</script>
@endsection