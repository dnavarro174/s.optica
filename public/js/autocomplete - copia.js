(function($) {
		////////////////////FORMULARIO
		

})(jQuery);

$( document ).ready(function() {

	/*$("#basics").easyAutocomplete(options);*/
	__clientAutocomplete();

function __clientAutocomplete(){
 
  var options = {
	url: function(q) {
		//return "api/countrySearch.php?phrase=" + phrase + "&format=json";
			if(q.length > 2){
				
			}else{
				$('#email').val('');
				$('#name').val('');
			}

		return baseURL('autocomplete/findProduct?q='+q);


		},

		getValue: "nombre",
		list: 
			{
			maxNumberOfElements: 10,
			onClickEvent: function() {
				//alert("Click !");
				var e = $('#basics').getSelectedItemData();
				console.log('abc');
				console.log(e);
				$('#email').val(e.costo_mn);
				$('#name').val(e.stock_total);
			}	
		}
	};

	//$("#provider-remote").easyAutocomplete(options);
  	$("#basics").easyAutocomplete(options);
}
});