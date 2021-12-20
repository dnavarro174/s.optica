    <div class="modal-body pt-0 form-act">
          <input type="hidden" name="tc_id" name="tc_id" value="{{ 0}}">{{-- $tc_id --}}
            

                      <div class="form-group row">
                        <label for="nom_proy" class="col-sm-4 col-form-label d-block">Proyecto <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                        <input type="text" required="" autocomplete="off" class="form-control text-uppercase" name="nom_proy" placeholder="PROYECTO" value="{{ old('nom_proy') }}" />
                        </div>
                        <input type="hidden" name="fecha_desde" id="fecha_desde" value="01/01/2020">
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
                        <label for="cliente" class="col-sm-4 col-form-label d-block">Cliente <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                        <input type="text" required="" class="form-control text-uppercase" name="cliente" id="cliente" placeholder="CLIENTE" autocomplete="off" value="{{ old('cliente') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="cta_cte" class="col-sm-4 col-form-label d-block">RUC <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                      <input type="text" required="" readonly="" class="form-control text-uppercase" name="cta_cte" id="cta_cte" placeholder="RUC" value="{{ old('cta_cte') }}" />
                        </div>
                      </div>



    <div class="form-group row">
      <div class="col-sm-12 text-center mt-4">
        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
        <a href="{{ route('ctas_corrientes.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
      </div>

    </div>
