function buscarusuario(){
  var pais=$("#select_filtro_pais").val();
  var dato=$("#s").val();
      if(dato == "")
        {
          var url="buscar_usuarios/"+pais+"";
        }
        else
        {
          var url="buscar_usuarios/"+pais+"/"+dato+"";
        }

      console.log('url: '+url);
        $(".main-panel").html($("#cargador_empresa").html());
        $.get(url,function(resp, resul){
          $('.main-panel').empty();
          if(resp.length>0){
            console.log("valor resul ="+resp.length);
            $(".main-panel").html(resp); 
          
          }else{
          	console.log("0 Registros.");
          }
        });
  }


  //------------------------ FORM IMPORT -------------------------//


  function eximForm(){
    $("#Modal_estudiantes").modal('show');
    $("#Modal_estudiantes form")[0].reset();
  }
  function cerrar_form(){
    $("#Modal_organizar").modal('hide');
    $("#Modal_organizar_cursos").modal('hide');
    $("#Modal_organizar_programaciones").modal('hide');
  }
  function openModal(){
    //$("#modalHistorial").modal('show');
    //.modal('hide');
  }


  function cmbOrganiza(obj){
    var _num = parseInt($(obj).attr("id").replace("cmbOrganizar",""));
    var coll = $(".col" + _num);

    if($(obj).val()>0){
      //VERIFICAR QUE NINGUNA COLUMNA ESTE SELECCIONADA
      var _valorS = $(obj).val(); 
      var _totC = $("#totCol").val();
      var h;
      for(h=1; h <= _totC ;h++){
        if(h != _num){
          if( $("#cmbOrganizar" + h).val() == _valorS){
              $("#cmbOrganizar" + h).val(0) ;
              var coll2 = $(".col" + h);
              coll2.each(function() {
                  $(this).attr("style","background:#E5E5E5");
              });  
          }
        }
      }

      coll.each(function() {
          $(this).attr("style","background:#FFFFFF");
      });      
    }else{
      coll.each(function() {
          $(this).attr("style","background:#E5E5E5");
      });  
    }
    organizaFirstRow();
  }

function organizaFirstRow(){
  //$("#tbl_estudiantes_imp_ord tbody tr:first td").attr("style", "background:#E5E5E5");
  if ($('#chkPrimeraFila').is(':checked')) {
    $("#tbl_estudiantes_imp_ord tbody tr:first").attr("style", "color:#B5B5B5;");
    $("#tbl_estudiantes_imp_ord tbody tr:first td").attr("style", "background:#E5E5E5");
  }else{
    $("#tbl_estudiantes_imp_ord tbody tr:first").attr("style", "color:#000000;");
    //$("#tbl_estudiantes_imp_ord tbody tr:first td").attr("style", "background:#FFFFFF");
    var _totC = $("#totCol").val();
    var h;
    for(h=1; h <= _totC ;h++){
      if( $("#cmbOrganizar" + h).val() != 0){
        $("#tbl_estudiantes_imp_ord tbody tr:first .col" + h).attr("style", "background:#FFFFFF");      
      }
    }
  } 
}

function eximFormOrganizar(data){
  $("#Modal_organizar").modal('show').addClass('modal-big');
  //$("#Modal_estudiantes form")[0].reset();
}



//------------------------ BEGIN DOC READY -------------------------//

$( document ).ready(function() {

//ocultar busqueda avanzada
  	$('#bAdvance').click(function(){
      $('#capBusqueda').css('display','block');
      $('.ocultar').css('display','block');
      $('.mostrar').css('display','none');
    });
    $('#bAdvanceOcultar').click(function(){
      $('#capBusqueda').css('display','none');
      $('.ocultar').css('display','none');
      $('.mostrar').css('display','block');
    });


    
    // eliminar varios reg.
    
    $('#delete_selec').click(function(e) {

       /*if (!confirm('¿Desea borrar los registros seleccionados?'))
        {return false;}*/
        e.preventDefault();
        e.stopImmediatePropagation();
        swal({
          title: "Desea borrar los registros seleccionados?",
          text: "Una vez eliminado, ¡no podrá recuperar estos registros! ",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            swal("¡Tu archivo imaginario ha sido eliminado!", {
              icon: "success",
            });
            $('#form-delete').submit();
          } else {
            //swal("¡Tu archivo imaginario está a salvo!");
            return false;
          }
        });

    });

    // seleccionar todos
    $('#chooseAll_1').change(function() {
      if ($('#chooseAll_1').is(':checked')) {
        $('#delete_selec').attr('disabled',false);
        $('.table tbody .odd').addClass('x7');
        console.log('boton act');
      }else{
        $('#delete_selec').attr('disabled',true);
        $('.x7').removeClass('x7');
        console.log('boton desact'); 
      }

      $("input:checkbox").prop('checked', $(this).prop("checked"));

    });

    // eliminar varios reg.
    $('.btn-delete').click(function() {
      //$('#chooseAll_1').attr('checked',true);//change todos
      console.log('click btn delete');

      //$(this).parentsUntil('tbody').css({'color':'red', 'background':'red'});
      const lis = $(this).parentsUntil('tbody').addClass('x7');

        $('#delete_selec').attr('disabled',false);
        var id = $(this).data('id');
        //console.log('click btn-delete=' +id);
        //$(this).parents('tr').fadeOut(1000);

        if ($('.btn-delete').is(':checked')) {
        }else{
          $('#delete_selec').attr('disabled',true);
          //console.log('boton desact');
          lis.removeClass('x7');
          
        }
    });

    


    //------------------------ IMPORT ESTUDIANTES-------------------------//

    $("#btnSumImport").click(
      function(){
        $("#estudiantesImportSave").submit();
      }
    );

    $("#btnSumImport_cursos").click(
      function(){
        $("#cursosImportSave").submit();
      }
    );
   $("#btnSumImport_programaciones").click(
      function(){
        $("#programacionesImportSave").submit();
      }
    );

   

    //------------------------ SAVE ESTUDIANTES-------------------------//

    /*$("#checkTodos").change(function () {
      $("input:checkbox").prop('checked', $(this).prop("checked"));
    });*/


});