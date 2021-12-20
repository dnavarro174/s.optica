arrProducto = [];

(function($) {
  'use strict';

  console.log('-- add_producto');
  
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
  console.log("a");
      e.preventDefault();
      //const prodActuales = [];
      let idprod = $('#Producto').val();
      let cant = ($('#cantidad').val());//parseInt
      cant = parseFloat(cant).toFixed(4);
      console.log("id producto:"+idprod +"  cant:"+cant);

      let desc = $('#descripcion').val();
      let ban, ban2 = true;
      let nomprod = '';
      if(idprod == ''){
        $('#Producto').addClass('border-danger');
        ban = false;
      }else{
        $('#Producto').removeClass('border-danger');
        nomprod = $('#Producto option:selected').text();
        ban = true;

        
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
          id  : parseInt(idprod),
          idproducto  : parseInt(idprod),
          nombre : nomprod,
          cantidad    : cant,
          descripcion    : desc
        }

       let url = '/inventario/public/salidas/stock/'+idprod+'/'+cant;
       //let url = '/p/inventario/public/salidas/stock/'+idprod+'/'+cant;
       //let url = '{{route('salidas.stock')}}/'+idprod+'/'+cant;

       $.ajax({
        url: url,
        method: 'get',
        data: {id: idprod,cant:cant},
        success: function(data){
          //JSON.parse
          let arr = JSON.parse(data);

          if(arr[0]['stock'] == 0){
            $('#msg_stock').html(`<div class="alert-danger p-4 mb-4"><p class='m-0 text-center'><strong>Stock = 0.</strong></p></div>`).fadeIn();
          }
          if(arr[0]['stock'] == 1){
            $('#msg_stock').html(`<div class="alert-danger p-4 mb-4"><p class='m-0 text-center'><strong>La cantidad ingresada excede al stock del producto. Stock = ${arr[0]['stock_bd']}</strong></p></div>`).fadeIn();
          }else{
            crearProducto(producto);
            $("#Producto option[value='"+idprod+"']").attr('disabled','disabled');
            //$('#birth_month option[value="'+data.month+'"]').prop('selected', true);
          }
          
          setTimeout(outDisplay_prod, 3000);

        },
        error: function(data){
          console.log(data);
        }

       });


       function outDisplay_prod(){
        $('#msg_stock').fadeOut();
       }

        $('#Producto').val('');
        $('#cantidad').val('');
        $('#descripcion').val('');

      }

    });


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
        
        //console.log(arrProducto.length);
        console.log(arrProducto);
        //$('#filas_contenedor tr').removeClass('alert-danger');

        const _token = $('input[name="_token"]').val();
        console.log(_token);

          $.ajax({
            url: "/inventario/public/salidas",
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


    const crearProducto = (newProd)=>{

       //const prodActuales = [...arrProducto, newProd ];
       arrProducto.push(newProd);       

      //const {id,cant,nomprod} = arrProducto;
      //const mensaje = Object.keys(arrProducto).length === 0 ? 'No hay datos' : 'Si hay datos';

      $('#ma_detalle tbody').empty();

      $.each(arrProducto, function(index, val){
        //console.log('Producto id:'+idprod + ' val id:'+val['id']+ 'tamaño:'+arrProducto.length);
        
        $("#ma_detalle tbody").append(`
            <tr><td>${val['nombre']}</td>
            <td>${val['cantidad']}</td>
            <td>${val['descripcion']}</td>
            <td><a href="#" data-id="${index}" data-idprod="${val['id']}" title='Quitar' class="btn btn-sm btn-danger del_producto">X</a></td>
            </tr>`);

      });
      //console.log(arrProducto);

    }

    

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
            <tr><td>${val['nombre']}</td>
            <td>${val['cantidad']}</td>
            <td>${val['descripcion']}</td>
            <td><a href="#" data-id="${index}" data-idprod="${val['id']}" title='Quitar' class="btn btn-sm btn-danger del_producto">X</a></td>
            </tr>`);
      });
      //FALTA ACTUALIZAR EL STOCK SI SE RETIRA
    }

}); 