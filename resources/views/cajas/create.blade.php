
    <div class="form-group">
        <label id="nom_caja">NOMBRE CAJA</label>
        <input type="text" class="form-control text-uppercase" id="nom_caja" name="nom_caja" placeholder="Nombre de la caja" required="" value="{{ old('nom_caja') }}" >
        
    </div>
    
    <div class="form-group row">
      <div class="col-sm-12 text-center mt-4">
        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-sm btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
        <a href="{{ route('categorias.index')}}" class="btn btn-sm btn-light"><i class='mdi mdi-arrow-left'></i>Descartar</a>
      </div>
    </div>
