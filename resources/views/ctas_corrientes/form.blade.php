
    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">RUC <span class="text-danger">*</span></label>
        <div class="col-xs-12 col-lg-10">
            <input type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number"  maxlength = "15"  class="form-control text-uppercase" id="cod_ruc" name="cod_ruc" placeholder="RUC" required="" value="{{ old('cod_ruc') }}" >
            <input type="hidden" class="form-control text-uppercase" id="cuenta_tipo" name="cuenta_tipo"  value="{{ $cuenta_tipo }}" >
            {!! $errors->first('cod_ruc', '<span class=error>:message</span>') !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">RAZÓN SOCIAL <span class="text-danger">*</span></label>
        <div class="col-xs-12 col-lg-10">
            <input type="text" class="form-control text-uppercase" id="razon_social" name="razon_social" placeholder="Razón Social" required="" value="{{ old('razon_social') }}" >
            {!! $errors->first('razon_social', '<span class=error>:message</span>') !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">DIRECCIÓN</label>
        <div class="col-xs-12 col-lg-10">
            <textarea class="form-control text-uppercase" id="direccion" name="direccion" placeholder="Dirección" cols="30" rows="3">{{ old('direccion') }}</textarea>
            {!! $errors->first('direccion', '<span class=error>:message</span>') !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">EMAIL 1<span class="text-danger">*</span></label>
        <div class="col-xs-12 col-lg-10">
            <input type="email" class="form-control text-uppercase" id="e_mail" name="e_mail" placeholder="Email" required="" value="{{ old('e_mail') }}" >
            {!! $errors->first('e_mail', '<span class=error>:message</span>') !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">EMAIL 2</label>
        <div class="col-xs-12 col-lg-10">
            <input type="email" class="form-control text-uppercase" id="e_mail_aux" name="e_mail_aux" placeholder="Email 2" value="{{ old('e_mail_aux') }}" >
            {!! $errors->first('e_mail_aux', '<span class=error>:message</span>') !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">TELEF. EMPRESA</label>
        <div class="col-xs-12 col-lg-10">
            <input type="text" class="form-control text-uppercase" id="tele_contac" name="tele_contac" maxlength="20" placeholder="Telef. Empresa" value="{{ old('tele_contac') }}" >
            {!! $errors->first('tele_contac', '<span class=error>:message</span>') !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">TELEF. 2</label>
        <div class="col-xs-12 col-lg-10">
            <input type="text" maxlength="20" class="form-control text-uppercase" id="tele" name="tele" placeholder="Telef. Empresa" value="{{ old('tele') }}" >
            {!! $errors->first('tele', '<span class=error>:message</span>') !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">CONTACTO 1</label>
        <div class="col-xs-12 col-lg-10">
            <input type="text" maxlength="100" class="form-control text-uppercase" id="contacto_1" name="contacto_1" placeholder="Nombre de contacto" value="{{ old('contacto_1') }}" >
            {!! $errors->first('contacto_1', '<span class=error>:message</span>') !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-xs-12 col-lg-2 col-form-label text-right d-none d-sm-block">CONTACTO 2</label>
        <div class="col-xs-12 col-lg-10">
            <input type="text" maxlength="100" class="form-control text-uppercase" id="contacto_2" name="contacto_2" placeholder="Nombre de contacto" value="{{ old('contacto_2') }}" >
            {!! $errors->first('contacto_2', '<span class=error>:message</span>') !!}
        </div>
    </div>



    <div class="form-group row">
      <div class="col-sm-12 text-center mt-4">
        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2"><i class='mdi mdi-content-save'></i>Guardar</button>
        <a href="{{ route('ctas_corrientes.index')}}" class="btn btn-light"><i class='mdi mdi-arrow-left'></i>Volver al listado</a>
      </div>

    </div>
