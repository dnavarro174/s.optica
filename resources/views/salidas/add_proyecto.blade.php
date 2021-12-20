{{-- <form  id="f_actividad" name="f_actividad" method="post" action="{{ route('proy.store') }}" class="formarchivo" >
                  {!! csrf_field() !!}  --}}
                      <input type="hidden" name="tc_id" name="tc_id" value="{{$tc_id}}">
            

                      <div class="form-group row">
                        <label for="nom_proy" class="col-sm-4 col-form-label d-block">Proyecto <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                      <input type="text" required="" class="form-control text-uppercase" name="nom_proy" placeholder="PROYECTO" value="{{ old('nom_proy') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="direccion" class="col-sm-4 col-form-label d-block">Dirección </label>
                        <div class="col-sm-8">
                      <input type="text" class="form-control text-uppercase" name="direccion" placeholder="DIRECCIÓN" value="{{ old('direccion') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="descripcion" class="col-sm-4 col-form-label d-block">Descripción </label>
                        <div class="col-sm-8">
                      <input type="text" class="form-control text-uppercase" name="descripcion" placeholder="DESCRIPCIÓN" value="{{ old('descripcion') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="cliente" class="col-sm-4 col-form-label d-block">Cliente </label>
                        <div class="col-sm-8">
                      <input type="text" class="form-control text-uppercase" name="razon_social" id="razon_social" placeholder="CLIENTE" value="{{ old('cliente') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cta_cte" class="col-sm-4 col-form-label d-block">RUC </label>
                        <div class="col-sm-8">
                      <input type="text" class="form-control text-uppercase" name="cta_cte" placeholder="RUC" value="{{ old('cta_cte') }}" />
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