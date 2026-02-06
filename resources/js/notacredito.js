var objson;
    $('#busqueda').on('change', function () {                                  // capturar el evento cambio del select modalidad
         var obj =$(this).val();
		if(obj.indexOf("-") > -1){
			agregar=0;
			TotalCapital=0;
			totalInteres=0;
			TotalSaldopendiente=0;
			Idadd = [];
			
				$('#estructura').prop('checked',false);
			 if($('#estructura').is(':checked')){ 
					$('#prestructura').fadeIn(500);
					$("#modalidad").removeClass('form-control noValidated');
				    $("#modalidad").addClass('form-control');
				    $("#plazo").removeClass('form-control noValidated');
				    $("#plazo").addClass('form-control');
					$("#id_interes").removeClass('form-control noValidated');
				    $("#id_interes").addClass('form-control');
					$("#total_interes").removeClass('form-control noValidated');
				    $("#total_interes").addClass('form-control');
					$("#newcuota").removeClass('form-control noValidated');
				    $("#newcuota").addClass('form-control');
					$("#fecha_vencimiento").removeClass('form-control noValidated');
				    $("#fecha_vencimiento").addClass('form-control');
						 
			}else{
					$("#modalidad").removeClass('form-control');
				    $("#modalidad").addClass('form-control noValidated');
				    $("#plazo").removeClass('form-control');
				    $("#plazo").addClass('form-control noValidated');
					$("#id_interes").removeClass('form-control');
				    $("#id_interes").addClass('form-control noValidated');
					$("#total_interes").removeClass('form-control');
				    $("#total_interes").addClass('form-control noValidated');
					$("#newcuota").removeClass('form-control');
				    $("#newcuota").addClass('form-control noValidated');
					$("#fecha_vencimiento").removeClass('form-control');
				    $("#fecha_vencimiento").addClass('form-control noValidated');
			}

            var id_cliente = $('#busqueda').val();
            getOptionComboCodigoPrestamo('prestamo','GetPrestamosActivosByIdClienteNota','codigo_prestamo','id_prestamo','codigo_prestamo','','',id_cliente);
		}
    });

    $('#id_cliente_mobile').on('change', function () {                                  // capturar el evento cambio del select modalidad
         var obj =$(this).val();
		if(obj.indexOf("-") > -1){
			agregar=0;
			TotalCapital=0;
			totalInteres=0;
			TotalSaldopendiente=0;
			Idadd = [];

				$('#estructura').prop('checked',false);
			 if($('#estructura').is(':checked')){
					$('#prestructura').fadeIn(500);
					$("#modalidad").removeClass('form-control noValidated');
				    $("#modalidad").addClass('form-control');
				    $("#plazo").removeClass('form-control noValidated');
				    $("#plazo").addClass('form-control');
					$("#id_interes").removeClass('form-control noValidated');
				    $("#id_interes").addClass('form-control');
					$("#total_interes").removeClass('form-control noValidated');
				    $("#total_interes").addClass('form-control');
					$("#newcuota").removeClass('form-control noValidated');
				    $("#newcuota").addClass('form-control');
					$("#fecha_vencimiento").removeClass('form-control noValidated');
				    $("#fecha_vencimiento").addClass('form-control');

			}else{
					$("#modalidad").removeClass('form-control');
				    $("#modalidad").addClass('form-control noValidated');
				    $("#plazo").removeClass('form-control');
				    $("#plazo").addClass('form-control noValidated');
					$("#id_interes").removeClass('form-control');
				    $("#id_interes").addClass('form-control noValidated');
					$("#total_interes").removeClass('form-control');
				    $("#total_interes").addClass('form-control noValidated');
					$("#newcuota").removeClass('form-control');
				    $("#newcuota").addClass('form-control noValidated');
					$("#fecha_vencimiento").removeClass('form-control');
				    $("#fecha_vencimiento").addClass('form-control noValidated');
			}

            var id_cliente = $('#id_cliente_mobile').val();

            getOptionComboCodigoPrestamo('prestamo','GetPrestamosActivosByIdClienteNota','codigo_prestamo','id_prestamo','codigo_prestamo','','',id_cliente);
		}
    });



    $('#codigo_prestamo').on('change', function () {
        var posicion = parseInt($(this).val())-1;
		var text = $('#codigo_prestamo option:selected').text();
		console.log(text);
		if(text != "Combinar Prestamo"){
			if(text !== "-Seleccione-"){
			$('#estructura').prop('disabled',false);	
				RellenarFormularioNota(objson[posicion]);
				agregarCamposNotas(objson[posicion]);
				 if($('#estructura').is(':checked')){
						 $('.prestructura').fadeIn(500);
						 $("#modalidad").removeClass('form-control noValidated');
						$("#modalidad").addClass('form-control');
						$("#plazo").removeClass('form-control noValidated');
						$("#plazo").addClass('form-control');
						$("#id_interes").removeClass('form-control noValidated');
						$("#id_interes").addClass('form-control');
						$("#total_interes").removeClass('form-control noValidated');
						$("#total_interes").addClass('form-control');
						$("#cuota").removeClass('form-control noValidated');
						$("#cuota").addClass('form-control');
						$("#fecha_vencimiento").removeClass('form-control noValidated');
						$("#fecha_vencimiento").addClass('form-control');
				 }else{
						$("#modalidad").removeClass('form-control');
						$("#modalidad").addClass('form-control noValidated');
						$("#plazo").removeClass('form-control');
						$("#plazo").addClass('form-control noValidated');
						$("#id_interes").removeClass('form-control');
						$("#id_interes").addClass('form-control noValidated');
						$("#total_interes").removeClass('form-control');
						$("#total_interes").addClass('form-control noValidated');
						$("#cuota").removeClass('form-control');
						$("#cuota").addClass('form-control noValidated');
						$("#fecha_vencimiento").removeClass('form-control');
						$("#fecha_vencimiento").addClass('form-control noValidated');
				  }
			}
		}else{
			combinarPrestamo(objson);
			$('#estructura').prop('disabled',true);	
			getOptionCombo('intereses', 'dataByComboInteres', 'id_interes', 'id_interes', 'porcentaje', '', '');
				 $('.prestructura').fadeIn(500);
						 $("#modalidad").removeClass('form-control noValidated');
						$("#modalidad").addClass('form-control');
						$("#plazo").removeClass('form-control noValidated');
						$("#plazo").addClass('form-control');
						$("#id_interes").removeClass('form-control noValidated');
						$("#id_interes").addClass('form-control');
						$("#total_interes").removeClass('form-control noValidated');
						$("#total_interes").addClass('form-control');
						$("#cuota").removeClass('form-control noValidated');
						$("#cuota").addClass('form-control');
						$("#fecha_vencimiento").removeClass('form-control noValidated');
						$("#fecha_vencimiento").addClass('form-control');
		}
		
    });
function reasignar(datajson){
$("#notacodigo").val(datajson.codigo_prestamo);
	$("#notacapital").val(datajson.porcapital);
	$("#notainteres").val(datajson.porinteres);
	$("#notasaldopendiente").val(datajson.saldo_pendiente);
}
function combinarPrestamo(datajson){
eliminaFilas();
		var nuevaFila="",Fila="",i=0;
		var capital=0,interes=0,nuevaFila3="",nimporte=parseFloat(datajson[0].saldo_pendiente);
		var totalcapital=parseFloat(datajson[0].porcapital),totalinteres=parseFloat(datajson[0].porinteres);
		reasignar(datajson[0]);
	for(i=1;i<datajson.length;i++){
		capital = Math.round(((Math.round(parseFloat(datajson[i].capital))/ (Math.round(parseFloat(datajson[i].deuda_total)))) * Math.round(parseFloat(datajson[i].saldo_pendiente)))*100)/100;
		interes = Math.round((parseFloat(datajson[i].saldo_pendiente) - capital)*100)/100;
		nuevaFila = "<tr><td><input type='hidden' name='cnid_prestamo"+i+"' id='cnid_prestamo"+i+"' value='"+datajson[i].id_prestamo+"' class='form-control'/><input type='text' name='cncodigo_prestamo"+i+"' id='cncodigo_prestamo"+i+"' value='"+datajson[i].codigo_prestamo+"' class='form-control' readOnly /></td><td><input type='text' name='cncapital"+i+"' id='cncapital"+i+"' value='"+capital+"' class='form-control' readOnly /></td><td><input type='text' name='cninteres"+i+"' id='cninteres"+i+"' value='"+interes+"' class='form-control' readOnly /></td><td><input type='text' name='cnsaldo_pendiente"+i+"' id='cnsaldo_pendiente"+i+"' value='"+datajson[i].saldo_pendiente+"' class='form-control' readOnly /></td></tr>";
		$("#tablanota tbody").append(nuevaFila);
		nimporte = nimporte + parseFloat(datajson[i].saldo_pendiente);
		totalcapital = totalcapital + capital;
		totalinteres = Math.round((totalinteres + interes)*100)/100;
	}
		$("#cantidadRows").val(i - 1 );
		Fila = "<tr><td>Totales:</td><td><input type='text' name='ncapital' id='ncapital' value='"+nimporte+"' class='form-control' /></td><td><input type='text' name='ninteres' id='ninteres' value='"+0+"' class='form-control' readOnly /></td><td><input type='text' name='nimporte' id='nimporte' value='"+nimporte+"' class='form-control' readOnly /></td></tr>";
		 nuevaFila3="<tr><td></td><td></td><td>Neto a Pagar:</td><td><input type='text' name='netopagar' id='netopagar' value='"+nimporte+"' class='form-control' readOnly /></td></tr>";
		$("#tablanota tbody").append(Fila);
		$("#tablanota tbody").append(nuevaFila3); 
		aEventinteres();
}
function getOptionComboCodigoPrestamo(controller, action, id_select, value, text1, text2,selected,id_cliente) {

    var cadena = id_cliente.split('-');
    var longitud = cadena[0].length;
    var id_cliente_ = cadena[0].substr(4,longitud-4);
    var parametros = {"id_cliente": id_cliente_};

    $.ajax({
        url: 'index.php?controller=' + controller + '&action=' + action + '',
        type: 'POST',
        data: parametros,
        success: function (result) {
            var data = JSON.parse(result);
            var option = $('#' + id_select + ' option');
            var select = $('#' + id_select);
            var op_selected="";

            if(data[2] != false){
                option.each(function () {if (this.value != 0 && this.value != 'All' ) {this.remove();}});
                var datos = data[2];
                objson=data[2];
                var combi="",j=1;
                if(data[1].Cantidad > 1){
                    var ult = parseInt(data[1].Cantidad) + 1;
                    combi="<option  value ='Combinar_Prestamo'>Combinar Prestamo</option>";
                }else{
                    op_selected = 'selected=selected';
                }
                $(".ncredito").text(data[0]);
                for (var x = 0; x < datos.length; x++) {
                    if (text2 != '') {
                        select.append('<option '+op_selected+' value ='+j+'>'+datos[x].codigo_prestamo + ' ' + datos[x].codigo_prestamo+'</option>');
                    } else {
                        select.append('<option '+op_selected+' value ='+j+'>'+datos[x].codigo_prestamo+'</option>');
                    }
                    j= j + 1;
                }
                select.append(combi);
                RellenarFormularioNota(datos[0]);
                agregarCamposNotas(datos[0]);
            }else{
                alertify.alert('Sistema Financiero Dice:','No se encontro ningun prestamo con el item buscado, probablemente no exista o ya se le creo una instancia de Nota de Credito.', function () {});
                limpiar()
                eliminaFilas();
                option.each(function () {if (this.value != 0 && this.value != 'All' ) {this.remove();}});
            }
        }
    });
}
function RellenarFormularioNota(datajson){
	$("#txtCliente").val(datajson.cliente);
	$("#txtIdCliente").val(datajson.id_cliente);
	$("#txtCedula").val(datajson.cedula);
	$("#txtDireccion").val(datajson.direccion);
	$("#txtDepartamento").val(datajson.localidad);
	$("#txtGestor").val(datajson.gestor);
	$("#txtCapital").val(datajson.capital);
	$("#txtDeudaTotal").val(datajson.deuda_total);
	$("#txtMontoInteres").val(datajson.montointeres);
	$("#txtInteres").val(datajson.porcentaje);
	$("#txtCuota").val(datajson.cuota);
	$("#txtPlazo").val(datajson.plazo);
	$("#txtModalidad").val(datajson.modalidad);
	$("#txtCuota").val(datajson.cuota);
	$("#notacodigo").val(datajson.codigo_prestamo);
	$("#notaid_codigo_prestamo").val(datajson.id_prestamo);
	$("#notacapital").val(datajson.porcapital);
	$("#notainteres").val(datajson.porinteres);
	$("#notasaldopendiente").val(datajson.saldo_pendiente);
	$("#txtFechaDesembolso").val(datajson.fecha_desembolso);
	$("#txtFechaVencimiento").val(datajson.fecha_vencimiento);
	$("#txtEstado").val(datajson.estado);
	$("#txtTotalAbonado").val(datajson.total_abonado);
	$("#txtsucursal").val(datajson.id_sucursal);
	
}
function agregarCamposNotas(datajson){
    eliminaFilas();
	var i=0;
	var nuevaFila2="<tr><td></td>";
	var nimporte = 0;
	var nuevaFila3="";
	
	nuevaFila2 += "<td><input type='text' name='ncapital' id='ncapital' value='"+datajson.porcapital+"' class='form-control' readOnly /></td><td><input type='text' name='ninteres' id='ninteres' value='"+datajson.porinteres+"' class='form-control' readOnly /></td><td><input type='text' name='nimporte' id='nimporte' value='"+datajson.saldo_pendiente+"' class='form-control' readOnly /></td></tr>";
	nuevaFila3="<tr><td></td><td></td><td>Neto a Pagar:</td><td><input type='text' name='netopagar' id='netopagar' value='"+datajson.saldo_pendiente+"' class='form-control' readOnly /></td></tr>";
	var nuevaFila = "<tr><td>Montos a Deducir:</td><td><input type='text' name='mcapital' id='mcapital' value='0' class='form-control' /></td><td><input type='text' name='minteres' id='minteres' value='"+i+"' class='form-control' /></td><td></td></tr>";
	$("#tablanota tbody").append(nuevaFila);
	$("#tablanota tbody").append(nuevaFila2);
	$("#tablanota tbody").append(nuevaFila3);
	asignarEvento();
}
function asignarEvento(){
$("#mcapital").on("change",function(){
	var monto = parseFloat($(this).val());
	var capital = parseFloat($("#notacapital").val());
	var interes = parseFloat($("#notainteres").val());
	monto = (monto <= capital) ? monto:capital;
	$(this).val(monto);
	if(monto > 0){
		capital = Math.round((capital - monto)*100)/100;
		$("#ncapital").val(capital);
		var interes = parseFloat($("#ninteres").val());
		$("#nimporte").val(Math.round((capital + interes)*100)/100);
		$("#netopagar").val(Math.round((capital + interes)*100)/100);
	}else{
		$("#ncapital").val(capital);
		var minteres =parseFloat($("#minteres").val());
		interes = Math.round((interes - minteres)*100)/100;
		$("#nimporte").val(Math.round((capital + interes)*100)/100);
		$("#netopagar").val(Math.round((capital + interes)*100)/100);
	}
	UpdateCuotaNew();
});
$("#minteres").on("change",function(){
  var monto = parseFloat($(this).val());
	var capital = parseFloat($("#notacapital").val());
	var interes = parseFloat($("#notainteres").val());
	monto = (monto <= interes) ? monto:interes;
	$(this).val(monto);
	if(monto > 0){
		$("#descuentointeresPorVen").val("0");
		interes = Math.round((interes - monto)*100)/100;
		$("#ninteres").val(interes);
		var capital = parseFloat($("#ncapital").val());
		$("#nimporte").val(Math.round((capital + interes)*100)/100);
		$("#netopagar").val(Math.round((capital + interes)*100)/100);
	}else{
		$("#ninteres").val(interes);
		var mcapital =parseFloat($("#mcapital").val());
		capital = capital - mcapital;
		$("#nimporte").val(Math.round((capital + interes)*100)/100);
		$("#netopagar").val(Math.round((capital + interes)*100)/100);
	}
	UpdateCuotaNew();
});
}
function noValidarHidden(id_div) {
$('#' + id_div).find(':input').each(function () {
	
	if($(this).attr("name") != "obsernotacridto" && $(this).attr("name") != "id_cliente_a" && $(this).attr("name") != "codigo_prestamo_a" && $(this).attr("name") != "notacodigo"){
		$(this).addClass('noValidated');
	}
});
}
function siValidarHidden(id_div) {
 $('#' + id_div).find(':input').each(function () {
		if($(this).attr("name") != "obsernotacridto" && $(this).attr("name") != "id_cliente_a" && $(this).attr("name") != "codigo_prestamo_a" && $(this).attr("name") != "notacodigo"){
		 $(this).removeClass('form-control noValidated');
		$(this).addClass('form-control');
	}
       
    });
}
function eliminaFilas(){
var n=0;
$("#tablanota tbody tr").each(function (){
n++;
});
for(i=n;i>0;i--){
$("#tablanota tbody tr:eq('"+i+"')").remove();
};
}
function eliminarUltFila(cantidad){
$("#tablanota tr:last").remove();
		if(agregar > 1){
			$("#tablanota tr:last").remove();
		}
}
function recalcularDeuda(){
	var idInteres = parseFloat($("#total_interes").val());
	//$("#interesNew").val(idInteres);
	var capital = parseFloat($("#ncapital").val());
	var interes =(capital *(idInteres/100));
	var deudatotal = capital + interes;
	$("#ninteres").val(Math.round(interes*100)/100); 
	$("#nimporte").val(Math.round(deudatotal*100)/100); 
	$("#netopagar").val(Math.round(deudatotal*100)/100);
	var numcuotas = parseInt($("#plazo option:selected").text());
	$("#newcuota").val(Math.round((deudatotal/numcuotas)*100)/100);
}
function aEventinteres(){
	
		$("#id_interes").on("change",function(){
			if ($("#plazo option:selected").text() !== "-Seleccione-") {
				fn_calcular_interes();
				recalcularDeuda();
			}
		});
		$("#ncapital").on("change",function(){
			if ($("#id_interes option:selected").text() !== "-Seleccione-") {
				recalcularDeuda();
			}
		});
		$("#mcapital").attr("readOnly",true);
		$("#minteres").attr("readOnly",true);
		$("#minteres").val(0);
		$("#mcapital").val(0);
}
function desactiveEstructura(){
 $('.prestructura').fadeOut(500);
		$("#mcapital").attr("readOnly",false);
		$("#minteres").attr("readOnly",false);
		$("#ncapital").val($("#notacapital").val());
		$("#ninteres").val($("#notainteres").val());
		$("#nimporte").val($("#notasaldopendiente").val()); 
		$("#netopagar").val($("#notasaldopendiente").val());
		$("#ncapital").attr("readOnly",true);
		
		$("#modalidad").removeClass('form-control');
		$("#modalidad").addClass('form-control noValidated');
		$("#plazo").removeClass('form-control');
		$("#plazo").addClass('form-control noValidated');
		$("#id_interes").removeClass('form-control');
		$("#id_interes").addClass('form-control noValidated');
		$("#total_interes").removeClass('form-control');
		$("#total_interes").addClass('form-control noValidated');
		$("#cuota").removeClass('form-control');
		$("#cuota").addClass('form-control noValidated');
		$("#fecha_vencimiento").removeClass('form-control');
		$("#fecha_vencimiento").addClass('form-control noValidated');
		$("#fecha_primera_cuota").removeClass('form-control');
	    $("#fecha_primera_cuota").addClass('form-control noValidated');
		 
}
$("#estructura").on("change",function(){
	if($(this).is(':checked')){
		var notainteres =parseFloat($("#notainteres").val());
		$("#ninteres").val(0);
		$("#ncapital").val($("#notasaldopendiente").val());
		$("#nimporte").val($("#notasaldopendiente").val()); 
		$("#netopagar").val($("#notasaldopendiente").val());
		getPrestamoReestructura();	 
		aEventinteres();
	}else{
			desactiveEstructura();
	}
});
function getPrestamoReestructura(){
	$('.prestructura').fadeIn(500);
		 $("#modalidad").removeClass('form-control noValidated');
				    $("#modalidad").addClass('form-control');
				    $("#plazo").removeClass('form-control noValidated');
				    $("#plazo").addClass('form-control');
					$("#id_interes").removeClass('form-control noValidated');
				    $("#id_interes").addClass('form-control');
					$("#total_interes").removeClass('form-control noValidated');
				    $("#total_interes").addClass('form-control');
					$("#cuota").removeClass('form-control noValidated');
				    $("#cuota").addClass('form-control');
					$("#fecha_vencimiento").removeClass('form-control noValidated');
				    $("#fecha_vencimiento").addClass('form-control');
				    $("#fecha_primera_cuota").addClass('form-control');
	            $("#fecha_primera_cuota").removeClass('form-control noValidated');
		$("#ncapital").attr("readOnly",false);
		getOptionCombo('intereses', 'dataByComboInteres', 'id_interes', 'id_interes', 'porcentaje', '', '');
}
$("#plazo").on("change",function(){
var numcuotas = parseInt($("#plazo option:selected").text());
var modalidad,txtsucursal;
fn_calcular_interes();
var saldo = parseFloat($("#netopagar").val());
var cuota = Math.round((saldo / numcuotas)*100)/100;
$("#numcuotas").val(numcuotas);
$("#newcuota").val(cuota);
modalidad = $("#modalidad").val();
txtsucursal = $("#txtsucursal").val();
//calcular fecha_vencimiento
recalcularDeuda();
$.ajax({
            url: 'index.php?controller=prestamo&action=getFechaVencimiento&plazo='+numcuotas+'&modalidad='+modalidad+'&sucursal='+txtsucursal,
            type: 'GET',
            success: function (datos) {
				var fechaVencimiento = JSON.parse(datos);
				 //console.log(fecha);
                $("#fecha_vencimiento").val(fechaVencimiento[0].fecha_de_vencimiento);
				 $("#fecha_primera_cuota").val(fechaVencimiento[1].fecha_primera_cuota);
            }     //fin funcion success
        });
});
function UpdateCuotaNew(){
var plazo = parseInt($("#plazo option:selected").text());
if (!isNaN(plazo)){
	var cuota = Math.round((parseFloat($("#netopagar").val()) / plazo)*100)/100;
	$("#newcuota").val(cuota);
}
}
function fn_calcular_interes() {
        plazo = parseInt($("#plazo option:selected").text());
        interes = parseFloat($("#id_interes option:selected").text());
        var modalidad = $("#modalidad").val();
        if (modalidad == "Semanal")
            $("#total_interes").val(plazo / 4 * interes);
        else if (modalidad == "Quincenal")
            $("#total_interes").val(plazo / 2 * interes);
        else if (modalidad == "Diario")
            $("#total_interes").val(plazo / 20 * interes);
        else
            $("#total_interes").val(plazo * interes);
    }
function limpiar(){
$("#notacodigo").val("");
$("#notacapital").val("");
$("#notainteres").val("");
$("#notasaldopendiente").val("");
}		