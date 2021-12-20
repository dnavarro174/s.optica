arrProducto = [];
console.log('leyendo DataTable.js');
(function($) {
  'use strict';
  /*$(function() {
    var datatables = $('#order-listing').DataTable({
      
      lengthChange: true,
      "aLengthMenu": [
        [15, 80, 100, -1],
        [15, 80, 100, "Todos"]
      ],
      "iDisplayLength": 15,

      "language": {
            "search":"Buscar",
            "lengthMenu": "Mostrando _MENU_ registros por página",
            //"zeroRecords": "No se ha encontrado ningún registro.",
            //"info": "Mostrando páginas _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "paginate": {
              "previous": "Anterior",
              "next": "Siguiente"
            }
      }
    });
  });*/

    // eliminar varios reg.
    $('.btn-delete').click(function() {
      //$('#chooseAll_1').attr('checked',true);//change todos
      console.log('click btn delete');

      //$(this).parentsUntil('tbody').css({'color':'red', 'background':'red'});
      const lis = $(this).parentsUntil('tbody').addClass('x7');

        $('#delete_selec').attr('disabled',false);
        var id = $(this).data('id');
        console.log('click btn-delete=' +id);
        //$(this).parents('tr').fadeOut(1000);

        if ($('.btn-delete').is(':checked')) {
        }else{
          $('#delete_selec').attr('disabled',true);
          //console.log('boton desact');
          lis.removeClass('x7');
          
        }
    });

    // pop up detalles de cursos llevados x un estudiante
    $('.estudianteHistorial').click(function (event){
      console.log("Historial Estudiante");

      $("#modalHistorial").modal('show');

      var id_est = $(this).data('id');
      var url = "historial/"+id_est+"";

      $.get(url, function (resp,resul){
        //console.log('Ajax '+ url);

        if(resp.length > 0){
          //console.log("estudianteHistorial ="+resp.length);

          $("#historiaE table tbody").empty();
          
          for(var i=0;i<resp.length;i++){
            $("#heTitle").empty().append("Historial Estudiante: "+resp[i].nombres +' '+resp[i].ap_paterno);
            $("#historiaE table tbody").append("<tr><th scope=row>"+resp[i].codigo+"</th><td>"+resp[i].tipo+"</td><td>"+resp[i].nombre+"</td><td>"+resp[i].fecha_desde+"</td><td>"+resp[i].fecha_hasta+"</td><td>"+resp[i].estado+"</td></tr");
          }

        }else{
           $("#heTitle").empty().append("Historial Estudiante: ");

          $("#historiaE table tbody").empty();

          $("#historiaE table tbody").append("<tr><th scope=row colspan='5'>No existen registros.</th></tr");
          console.log('No existen registros.');

        }

      });

    });

    // Asignar código de programación al estudiante:
    $('.asignarProg').click(function (event){
      $("#modalCodProg").modal('show');

      var id_est = $(this).data('id');
      var url = "det_programacion2/"+id_est+"";      

      $.get(url, function (resp,resul){
        console.log('Ajax '+ url);
        var chek = "";
        var dni_check ="";
        var chek_html = "";
        var xcodprog = 0;
        var posicion = 0;
        //console.log('codigos '+resp.codigos.length); //codigo_check
        //console.log('codigo_check '+resp.codigo_check.length); //codigo_check
        //console.log('HTML '+resp.html.length); //codigo_check

        if(resp.codigos.length > 0){

          $("#detProgramacion table tbody").empty();
          
          for(var i=0;i<resp.codigos.length;i++){
            chek = "";
            var pdf="";

            for(var j=0; j < resp.codigo_check.length;j++ ){
              
              if( resp.codigos[i].codigo == resp.codigo_check[j].programacion_id){
                posicion = i;
                if( resp.codigos[posicion] ){ chek ="checked = checked"; }

              }
              var chek_html = 0;

              for( var k=0; k<resp.html.length; k++){
                
                if( resp.codigo_check[j].programacion_id == resp.html[k].lista && resp.codigos[i].codigo == resp.html[k].lista){
                  chek_html = 1;
                  xcodprog = resp.html[k].lista;
                  //console.log('check html: '+'i='+i+ ' j='+j + ' k==> '+k); console.log('chek_html=1 ===== '+chek_html);
                }

              }

              if(chek_html == 1){
                pdf = "<a href='storage/confirmacion/"+xcodprog+'-'+id_est+".pdf' title='Descargar' target='_blank'><i class='mdi mdi-file-pdf icon-md text-danger'></i></a>";
                //console.log('Bandera html SI - '+'i='+i+ ' j='+j + ' k==> '+k)
              }

            }
            
              //$("#heTitle").empty().append("Historial Estudiante: "+resp[i].nombres +' '+resp[i].ap_paterno);
              $("#detProgramacion table tbody").append("<tr><th scope=row>"+
                "<input type=hidden value="+id_est+" name=id_dni>"+
                "<input "+chek+" type='checkbox' class=codpro value='" +resp.codigos[i].codigo+"' class='optPermiso opc1' name='detprog_"+i+"' num='60'><label for=''>&nbsp;"
                +resp.codigos[i].codigo +"</label></th><td>"
                +resp.codigos[i].nombre+"</td>"+
                "<td>"+pdf+"</td><td>"
                +resp.codigos[i].fecha_desde+"</td><td>"
                +resp.codigos[i].fecha_hasta+"</td></tr");
          }
          
            $("#totalRows").val(resp.codigos.length);

        }else{
          $("#detProgramacion table tbody").empty();

          $("#detProgramacion table tbody").append("<tr><th scope=row colspan='5'>No existen registros.</th></tr");
          console.log('No existen registros.');

        }

      });

    });

    // Recorremos todos los checkbox para contar los que estan seleccionados
    /*var contador = 0;
    $("input[type=checkbox].codpro").each(function(j, el){
      if($(this).is(":checked")){
        contador++;
        $("#totalRows").val(contador);
      }
    });*/

    // Ajax asignar programaciones a Estudiantes
    $('form#detalleProgramacion').submit( function( event ) {
        event.preventDefault();  
      }).validate({
        // Rules for form validation
        errorClass: 'error',
          submitHandler: function(form) {
            var actionform = $("#detalleProgramacion").attr('action');
            $("#enviar_det_programacion").attr("disabled","disabled");
              $.ajax({
                url: actionform,
                type:'POST',
                data: new FormData( form ),
                processData: false,
                contentType: false,
                  beforeSend: function(){
                      //toastr.warning('Procesando su solicitud');
                  },
                success: function(respuesta){
                   $("#enviar_det_programacion").removeAttr("disabled");
                   //Alert("ok");
                   //swal({ type:'success',title:'Actualización correcta',showConfirmButton: false,timer: 1500});
                  swal({
                    type: 'success',
                    title: 'Éxito...',
                    text: 'Se grabarón los cambios.',
                    //footer: '<a href>Why do I have this issue?</a>'
                  });
                },
                error: function(xhr, status, error){
                var err = JSON.parse(xhr.responseText);
                      alert("error, intente mas tarde");
                      e.preventDefault();         
                }
            });
          },
          errorPlacement : function(error, element) {
          error.insertAfter(element.parent());
        }
    });

    $('#add_row').on('click', addRows);
    $(document).on('click', '.btn-deleteReg', removeElement);

    function addRows(){
      event.preventDefault();
      console.log('click');
      var fila = $("#reg_datos").html();
      $("#filas_contenedor").append(fila);
    }

    function removeElement(){
      event.preventDefault();
      $(this).parent().parent().remove();
      //$(this).closest("tr").remove();
      console.log('Eliminado');

    }


    // AJAX AUTOCOMPLETE FORM SALIDAS
    // $('#responsable').keyup(function(){
    //   var q = $(this).val();
    //   console.log(q);
    //   if(q != ''){
    //     const _token = $('input[name="_token"]').val();
    //     console.log(_token);
    //     $.ajax({
    //       url: "/public/salidas/autocomplete",//{{ route('salidas.productos') }}
    //       method:"POST",
    //       data: {q: q, _token: _token},
    //       success: function(data){
    //         $('#responsable_list').fadeIn();
    //         console.log('success');
    //         $('#responsable_list').html(data);
    //       }
          
    //     });

    //   }
    // });
    

    $('#add_producto').click((e)=>{
      e.preventDefault();
      //const prodActuales = [];
      let idprod = $('#Producto').val();
      let cant = $('#cantidad').val();
      let desc = $('#descripcion').val();
      let ban, ban2 = true;
      let nomprod;
      if(idprod == ''){
        $('#Producto').addClass('border-danger');
        ban = false;
      }else{
        $('#Producto').removeClass('border-danger');
        nomprod = $('#Producto option:selected').text();
        ban = true;

        $("#Producto option:selected").attr('disabled','disabled')
        //$("#cantidad").attr('disabled','disabled')
      }
      if(cant == ''){
        $('#cantidad').addClass('border-danger');
        ban2 = false;
      }else{
        $('#cantidad').removeClass('border-danger');
        ban2 = true;
      }
      if(ban && ban2){

        const producto = {
          id  : idprod,
          cant    : cant,
          nomprod : nomprod,
          desc    : desc
        }

        crearProducto(producto);
        
        $('#Producto').val('');
        $('#cantidad').val('');
        $('#descripcion').val('');

      }

    });

    // validar form
    /*var namePattern = "^[a-z A-Z]{4,30}$";
    var emailPattern = "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$";

    function checkInput(idInput, pattern) {
      return $(idInput).val().match(pattern) ? true : false;
    }
    function enableSubmit (idForm) {
      $(idForm + " button.submit").removeAttr("disabled");
    }

    function disableSubmit (idForm) {
      $(idForm + " button.submit").attr("disabled", "disabled");
    }

    function checkForm (idForm) {
      console.log('checkForm');
      $(idForm + " *").on("change keydown", function() {
        if (checkInput("#responsable", namePattern) && 
          checkInput("#fecha_desde", namePattern) )
        {
          enableSubmit(idForm);
        } else {
          disableSubmit(idForm);
        }
      });
    }*/

    var outDisplay = function(){
      $('#save_form').fadeOut();
    };

    $('#actionSubmit_salida').click( e => {
      e.preventDefault();
      let ban, ban2 = true;

      //checkForm("#salidasForm");

      $("#actionSubmit_salida").attr('disabled','disabled').html('Procesando...');
      const respon = $('#responsable').val();
      const fecha_desde = $('#fecha_desde').val();
      const motivo = $('#motivo').val();

      if(fecha_desde == ''){
        $("#datepicker-popup").addClass('border-danger');
        ban = false;
      }else{
        $("#datepicker-popup").removeClass('border-danger');
        ban = true;
      }
      if(respon == ''){
        $("#responsable").addClass('border-danger');
        ban2 = false;
      }else{
        $("#responsable").removeClass('border-danger');
        ban2 = true;
      }


      if(arrProducto.length >= 1 && ban && ban2){
        
        console.log(arrProducto.length);
        //$('#filas_contenedor tr').removeClass('alert-danger');
        //guardarSalida(arrProducto);

        const _token = $('input[name="_token"]').val();
      
        console.log(_token);
        $.ajax({
          url: "/public/salidas",
          method: 'POST',
          data: {responsable: respon, motivo: motivo, fecha_desde: fecha_desde, arrProducto: arrProducto, _token:_token},
          success: function(data){
            console.log('success');
            if(data==='ok'){
              $('#save_form').fadeIn();
              $('#Producto option').removeAttr('disabled');
              $('#responsable, #motivo, #fecha_desde').val('');
              $('#ma_detalle tbody').empty();
              $("#ma_detalle tbody").append(`
                <tr class="reg_ejm"><td colspan="4">No hay datos</td></tr>`);

              $("#actionSubmit_salida").removeAttr('disabled').html('Guardar');
              
              setTimeout(outDisplay, 2000);

            }
          }
        });

      }else{
        //console.log('Agregar productos.');
        $('#filas_contenedor tr').addClass('alert-danger');
        $("#actionSubmit_salida").removeAttr('disabled').html('Guardar');

      }
    });

    const guardarSalida = (arr)=>{

    
    //     $.ajax({
    //       url: "/public/salidas/autocomplete",//{{ route('salidas.productos') }}
    //       method:"POST",
    //       data: {q: q, _token: _token},
    //       success: function(data){
    //         $('#responsable_list').fadeIn();
    //         console.log('success');
    //         $('#responsable_list').html(data);
    //       }
          
    //     });
    }



    const crearProducto = (newProd)=>{

       //const prodActuales = [...arrProducto, newProd ];
       arrProducto.push(newProd);

      //const {id,cant,nomprod} = arrProducto;
      //const mensaje = Object.keys(arrProducto).length === 0 ? 'No hay datos' : 'Si hay datos';

      $('#ma_detalle tbody').empty();

      $.each(arrProducto, function(index, val){
        //console.log('Producto id:'+idprod + ' val id:'+val['id']+ 'tamaño:'+arrProducto.length);
        
        $("#ma_detalle tbody").append(`
            <tr><td>${val['nomprod']}</td>
            <td>${val['cant']}</td>
            <td>${val['desc']}</td>
            <td><a href="#" data-id="${index}" data-idprod="${val['id']}" title='Quitar' class="btn btn-sm btn-danger del_producto">X</a></td>
            </tr>`);
        //console.log(arrProducto);

      });

    }

    // end asig prog a estudiantes

})(jQuery);

/*$(document).on('click', 'li', function(){
  console.log('click lista');
  $('#responsable').val($(this).text());
  $('#responsable_list').fadeOut();
})*/
// DOM NUEVO 
$(document).on("click", ".del_producto", function (e) { 
    e.preventDefault();
    let id = $(this).attr('data-id');
    let idprod = $(this).attr('data-idprod');

    // para no eliminar en vista: show
    if(id !== 'undefined' && idprod > 0){

      console.log(idprod);
      console.log(arrProducto);

      $("#Producto option[value="+idprod+"]").removeAttr('disabled');
      //$('#cantidad').removeAttr('disabled','');
     
      arrProducto.splice(id,1);

      $('#ma_detalle tbody').empty();

      $.each(arrProducto, function(index, val){

        $("#ma_detalle tbody").append(`
            <tr><td>${val['nomprod']}</td>
            <td>${val['cant']}</td>
            <td>${val['desc']}</td>
            <td><a href="#" data-id="${index}" data-idprod="${val['id']}" title='Quitar' class="btn btn-sm btn-danger del_producto">X</a></td>
            </tr>`);
      });
      console.log(arrProducto);
    }

}); 

