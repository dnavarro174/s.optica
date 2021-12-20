<div class="row">
                      <div class="col-sm-12 form-group">
                        <label class=" col-form-label" for="usuario">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control " id="name" name="name" required="" placeholder="Nombre" value="{{ old('name') }}" >
                        {!! $errors->first('name', '<span class=error>:message</span>') !!}
                      </div>
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control " id="email" name="email" placeholder="Email" required="" value="{{ old('email') }}" >
                        {!! $errors->first('email', '<span class=error>:message</span>') !!}
                      </div>

                      <div class="col-sm-12 form-group">
                        <label class="col-form-label" for="password">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control " id="password" name="password" required="" placeholder="Contraseña" value="{{ old('password') }}" >
                        {!! $errors->first('password', '<span class=error>:message</span>') !!}
                      </div>
                    </div>
                    <div class="row">
                      
                      <div class="col-sm-12 form-group">
                        <label class="col-form-label">Estado</label>
                        <select class="form-control " id="cboEstado" name="cboEstado">
                          <option value="0">SELECCIONE</option>
                          <option value="1"
                          {{ old('cboEstado')==1 ? "selected":""}}
                          >Activo</option>
                          <option value="2"
                          {{ old('cboEstado')==2 ? "selected":""}}
                          >Inactivo</option>
                        </select>
                      </div>
                    </div>

                    
                    <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar</button>
                        <a href="{{ route('usuarios.index')}}" class="btn btn-light">Volver al listado</a>
                      </div>
                    </div>