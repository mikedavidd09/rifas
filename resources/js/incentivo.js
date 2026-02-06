jQuery(function($){

	$("#id_sucursal").on("change",function(){

		var id_sucursal = $(this).val();

		 var data={
			 		'id_sucursal':id_sucursal
			 };

			 $.ajax({
			        type: 'POST',
			        url: 'index.php?controller=Incentivo&action=getDataincentivo',
			        data: data,
			        beforeSend: function () {
			            $('.pace').removeClass('pace-inactive');
			            $('.pace').addClass('pace-active');
			        },
			        success: function (response) {
			        	// si el incentivo es cero o null, habilitar par que sea introducido los valores 
						$('.pace').removeClass('pace-active');
			            $('.pace').addClass('pace-inactive');
			        	var obj = JSON.parse(response);
			            console.log(obj);

			            
			            if(obj.data == undefined){
			            	
			            
			            	$("#id_sucursal_parametriza").val(obj.id_sucursal);
			            	$("#id_parametro").val(obj.id_parametro);
			            	$("#n_cliente").val(obj.n_cliente);
			            	$("#sanidad").val(obj.sanidad);
			            	$("#cliente_mora_porc").val(obj.porc_cliente_mora);
			            	$("#n_cliente_valor").val(obj.valor_ncl);
			            	$("#sanidad_valor").val(obj.valor_sa);
			            	$("#cliente_mora_porc_valor").val(obj.valor_cm);
			            	$("#monto_cliente_vencido_porc").val(obj.porc_monto_vencido);
			            	$("#saldo_cartera").val(obj.saldo_cartera);
			            	$("#mora_porct").val(obj.porc_mora);
			            	$("#monto_vencido_porc_valor").val(obj.valor_mv);

			            	$("#saldo_cartera_valor").val(obj.valor_sc);
			            	$("#mora_porct_valor").val(obj.valor_m);

			            	$("#meta_recuado").val(obj.meta_recuado);
			            	$("#meta_recuado_valor").val(obj.valor_mrecaudo);

			            	//activar el boto actualizar
			            	$("#sms_parametros").hide();
			            	var formulario="frm_insertEupdateIncentivos";
			            	var controller="Incentivo";
			            	var action="update";
			            	$("#inferior").html("<a class='link' href='#' href='#' onclick=validarEnviar('"+formulario+"','"+controller+"','"+action+"',"+obj.id_parametro+");><input type='button' value='Actualizar' class='btn btn-info' /></a>");
			            	//$("#inferior").html('<input href="#" type="submit" value="Actualizar" id="update_parametrizar" class="btn btn-primary" onclick="validarEnviar(frm_insertEupdateIncentivos,Incentivo,update,'+obj.id_parametro+');return false;" />');

			            	//" <a class="link" href="#"" href="#" onclick="validarEnviar(frm_insertEupdateIncentivos,Incentivo,update,"+obj.id_parametro+");><input type='button' value='Actualizar' class='btn btn-info '/></a>"
			            }else{
			            	console.log("Es null aqui se va a ingresar nuevo registro");
			            	//limpio formulario y mando mensaje que tiene que ingresar los parametros y activar el boton guardar
			            	$("form select").each(function() { this.selectedIndex = 0 });
     						$("form input[type=text] , form textarea").each(function() { this.value = '' });
			            	$("#id_sucursal_parametriza").val(id_sucursal);
			            	$("#id_parametro").val(0);

			            	$("#sms_parametros").show();
			            	$("#inferior").html("<input href='#' type='submit' value='Registrar' id='registro_parametrizar' class='btn btn-success' onclick=validarEnviar('frm_insertEupdateIncentivos','Incentivo','crear','');return false; />");			            }		           
			        }
   			 });



	});

	$("#ver_parametros_generados").click("change",function(){
		getParametrosObtenidosPorColaborador("get");

		return false;
	});

	$("#generar_parametros_generados").on("click",function(){
		
		getParametrosObtenidosPorColaborador("set");
		$("#btn-guardar-incentivo").show();
		return false;
	})
	$("#ingresarcierreincentivo").on("click",function(){
		var id_sucursal	= $("#id_sucursal_pccol").val();
		var fecha_inicio = $("#fecha_inicio_periodo").val();
		var fecha_final = $("#fecha_final_periodo").val();
			console.log(id_sucursal,fecha_inicio,fecha_final);
		var data={
					'id_sucursal':id_sucursal,
			 		'fecha_inicio':fecha_inicio,
			 		'fecha_final':fecha_final
			 };

			 $.ajax({
			        type: 'POST',
			        url: 'index.php?controller=Incentivo&action=setIncentivosCierreMes',
			        data: data,
			        beforeSend: function () {
			            $('.pace').removeClass('pace-inactive');
			            $('.pace').addClass('pace-active');
			        },
			        success: function (response) {
						$('.pace').removeClass('pace-active');
			            $('.pace').addClass('pace-inactive');
			        	console.log(response);
			            var obj = JSON.parse(response);
			      
					    if(obj.respuesta == "true"){
					    	alertify.alert('Wolf Financiero S.A. Dice:',obj.mensaje, function () {});
					    }else{
					    	alertify.alert('Wolf Financiero S.A. Dice:',obj.mensaje, function () {});
					    }
					          
			        }
   			 });

		return false;
	});

	$("#generacion_de_bono").on("click",function(){
		var mes = $("#mes_periodo").val();
		var anho = $("#anho_periodo").val();

		if (mes != 0 && anho != 0) {
			var data={
			 		'mes':mes,
			 		'anho':anho
			 };

			 $.ajax({
			        type: 'POST',
			        url: 'index.php?controller=Incentivo&action=getBonoColaboradorAndParametros',
			        data: data,
			        beforeSend: function () {
			            $('.pace').removeClass('pace-inactive');
			            $('.pace').addClass('pace-active');
			        },
			        success: function (response) {
						$('.pace').removeClass('pace-active');
			            $('.pace').addClass('pace-inactive');
			        	console.log(response);
			            var obj = JSON.parse(response);
			            console.log(obj);
			            var i=0;
			            var fila="";
			            var aux="";
			            /*$('#historial').DataTable({
					        ajax: obj,
					    });*/
					    if(obj.respuesta == "true"){
					    	var data = obj.data;
					    	console.log(data);
					    	if(!Array.isArray(data)){
					    	aux="<tr><td>"+data.nombre+" "+data[i].apellido+"</td>";
				            	aux +="<td>"+data.M_CLiente+"</td>";
				            	aux +="<td>"+data.R_Cliente+"</td>";
				            	aux +="<td>"+data.Pago_Ncliente+"</td>";
				            	aux +="<td>"+data.M_Sanidad+"</td>";
				            	aux +="<td>"+data.R_Sanidad+"</td>";
				            	aux +="<td>"+data.Pago_Sanidad+"</td>";
				            	aux +="<td>"+data.M_CLiente_ENmora+"</td>";
				            	aux +="<td>"+data.R_CLiente_ENmora+"</td>";
				            	aux +="<td>"+data.Pago_ClienteMora+"</td>";
				            	aux +="<td>"+data.M_MontoVencido+"</td>";
				            	aux +="<td>"+data.R_MontoVencido+"</td>";
				            	aux +="<td>"+data.Pago_MontoVencido+"</td>";
				            	aux +="<td>"+data.M_SaldoCartera+"</td>";
				            	aux +="<td>"+data.R_SaldoCartera+"</td>";
				            	aux +="<td>"+data.Pago_SaldoCartera+"</td>";
				            	aux +="<td>"+data.M_Mora+"</td>";
				            	aux +="<td>"+data.R_Mora+"</td>";
				            	aux +="<td>"+data.Pago_Mora+"</td>";
				            	aux +="<td>"+data.Meta_recuado+"</td>";
				            	aux +="<td>"+data.recaudo_real+"</td>";
				            	aux +="<td>"+data.pago_metarecuado+"</td>";
				            	aux +="<td>"+data.Total_Percibir_Bono+"</td></tr>";
			            	fila +=aux;
					    }else{
					    	var data = obj.data;
					    	var classAsignada="";
					    	for(i=0;i<data.length;i++){
				            	aux="<tr><td>"+data[i].nombre+" "+data[i].apellido+"</td>";
				            	aux +="<td class='Meta'>"+data[i].M_CLiente+"</td>";
				            	aux +="<td class='real'>"+data[i].R_Cliente+"</td>";
				            	classAsignada = (data[i].Pago_Ncliente > 0) ? "Pago":"Red";
				            	aux +="<td class='"+classAsignada+"'>"+data[i].Pago_Ncliente+"</td>";
				            	aux +="<td class='Meta'>"+data[i].M_Sanidad+"</td>";
				            	aux +="<td class='Real'>"+data[i].R_Sanidad+"</td>";
				            	classAsignada = (data[i].Pago_Sanidad > 0) ? "Pago":"Red";
				            	aux +="<td class='"+classAsignada+"'>"+data[i].Pago_Sanidad+"</td>";
				            	aux +="<td class='Meta'>"+data[i].M_CLiente_ENmora+"</td>";
				            	aux +="<td class='Real'>"+data[i].R_CLiente_ENmora+"</td>";
				            	classAsignada = (data[i].Pago_ClienteMora > 0) ? "Pago":"Red";
				            	aux +="<td class='"+classAsignada+"'>"+data[i].Pago_ClienteMora+"</td>";
				            	aux +="<td class='Meta'>"+data[i].M_MontoVencido+"</td>";
				            	aux +="<td class='Real'>"+data[i].R_MontoVencido+"</td>";
				            	classAsignada = (data[i].Pago_MontoVencido > 0) ? "Pago":"Red";
				            	aux +="<td class='"+classAsignada+"'>"+data[i].Pago_MontoVencido+"</td>";
				            	aux +="<td class='Meta'>"+data[i].M_SaldoCartera+"</td>";
				            	aux +="<td class='Real'>"+data[i].R_SaldoCartera+"</td>";
				            	classAsignada = (data[i].Pago_SaldoCartera > 0) ? "Pago":"Red";
				            	aux +="<td class='"+classAsignada+"'>"+data[i].Pago_SaldoCartera+"</td>";
				            	aux +="<td class='Meta'>"+data[i].M_Mora+"</td>";
				            	aux +="<td class='Real'>"+data[i].R_Mora+"</td>";
				            	classAsignada = (data[i].Pago_Mora > 0) ? "Pago":"Red";
				            	aux +="<td class='"+classAsignada+"'>"+data[i].Pago_Mora+"</td>";

				            	aux +="<td class='Meta'>"+data[i].Meta_recuado+"</td>";
				            	aux +="<td class='real'>"+data[i].recaudo_real+"</td>";
				            	classAsignada = (data[i].pago_metarecuado > 0) ? "Pago":"Red";
				            	aux +="<td class='"+classAsignada+"'> "+data[i].pago_metarecuado+"</td>";
				            	aux +="<td>"+data[i].Total_Percibir_Bono+"</td></tr>";
				            	fila +=aux;
				            	
				            }
					    }
			            
			           		$('tbody').html(fila);  
			           		shapeTable(); 
					    }else{
					    	alertify.alert('Wolf Financiero S.A. Dice:',obj.mensaje, function () {});
					    }
					          
			        }
   			 });


		}else{
		  alertify.alert('Wolf Financiero S.A. Dice:',"Todos los parametros son obligatorios", function () {});
		}

		return false;
	})

	function getParametrosObtenidosPorColaborador(method){

		var id_sucursal	= $("#id_sucursal_pccol").val();
		var fecha_inicio = $("#fecha_inicio_periodo").val();
		var fecha_final = $("#fecha_final_periodo").val();
			console.log(id_sucursal,fecha_inicio,fecha_final);
		var data={
			 		'id_sucursal':id_sucursal,
			 		'fecha_inicio':fecha_inicio,
			 		'fecha_final':fecha_final
			 };
			 // este boton podra ver los datos que ya estan generados previamente con el cron o se haya ingresado manualmente

			 if(id_sucursal != 0 && fecha_inicio != "" && fecha_final != ""){
			 	//limpiando border en rojo
			 	$("#id_sucursal_pccol").attr("Style","");
			 	$("#fecha_inicio_periodo").attr("Style","");
			 	$("#fecha_final_periodo").attr("Style","");
			 	$.ajax({
			        type: 'POST',
			        url: 'index.php?controller=Incentivo&action=getParametrosBonoColaborador&method='+method,
			        data: data,
			        beforeSend: function () {
			            $('.pace').removeClass('pace-inactive');
			            $('.pace').addClass('pace-active');
			        },
			        success: function (response) {
						$('.pace').removeClass('pace-active');
			            $('.pace').addClass('pace-inactive');
			        	console.log(response);
			            var obj = JSON.parse(response);
			            console.log(obj);
			            var i=0;
			            var fila="";
			            var aux="";
			            /*$('#historial').DataTable({
					        ajax: obj,
					    });*/
					    if(obj.respuesta == "true"){
					    	var data = obj.data;
					    	console.log(data);
					    	if(!Array.isArray(data)){
					    	aux="<tr><td>"+data.id_colaborador+"</td>";
					    	aux +="<td>"+data.colaborador+"</td>";
			            	aux +="<td>"+data.bcliente+"</td>";
			            	aux +="<td>"+data.bsanidad+"</td>";
			            	aux +="<td>"+data.bporc_cliente_mora+"</td>";
			            	aux +="<td>"+data.bporc_monto_vencido+"</td>";
			            	aux +="<td>"+data.bsaldo_cartera+"</td>";
			            	aux +="<td>"+data.bporcmora+"</td></tr>";
			            	/*aux +="<td>"+obj.fecha_inicio+"</td>";
			            	aux +="<td>"+obj.fecha_final+"</td>";
			            	aux +="<td>"+obj.fecha_registro+"</td>";
			            	aux +="<td>"+obj.usuario_registrador+"</td>";
			            	aux +="<td>"+obj.usuario_aprobador+"</td></tr>";*/
			            	fila +=aux;
					    }else{
					    	var data = obj.data;
					    	for(i=0;i<data.length;i++){
				            	aux="<tr><td>"+data[i].id_colaborador+"</td>";
				            	aux +="<td>"+data[i].colaborador+"</td>";
				            	aux +="<td>"+data[i].bcliente+"</td>";
				            	aux +="<td>"+data[i].bsanidad+"</td>";
				            	aux +="<td>"+data[i].bporc_cliente_mora+"</td>";
				            	aux +="<td>"+data[i].bporc_monto_vencido+"</td>";
				            	aux +="<td>"+data[i].bsaldo_cartera+"</td>";
				            	aux +="<td>"+data[i].bporcmora+"</td></tr>";
				            	/*aux +="<td>"+obj[i].fecha_inicio+"</td>";
				            	aux +="<td>"+obj[i].fecha_final+"</td>";
				            	aux +="<td>"+obj[i].fecha_registro+"</td>";
				            	aux +="<td>"+obj[i].usuario_registrador+"</td>";
				            	aux +="<td>"+obj[i].usuario_aprobador+"</td>";*/
				            	fila +=aux;
				            	
				            }
					    }
			            
			           		$('tbody').html(fila);   
					    }else{
					    	alertify.alert('Wolf Financiero S.A. Dice:',obj.mensaje, function () {});
					    }
					          
			        }
   			 });

			 }else{
			 	console.log("mandar alert que tiene que rellenar los parametros");
			 	if(id_sucursal == 0)
			 		$("#id_sucursal_pccol").attr("Style","border: 1px solid red");
			 	else
			 		$("#id_sucursal_pccol").attr("Style","");
			 	if(fecha_inicio == "")
			 		$("#fecha_inicio_periodo").attr("Style","border: 1px solid red");
			 	else
			 		$("#fecha_inicio_periodo").attr("Style","");
			 	if(fecha_final == "")
			 		$("#fecha_final_periodo").attr("Style","border: 1px solid red");
			 	else
			 		$("#fecha_final_periodo").attr("Style","");

			 	alertify.alert('Wolf Financiero S.A. Dice:',"Revise los campos que estan en rojo", function () {});

			 }



	}

/*$(window).resize(function() {
    shapeTable();
});*/
function shapeTable() {

	var smallBreak = 1050; // Your small screen breakpoint in pixels
var columns = $('.dataTable tr').length;
var rows = $('.dataTable th').length;
console.log(columns,rows);
//$(document).ready(shapeTable());


    //if ($(window).width() < smallBreak) {
        for (i=0;i < rows; i++) {
            var maxHeight = $('.dataTable th:nth-child(' + i + ')').outerHeight();
            for (j=0; j < columns; j++) {
                if ($('.dataTable tr:nth-child(' + j + ') td:nth-child(' + i + ')').outerHeight() >= maxHeight) {
                    maxHeight = $('.dataTable tr:nth-child(' + j + ') td:nth-child(' + i + ')').outerHeight();
                }
                if ($('.dataTable tr:nth-child(' + j + ') td:nth-child(' + i + ')').prop('scrollHeight') > $('.dataTable tr:nth-child(' + j + ') td:nth-child(' + i + ')').outerHeight()) {
                    maxHeight = $('.dataTable tr:nth-child(' + j + ') td:nth-child(' + i + ')').prop('scrollHeight');
                }
            }
            for (j=0; j < columns; j++) {
            	 var tdhei = parseFloat(maxHeight) + 4;
                $('.dataTable tr:nth-child(' + j + ') td:nth-child(' + i + ')').css('height',tdhei);
                $('.dataTable th:nth-child(' + i + ')').css('height',maxHeight);
            }
        }
   /* } else {
        $('.dataTable td, .dataTable th').removeAttr('style');
    }*/
}
	
});