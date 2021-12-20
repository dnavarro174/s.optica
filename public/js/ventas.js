var IMPORTE = 0;

var _totItems = $("#tot_reg").val();
var _fila = "";
var descuento, xsubtotal, xigv, xtotal = 0;

function msgInputFocus(text,input,title,icon){
    if(typeof icon==='undefined')icon="error";
    if(typeof input==='undefined')input="";
    if(typeof title==='undefined')title="Error";
    swal({
        title: title,
        text: text,
        icon: icon,
        closeOnConfirm: false
    }).then(function (){
        if(input!=""){
            $("#"+input).focus().select();
        }
    });
    return false;
}
function floatval(mixed_var) {
    return (parseFloat(mixed_var) || 0);
}
function round(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

//////////////////////////////AGREGAR FILA
$("#add_producto2").click(
	function(e){
	    e.preventDefault();
	    e.stopImmediatePropagation();
	    console.log('Add row');

	    if($("#cod_artic").val()=="" )return msgInputFocus("El producto no esta registrado","producto");
	    if($("#producto").val()=="" )return msgInputFocus("Ingrese producto","producto");
	    if($("#cant").val()=="" ||  $("#cant").val()==0)return msgInputFocus("Ingrese cantidad, digito mayor de 0","cant");
	    if($("#costo_mn").val()=="" || $("#costo_mn").val()==0 || $("#costo_mn").val()=="NaN")return msgInputFocus("Ingrese precio del producto","costo_mn");
	    if($("#subtotal").val()=="" || $("#subtotal").val()==0 || $("#subtotal").val()=="NaN")return msgInputFocus("Ingrese subtotal","subtotal");

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

		// validar stock - salidas/stock/{id}/{cant}
		let tipo_ = $("#tipo_").val();

		let cod_artic = $("#cod_artic").val();
		let cant 	  = $("#cant").val();

		let proy 	  = $("#proyectos_id").val();

		cod_artic = (cod_artic)?cod_artic:0;
		cant = (cant)?cant:0;
		proy = (proy)?proy:0;
		if(cod_artic == 0 && cant == 0){
			swal("Atención", "Ingrese un artículo", "warning");
			$('#producto').focus().val('').css('background','#d9ebfd');
		}

		/*if(tipo_ == "transferencia"){

		}else{

			if(nro_preing != 3){
				if(proy == 0){
					swal("Atención", "Ingrese el nombre del proyecto", "warning");
					$('#proyecto').val('').css('background','#d9ebfd');
					$('#proyecto').focus();
					return;
				}

			}

		}*/




		let url = baseURL('salidas/stock/'+cod_artic+'/'+cant);

		$.ajax({
			url: url,
			type: 'get',
			success: function(resp){

				if(resp[0].stock == "A"){
					swal("Atención", "El artículo no tiene registrado stock", "warning");
					return false;
				}else if(resp[0].stock == "B"){
					swal("Atención", "El artículo no tiene stock. Stock: "+resp[0].stock_bd, "warning");
					return false;
				}else if(resp[0].stock == "C"){
					swal("Atención", "El artículo no cuenta con stock suficiente. Stock: "+resp[0].stock_bd, "warning");
					return false;
				}else{
					//swal("Atención", "El artículo cuenta con stock. "+resp[0].stock, "warning");

				    $(".reg_ejm").remove();
				    if($("#subtotal").val()!="" ){

					    _fila = "";
                        var costo = floatval($("#costo_mn").val());
                        var importes = costo>0?costo/1.18:0;
                        var igves=costo-importes;
					    //$("#filas_contenedor").empty();
						_totItems++;
						_fila += "<tr id='td_"+_totItems+"'>";
						_fila += "<td style='text-align: center'>"+_totItems+"</td>";
						_fila += "<td style='text-align: center'><input type='text' class='textos' readonly name='cod_art_"+_totItems+"' value='"+$("#cod_artic").val()+"' ></td>";
						_fila += "<td>"+$("#producto").val()+"</td>";
						_fila += "<td style='text-align: center'><input type='text' class='textos' readonly name='uni_med_"+_totItems+"' value='"+$("#cod_umedida").val()+"' ></td>";
						_fila += "<td style='text-align: center'><input type='text' class='textos cant_"+_totItems+" impocal' readonly name='cant_"+_totItems+"' value='"+$("#cant").val()+"' ></td>";
						_fila += "<td style='text-align: center'><input type='text' class='textos costo_"+_totItems+" impocal' readonly name='costo_"+_totItems+"' value='"+$("#costo_mn").val()+"' ></td>";

						_fila += "<td style='text-align: center'><input type='text' class='textos importes_"+_totItems+"' readonly name='importes_"+_totItems+"' value='"+importes.toFixed(2)+"' ></td>";
						_fila += "<td style='text-align: center'><input type='text' class='textos igves_"+_totItems+"' readonly name='igves_"+_totItems+"' value='"+igves.toFixed(2)+"' ></td>";

                        _fila += "<td style='text-align: center'><input type='text' class='textos subt_"+_totItems+"' readonly name='subt_"+_totItems+"' value='"+$("#subtotal").val()+"' ></td>";

                        //_fila += "<td style='text-align: center'><i class='mdi mdi-pencil text-info icon-md' num='"+_totItems+"' title='Quitar'></i>";
						_fila += "<td style='text-align: center'>";
						_fila += "<i class='mdi mdi-pencil text-dark icon-md btnAdd pr-1' num='"+_totItems+"' title='Agregar'></i>";
						_fila += "<i class='mdi mdi-content-save text-dark icon-md btnSave save_"+_totItems+" pr-1' style='display:none;' num='"+_totItems+"' title='Guardar'></i>";
						_fila += "<i class='mdi mdi-delete text-danger icon-md btnQuitar' num='"+_totItems+"' title='Quitar'></i>";
						_fila += "</td></tr>";

						$("#tot_reg").val(_totItems);
						$("#filas_contenedor").append(_fila);
						reordenaFila();
						limpiar();
				    }
				}


			}
		});




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
	console.log('click btnAdd');
	var num =   $(this).attr("num") ;

	$('#td_'+num+" input.costo_"+num).css('border','1px solid red').attr('readonly',false);
	$('#td_'+num+" input.cant_"+num).css('border','1px solid red').attr('readonly',false);
	$('#td_'+num+" input.subt_"+num).attr('readonly',false);
	$('#td_'+num+" .save_"+num).css('display','inline-block');

	reordenaFila();
	$("#tot_reg").val(_totItems);
});

$(document).on('keyup change','.impocal', function(e){
    $this = $(this);
    var $tr = $this.parents("tr").eq(0);
    var num = $tr.attr("id").replace("td_","");
    var pre = floatval($('#td_'+num+" input.costo_"+num).val());
    var can = floatval($('#td_'+num+" input.cant_"+num).val());
    var nom = $tr.find("td").eq(2).html();
    var text = nom.split("STOCK:",2)[1];
    var stock = floatval(text);
    if(can>stock){
        $('#td_'+num+" input.cant_"+num).val(stock);
        can = stock;
    }
    var sub = pre*can;
    var importes = sub/1.18;
    var igves = sub-importes;

    console.log(nom);
    console.log(stock);
    $('#td_'+num+" input.subt_"+num).val(sub.toFixed(2));
    $('#td_'+num+" input.importes_"+num).val(importes.toFixed(2));
    $('#td_'+num+" input.igves_"+num).val(igves.toFixed(2));


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
		$('#td_'+num+" input.subt_"+num).val(ssubt.toFixed(2));
	}

	$('#td_'+num+" input.costo_"+num).css('border','1px solid darkgrey').attr('readonly',true);
	$('#td_'+num+" input.cant_"+num).css('border','1px solid darkgrey').attr('readonly',true);
	$('#td_'+num+" input.subt_"+num).attr('readonly',true);
	$('#td_'+num+" .save_"+num).css('display','none');

	reordenaFila();
	$("#tot_reg").val(_totItems);
	$("#importe").val(IMPORTE);
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
});

function reordenaFila(){
	var _cont = 1;
	var _val1 = 0;
	var _val2 = 0;
	var _val3 = 0;
	$('#ma_detalle tr').each(function(e) {
		if($(this).find("td:eq(4)").find("input").val()){
		//console.log(parseInt( $(this).find("td:eq(4)").find("input").val()))
			$(this).find("td:first").html(_cont);
			_val1 = _val1 + parseInt( $(this).find("td:eq(4)").find("input").val());
			_val2 = _val2 + floatval( $(this).find("td:eq(5)").find("input").val());
			_val3 = _val3 + floatval( $(this).find("td:eq(8)").find("input").val());//antes 6
			_cont++;
		}
	});
    IMPORTE = _val3;console.log("TOTALIZA",IMPORTE);


	$("#tot1").html(_val1);
	$("#tot2").html(_val2.toFixed(2));

    console.log("subtotoal1", _val3);



    xsubtotal = round(_val3 / 1.18,2);
	xsubtotal3 = round(_val3 / 1.18,3);
    console.log(xsubtotal);
    console.log(xsubtotal3);
	xsubtotal = round(xsubtotal,2);

    $("#xsubtotal").val(xsubtotal.toFixed(2));
    $("#xsubtotal_text").html(xsubtotal.toFixed(2));

    var d = floatval($('#descuento').val());
    descuento = d>0&&d<=100?descuento/100 * xsubtotal:0;
    totaliza();
}



//////////////////////////////SUBTOTAL
$("#cant, #costo_mn").keyup(
	function(e){
		if($("#costo_mn").val()!=""){
			let v = parseFloat($("#costo_mn").val() * $("#cant").val());

			$("#subtotal").val(v.toFixed(2));

		}
	}
);

function totaliza(){
    var subtotal = floatval($('#xsubtotal').val());
    var total_recibido = floatval($('#total_recibido').val());
    var d = floatval($('#descuento').val());
    var descuento = d>0&&d<=100?round(d/100 * IMPORTE,2):0;//subtotal
    var igv      = round(IMPORTE - subtotal,2);
    //var igv      = round((subtotal - descuento) * 0.18,2);
    var total    = (subtotal - descuento) + igv;
    vuelto = total_recibido>0?round(total_recibido-total,2):0;
    //console.log(`Importe ${IMPORTE} - subtotal ${subtotal} - descuento ${descuento} - total ${total} - `);


    //$("#xsubtotal").val(subtotal);
    //$("#xsubtotal_text").html(subtotal.toFixed(2));
    $("#xdesc").val(descuento.toFixed(2));
    $("#xdesc_text").html(descuento.toFixed(2));
    $("#xigv").val(igv.toFixed(2));
    $("#xigv_text").html(igv.toFixed(2));
    $("#xtotal").val(total.toFixed(2));
    $("#xtotal_text").html(total.toFixed(2));
    $("#vuelto").val(vuelto.toFixed(2));
}

$('#descuento,#total_recibido').keyup((e)=>{
    totaliza();
    return;



	descuento = parseInt(e.target.value);
	if(descuento == ""){

		$('#xdesc').val(0);
		$('#xdesc_text').html('0');

		$("#xsubtotal").val(xsubtotal);
		$("#xsubtotal_text").html(xsubtotal);
		$("#xigv").val(xigv);
		$("#xigv_text").html(xigv);
		$("#xtotal").val(xtotal);
		$("#xtotal_text").html(xtotal);

	}else{
		descuento = descuento / 100;

		let subt = parseInt($('#xsubtotal').val());
		let xxigv = subt * 0.18;
		let xxtotal = parseInt($('#xtotal').val());

		let xxsubtotal = subt / 1.18;
		descuento = descuento * xxsubtotal;
		xxigv = (subt - descuento)*0.18;
		xxtotal = (sub - descuento)+xxigv;

		xxsubtotal = xxsubtotal.toFixed(2);
		descuento = descuento.toFixed(2);
		xxigv = xxigv.toFixed(2);
		xxtotal = xxtotal.toFixed(2);

		$('#xdesc').val(descuento.toFixed(2));
		$('#xdesc_text').html(descuento.toFixed(2));
		console.log(e.target.value+ ' -- '+xxtotal);

		$("#xsubtotal").val(xxsubtotal);
		$("#xsubtotal_text").html(xxsubtotal);
		$("#xigv").val(xxigv);
		$("#xigv_text").html(xxigv);
		$("#xtotal").val(xxtotal);
		$("#xtotal_text").html(xxtotal);
	}



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

    /*if( $("#nro_preing").val()!=3){
	    if( $("#proyectos_id").val()==0 && $("#proyectos_id").val() == "" ){
	    	swal("Error!", "Ingrese el nombre del proyecto", "warning");
	    	$("#proyecto").focus().css('background','#d9ebfd');
	    	return;
	    }
    }*/

    //$("#imgLoading").attr("style","display: block; float: right; padding-left: 10px; padding-top: 10px");
	var actionformPar = $("#ingresosForm2").attr('action');

	$("#btnEnviar").attr("disabled","disabled");
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
  			$("#btnEnviar").attr("disabled",false);

	    }
	});
    $("#importe").val(IMPORTE);

});


///solo enteros
$(document).ready(function () {
    IMPORTE = floatval($("#importe").val());

    /*$("#cant").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
               return false;
        }
    });*/

    // agregar proyectos y tipo de cambio
    console.log('listo_autocomplete');
	  $('.easy-autocomplete').css('width','100%');

	  $('form#f_actividad').submit(function (e) {
	      e.preventDefault();
	      e.stopImmediatePropagation();
	      console.log('Submit form ingresos');
	      $('#saveActividades').attr('disabled');

	      var actionformPar = $("#f_actividad").attr('action');
	      //$("#saveActividades").attr("disabled","disabled");
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
	              swal({
	                    type: 'success',
	                    title: 'Éxito...',
	                    text: 'Registro guardado!',
	                  })
	                  .then((value) => {
	                      if(res.rs!=0){
	                        $('#Modal_add_actividad').modal('hide');
	                        //location.reload();

	                      }else{
	                        $("#f_actividad")[0].reset();
	                      }

	                      if(res.monedas != 0){
	                        $('#tipo_cambio').val('');
	                        $('#moneda').empty();
	                        $("#moneda").append("<option value='' selected>SELECCIONE</option>");
	                        $('#add_TC').css('display','none');
	                        for(var i=0;i<res.monedas.length;i++){
	                          $("#moneda").append("<option val_dolares='"+res.monedas[i].TC_me+"' val_soles='"+res.monedas[i].TC_compra_mn+"' tipo='"+res.monedas[i].cod_moneda+"' val_compra_mn='"+res.monedas[i].TC_compra_mn+"' value='"+res.monedas[i].id+"' fecha='"+res.monedas[i].fecha+"'>"+res.monedas[i].nom_moneda+"</option>");
	                        }
	                      }else{
	                        console.log('No monedas');
	                      }

	                  });


	          },
	          error: function(xhr, status, error){
	            $("#saveActividades").removeAttr("disabled");
	            var err = JSON.parse(xhr.responseText);
	            var tipo = err.tipo;
	            console.log(err.error);
	            console.log(status);
	            alert(err.error);

	          $("#btnGen").removeAttr("disabled");
	          return false;

	          }
	      });
	    });

});



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

		$textos.eq(3).removeClass().addClass("textos2 costo_"+index).attr("name","costo_"+index)
		$textos.eq(4).removeClass().addClass("textos2 subt_"+index).attr("name","subto_"+index)
		$tr.find(".btnAdd").attr("num",index);
		$tr.find(".btnSave").removeClass().addClass("mdi mdi-content-save text-dark icon-md btnSave save_"+index).attr("num",index);
		$tr.find(".btnQuitar").attr("num",index);
	});
}
