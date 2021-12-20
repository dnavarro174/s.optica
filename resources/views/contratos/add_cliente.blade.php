
                      <input type="hidden" name="tc_id" name="tc_id" value="{{$tc_id}}">
            
                      <div class="form-group row">
                        <label for="cod_ruc" class="col-sm-4 col-form-label d-block">DNI <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                          <input type="text" required="" class="form-control" name="cod_ruc" placeholder="DNI" value="{{ old('cod_ruc') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="razon_social" class="col-sm-4 col-form-label d-block">Nombre y Apellidos <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                          <input type="text" required="" class="form-control text-capitalize" name="razon_social" placeholder="NOMBRE Y APELLIDOS" value="{{ old('razon_social') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="edad" class="col-sm-4 col-form-label d-block">Edad </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" name="edad" placeholder="EDAD" value="{{ old('edad') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="direccion" class="col-sm-4 col-form-label d-block">Dirección </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" name="direccion" placeholder="DIRECCIÓN" value="{{ old('direccion') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="tele" class="col-sm-4 col-form-label d-block">Teléfono </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" name="tele" placeholder="TELÉFONO" value="{{ old('tele') }}" />
                        </div>
                      </div>
                        
                      <div id="cargador_excel" class="content-wrapper p-0 d-none" align="center">  {{-- msg cargando --}}
                        <div class="card bg-white" style="background:#f3f3f3 !important;" >
                          <div class="">
                            <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                            <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                          </div>
                        </div>
                      </div>{{-- msg cargando --}}