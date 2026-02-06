jQuery(function($){

	$("#tipoasignacionvac").on("change",function(){
		var tipo = $(this).val();

		if(tipo == 9 || tipo == 816){
			$("#update_balance").show();
			$("#vac_disfrute").hide();//todosLosColaboradores
			$("#todosLosColaboradores").show();
			$("#dias_efectivo").addClass("form-control noValidated");
			$("#dias_real_disfrute").addClass("form-control noValidated");
			$("#fecha_final_tomar").addClass("form-control noValidated");
			$("#fecha_inicio_vac").addClass("form-control noValidated");

			$("#dias_trabajados").removeClass("form-control noValidated");
			$("#dias_real_acumulativo").removeClass("form-control noValidated");

			$("#dias_trabajados").addClass("form-control");
			$("#dias_real_acumulativo").addClass("form-control");
			
		}else if(tipo == 800){
			$("#update_balance").hide();
			$("#vac_disfrute").show();
			$("#todosLosColaboradores").hide();
			//borrando el opcional
			$("#dias_efectivo").removeClass("form-control noValidated");
			$("#dias_real_disfrute").removeClass("form-control noValidated");
			$("#fecha_final_tomar").removeClass("form-control noValidated");
			$("#fecha_inicio_vac").removeClass("form-control noValidated");

			$("#dias_trabajados").addClass("form-control noValidated");
			$("#dias_real_acumulativo").addClass("form-control noValidated");


			//agregando el requerido
			$("#dias_efectivo").addClass("form-control");
			$("#dias_real_disfrute").addClass("form-control");
			$("#fecha_final_tomar").addClass("form-control");
			$("#fecha_inicio_vac").addClass("form-control");

		}
	})

	$("#all").on("change",function(){
		var select = $(this).val();
		console.log(select);
		if($(this).is(":checked")){
			$("#id_colaborador").attr("readOnly","readOnly");
			$("#id_colaborador").addClass("form-control noValidated");
		}else{
			$("#id_colaborador").removeAttr("readOnly");
			$("#id_colaborador").removeClass("form-control noValidated");
			$("#id_colaborador").addClass("form-control");
		}
	});

	$("#dias_trabajados").on("change",function(){
			 var dias = parseFloat($(this).val());
			 var factor=0.082;
			 var diasReal = dias * factor;

			 $("#dias_real_acumulativo").val(diasReal);
	});

	$("#dias_efectivo").on("change",function(){
			 var diasEfectivo = parseFloat($(this).val());
			 var diasRealTomar= 0;
			 var factor=6;
			 var finsemanaSumar = parseInt(diasEfectivo / factor);

			 diasRealTomar = diasEfectivo + finsemanaSumar;

			 console.log(finsemanaSumar);
			 if(Number.isInteger(diasEfectivo)){
			 	$("#salida").hide();
			 	$("#salida").removeClass("form-control");
			 	$("#salida").addClass("form-control noValidated");
			 	// $("#salida option[value='2']").attr("selected", true);
			 	$("#hora_salida").val("07:00:00");
			 }else{
			 	$("#salida").show();
			 	$("#salida").removeClass("form-control noValidated");
				$("#id_colaborador").addClass("form-control");
			 }


			 $("#dias_real_disfrute").val(diasRealTomar);
	});
	$("#fecha_inicio_vac").on("change",function(){
			 //sumar fecha de inicio con los dias reales a tomar
			 //validar si hay feriado dentro del rango establecido
			 //para hacer recorrerla la fecha , ya que dias ferias se respetan no deben de ser como vacaciones 
			 //dependiente los feriados que haiga la fecha final se le sumara.

			 var fecha = $(this).val();
			 var diasATomar =  $("#dias_real_disfrute").val();
			 var id_colaborador = parseInt($("#id_colaborador").val());
			 var salida = $("#hora_salida").val();
			 salida = (salida == "00:00") ? "07:00:00":salida;

			 if(id_colaborador > 0 ){
			 var data={
			 		'fecha_inicio':fecha,
			 		'dias_tomar':diasATomar,
			 		'id_colaborador':id_colaborador,
			 		'hora_salida':salida
			 };

			 $.ajax({
			        type: 'POST',
			        url: 'index.php?controller=Vacaciones&action=calculateFinFecha',
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
			            console.log(obj.cantidad_feriado);
			            
			            $("#cantidad_feriados").val(obj.cantidad_feriado);
			            $("#fecha_final_tomar").val(obj.fecha_final);
			            calendario(fecha,obj.fecha_final);
			           
			        }
   			 });
   		}else{
   			 alertify.alert('Wolf Financiero S.A. Dice:',"Debe de escoger al menos un colaborador", function () {});
   		}
	});

	function calendario(fechainicio,fechafinal){
		var objDate = fechafinal.split(" ");
		var evento = [
	            {
	                'title': 'Solicitud de vacaciones',
	                'description': 'se puede poner el nombre del colaborador',
	                'start': fechainicio,
	                'end':  objDate[0],
	                'color': '#3A87AD',
	                'textColor': '#ffffff',
	            }
	        ];
			console.log(evento);
			//$('#calendar').fullCalendar('destroy'); // destruye el calendario se tiene que volver a iniciar
			$('#calendar').fullCalendar( 'removeEvents'); //.fullCalendar('removeEventSources');
			$("#calendar").fullCalendar("addEventSource",evento);
			 //$('#calendar').fullCalendar('render');
			 $('#calendar').fullCalendar( "rerenderEvents" );
	}
$('#historial').DataTable();
	$("#id_colaborador_historial").on("change",function(){
		var id_colaborador = parseInt($("#id_colaborador_historial").val());
		var data={
			 		'id_colaborador':id_colaborador
			 };
			 $('tbody').html("");
		$.ajax({
			        type: 'POST',
			        url: 'index.php?controller=Vacaciones&action=getHistorialVacAndAcumulate',
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
					    if(!Array.isArray(obj)){
					    	aux="<tr><td>"+obj.id_hist_vac+"</td>";
			            	aux +="<td>"+obj.dias_efectivo+"</td>";
			            	aux +="<td>"+obj.dias_real_tomar+"</td>";
			            	aux +="<td>"+obj.dias_acumulado+"</td>";
			            	aux +="<td>"+obj.cantidad_feriado+"</td>";
			            	aux +="<td>"+obj.codigo+"</td>";
			            	aux +="<td>"+obj.descripcion_codigo+"</td>";
			            	aux +="<td>"+obj.fecha_inicio+"</td>";
			            	aux +="<td>"+obj.fecha_final+"</td>";
			            	aux +="<td>"+obj.fecha_registro+"</td>";
			            	aux +="<td>"+obj.usuario_registrador+"</td>";
			            	aux +="<td>"+obj.usuario_aprobador+"</td></tr>";
			            	fila +=aux;
					    }else{
					    	for(i=0;i<obj.length;i++){
				            	aux="<tr><td>"+obj[i].id_hist_vac+"</td>";
				            	aux +="<td>"+obj[i].dias_efectivo+"</td>";
				            	aux +="<td>"+obj[i].dias_real_tomar+"</td>";
				            	aux +="<td>"+obj[i].dias_acumulado+"</td>";
				            	aux +="<td>"+obj[i].cantidad_feriado+"</td>";
				            	aux +="<td>"+obj[i].codigo+"</td>";
				            	aux +="<td>"+obj[i].descripcion_codigo+"</td>";
				            	aux +="<td>"+obj[i].fecha_inicio+"</td>";
				            	aux +="<td>"+obj[i].fecha_final+"</td>";
				            	aux +="<td>"+obj[i].fecha_registro+"</td>";
				            	aux +="<td>"+obj[i].usuario_registrador+"</td>";
				            	aux +="<td>"+obj[i].usuario_aprobador+"</td></tr>";
				            	fila +=aux;
				            	
				            }
					    }
			            
			           		$('tbody').html(fila);         
			        }
   			 });
	});
});