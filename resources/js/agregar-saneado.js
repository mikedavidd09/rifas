getOptionComboColaboradorSaneo('Colaborador','dataByComboAnalistaSaneo','mi-usuario-saneado','id_colaborador','nombre','apellido','','')
$.getScript("assets/js/searche-case-insensitive.js", function(){
    getSearcheCaseInsensitiveCustomDataListSaneado('Cliente','searcheCaseInsensitevi','nombre','codigo_cliente','id_cliente');
});
$("#busqueda").on('change', function () {
    var val=$('#busqueda').val();
	console.log(val);
    var id_value = val.split("-");
    $('input[name="id_cliente"]').val(id_value[0]);
	getDataListClienteAsanear("Cliente","getDataListViewSaneo",id_value[0]);
});
$("#agregar_lista_a_sanear").on("click",function(){
	var cadena;
	var contador=parseInt($("#contador").val());
	var encontrado=false;
	if($("#nombre_cliente").val() != "" && $("#id_prestamo").val() != ""  && $("#id_colaborador").val() != ""){
		cadena = $("#nombre_cliente").val() + "["+ $("#id_prestamo").val() + "-" + $("#id_colaborador").val() + "-" + $("#saldo_pendiente").val() + "-" + $("#total_abonado").val() + "-" + $("#id_cliente").val() + "]";
		
		if(contador > 0 ){ 
			$("#my-lista-saneo option").each(function(){
				console.log($(this).text(),cadena);
			if($(this).text()  == cadena){
				encontrado=true;
			}
		});
		} else { 
			//encontrado=true;
			contador = contador + 1;
			$("#contador").val(contador)
		}
		
		if(encontrado == false){
			$("#my-lista-saneo").append("<option  value ='"+cadena+"'>"+cadena+"</option>");
			encontrado=false;
		}
	}else{
		alertify.alert('Wolf Financiero S.A. Dice:',"Debes de Buscar el cliente a Sanear", function () {});
	}
		
});
function getDataListClienteAsanear(controller,action,id_cliente){
	var parametros = {"id_cliente": id_cliente};
	console.log("Mellamaste",parametros);
	 $.ajax({
            url: 'index.php?controller=' + controller + '&action=' + action,
            type: 'POST',
            data: parametros,
			beforeSend: function () {
            console.log("Antes de enviarme");
        },
            success: function (response) {
				console.log("Respondiendo",response);
				 var obj = JSON.parse(response);
				 if(obj.respuesta == "true"){
					 //var data=JSON.parse(obj.data);
					$("#codigo_cliente").val(obj.data[0].codigo_cliente);
					$("#nombre_cliente").val(obj.data[0].cliente);
					$("#codigo_prestamo").val(obj.data[0].codigo_prestamo);
					$("#capital").val(obj.data[0].capital);
					$("#deuda_total").val(obj.data[0].deuda_total);
					$("#saldo_pendiente").val(obj.data[0].saldo_pendiente);
					$("#total_abonado").val(obj.data[0].total_abonado);
					$("#gestor").val(obj.data[0].gestor);
					$("#id_prestamo").val(obj.data[0].id_prestamo);
					$("#id_colaborador").val(obj.data[0].id_colaborador);
				 }else{
					 alertify.alert('Wolf Financiero S.A. Dice:',obj.mensaje, function () {});
				 }

            }


        });
	
}

$("#my-lista-saneo").on("dblclick",function(){

	var valor=$("#my-lista-saneo").val();

	var texto=$("#my-lista-saneo option:selected").text();

	$("#my-lista-saneo").find("option[value='"+valor+"']").next().attr("selected","selected");

	$("#my-lista-saneo").find("option[value='"+valor+"']").remove();

});
//para que no se ingrese ningun caracter especial en los inputs
$(".form-control").keypress(function (e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) return true; // 3
    if (tecla == 32) return true;
    patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
});
