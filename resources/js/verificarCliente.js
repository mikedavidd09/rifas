$('#busqueda').on('change', function () {
	$(".mensaje").html("");
	$(".mensajeLista").html("");
     verificarClientePrestamo('cliente','getVerificarClientePrestamo','');
	 verificarListaNegra('cliente','getVerificarClienteListaNegra','');
	 verificarCicloPrestamo('prestamo','getCicloPrestamo','');
	 verificarUltimoInteresDelPrestamoCliente('prestamo','getInteresUltPrestamo','');
});
$('#id_cliente_mobile').on('change', function () {
	$(".mensaje").html("");
	$(".mensajeLista").html("");
     verificarClientePrestamo('cliente','getVerificarClientePrestamo',$(this).val());
	 verificarListaNegra('cliente','getVerificarClienteListaNegra',$(this).val());
	 verificarCicloPrestamo('prestamo','getCicloPrestamo',$(this).val());
	 verificarUltimoInteresDelPrestamoCliente('prestamo','getInteresUltPrestamo',$(this).val());
});

function verificarUltimoInteresDelPrestamoCliente(controller, action,mobile){
	   var id_cliente = (mobile == "") ? $('#busqueda').val():mobile;
        var cadena = id_cliente.split('-');
        var longitud = cadena[0].length;
        var id_cliente = cadena[0].substr(4,longitud-4);
        var parametros = {"id_cliente": id_cliente};

        $.ajax({
            url: 'index.php?controller=' + controller + '&action=' + action + '',
            type: 'POST',
            data: parametros,
            success: function (result) {
					var datos = JSON.parse(result);
					//console.log(datos.data.id_interes);

                    $("#id_interes").val(datos.data.id_interes);

                    $("#id_interes").attr("readOnly","readOnly")
                    $("#capital_max").val(datos.data.capital_max);
                    $("#capital").val(datos.data.capital_max);

                    if(datos.data.capital_max == 0){
                        alertify.alert(' Wolf Financiero S.A. Dice:',"El Capital Maximo esta a cero por favor comuniquese con comite para su debida actualizacion", function () {});
                    }
            }

        });
}
function verificarClientePrestamo(controller, action,mobile){
		var id_cliente = (mobile == "") ? $('#busqueda').val():mobile;
        var cadena = id_cliente.split('-');
        var longitud = cadena[0].length;
        var id_cliente = cadena[0].substr(4,longitud-4);
        var parametros = {"id_cliente": id_cliente};
        $.ajax({
            url: 'index.php?controller=' + controller + '&action=' + action + '',
            type: 'POST',
            data: parametros,
            success: function (result) {
                if(result!=false){
					var data = JSON.parse(result);
					for (var x = 0; x < data.length; x++) {
							$(".mensaje").append('<div class="alert alert-info" role="alert">El Cliente Posee un prestamo activo Codigo Prestamo:<b>'+data[x]["codigo_prestamo"]+'</b>, con un saldo pendiente de :<b>C$'+data[x]["saldo_pendiente"]+'</b></div>');
					}
				}

            }


        });
}
function verificarListaNegra(controller, action,mobile){
		var id_cliente = (mobile == "") ? $('#busqueda').val():mobile;
        var cadena = id_cliente.split('-');
        var longitud = cadena[0].length;
        var id_cliente = cadena[0].substr(4,longitud-4);
        var parametros = {"id_cliente": id_cliente};
		$.ajax({
            url: 'index.php?controller=' + controller + '&action=' + action + '',
            type: 'POST',
            data: parametros,
            success: function (result) {
			var data = JSON.parse(result);
                if(data!=false){
					$("#flags").val(data["id_flags"]);
					$(".mensajeLista").append('<div class="alert alert-warning" role="alert">Este Cliente esta en lista no Represtable con las siguiente observacion:<br ><b>'+data["observacion"]+'</b></div>');
				}else{
					$("#flags").val(0);
				}

            }


        });
}
function verificarCicloPrestamo(controller, action,mobile){
		var id_cliente =  (mobile == "") ? $('#busqueda').val():mobile;
		 
        var cadena = id_cliente.split('-');
        var longitud = cadena[0].length;
        var id_cliente = cadena[0].substr(4,longitud-4);
        var parametros = {"id_cliente": id_cliente};
		$.ajax({
            url: 'index.php?controller=' + controller + '&action=' + action + '',
            type: 'POST',
            data: parametros,
            success: function (result) {
                if(result!=false){
					var data = JSON.parse(result);
					console.log(data);
					$("#ciclo").val(parseInt(data[0]["ciclo"])+1);
				}

            }


        });
}