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
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12">
                  
                      <h4 class="card-title mb-4">Crear nuevo producto</h4>
                      
                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                      @if(session()->has('info'))
                        <div class="alert alert-success" role="alert">
                          {{ session('info') }}
                        </div>
                        
                        <a href="{{ route('productos.index') }}" class="btn btn-success">Regresar</a>

                      @else
                        <form class="forms-sample" id="estudiantesForm" action="{{ route('productos.store') }}" method="post">
                          {!! csrf_field() !!}

                          <div class="row">
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input required="" type="text" class="form-control text-uppercase" name="nombre" placeholder="NOMBRE DEL PRODUCTO"  value="{{ old('nombre') }}" >
                                  {!! $errors->first('nombre', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="marca">Marca</label>
                                <div class="input-group mb-2">
                                  <input required="" type="text" class="form-control text-uppercase" name="marca" placeholder="MARCA DEL PRODUCTO"  value="{{ old('marca') }}" >
                                  {!! $errors->first('marca', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <div class="input-group mb-2">
                                  <textarea name="descripcion" class="form-control text-uppercase" id="" cols="30" rows="3" placeholder="DESCRIPCIÓN">{{ old('descripcion') }}</textarea>
                                  {!! $errors->first('descripcion', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="ubicacion">Ubicación</label>
                                <div class="input-group mb-2">
                                  <textarea name="ubicacion" class="form-control text-uppercase" id="" cols="30" rows="3" placeholder="UBICACIÓN">{{ old('ubicacion') }}</textarea>
                                  {!! $errors->first('ubicacion', '<span class=error>:message</span>') !!}
                                </div>
                              </div>
                            </div>




                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="tipo_articulo">Tipo Prod. <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <select class="form-control text-uppercase" required="" id="tipo_articulo" name="tipo_articulo">
                                    <option value="">SELECCIONE</option>
                                    <option value="M">Material</option>
                                    <option value="H">Herramientas</option>
                                  </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="cod_categoria">Categoría <span class="text-danger">*</span></label>
                                <a href="#" class="btn btn-link btn-sm py-0 link-add" onclick="formActividad('categoria','{{ url('') }}', 'Crear Categoría')">Crear Categoría</a> 
                                <div class="input-group mb-2">
                                  <select name="cod_categoria" id="cod_categoria" class="form-control text-uppercase" required="">
                                    <option value="">SELECCIONE</option>
                                    @foreach($categorias as $c)
                                    <option value="{{ $c->id }}">{{ $c->categoria }}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="precio_compra">Precio Compra <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                    <label class="sr-only" for="precio_compra">Precio compra</label>
                                    <div class="input-group mb-2">
                                      <div class="input-group-prepend">
                                        <div class="input-group-text">S/</div>
                                      </div>
                                      <input type="text" class="form-control" name="precio_compra" id="precio_compra" placeholder="0" value="{{ old('precio_venta', 0) }}">
                                    </div>
                                  {!! $errors->first('precio_compra', '<span class=error>:message</span>') !!}
                                </div>
                              </div>

                            </div>
                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="precio_venta">Precio Venta <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <label class="sr-only" for="precio_venta">Precio compra</label>
                                    <div class="input-group mb-2">
                                      <div class="input-group-prepend">
                                        <div class="input-group-text">S/</div>
                                      </div>
                                      <input type="text" class="form-control" name="precio_venta" id="precio_venta" placeholder="0" value="{{ old('precio_venta', 0) }}">
                                    </div>
                                  {!! $errors->first('precio_venta', '<span class=error>:message</span>') !!}
                                </div>
                                <input type="hidden" name="cod_sunat" value="{{$numbers}}">
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="cod_umedida">Unidad de medida <span class="text-danger">*</span></label>
                                <a href="#" class="btn btn-link btn-sm py-0 link-add" onclick="formActividad('umedida','{{ url('') }}', 'Crear Unidad de medida')">Crear U.M.</a>
                                <div class="input-group mb-2">
                                  <select name="cod_umedida" id="cod_umedida" class="form-control text-uppercase" required="">
                                    <option value="">SELECCIONE</option>
                                    @foreach($medidas as $m)
                                    <option value="{{ $m->id }}">{{ $m->dsc_umedida }}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                              <div class="form-group">
                                <label for="stock_min">Stock mínimo <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                  <input type="number" class="form-control text-uppercase" required="" id="stock_min" name="stock_min" placeholder="STOCK MÍNIMO"  value="{{ old('stock_min') }}" >
                                  {!! $errors->first('stock_min', '<span class=error>:message</span>') !!}
                                </div>
                                <input type="hidden" name="cod_sunat" value="{{$numbers}}">
                              </div>
                            </div>

                          </div> {{-- end row --}}


                          <div class="form-group row">
                            <div class="col-sm-12 text-center mt-4">
                              <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
                              <a href="{{ route('productos.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
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