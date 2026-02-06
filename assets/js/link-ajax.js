/*** solamente para los botones interno de los formularios 
$(".link").click(function () {
    var link = $(this).attr("href");
    $.ajax({
        url: link
    }).done(function (html) {
        $("#page").html(html);
    });
    return false;
});
***/

$(".linking").on('click',function(){
   var ruta=$(this).attr("href");
   var origen = parseInt(ruta.substr(ruta.length-1,ruta.length));
   var thid=$(this);
   if(ruta != '#'){
		if(origen == 1){
			smsConfirm_(ruta,origen,thid);
		}else{
			ajaxEstado(ruta,origen,thid);
			return false;
		}
	}
});
function ajaxEstado(ruta,origen,thid){
	$.ajax({
        type: 'GET',
        url: ruta,
        success: function (response) {
		var r = JSON.parse(response);
            if(origen == 1){
			if(r == "Aprobado"){
				 alerta_("Prestamo Aprobado Correctamente.");
				thid.find("input[type=checkbox]").attr("disabled","disabled");
				thid.attr({
				   'title': 'Prestamo ya Aprobado',
				   'href': '#'
				});
				
			}
		}else{
			if(r == "Activo"){
				 alerta_("El Prestamo se activo Correctamente.");
			}else if(r == "Rechazado"){
				 alerta_("El prestamo se a rechazado.");
			}
			else if(r == "Retornado"){
                alerta_("El prestamo ha sido Retornado al Analista.");
            }
            else if(r == "Pendiente"){
                alerta_("El prestamo ha sido Enviado a Solicitud de Prestamo.");
            }
		}
        }
    });
}
function smsConfirm_(ruta,origen,thid){
	var confirm = alertify.confirm('Wolf Financiero Dice:', "Estas Seguro!!,Vas a Aprobar este prestamo", null, null).set('labels', {
            ok: 'Si',
            cancel: 'No'
        });
        confirm.set({transition: 'slide'});
        confirm.set('onok', function () { //callbak al pulsar botón positivo
            ajaxEstado(ruta,origen,thid);
        });
        confirm.set('oncancel', function () { //callbak al pulsar botón negativo
          thid.find("input[type=checkbox]").prop('checked', false);
            alertify.error('Se cancelo la Aprobaciòn del Prestamo.');
			//$("#asignarno").prop('checked', false);	
        });
        confirm.set('onnegativo', function () { //callbak al pulsar botón negativo
            alertify.error('Se cancela la transaccion');
        });
}
function alerta_(sms){
      //un alert$(this).attr('checked', false);
      alertify.alert('Wolf Financiero Dice:',sms, function () {
	  var url = "index.php?controller=prestamo&action=getBandejaPrestamoPendiente";
          $.ajax({
                  url: url
              }).done(function (html) {     
                      $("#bandeja").html(html);
              })
      });
}
