var _flagMod = 0;
(function($) {
  'use strict';
  $.validator.setDefaults({
    submitHandler: function() {
      console.log('paso');
      submit();
      //alert("submitted!");
      
    }
  });
  $(function() {


    //$("#estudiantesForm :input").attr("disabled", true);
    //$("#estudiantesForm #cboTipDoc").attr("disabled", false);
    

    // DNI - CARNET EXTR.
    $("#cboTipDoc").on('change', function(){
      var tipo = $("#cboTipDoc").val();
      if(tipo == 1){
        var inputdni = "";
        inputdni = $("#inputdni").attr("type", 'number');

      }
      if(tipo == 2){
        inputdni = $("#inputdni").attr("type", 'text').attr('maxlength',15);

      }
      console.log(tipo);
    });

    // PASAPORTE  : CONTINENTE PAIS CIUDAD
    $('#cboDepartamento').change(function (event){
      
      $.get("ubigeo/"+event.target.value+"",function(resp,depa){
        console.log("ID: "+event.target.value);
        if(resp.length>0){
          console.log("valor resp ="+resp.length);

          $('#cboProvincia').empty();
          $("#cboProvincia").append("<option value='0'>SELECCIONE</option>");
          for(var i=0;i<resp.length;i++){
            $("#cboProvincia").append("<option value='"+resp[i].ubigeo_id+"'>"+resp[i].nombre+"</option>");
          }
        }else{
          console.log("0 Registros.");
        }
        
      });
    });

    $('#cboProvincia').change(function (event){
      //console.log("PASO3");
      console.log("ID: "+event.target.value);
      $.get("ubigeo2/"+event.target.value+"",function(resp,depa){
        if(resp.length>0){
          console.log("valor resp ="+resp.length);
          //console.log('aaaa'+event.target.value);
          $('#cboDistrito').empty();
          $("#cboDistrito").append("<option value='0'>SELECCIONE</option>");
          for(var i=0;i<resp.length;i++){
            $("#cboDistrito").append("<option value='"+resp[i].ubigeo_id+"'>"+resp[i].nombre+"</option>");
          }
        }else{console.log("0 Registros.");}
      });
    });

    /* plantillaHTML */
    $('.openHTML').click(function(){

      $('#openHTML').modal('show');
      var id = $(this).data('id');
      var url = "verHTML/"+id+"";

      $.get(url,function(resp, resul){
        $('#plantillaHTML').empty();
        //console.log(resp);
        $("#plantillaHTML").html(resp.plantillahtml); 
      });
    });

    // enviar correos a una programación
    $('.pcorreos').click(function(){

      var id_evento = "";

      if ($('.btn-html').is(':checked')) {
        
        $('li input:radio:checked').each(function(){
          var id_plantilla = $(this).val();
          //alert('id plan ' +id_plantilla);
        });

      }else{
        //$('#delete_selec').attr('disabled',true);
        alert('Debe seleccionar una plantilla HTML');
        $('.bloque_plantilla').css('background','#d5ebf3');

        //console.log('boton desact');
      }


      /*$('#openHTML').modal('show');
      var id = $(this).data('id');
      var url = "verHTML/"+id+"";

      $.get(url,function(resp, resul){
        $('#plantillaHTML').empty();
        //console.log(resp);
        $("#plantillaHTML").html(resp.plantillahtml); 
      });*/

    });

    var check_todo = 0;

    $('#chek_enviarTodos').click(function(e) {

      if ($('#chek_enviarTodos').is(':checked')) {

        var check_enviar = $('#chek_enviarTodos').val();
        console.log('check_enviar: activado ='+check_enviar);

        if ($('._check').is(':checked')) {

          $('input[type=checkbox]:checked').each(function(i,j){
            //alert('Usted ha seleccionado ');
            $(".btn-delete").prop('checked', false);
            //$(".btn-delete").prop('disabled', true).prop('checked', false);
          });
        }

        check_todo = 1;
         
      }else{
        console.log('check_enviar desactivado');
      }

    });
    
    //$('#enviarCorreos').click(function(e) {
    $('#form_html').submit(function(e) {
      
      $('#enviarCorreos').attr('disabled', true);

      if ($('#chek_enviarTodos').is(':checked')) {

        var check_enviar = $('#chek_enviarTodos').val();
        console.log('check_enviar ='+check_enviar);
          
      }

      // check estudiantes
      var datoSelect;
      var check_estudiante = 0;
      var check_plantilla = 0;
      var arrayEstudiante  = [];
      var arrayPosition = [];

      var check_  = 0;

      if(check_todo == 0){
        if ($('._check').is(':checked')) {

          $('input[type=checkbox]:checked').each(function(i,j){
            var check_estudiante = $(this).val();
            arrayPosition.push(i);
            arrayEstudiante.push(check_estudiante);
          });

          check_ = 1;
          
        }else{
          alert('Debe seleccionar algún estudiante');
          $('#enviarCorreos').attr('disabled', false);
        }

      }
      

      // check plantilla
      var check_2  = 0;

      if ($('.btn-html').is(':checked')) {
        
        $('li input:radio:checked').each(function(i,j){
          check_plantilla = $(this).val();

        });
        check_2 = 1;

      }else{

        alert('Debe seleccionar una plantilla HTML');
        $('.bloque_plantilla').css('background','#d5ebf3');
        $('#enviarCorreos').attr('disabled', false);
      }

      if( (check_ == 1 && check_2 == 1) || (check_todo == 1 && check_2 == 1)){
        //console.log('Plantilla html id: '+check_plantilla);
        console.log('Paso check 1 y 2 ');
        console.log('Enviar Correos submit');
        
      }else{
        e.preventDefault();

      }


    });

    // PROCESAR CORREOS CON BOTON

    $('.eCorreos').click(function() {
      var keys = $(this).data('id');
      console.log('eCorreos=' +keys);
      //alert(keys);
      var url = "plantillaemail/procesaremailsxlote/"+keys+"";

      $.get(url,function(resp, resul){

        if(resp.length > 0){

          //$('#plantillaHTML').empty();
          console.log(resp);
          //$("#plantillaHTML").html(resp.plantillahtml);
          swal("Correos Enviados", "Cantidad de Correos Procesados: "+resp.length+"", "success");

        }else{

          swal("Aviso", "...no existen estudiantes en esta programación.");
          console.log('Menor');

        }
        
      });


    });

    // check eliminar registros
    $('._check').click(function() {
  
        $('#enviarCorreos').attr('disabled',false);
        var id = $(this).data('id');
        //console.log('_check=' +id);
        //$(this).parents('tr').fadeOut(1000);

        if ($('._check').is(':checked')) {
          $("#chek_enviarTodos").prop('checked', false);
        }else{
          $('#enviarCorreos').attr('disabled',true);
          console.log('_check desact');
        }
         //row.fadeOut(1000);
    });

    var $inputdni=$("#inputdni");
    $inputdni.bind("change",function(){
      var pos=this.value;
      $.get("vdni/"+event.target.value+"",function(resp,depa){
        if(resp.length>0){
          console.log("El DNI ya esta registrado.");
          //alert("El DNI ya esta registrado.");
          swal({ type:'error',title:'El DNI ya esta registrado.',showConfirmButton: false,timer: 1500});
          $('#inputdni').val("");
        }/*else{
          console.log("El DNI no esta registrado.");
        }*/
      });

    });
    // validate the comment form when it is submitted
    $("#commentForm").validate({
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
    // validate signup form on keyup and submit
    $("#estudiantesForm").validate({ 
      rules: {
        inputdni: "required",
        //inputNombres: "required",
        inputdni: {
          required: true,
          minlength: 8,
          maxlength: 15
          //maxlength: 8
        },
        inputEmail: {
          //required: true,
          email: true
        }
        
      },
      messages: {
        inputdni: "Escribir DNI",
        //inputNombres: "Please enter your lastname",
        inputdni: {
          required: "Escribir DNI",
          minlength: "DNI debe tener 8 digitos",
          maxlength: "DNI debe máximo 15 digitos",
        },
        
        email: "Escribir email valido"
      },
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
    
    //------------------------ESTUDIANTES-------------------------//

    $('form#estudiantesImportSave').submit( function( e ) {
        $("#btnSumImport").attr("disabled","disabled");
        var _numC = $("#totCol").val();
        var x;
        var _flag=0;
        var _flagDni=0;
        var _flagCodProg=0;
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() > 0 ){
            _flag = 1;break;
          }
        }
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() == 1 ){
            _flagDni = 1;break;
          }
        }
        for(x = 0; x < _numC ; x++){
          // 14 // 7 ES LA POSC DEL ARCHIVO importresults.blade.php
          if($("#cmbOrganizar" + x).val() == 14 ){
            _flagCodProg = 1;break;
          }
        }

        if(_flag==0){
            //alert("Por favor asignar al menos una columna");
            swal({ type:'info',title:'Por favor asignar al menos una columna',showConfirmButton: false,timer: 1500});
            $("#btnSumImport").removeAttr("disabled");
            return false;
        }

        if(_flagDni==0){
            //alert("El DNI es un campo obligatorio");
            swal({ type:'info',title:'El DNI es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport").removeAttr("disabled");
            return false;
        }
        if(_flagCodProg==0){
            swal({ type:'info',title:'El CÓGIDO PROGRAMACIÓN es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport").removeAttr("disabled");
            return false;
        }

        $("#cargador_excel2").attr("style","display: block; position: -webkit-sticky;position: sticky;left: 0;");
        e.preventDefault();
        var actionform = $(this).attr('action');
     // $("#btnGuardar").attr("disabled","disabled");
        $.ajax({
            url: actionform,
            type:'POST',
            data: new FormData( this ),
            processData: false,
            contentType: false,
              beforeSend: function(){
                  //toastr.warning('Procesando su solicitud');
              },
            success: function(respuesta){
                //console.log(respuesta);
                $("#cargador_excel2").attr("style","display: none");
                
                //swal({ type:'success',title:'Datos cargados',showConfirmButton: false,timer: 1500});
                swal({
                  title: "Registro importado",
                  text: "Los registros fueron importados correctamente.",
                  icon: "success",
                  button: "Cerrar",
                });

                $("#btnSumImport").removeAttr("disabled");
                $("#iframePrev").attr("style","display: block;");
                $("#estudiantesImportSave").attr("style","display: none");
                $("#btnSumImport").attr("style","display: none");
                $("#iframePrev").attr("style","display: none ");
                //sleep(500);
                document.getElementById("iframePrev").contentDocument.location.reload(true);
                $("#iframePrev").attr("style","display: block;border: 1px solid #e6e6e6;");
                
                //$('#order-listing').DataTable().ajax.reload();

                e.preventDefault();
            },
            error: function(xhr, status, error){
              $("#cargador_excel2").attr("style","display: none");
              var err = JSON.parse(xhr.responseText);           
            }
        });
    });


      $("#btnCerrarIf").click(function(){
        //location.href="/estudiantes";
        eximForm();
      })

      $("#btnRegresar").click(function(){
        _flagMod = 1;
        eximForm();
      })

      $('#Modal_organizar').on('hidden.bs.modal', function () {
        if(_flagMod == 0){
          //location.href="/estudiantes"; 
          cerrar_form();
        }

      });

     $("#archivo").change(function (){
        _flagMod = 0;
        $("#f_cargar_datos_estudiantes").submit();
     });

    $('form#f_cargar_datos_estudiantes').submit( function( event ) {
        $("#btnImport1").attr("disabled","disabled");
        $("#cargador_excel").attr("style","display:block");      
        event.preventDefault();        
    }).validate({
      // Rules for form validation
      errorClass: 'error', 
        rules : {
          archivo: {
            required: true,
            extension: "xls|csv"
          }
        },
        // Messages for form validation
        messages : {
          archivo : {
            required: "Solo se aceptan archivos XLS y CSV"
          }

        },
        submitHandler: function(form) {

          $("#iframePrev").attr("style","display: none ");
          $("#estudiantesImportSave").attr("style","display: block");
          $("#btnSumImport").attr("style","display: block");
                
         // $("#btnImport1").removeAttr("disabled");

          var actionform = $("#f_cargar_datos_estudiantes").attr('action');
          //$("#btnGuardar").attr("disabled","disabled");
          $.ajax({
              url: actionform,
              type:'POST',
              data: new FormData( form ),
              processData: false,
              contentType: false,
                beforeSend: function(){
                    //toastr.warning('Procesando su solicitud');
                },
              success: function(datos){
                //console.log(datos);
                $("#hdnTabla").val(datos);
                $("#btnImport1").removeAttr("disabled");    
                $("#cargador_excel").attr("style","display:none");       

                $('#Modal_estudiantes').modal('hide');
                $('#tbl_estudiantes_imp_ord').html("");
                var _combo = "<select class='form-control text-uppercase' required onchange='cmbOrganiza(this)' id='cambiar' name='cambiar' style='width:200px'>";
                _combo = _combo + "<option value=0></option>";
                _combo = _combo + "<option value=1>dni_doc</option>";
                _combo = _combo + "<option value=2>nombres</option>";
                _combo = _combo + "<option value=3>ap_paterno</option>";
                _combo = _combo + "<option value=4>ap_materno</option>";
                _combo = _combo + "<option value=5>fecha_nac</option>";
                _combo = _combo + "<option value=16>organizacion</option>";
                _combo = _combo + "<option value=6>cargo</option>";
                _combo = _combo + "<option value=7>profesion</option>";
                _combo = _combo + "<option value=8>direccion</option>";
                _combo = _combo + "<option value=9>telefono</option>";
                _combo = _combo + "<option value=10>celular</option>";
                _combo = _combo + "<option value=11>email</option>";
                _combo = _combo + "<option value=12>sexo</option>";
                /*_combo = _combo + "<option value=13>Entidad</option>";*/
                _combo = _combo + "<option value=14>Codigo Programación</option>";
                _combo = _combo + "<option value=15>Email_Labor</option>";
                _combo = _combo + "</select>";

                $("#Modal_organizar").modal('show').addClass('modal-big');

                if(datos.length>0){
                  var _strTable = '<thead><tr role="row">';
                  var _numCol = datos[0].length ;
                  /*if(_numCol>12){
                    alert("El número máximo de columnas es 12, por favor revise su archivo de Excel");
                    return false;
                  }*/
                  $("#totCol").val(_numCol);
                  var x;
                  var xx = 1;
                  for(x = 0; x < _numCol ; x++){
                    var _nnombre = "cmbOrganizar"+ xx;
                    var _num = xx;
                    
                    _combo = _combo.replace("cambiar", _nnombre );
                    _combo = _combo.replace("cambiar", _nnombre );

                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );
                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );

                    _strTable = _strTable + '<th >' + _combo + '</th>';
                    xx++;
                  }
                  _strTable = _strTable + '</tr></thead>';
                  
                  var y;
                  _strTable = _strTable + '<tbody>';
                  for(y=0; y< datos.length; y++){
                    _strTable = _strTable + '<tr>';
                    var z;  
                    for(z=0; z< _numCol; z++){
                      var _clase = "col" + (z+1);
                      _strTable = _strTable + '<td style="background:#E5E5E5;" class="'+_clase+'">' + datos[y][z] + '</td>' ;
                    }
                    _strTable = _strTable + '</tr>';
                  }
                  _strTable = _strTable + '</tbody>';
                  $('#tbl_estudiantes_imp_ord').append(_strTable);

                }
                organizaFirstRow();
                  
              },
              error: function(xhr, status, error){ 
                    var err = JSON.parse(xhr.responseText);
                    //alert(err["error"]);
                    swal(err["error"]);
                    $("#btnImport1").removeAttr("disabled");    
                    $("#cargador_excel").attr("style","display:none");                        

              }
          });
        },
      errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
      }
    });


    //------------------------ END ESTUDIANTES-------------------------//

    //------------------------ CURSOS ---------------------------------//

    
    $('form#cursosImportSave').submit( function( e ) {
        $("#btnSumImport_cursos").attr("disabled","disabled");
        var _numC = $("#totCol").val();
        var x;
        var _flag=0;
        var _flagDni=0;
        var _flagCodProg=0;
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() > 0 ){
            _flag = 1;break;
          }
        }
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() == 1 ){
            _flagDni = 1;break;
          }
        }
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() == 14 ){
            _flagCodProg = 1;break;
          }
        }

        if(_flag==0){
            //alert("Por favor asignar al menos una columna");
            swal({ type:'info',title:'Por favor asignar al menos una columna',showConfirmButton: false,timer: 1500});
            $("#btnSumImport_cursos").removeAttr("disabled");
            return false;
        }

        if(_flagDni==0){
            //alert("El DNI es un campo obligatorio");
            swal({ type:'info',title:'El DNI es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport_cursos").removeAttr("disabled");
            return false;
        }

        if(_flagCodProg==0){
            swal({ type:'info',title:'El CÓGIDO PROGRAMACIÓN es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport").removeAttr("disabled");
            return false;
        }


        $("#cargador_excel2").attr("style","display: block; position: -webkit-sticky;position: sticky;left: 0;");
        e.preventDefault();
        var actionform = $(this).attr('action');
     // $("#btnGuardar").attr("disabled","disabled");
        $.ajax({
            url: actionform,
            type:'POST',
            data: new FormData( this ),
            processData: false,
            contentType: false,
              beforeSend: function(){
                  //toastr.warning('Procesando su solicitud');
              },
            success: function(respuesta){
                //console.log(respuesta);
                $("#cargador_excel2").attr("style","display: none");
                //alert("DATOS CARGADOS");
                //swal('Datos cargados');
                swal({ type:'success',title:'Datos cargados',showConfirmButton: false,timer: 1500});

                $("#btnSumImport_cursos").removeAttr("disabled");
                $("#iframePrev").attr("style","display: block;");
                $("#cursosImportSave").attr("style","display: none");
                $("#btnSumImport_cursos").attr("style","display: none");
                $("#iframePrev").attr("style","display: none ");
                //sleep(500);
                document.getElementById("iframePrev").contentDocument.location.reload(true);
                $("#iframePrev").attr("style","display: block;border: 1px solid #e6e6e6;");
                
                //$('#order-listing').DataTable().ajax.reload();

                e.preventDefault();
            },
            error: function(xhr, status, error){
              $("#cargador_excel2").attr("style","display: none");
              var err = JSON.parse(xhr.responseText);           
            }
        });
    });


      $("#btnCerrarIf_cursos").click(function(){
        //location.href="/cursos";
        eximForm();
      })

      $("#btnRegresar").click(function(){
        _flagMod = 1;
        eximForm();
      })

      $('#Modal_organizar_cursos').on('hidden.bs.modal', function () {
        if(_flagMod == 0){
          //location.href="/cursos";
          eximForm(); 
        }

      });

     $("#archivo").change(function (){
        _flagMod = 0;
        $("#f_cargar_datos_cursos").submit();
     });

    $('form#f_cargar_datos_cursos').submit( function( event ) {

        $("#btnImport1").attr("disabled","disabled");
        $("#cargador_excel").attr("style","display:block");      
        event.preventDefault();        
    }).validate({
      // Rules for form validation
      errorClass: 'error', 
        rules : {
          archivo: {
            required: true,
            extension: "xls|csv"
          }
        },
        // Messages for form validation
        messages : {
          archivo : {
            required: "Solo se aceptan archivos XLS y CSV"
          }

        },
        submitHandler: function(form) {

          $("#iframePrev").attr("style","display: none ");
          $("#cursosImportSave").attr("style","display: block");
          $("#btnSumImport_cursos").attr("style","display: block");
                
         // $("#btnImport1").removeAttr("disabled");

          var actionform = $("#f_cargar_datos_cursos").attr('action');
          //$("#btnGuardar").attr("disabled","disabled");

          $.ajax({
              url: actionform,
              type:'POST',
              data: new FormData( form ),
              processData: false,
              contentType: false,
                beforeSend: function(){
                    //toastr.warning('Procesando su solicitud');
                },
              success: function(datos){
                //console.log(datos);
                $("#hdnTabla").val(datos);
                $("#btnImport1").removeAttr("disabled");    
                $("#cargador_excel").attr("style","display:none");

                $('#Modal_estudiantes').modal('hide');
                $('#tbl_estudiantes_imp_ord').html("");
                var _combo = "<select class='form-control text-uppercase' required onchange='cmbOrganiza(this)' id='cambiar' name='cambiar' style='width:200px'>";
                _combo = _combo + "<option value=0></option>";

                _combo = _combo + "<option value=1>nom_curso</option>";
                _combo = _combo + "<option value=2>descripcion</option>";
                //_combo = _combo + "<option value=3>modalidad_id</option>";
                //_combo = _combo + "<option value=4>tipo_id</option>";
                _combo = _combo + "<option value=5>cat_curso_id</option>";
                //_combo = _combo + "<option value=6>sede_id</option>";
                _combo = _combo + "<option value=7>sesiones</option>";
                _combo = _combo + "<option value=8>horas_academicas</option>";
                _combo = _combo + "</select>";

                $("#Modal_organizar_cursos").modal('show').addClass('modal-big');

                if(datos.length>0){
                  var _strTable = '<thead><tr role="row">';
                  var _numCol = datos[0].length ;
                  /*if(_numCol>12){
                    alert("El número máximo de columnas es 12, por favor revise su archivo de Excel");
                    return false;
                  }*/
                  $("#totCol").val(_numCol);
                  var x;
                  var xx = 1;
                  for(x = 0; x < _numCol ; x++){
                    var _nnombre = "cmbOrganizar"+ xx;
                    var _num = xx;
                    
                    _combo = _combo.replace("cambiar", _nnombre );
                    _combo = _combo.replace("cambiar", _nnombre );

                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );
                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );

                    _strTable = _strTable + '<th >' + _combo + '</th>';
                    xx++;
                  }
                  _strTable = _strTable + '</tr></thead>';
                  
                  var y;
                  _strTable = _strTable + '<tbody>';
                  for(y=0; y< datos.length; y++){
                    _strTable = _strTable + '<tr>';
                    var z;  
                    for(z=0; z< _numCol; z++){
                      var _clase = "col" + (z+1);
                      _strTable = _strTable + '<td style="background:#E5E5E5;" class="'+_clase+'">' + datos[y][z] + '</td>' ;
                    }
                    _strTable = _strTable + '</tr>';
                  }
                  _strTable = _strTable + '</tbody>';
                  $('#tbl_estudiantes_imp_ord').append(_strTable);

                }
                organizaFirstRow();
                  
              },
              error: function(xhr, status, error){ 
                    var err = JSON.parse(xhr.responseText);
                    //alert(err["error"]);
                    swal(err["error"]);
                    $("#btnImport1").removeAttr("disabled");    
                    $("#cargador_excel").attr("style","display:none");                        

              }
          });
        },
      errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
      }
    });


    //------------------------ END CURSOS -----------------------------//


    //------------------------ PROGRAMACION ---------------------------//
    $('form#programacionesImportSave').submit( function( e ) {
        $("#btnSumImport_programaciones").attr("disabled","disabled");
        var _numC = $("#totCol").val();
        var x;
        var _flag=0;
        var _flagDni=0;
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() > 0 ){
            _flag = 1;break;
          }
        }
        for(x = 0; x < _numC ; x++){
          if($("#cmbOrganizar" + x).val() == 1 ){
            _flagDni = 1;break;
          }
        }
        // FALTA: poner codigo programación

        if(_flag==0){
            //alert("Por favor asignar al menos una columna");
            swal({ type:'info',title:'Por favor asignar al menos una columna',showConfirmButton: false,timer: 1500});
            $("#btnSumImport_programaciones").removeAttr("disabled");
            return false;
        }

        if(_flagDni==0){
            //alert("El DNI es un campo obligatorio");
            swal({ type:'info',title:'El CODIGO es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport_programaciones").removeAttr("disabled");
            return false;
        }
        if(_flagCodProg==0){
            swal({ type:'info',title:'El CÓGIDO PROGRAMACIÓN es un campo obligatorio',showConfirmButton: false,timer: 2000});
            $("#btnSumImport").removeAttr("disabled");
            return false;
        }

        $("#cargador_excel2").attr("style","display: block; position: -webkit-sticky;position: sticky;left: 0;");
        e.preventDefault();
        var actionform = $(this).attr('action');
     // $("#btnGuardar").attr("disabled","disabled");
        $.ajax({
            url: actionform,
            type:'POST',
            data: new FormData( this ),
            processData: false,
            contentType: false,
              beforeSend: function(){
                  //toastr.warning('Procesando su solicitud');
              },
            success: function(respuesta){
                //console.log(respuesta);
                $("#cargador_excel2").attr("style","display: none");
                
                //swal({ type:'success',title:'Datos cargados',showConfirmButton: false,timer: 1500});
                swal({
                  title: "Registro importado",
                  text: "Los registros fueron importados correctamente.",
                  icon: "success",
                  button: "Cerrar",
                });

                $("#btnSumImport_programaciones").removeAttr("disabled");
                $("#iframePrev").attr("style","display: block;");
                $("#programacionesImportSave").attr("style","display: none");
                $("#btnSumImport_programaciones").attr("style","display: none");
                $("#iframePrev").attr("style","display: none ");
                //sleep(500);
                document.getElementById("iframePrev").contentDocument.location.reload(true);
                $("#iframePrev").attr("style","display: block;border: 1px solid #e6e6e6;");
                
                //$('#order-listing').DataTable().ajax.reload();

                e.preventDefault();
            },
            error: function(xhr, status, error){
              $("#cargador_excel2").attr("style","display: none");
              var err = JSON.parse(xhr.responseText);
              console.log('Error');
            }
        });
    });

      $("#btnCerrarIf_programaciones").click(function(){
        //location.href="/programaciones";
        eximForm();
      })

      $("#btnRegresar").click(function(){
        _flagMod = 1;
        eximForm();
      })

      $('#Modal_organizar_programaciones').on('hidden.bs.modal', function () {
        if(_flagMod == 0){
          //location.href="/programaciones"; 
          eximForm();
        }

      });

     $("#archivo").change(function (){
        _flagMod = 0;
        $("#f_cargar_datos_programaciones").submit();
     });

    $('form#f_cargar_datos_programaciones').submit( function( event ) {

        $("#btnImport1").attr("disabled","disabled");
        $("#cargador_excel").attr("style","display:block");      
        event.preventDefault();        
    }).validate({
      // Rules for form validation
      errorClass: 'error', 
        rules : {
          archivo: {
            required: true,
            extension: "xls|csv"
          }
        },
        // Messages for form validation
        messages : {
          archivo : {
            required: "Solo se aceptan archivos XLS y CSV"
          }

        },
        submitHandler: function(form) {

          $("#iframePrev").attr("style","display: none ");
          $("#programacionesImportSave").attr("style","display: block");
          $("#btnSumImport_programaciones").attr("style","display: block");
                
         // $("#btnImport1").removeAttr("disabled");

          var actionform = $("#f_cargar_datos_programaciones").attr('action');
          //$("#btnGuardar").attr("disabled","disabled");

          $.ajax({
              url: actionform,
              type:'POST',
              data: new FormData( form ),
              processData: false,
              contentType: false,
                beforeSend: function(){
                    //toastr.warning('Procesando su solicitud');
                },
              success: function(datos){
                //console.log(datos);
                $("#hdnTabla").val(datos);
                $("#btnImport1").removeAttr("disabled");    
                $("#cargador_excel").attr("style","display:none");

                $('#Modal_estudiantes').modal('hide');
                $('#tbl_estudiantes_imp_ord').html("");
                var _combo = "<select class='form-control text-uppercase' required onchange='cmbOrganiza(this)' id='cambiar' name='cambiar' style='width:200px'>";
                _combo = _combo + "<option value=0></option>";
                
                _combo = _combo + "<option value=1>codigo</option>";
                _combo = _combo + "<option value=2>nombre programa</option>";
                _combo = _combo + "<option value=3>tipo</option>";
                _combo = _combo + "<option value=4>modalidad</option>";
                _combo = _combo + "<option value=5>nombre_curso</option>";
                _combo = _combo + "<option value=6>area_tematica</option>";
                _combo = _combo + "<option value=7>docente</option>";
                _combo = _combo + "<option value=8>aula</option>";
                _combo = _combo + "<option value=13>piso</option>";
                _combo = _combo + "<option value=9>nsesiones</option>";
                _combo = _combo + "<option value=10>fecha_desde</option>";
                _combo = _combo + "<option value=11>fecha_hasta</option>";
                _combo = _combo + "<option value=12>frecuencia</option>";
                _combo = _combo + "<option value=14>estado</option>";
                _combo = _combo + "</select>";

                $("#Modal_organizar_programaciones").modal('show').addClass('modal-big');

                if(datos.length>0){
                  var _strTable = '<thead><tr role="row">';
                  var _numCol = datos[0].length ;
                  /*if(_numCol>12){
                    alert("El número máximo de columnas es 12, por favor revise su archivo de Excel");
                    return false;
                  }*/
                  $("#totCol").val(_numCol);
                  var x;
                  var xx = 1;
                  for(x = 0; x < _numCol ; x++){
                    var _nnombre = "cmbOrganizar"+ xx;
                    var _num = xx;
                    
                    _combo = _combo.replace("cambiar", _nnombre );
                    _combo = _combo.replace("cambiar", _nnombre );

                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );
                    _combo = _combo.replace("cmbOrganizar"+ (xx -1), _nnombre );

                    _strTable = _strTable + '<th >' + _combo + '</th>';
                    xx++;
                  }
                  _strTable = _strTable + '</tr></thead>';
                  
                  var y;
                  _strTable = _strTable + '<tbody>';
                  for(y=0; y< datos.length; y++){
                    _strTable = _strTable + '<tr>';
                    var z;  
                    for(z=0; z< _numCol; z++){
                      var _clase = "col" + (z+1);
                      _strTable = _strTable + '<td style="background:#E5E5E5;" class="'+_clase+'">' + datos[y][z] + '</td>' ;
                    }
                    _strTable = _strTable + '</tr>';
                  }
                  _strTable = _strTable + '</tbody>';
                  $('#tbl_estudiantes_imp_ord').append(_strTable);

                }
                organizaFirstRow();
                  
              },
              error: function(xhr, status, error){ 
                    var err = JSON.parse(xhr.responseText);
                    //alert(err["error"]);
                    swal(err["error"]);
                    $("#btnImport1").removeAttr("disabled");    
                    $("#cargador_excel").attr("style","display:none");                        

              }
          });
        },
      errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
      }
    });



    //------------------------ END PROGRAMACION -----------------------//
    $("#chkPrimeraFila").on('change', function(event){
      organizaFirstRow();
    });




    // propose username by combining first- and lastname
    $("#username").focus(function() {
      var firstname = $("#firstname").val();
      var lastname = $("#lastname").val();
      if (firstname && lastname && !this.value) {
        this.value = firstname + "." + lastname;
      }
    });


    /* FORM STEPS JQUERY */
    //console.log('Paso');

  



  });
})(jQuery);