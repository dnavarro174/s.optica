
    <input type="hidden" id="tipo" name="tipo" value="{{$id}}">
    <div class="form-group row">
      <label for="cod_umedida" class="col-sm-3 col-form-label d-block">Código <span class="text-danger">*</span></label>
      <div class="col-sm-8">
        <input type="text" required="" class="form-control" name="cod_umedida" placeholder="Código" value="{{ old('cod_umedida') }}" />
      </div>
    </div>
    <div class="form-group row">
      <label for="dsc_umedida" class="col-sm-3 col-form-label d-block">Nombre <span class="text-danger">*</span></label>
      <div class="col-sm-8">
        <input type="text" required="" class="form-control" name="dsc_umedida" placeholder="Nombre" value="{{ old('dsc_umedida') }}" />
      </div>
    </div>