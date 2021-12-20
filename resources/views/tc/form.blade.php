{{-- <form  id="f_actividad" name="f_actividad" method="post" action="{{ route('tc.store') }}" class="formarchivo" >
                  {!! csrf_field() !!} --}}
                      <input type="hidden" name="tc_id" name="tc_id" value="{{$tc_id}}">
                      
                      <div class="form-group row">
                        <label for="fecha" class="col-sm-4 col-form-label d-block">Fecha <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                          <div id="datepicker-popup" class="input-group date datepicker">
                            <input readonly="" type="text" name="fecha" id="fecha" class="form-control text-uppercase" placeholder="01/01/2019" value="{{ $fecha }}" required="">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="TC_compra_mn" class="col-sm-4 col-form-label d-block">Compra en Soles <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                      <input type="text" required="" class="form-control" name="TC_compra_mn" placeholder="1" value="{{ old('TC_compra_mn') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="TC_me" class="col-sm-4 col-form-label d-block">Compra en Dolares <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                      <input type="text" required="" class="form-control" name="TC_me" placeholder="3.35" value="{{ old('TC_me') }}" />
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