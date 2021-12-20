{{-- <form  id="f_actividad" name="f_actividad" method="post" action="{{ route('prov.store') }}" class="formarchivo" >
                  {!! csrf_field() !!} --}}
                      <input type="hidden" name="tc_id" name="tc_id" value="{{$tc_id}}">
            

                      <div class="form-group row">
                        <label for="cod_ruc" class="col-sm-4 col-form-label d-block">RUC <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                      <input type="text" required="" class="form-control" name="cod_ruc" placeholder="RUC" value="{{ old('cod_ruc') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="razon_social" class="col-sm-4 col-form-label d-block">Razón Social <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                      <input type="text" required="" class="form-control" name="razon_social" placeholder="RAZÓN SOCIAL" value="{{ old('razon_social') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="contacto_1" class="col-sm-4 col-form-label d-block">Contacto </label>
                        <div class="col-sm-8">
                      <input type="text" class="form-control" name="contacto_1" placeholder="CONTACTO" value="{{ old('contacto_1') }}" />
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

                {{-- <div class="modal-footer">
                <a href="{{route('tc.index')}}" class="btn btn-link">Ver listado</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark" id="saveActividades">Guardar</button>
              </div>
              </form> --}}