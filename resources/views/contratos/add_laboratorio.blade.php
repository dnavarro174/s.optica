
                      <input type="hidden" name="tc_id" name="tc_id" value="{{$tc_id}}">
                      <div class="form-group row">
                        <label for="laboratorio" class="col-sm-4 col-form-label d-block">Laboratorio <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                          <input type="text" required="" class="form-control text-capitalize" name="laboratorio" placeholder="Laboratorio" value="{{ old('laboratorio') }}" />
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