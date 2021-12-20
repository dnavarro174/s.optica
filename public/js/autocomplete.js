
$( document ).ready(function() {
	//console.log('test autocomp');
	__clientAutocomplete();
	__proveeAutocomplete();
	__productoAutocomplete();
	__proyectoAutocomplete();
	__labAutocomplete();

	$('.easy-autocomplete').css('width','100%');

function __clientAutocomplete(){

  var options = {
	url: function(y) {
		console.log(y);
		//return "api/countrySearch.php?phrase=" + phrase + "&format=json";
			if(y.length <= 2 || y.length==0){
				$('#cta_cte').val('');
				$('#cod_ruc2').val('');
			}

		return baseURL('autocomplete/findClient?q='+y);
		},

		getValue: "razon_social",
		list:
			{
			maxNumberOfElements: 10,
			onClickEvent: function() {
				var e = $('#cliente').getSelectedItemData();
				$('#cta_cte').val(e.cod_ruc);
				console.log('codigo: '+e.id);
				$('.mostrar_1').show('slow'); $('.easy-autocomplete').css('width','100%');
				$("#producto").focus();
	    		return;
			},

			onKeyEnterEvent: function() {
				var e = $('#cliente').getSelectedItemData();
				$('#cta_cte').val(e.cod_ruc);
				console.log('codigo: '+e.id);
				$('.mostrar_1').show('slow'); $('.easy-autocomplete').css('width','100%');
			},
			onSelectItemEvent:  function() {
				var e = $('#cliente').getSelectedItemData();
				$('#cta_cte').val(e.cod_ruc);
				console.log('codigo: '+e.id);
				$('.mostrar_1').show('slow'); $('.easy-autocomplete').css('width','100%');
			}
		}
	};

  	$("#cliente").easyAutocomplete(options);
}

	function __proveeAutocomplete_det(){
	    var e = $('#razon_social').getSelectedItemData();
	    $('#cod_ruc, #cod_ruc2').val(e.cod_ruc);
	    $('#razon_social').val(e.razon_social);
	    $('#cod_emp2').val(e.id);
	    $('#edad').val(e.edad);
	    $('#tele').val(e.tele);
	    $('.mostrar_1').show('slow'); $('.easy-autocomplete').css('width','100%');

	    var tipo_persona = e.tipo_persona;
	    if(tipo_persona=="01")
	        $('#nro_preing-').val("1");
	    if(tipo_persona=="02")
	        $('#nro_preing-').val("6");
	    var direccion = e.direccion;
	    $('#direccion').val(direccion);

	    //console.log(e);
	}

	function __labAutocomplete_det(){
	    var e = $('#laboratorio').getSelectedItemData();
	    $("#laboratorio_id").val(e.id);
	    $('.mostrar_1').show('slow'); $('.easy-autocomplete').css('width','100%');

	}

function __proveeAutocomplete(){

  var options = {
	url: function(q) {
		console.log(q);
			if(q.length <= 2){
				$('#cod_ruc').val('');
				$('#cod_ruc2').val('');
			}

			return baseURL('autocomplete/findProvee?q='+q);
		},

		//getValue: "razon_social",
		getValue: function(element) {
			return element.razon_social + " - " + element.cod_ruc;
		},
		// end getValue
		list: {
		    /*match: {
		      enabled: true
		    },*/

		    maxNumberOfElements: 10,

			onClickEvent: function() {
                __proveeAutocomplete_det();
				$("#producto").focus();
	    		return;
			},

			onKeyEnterEvent: function() {
                __proveeAutocomplete_det();
			},
			onSelectItemEvent:  function() {
                __proveeAutocomplete_det();
			}
		}
	};

  	$("#razon_social").easyAutocomplete(options);
}

function __productoAutocomplete_calc(cod_artic,cod_artic2,cod_umedida,cant,costo_mn,subtotal){
    var e = $('#producto').getSelectedItemData();
    cod_artic.val(e.cod_artic);
    cod_artic2.val(e.cod_artic2);
    cod_umedida.val(e.cod_umedida);

    cant.val(1);
    costo_mn.val(floatval(e.precio_venta).toFixed(2));
    sb = floatval(costo_mn.val()) * floatval(cant.val());
    subtotal.val(sb);
}

function __productoAutocomplete(){

	var cod_artic = $('#cod_artic');
	var cod_artic2 = $('#cod_artic2');
	var cod_umedida = $('#cod_umedida');
	var costo_mn = $('#costo_mn');
	var cant = $('#cant');
	var subtotal = $('#subtotal');

  var options = {
	url: function(q) {
			if(q.length <= 2){
				cod_artic.val('');
				cod_artic2.val('');
				cod_umedida.val('');
				costo_mn.val('');
				subtotal.val('');
			}

		return baseURL('autocomplete/findProduct?q='+q);
		},

		//getValue: "nombre",
		getValue: function(element) {
			return element.nombre + " - STOCK: " + element.stock_alm;
		},
		// end getValue

		list:
			{
			maxNumberOfElements: 10,
			onClickEvent: function() {
                __productoAutocomplete_calc(cod_artic,cod_artic2,cod_umedida,cant,costo_mn,subtotal);
				cant.select();
			},

			onKeyEnterEvent: function() {
                __productoAutocomplete_calc(cod_artic,cod_artic2,cod_umedida,cant,costo_mn,subtotal);
                cant.select().val(1);
                //console.log(`zcant: ${cant.val()}`);
			},
			/*onSelectItemEvent:  function() {
				var e = $('#producto').getSelectedItemData();
				cod_artic.val(e.cod_artic);
				cod_artic2.val(e.cod_artic2);
				cod_umedida.val(e.cod_umedida);

				cant.focus().val(1);
				costo_mn.val(parseFloat(e.precio_venta).toFixed(2));
				sb = costo_mn * cant;
				subtotal.val(sb);
			}*/
		}
	};

  	$("#producto").easyAutocomplete(options);
}


function __proyectoAutocomplete(){

  var options = {
	url: function(q) {
		//return "api/countrySearch.php?phrase=" + phrase + "&format=json";
			if(q.length <= 2){
				$('#cod_ruc').val('');
			}
			console.log(q);

		return baseURL('autocomplete/findProyecto?q='+q);
		},

		getValue: "nom_proy",
		list:
			{
			maxNumberOfElements: 10,
			onClickEvent: function() {
				var e = $('#proyecto').getSelectedItemData();
				$('#cod_ruc').val(e.cod_ruc);
				$('#proyectos_id').val(e.id);
				$('.mostrar_1').show('slow'); $('.easy-autocomplete').css('width','100%');
				$("#producto").focus();
	    		return;
			},

			onKeyEnterEvent: function() {
				var e = $('#cliente').getSelectedItemData();
				$('#cod_ruc').val(e.cod_ruc);
				$('#proyectos_id').val(e.id);
				$('.mostrar_1').show('slow'); $('.easy-autocomplete').css('width','100%');
			},
			onSelectItemEvent:  function() {
				var e = $('#cliente').getSelectedItemData();
				$('#cod_ruc').val(e.cod_ruc);
				$('#proyectos_id').val(e.id);
				$('.mostrar_1').show('slow'); $('.easy-autocomplete').css('width','100%');
			}
		}
	};

  	$("#proyecto").easyAutocomplete(options);
}


function __calculaSubTotal(){
}

	$("#costo_mn").on("input", function(e) {
		e.preventDefault();
		let s = $(this);
		let monto = s.val();
		let cant  = $("#cant").val();
		//if(monto == 0 || monto == ""){
		if(monto == ""){
			alert('Ingrese monto');
		}
		console.log(cant);
	});


	$("#cliente, #proyecto").on('input', function(e){
		let v = $(this).val();
		if(v.length == 0){
			$('#cta_cte,#cod_ruc').val('');
			$('#proyectos_id').val('');
		}
	});


	// Guardar Modal: Cliente - Form Modal
  $('form#f_proveedor').submit(function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      console.log('Submit cliente');

      $('#saveProveedor').attr('disabled');

      var actionformPar = $("#f_proveedor").attr('action');
      //$("#saveProveedor").attr("disabled","disabled");
      $.ajax({
          url: actionformPar,
          type:'POST',
          data: new FormData(this),
          processData: false,
          contentType: false,
            beforeSend: function(){
                //toastr.warning('Procesando su solicitud');
            },
          success: function(res){

            swal("Correcto", "Registro guardado", "success")
            .then((value) => {
              console.log('Save Proveedor');
              console.log(res);
                if(res.rs!=0){
                  $('#Modal_add_provee').modal('hide');
                  //location.reload();

                }else{
                  $("#f_proveedor")[0].reset();
                }

                if(res.ok == "ok"){
                  $('#cod_ruc,#cod_ruc2,#cod_emp2').val(res.cod_ruc);
                  $('#razon_social').val(res.razon_social);
                  $('#tele').val(res.tele);
                  $('#edad').val(res.edad);
                  $('.mostrar_1').removeAttr('style');
                  $('.easy-autocomplete').css('width','100%');
                }else{
                  console.log('Ok = no');
                }

            });
          },
          error: function(xhr, status, error){
            $("#saveProveedor").removeAttr("disabled");
            var err = JSON.parse(xhr.responseText);
            var tipo = err.tipo;
            console.log(err.error);
            console.log(status);
            swal("Advertencia", err.error, "warning");
            $("#btnGen").removeAttr("disabled");
            return false;

          }
      });
  });

  // Guardar Modal: Laboratorio - Form Modal
  $('form#f_laboratorio').submit(function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      console.log('Submit laboratorio');

      $('#saveLaboratorio').attr('disabled');

      var actionformPar = $("#f_laboratorio").attr('action');
      //$("#saveLaboratorio").attr("disabled","disabled");
      $.ajax({
          url: actionformPar,
          type:'POST',
          data: new FormData(this),
          processData: false,
          contentType: false,
            beforeSend: function(){
                //toastr.warning('Procesando su solicitud');
            },
          success: function(res){

            swal("Correcto", "Registro guardado", "success")
            .then((value) => {
              console.log('Save Laboratorio');
              console.log(res);
                if(res.rs!=0){
                  $('#Modal_add_provee').modal('hide');
                  //location.reload();

                }else{
                  $("#f_laboratorio")[0].reset();
                }

                if(res.ok == "ok"){
                	console.log(res);
                  $('#laboratorio').val(res.laboratorio);
                  $('#id_laboratorio').val(res.id_laboratorio);
                  $('.mostrar_1').removeAttr('style');
                  $('.easy-autocomplete').css('width','100%');
                }else{
                  console.log('Ok = no');
                }

            });
          },
          error: function(xhr, status, error){
            $("#saveLaboratorio").removeAttr("disabled");
            var err = JSON.parse(xhr.responseText);
            var tipo = err.tipo;
            console.log(err.error);
            console.log(status);
            swal("Advertencia", err.error, "warning");
            $("#btnGen").removeAttr("disabled");
            return false;

          }
      });
  });


  function __labAutocomplete(){

	  var options = {
		url: function(q) {
			console.log(q);
				/*if(q.length <= 2){
					$('#laboratorio').val('');
				}*/

				return baseURL('autocomplete/findLaboratorio?q='+q);
			},

			getValue: function(element) {
				return element.laboratorio ;
			},
			// end getValue
			list: {
			    /*match: {
			      enabled: true
			    },*/

			    maxNumberOfElements: 10,

				onClickEvent: function() {
	                __labAutocomplete_det();
					
				},

				onKeyEnterEvent: function() {
	                __labAutocomplete_det();
				},
				onSelectItemEvent:  function() {
	                __labAutocomplete_det();
				}
			}
		};

	  	$("#laboratorio").easyAutocomplete(options);
	}

}); //end form ready


function formActividad(dia, tc_id, url){

      event.preventDefault();
      event.stopImmediatePropagation();

      $(".easy-autocomplete").css('width','100%');
      console.log('easy-autocomplete');

      //let fecha = $('#fecha_desde').val();
      let fecha = '12/12/2021';
      fecha = fecha.replace('/','-');
      fecha = fecha.replace('/','-');

      if(tc_id == "proyecto"){
        $("#Modal_add_provee").modal('show');
        var url = url+"/proy_add/"+fecha+"/"+tc_id;
      }else{
        $("#Modal_add_actividad").modal('show');
        var url = url+"/tc_add/"+fecha+"/"+tc_id;
      }

}

// Abrir modal form nuevo cliente
function formCliente(dia, tc_id, url){

      event.preventDefault();
      event.stopImmediatePropagation();

      if(tc_id == "cliente"){
        $("#Modal_add_provee").modal('show');
        var url = url+"/clienteAdd/"+tc_id;

      }else if(tc_id='laboratorio'){
      	console.log('laboratorio....');
      	$("#Modal_add_laboratorio").modal('show');
        var url = url+"/laboratorioAdd/"+tc_id;

      }else{
        $("#Modal_add_actividad").modal('show');
        var url = url+"/tc_add/"+fecha+"/"+tc_id;
      }

      $.get(url, function (resp,resul){
        /*console.log(resp);
        console.log(resul);
        console.log('Ajaxs '+ url);*/
        console.log('form creado');
        $(".form-act").html(resp);

      });
}
