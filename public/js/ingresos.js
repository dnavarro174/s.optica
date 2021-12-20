var _totItems = $("#tot_reg").val();
var _fila = "";

//////////////////////////////AGREGAR FILA
$("#add_producto2").click(
	function(e){ 
	    e.preventDefault(); 
	    e.stopImmediatePropagation();
	    if($("#cod_artic").val()=="" ){
	    	alert("El producto no esta registrado");
	    	$("#producto").val('').focus();
	    	return;
	    }

	    if($("#producto").val()=="" ){
	    	alert("Ingrese producto");
	    	$("#producto").focus();
	    	return;
	    }
	    if($("#cant").val()=="" ||  $("#cant").val()==0){
	    	alert("Ingrese cantidad, digito mayor de 0");
	    	$("#cant").focus();
	    	return; 
	    }
	    if($("#costo_mn").val()=="" ||  $("#costo_mn").val()==0){
	    	alert("Ingrese costo unitario");
	    	$("#costo_mn").focus();
	    	return;
	    }
	    if($("#subtotal").val()=="" ||  $("#subtotal").val()==0){
	    	alert("Ingrese subtotal");
	    	$("#subtotal").focus();
	    	return;
	    }
	    var _flg1 = 0;
		$('#ma_detalle tr').each(function() {
			var _fnd = parseInt( $(this).find("td:eq(1)").find("input").val());
			if(_fnd == $("#cod_artic").val()){
				_flg1 = 1;
			}
		});

		if(_flg1 == 1){
			swal("Atención", "El artículo ya esta registrado!", "warning");
			return;
		}
	

	    $(".reg_ejm").remove()
	    if($("#subtotal").val()!="" ){
		    _fila = ""; 
		    //$("#filas_contenedor").empty();
			_totItems++;
			_fila += "<tr id='td_"+_totItems+"'>";
			_fila += "<td style='text-align: center'>"+_totItems+"</td>";
			_fila += "<td style='text-align: center'><input type='text' class='textos' readonly name='cod_art_"+_totItems+"' value='"+$("#cod_artic").val()+"' ></td>";
			_fila += "<td>"+$("#producto").val()+"</td>";
			_fila += "<td style='text-align: center'><input type='text' class='textos' readonly name='uni_med_"+_totItems+"' value='"+$("#cod_umedida").val()+"' ></td>";
			_fila += "<td style='text-align: center'><input type='text' class='textos cant_"+_totItems+"' readonly name='cant_"+_totItems+"' value='"+$("#cant").val()+"' ></td>";
			_fila += "<td style='text-align: center'><input type='text' class='textos2 costo_"+_totItems+"' readonly name='costo_mn_"+_totItems+"' value='"+$("#costo_mn").val()+"' ></td>";
			_fila += "<td style='text-align: center'><input type='text' class='textos2 subt_"+_totItems+"' readonly name='subto_"+_totItems+"' value="+$("#subtotal").val()+"></td>"; 
			//_fila += "<td style='text-align: center'><i class='mdi mdi-pencil text-info icon-md' num='"+_totItems+"' title='Quitar'></i>";
			_fila += "<td style='text-align: center'>";
			_fila += "<i class='mdi mdi-pencil text-dark icon-md btnAdd' num='"+_totItems+"' title='Agregar'></i>";
			_fila += "<i class='mdi mdi-content-save text-dark icon-md btnSave save_"+_totItems+"' style='display:none;' num='"+_totItems+"' title='Guardar'></i>";
			_fila += "<i class='mdi mdi-delete text-danger icon-md btnQuitar' num='"+_totItems+"' title='Quitar'></i>";
			_fila += "</td></tr>";

			$("#tot_reg").val(_totItems);
			$("#filas_contenedor").append(_fila);
			reordenaFila();
			limpiar();
	    }
	}
);
	
function limpiar(){
	$("#cod_artic, #cod_artic2, #producto, #cod_umedida, #cant, #costo_mn, #subtotal").val("");
	$("#producto").focus("");
}

function limpiar_css(v){
	let a = v;
	a.removeAttr('style');
}
/////////////////////////////EDITAR FILA
$(document).on('click','.btnAdd', function(e){
	//_totItems--;
	var num =   $(this).attr("num") ;

	$('#td_'+num+" input.costo_"+num).css('border','1px solid red').attr('readonly',false);
	$('#td_'+num+" input.cant_"+num).css('border','1px solid red').attr('readonly',false);
	$('#td_'+num+" input.subt_"+num).attr('readonly',false);
	$('#td_'+num+" .save_"+num).css('display','inline-block');
	
	reordenaFila();
	$("#tot_reg").val(_totItems);
});
/////////////////////////////EDITAR_SAVE FILA
$(document).on('click','.btnSave', function(e){
	//_totItems--;
	var num =   $(this).attr("num");

	let ccosto = $('#td_'+num+" input.costo_"+num).val();
	let ccant = $('#td_'+num+" input.cant_"+num).val();
	let ssubt = $('#td_'+num+" input.subt_"+num).val();

	console.log('ssubt: '+ssubt);

	if(ccant=="" || ccant == 0){
		alert("La cantidad tiene que ser mayor a 0");
		return false;
	}
	if(ccant!=""){
		let ssubt = parseFloat(ccosto * ccant);
		$('#td_'+num+" input.subt_"+num).val(ssubt.toFixed(4));
	}

	$('#td_'+num+" input.costo_"+num).css('border','1px solid darkgrey').attr('readonly',true);
	$('#td_'+num+" input.cant_"+num).css('border','1px solid darkgrey').attr('readonly',true);
	$('#td_'+num+" input.subt_"+num).attr('readonly',true);
	$('#td_'+num+" .save_"+num).css('display','none');
	
	reordenaFila();
	$("#tot_reg").val(_totItems);
});


/////////////////////////////ELIMINAR FILA
$(document).on('click','.btnQuitar', function(e){
	_totItems--;
	var num =   $(this).attr("num") ;
	/*$( "#td_" + num ).remove();*/
	$(this).parents('tr').eq(0).remove();
	recrea();
	reordenaFila();
	$("#tot_reg").val(_totItems);
	//console.log('tot_reg: '+_totItems);
});	

function reordenaFila(){
	console.log('Reordenando');
	var _cont = 1;
	var _val1 = 0;
	var _val2 = 0;
	var _val3 = 0;
	$('#ma_detalle tr').each(function(e) {
		if($(this).find("td:eq(4)").find("input").val()){
			//console.log(parseInt( $(this).find("td:eq(4)").find("input").val()))
			$(this).find("td:first").html(_cont);
			_val1 = _val1 + parseInt( $(this).find("td:eq(4)").find("input").val());
			_val2 = _val2 + parseFloat( $(this).find("td:eq(5)").find("input").val());
			_val3 = _val3 + parseFloat( $(this).find("td:eq(6)").find("input").val());
			_cont++;			
		}
	});

	$("#tot1").html(_val1);
	$("#tot2").html(_val2.toFixed(4));
	$("#tot3").html(_val3.toFixed(4));
	$("#tot3_a").val(_val3.toFixed(4));
	console.log(_cont);
}

//////////////////////////////SUBTOTAL
$("#cant, #costo_mn").keyup(
	function(e){ 
		let cant = parseFloat($("#cant").val());
		if($("#costo_mn").val()!=""){
			let v = parseFloat($("#costo_mn").val() * cant);

			$("#subtotal").val(v.toFixed(4));
			
		}
	}
);

$("#fecha_desde").change(function(e){

	e.preventDefault();
	let nro_doc = $("#nro_doc").val();
	let f_desde = $("#fecha_desde").val();

	let url = baseURL('check_nrodoc');
	let tipo = 1;
	let param = {
		nro_doc,
		f_desde,
		tipo
	}

	var moneda = $('#moneda');

	$.get(url, param, function(resp){

		console.log(resp);
        console.log("valor monedas ="+resp.monedas.length);
		if(resp.monedas.length>0){
            $('#tipo_cambio').val('');
            $('#moneda').empty();
            $("#moneda").append("<option value='' selected>SELECCIONE</option>");
        	$('#add_TC').css('display','none');
            for(var i=0;i<resp.monedas.length;i++){
              $("#moneda").append("<option val_dolares='"+resp.monedas[i].TC_me+"' val_soles='"+resp.monedas[i].TC_compra_mn+"' tipo='"+resp.monedas[i].cod_moneda+"' val_compra_mn='"+resp.monedas[i].TC_compra_mn+"' value='"+resp.monedas[i].id+"' fecha='"+resp.monedas[i].fecha+"'>"+resp.monedas[i].nom_moneda+"</option>");
            }
        }else{
        	$("#moneda").empty();
        	$("#moneda").append("<option value='' selected>SELECCIONE</option>");
        	$('#add_TC').css('display','block');
        	let a = baseURL('');
        	formActividad('1','0',a);
        	swal("Mensaje", "Registrar tipo de cambio", "warning"); return;
        }
        if(resp.nro_doc>0){
        	console.log(nro_doc);
            swal("Atención", "El Número de Documento ya esta registrado", "warning");
            $('#nro_doc').css('border','1px solid red');
			return;
        }
	});

});

////////////////////FORMULARIO
 $("form").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });

$('form#ingresosForm2').submit(function (e) {

    e.preventDefault(); 
    e.stopImmediatePropagation();

    if( $("#tot_reg").val()==0 ){
    	swal("Error!", "Ingrese al menos un item!", "error"); 
    	$("#cant").focus();
    	return;
    }

    //$("#imgLoading").attr("style","display: block; float: right; padding-left: 10px; padding-top: 10px");
	var actionformPar = $("#ingresosForm2").attr('action');

	//$("#btnEnviar").attr("disabled","disabled");
	//$("#btnCancelar").attr("disabled","disabled");
	
	$.ajax({
    	url: actionformPar,
    	type:'POST',
    	data: new FormData(this),
    	processData: false,
    	contentType: false,
        beforeSend: function(){
            //toastr.warning('Procesando su solicitud');
            console.log('iiii');
        },
    	success: function(res){
    		console.log(res);
            /*swal({
              title: "Registro correcto",
              text: "Los registros fueron grabados correctamente.",
              icon: "success",
              button: "Cerrar",
            },function(){
				location.href= "{{ route('almacen.index') }}";
			});*/

			swal({ 
				title: "Registro correcto",
              	text: "Los registros fueron grabados correctamente.",
              	icon: "success",
			  }).then((value) => {
			  		let p = $("#ruta_alm").val()+'/'+res.id+'/edit';
  					location.href = p;
				});;

    	},
    	error: function(xhr, status, error){
    		
    		//$("#btnAceptar").removeAttr("disabled");
    		//$("#btnCancelar").removeAttr("disabled");
			//alert("Error, intente mas tarde");
  			swal("Error!",xhr.responseJSON.error, "error");

	    }
	});

});

/*$("#actionSubmit_salida2").click(function(e){
	e.preventDefault();
	if( $("#tot_reg").val()==0 ){
    	swal("Error!", "Ingrese al menos un item!", "error"); return;
    }
    console.log('submit enviando');
    $("form#ingresosForm2").submit();
});*/

$("#moneda").change(function(){

    var element = $(this).find('option:selected'); 
    var tipo = element.attr('tipo');

    if(tipo == 1){
    	var val_compra_mn = element.attr('val_soles');
    }else{
    	var val_compra_mn = element.attr('val_dolares')
    }
    $("#tipo_cambio").val(val_compra_mn);
    
});
$("#moneda").trigger("change");


///solo enteros

$(document).ready(function () {
  $("#cant").keypress(function (e) {
     	/*  bloquea step="any" los decimales
     	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
               return false;
    	}*/
	});

});

$( "#cant" ).blur(function() {
    this.value = parseFloat(this.value).toFixed(2);
    console.log('slll');
});


//valida tipo de cambio de fecha
$("#moneda").change(
	function(){
		if( $("#fecha_desde").val()!="" ){
		    var element = $(this).find('option:selected'); 
		    var _fecha_mon = element.attr("fecha"); 
		    if(_fecha_mon != $("#fecha_desde").val()){
		    	swal("Atención", "Registre el tipo de cambio para la fecha " + $("#fecha_desde").val(), "warning");
		    	$("#moneda").val("");
		    	$('#fecha_desde').css('border','1px solid red');
		    	$('#add_TC').css('display','block');
		    }else{
		    	$('#add_TC').css('display','none');
		    }
			
		}else{
			swal("Atención", "Seleccione fecha!", "warning");
		}
	}
);


/**/

$("#nro_doc").change(function(){
	//$(this).css('border','none');
	limpiar_css($(this));
});

function saltar(e,id)
{
  // Obtenemos la tecla pulsada
  (e.keyCode)?k=e.keyCode:k=e.which;
 
  // Si la tecla pulsada es enter (codigo ascii 13)
  if(k==13)
  {
    // Si la variable id contiene "submit" enviamos el formulario
    if(id=="submit")
    {
      document.forms[0].submit();
    }else{
      // nos posicionamos en el siguiente input
      document.getElementById(id).focus();
    }
  }
}

function recrea(){
	var $filas = $("#filas_contenedor tr");
	var index = 0;
	$filas.each(function(){
		index++;
		var $tr = $(this);
		$tr.attr("id","td_"+index);
		$textos=$tr.find("input:text");
		$textos.eq(0).removeClass().addClass("textos cod_art_"+index).attr("name","cod_art_"+index)
		$textos.eq(1).removeClass().addClass("textos uni_med_"+index).attr("name","uni_med_"+index)
		$textos.eq(2).removeClass().addClass("textos cant_"+index).attr("name","cant_"+index)

		$textos.eq(3).removeClass().addClass("textos2 costo_"+index).attr("name","costo_mn_"+index)
		$textos.eq(4).removeClass().addClass("textos2 subt_"+index).attr("name","subto_"+index)
		$tr.find(".btnAdd").attr("num",index);
		$tr.find(".btnSave").removeClass().addClass("mdi mdi-content-save text-dark icon-md btnSave save_"+index).attr("num",index);
		$tr.find(".btnQuitar").attr("num",index);
	});
}