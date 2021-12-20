
    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">NOMBRE</label>
        <div class="col-xs-12 col-lg-10">
            <input type="text" class="form-control text-uppercase" id="categoria" name="categoria" placeholder="Nombre de la categoría" required="" value="{{ old('categoria') }}" >
            {!! $errors->first('categoria', '<span class=error>:message</span>') !!}
        </div>
    </div>
    
    <div class="form-group row">
      <div class="col-sm-12 text-center mt-4">
        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
        <a href="{{ route('categorias.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
      </div>
    </div>
