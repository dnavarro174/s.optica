<form class="forms-sample" id="estudiantesForm"  action="{{ route('whatsapp.send') }}" method="post">
                            
                            {!! csrf_field() !!}

                          <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Responsable</label>
                            <div class="col-lg-9 col-xl-9">
                                <input type="text" class="form-control text-uppercase" autofocus="" id="responsable" name="responsable" placeholder="Responsable" required="" onfocus=""  >
                                {!! $errors->first('responsable', '<span class=error>:message</span>') !!}
                                <div class="row" id="responsable_list"></div>
                            </div>
                          </div>

                          

                            
                            <div class="form-group row">
                              <div class="col-sm-12 text-center mt-4">
                                <button id="actionSubmit_salida" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
                                <a href="{{ route('salidas.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
                              </div>

                            </div>

                    </form>