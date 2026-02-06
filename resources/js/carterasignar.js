jQuery(function($){
    
    /*$("#id_sucursal").on("change",function(){
	var valor = $(this).val();
	
	if(valor != "0"){
		$("#mi-cartera-colaborador").attr("disabled",false);
		$("#mi-cartera-asignada-colaborador").attr("disabled",false);
		getOptionComboXSucursal('Colaborador','dataByComboAnalistaXsucursal','mi-cartera-colaborador','id_colaborador','nombre','apellido',valor,'')
		$("#id_sucursal2").val(valor);
		getOptionComboXSucursal('Colaborador','dataByComboAnalistaXsucursal','mi-cartera-asignada-colaborador','id_colaborador','nombre','apellido',valor,'')
	}else{
		$("#mi-cartera-colaborador").attr("disabled",true);
		$("#mi-cartera-asignada-colaborador").attr("disabled",true);
	}
});*/

	$("#mi-cartera-colaborador").on("change",function(){

		CallAjax($(this).val());

	});

	

function CallAjax(id_colaborador){

		var option = $('#my-select-cartera option');

        var select = $('#my-select-cartera');

		 var op_selected="selected";

		option.each(function () {if (this.value != 0 && this.value != 'All' ) {this.remove();}});

	$.ajax({

        type: 'POST',

        url: 'index.php?controller=Cliente&action=getCartera&id_colaborador='+id_colaborador,

        data: $("#asignacion_form").serialize(),

        success: function (response) {

            var obj = JSON.parse(response);
			var cliente=0;
           for (var x = 0; x < obj.length; x++) {

				select.append('<option '+op_selected+' value ='+obj[x].id_cliente+'>'+obj[x].cliente+'</option>');

				op_selected="";
				cliente= cliente +1;
			}

            select.focus();
			$(".numero").text(cliente);
        }

    });

}

$("#my-select-cartera").on("dblclick",function(){

	var valor=$("#my-select-cartera").val();

	var texto=$("#my-select-cartera option:selected").text();

	$("#my-select-cartera").find("option[value="+valor+"]").next().attr("selected","selected");

	$("#my-select-cartera").find("option[value="+valor+"]").remove();

	$('#my-select-asignar').append('<option value='+valor+'>'+texto+'</option>');

});

$("#my-select-asignar").on("dblclick",function(){

	var valor=$("#my-select-asignar").val();

	var texto=$("#my-select-asignar option:selected").text();

	$("#my-select-asignar").find("option[value="+valor+"]").next().attr("selected","selected");

	$("#my-select-asignar").find("option[value="+valor+"]").remove();

	$('#my-select-cartera').append('<option value='+valor+'>'+texto+'</option>');

});

$("#asigna_select").on("click",function(){

	clientesSelected("my-select-cartera","my-select-asignar");

});



$("#asigna_todos").on("click",function(){

var valor;
var texto;
var cliente = parseInt($(".numero").text());
var clientestransf = parseInt($(".numerotransf").text());
var conteo=0;
	$("#my-select-cartera option").each(function(){
		valor= $(this).val();
		texto = $(this).text();
		$('#my-select-asignar').append('<option value='+valor+'>'+texto+'</option>'); 
		$(this).remove();
		conteo = conteo + 1;
	});
	$("#my-select-cartera").focus();
	$(".numero").text(cliente - conteo);
	$(".numerotransf").text(clientestransf + conteo);
});

$("#designa_todos").on("click",function(){

var valor;
var texto;
var cliente = parseInt($(".numero").text());
var clientestransf = parseInt($(".numerotransf").text());
var conteo=0;
	$("#my-select-asignar option").each(function(){
		valor= $(this).val();
		texto = $(this).text();
		$('#my-select-cartera').append('<option value='+valor+'>'+texto+'</option>'); 
		$(this).remove();
		conteo = conteo + 1;
	});
	$("#my-select-asignar").focus();
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
if(id == "my-select-cartera"){
$(".numero").text(cliente - conteo);
$(".numerotransf").text(clientestransf + conteo);
}else{
$(".numero").text(cliente + conteo);
$(".numerotransf").text(clientestransf - conteo);
}
$("#"+id).focus();
}



$("#designa_select").on("click",function(){

clientesSelected("my-select-asignar","my-select-cartera");

});

});