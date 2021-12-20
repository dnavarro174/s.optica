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
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <div class="row">
                    <div class="col-xl-12">
                  
                      <h4 class="card-title mb-4">Editar @if(session('cuenta_tipo') == 1) Clientes @else Proveedores @endif</h4>
                      
                      @if (session('alert'))
                          <div class="alert alert-success ">
                              <i class='mdi mdi-playlist-check'></i><strong> {{ session('alert') }}</strong>
                          </div>
                      @endif

                         <form class="forms-sample" id="estudiantesForm"  action="{{ route('ctas_corrientes.update', $datos->id) }}" method="post">
                            {!! method_field('PUT') !!}
                            {!! csrf_field() !!}

                            <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">RUC <span class="text-danger">*</span></label>
                              <div class="col-xs-12 col-lg-10">
                                  <input disabled="" type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "15"  class="form-control text-uppercase" id="cod_ruc" name="cod_ruc" placeholder="RUC" required="" value="{{ $datos->cod_ruc }}" >
                                  <input type="hidden" class="form-control text-uppercase" name="cod_ruc"  value="{{ $datos->cod_ruc }}" >
                                  <input type="hidden" class="form-control text-uppercase" id="cuenta_tipo" name="cuenta_tipo"  value="{{ $cuenta_tipo }}" >
                                  {!! $errors->first('cod_ruc', '<span class=error>:message</span>') !!}
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">RAZÓN SOCIAL <span class="text-danger">*</span></label>
                              <div class="col-xs-12 col-lg-10">
                                  <input type="text" class="form-control text-uppercase" id="razon_social" name="razon_social" placeholder="Razón Social" required="" value="{{ $datos->razon_social }}" >
                                  {!! $errors->first('razon_social', '<span class=error>:message</span>') !!}
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">DIRECCIÓN</label>
                              <div class="col-xs-12 col-lg-10">
                                  <textarea class="form-control text-uppercase" id="direccion" name="direccion" placeholder="Dirección" cols="30" rows="3">{{ $datos->direccion }}</textarea>
                                  {!! $errors->first('direccion', '<span class=error>:message</span>') !!}
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">EMAIL 1<span class="text-danger">*</span></label>
                              <div class="col-xs-12 col-lg-10">
                                  <input type="email" class="form-control text-uppercase" id="e_mail" name="e_mail" placeholder="Email" required="" value="{{ $datos->e_mail }}" >
                                  {!! $errors->first('e_mail', '<span class=error>:message</span>') !!}
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">EMAIL 2</label>
                              <div class="col-xs-12 col-lg-10">
                                  <input type="email" class="form-control text-uppercase" id="e_mail_aux" name="e_mail_aux" placeholder="Email 2" value="{{ $datos->e_mail_aux}}" >
                                  {!! $errors->first('e_mail_aux', '<span class=error>:message</span>') !!}
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">TELEF. EMPRESA</label>
                              <div class="col-xs-12 col-lg-10">
                                  <input type="text" class="form-control text-uppercase" id="tele_contac" name="tele_contac" maxlength="20" placeholder="Telef. Empresa" value="{{ $datos->tele_contac }}" >
                                  {!! $errors->first('tele_contac', '<span class=error>:message</span>') !!}
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">TELEF. 2</label>
                              <div class="col-xs-12 col-lg-10">
                                  <input type="text" maxlength="20" class="form-control text-uppercase" id="tele" name="tele" placeholder="Telef. Empresa" value="{{ $datos->tele }}" >
                                  {!! $errors->first('tele', '<span class=error>:message</span>') !!}
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">CONTACTO 1</label>
                              <div class="col-xs-12 col-lg-10">
                                  <input type="text" maxlength="100" class="form-control text-uppercase" id="contacto_1" name="contacto_1" placeholder="Nombre de contacto" value="{{ $datos->contacto_1 }}" >
                                  {!! $errors->first('contacto_1', '<span class=error>:message</span>') !!}
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">CONTACTO 2</label>
                              <div class="col-xs-12 col-lg-10">
                                  <input type="text" maxlength="100" class="form-control text-uppercase" id="contacto_2" name="contacto_2" placeholder="Nombre de contacto" value="{{ $datos->contacto_2 }}" >
                                  {!! $errors->first('contacto_2', '<span class=error>:message</span>') !!}
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">ESTADO</label>
                              <div class="col-xs-12 col-lg-10">
                                  <select class="form-control text-uppercase" id="activo" name="activo">
                                    <option value="">SELECCIONE</option>
                                    <option value="S"
                                      @if ('S' === $datos->flag_activo) selected @endif >ACTIVO</option>
                                    <option value="N"
                                      @if ('N' === $datos->flag_activo) selected @endif>INACTIVO</option>
                                  </select>
                              </div>
                          </div>

                            <div class="form-group row">
                              <div class="col-sm-12 text-center mt-4">
                                <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
                                <a href="{{ route('ctas_corrientes.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
                              </div>

                            </div>


                        </form>
                      
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

@endsection