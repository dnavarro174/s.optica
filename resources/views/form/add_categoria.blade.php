
    <input type="hidden" id="tipo" name="tipo" value="{{$id}}">
    <div class="form-group row">
      <label for="categoria" class="col-sm-3 col-form-label d-block">Categoría <span class="text-danger">*</span></label>
      <div class="col-sm-8">
    <input type="text" required="" class="form-control" name="categoria" placeholder="Categoría" value="{{ old('categoria') }}" />
      </div>
    </div>