jQuery(function($){
    
    
		
		getOptionCombo('Colaborador','dataByComboSupervision','mi-supervisor-asignado','id_colaborador','nombre','apellido','','');
		getOptionCombo('Colaborador','dataByComboSupervision','mi-supervisor-reasignado','id_colaborador','nombre','apellido','','');


	$("#mi-supervisor-asignado").on("change",function(){

		CallAjax($(this).val());

	});

	

function CallAjax(id_colaborador){

		var option = $('#my-select-vendedores option');

        var select = $('#my-select-vendedores');

		 var op_selected="selected";

		option.each(function () {if (this.value != 0 && this.value != 'All' ) {this.remove();}});

	$.ajax({

        type: 'POST',

        url: 'index.php?controller=Colaborador&action=getVendedores&id_colaborador='+id_colaborador,

        data: $("#asignacion_form").serialize(),

        success: function (response) {

            var obj = JSON.parse(response);
			var cliente=0;
           for (var x = 0; x < obj.length; x++) {

				select.append('<option '+op_selected+' value ='+obj[x].id_colaborador+'>'+obj[x].nombre+' '+obj[x].apellido+'</option>');

				op_selected="";
				cliente= cliente +1;
			}

            select.focus();
			$(".numero").text(cliente);
        }

    });

}

$("#my-select-vendedores").on("dblclick",function(){

	var valor=$("#my-select-vendedores").val();

	var texto=$("#my-select-vendedores option:selected").text();

	$("#my-select-vendedores").find("option[value="+valor+"]").next().attr("selected","selected");

	$("#my-select-vendedores").find("option[value="+valor+"]").remove();

	$('#my-vendedores-reasignar').append('<option value='+valor+'>'+texto+'</option>');

	var vendedoresNum = parseInt($(".numero").text());
	var vendedorestransf = parseInt($(".numerotransf").text());
	$(".numero").text(vendedoresNum - 1);
	$(".numerotransf").text(vendedorestransf + 1);

});

$("#my-vendedores-reasignar").on("dblclick",function(){

	var valor=$("#my-vendedores-reasignar").val();

	var texto=$("#my-vendedores-reasignar option:selected").text();

	$("#my-vendedores-reasignar").find("option[value="+valor+"]").next().attr("selected","selected");

	$("#my-vendedores-reasignar").find("option[value="+valor+"]").remove();

	$('#my-select-vendedores').append('<option value='+valor+'>'+texto+'</option>');

	var vendedoresNum = parseInt($(".numero").text());
	var vendedorestransf = parseInt($(".numerotransf").text());
	$(".numero").text(vendedoresNum + 1);
	$(".numerotransf").text(vendedorestransf - 1);

});

$("#asigna_select").on("click",function(){

	clientesSelected("my-select-vendedores","my-vendedores-reasignar");

});



$("#asigna_todos").on("click",function(){

var valor;
var texto;
var cliente = parseInt($(".numero").text());
var clientestransf = parseInt($(".numerotransf").text());
var conteo=0;
	$("#my-select-vendedores option").each(function(){
		valor= $(this).val();
		texto = $(this).text();
		$('#my-vendedores-reasignar').append('<option value='+valor+'>'+texto+'</option>'); 
		$(this).remove();
		conteo = conteo + 1;
	});
	$("#my-select-vendedores").focus();
	$(".numero").text(cliente - conteo);
	$(".numerotransf").text(clientestransf + conteo);
});

$("#designa_todos").on("click",function(){

var valor;
var texto;
var cliente = parseInt($(".numero").text());
var clientestransf = parseInt($(".numerotransf").text());
var conteo=0;
	$("#my-vendedores-reasignar option").each(function(){
		valor= $(this).val();
		texto = $(this).text();
		$('#my-select-vendedores').append('<option value='+valor+'>'+texto+'</option>'); 
		$(this).remove();
		conteo = conteo + 1;
	});
	$("#my-vendedores-reasignar").focus();
	$(".numero").text(cliente + conteo);
	$(".numerotransf").text(clientestransf - conteo);
});

function clientesSelected(id,select){

var valor;
var cliente = parseInt($(".numero").text());
var texto;
var clientestransf = parseInt($(".numerotransf").text());
var conteo=0;
$("#"+id+" option:selected").each(function() {

	valor= $(this).val();

	texto = $(this).text();

	$('#'+select).append('<option value='+valor+'>'+texto+'</option>'); 

	$(this).remove();
	conteo = conteo + 1;
});
$("#"+id+" option:first").attr('selected','selected');
if(id == "my-select-vendedores"){
$(".numero").text(cliente - conteo);
$(".numerotransf").text(clientestransf + conteo);
}else{
$(".numero").text(cliente + conteo);
$(".numerotransf").text(clientestransf - conteo);
}
$("#"+id).focus();
}



$("#designa_select").on("click",function(){

clientesSelected("my-vendedores-reasignar","my-select-vendedores");

});

});

