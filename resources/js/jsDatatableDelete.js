$('#delete tbody').on( 'click', 'a', function () {
var link = $(this).attr("href");
var table = $('#delete').DataTable();
	$(this).parents('tr').hasClass('selected');
	smsConfirmDelete(link,table, $(this).parents('tr') );
		return false;
} );

function smsConfirmDelete(link,tabla,parentTR){
	var confirm = alertify.confirm('ServiCreditoS.A. Dice:', "Estas Seguro!!,Desea Borrar esta Cuota?", null, null).set('labels', {
            ok: 'Si',
            cancel: 'No'
        });
        confirm.set({transition: 'slide'});
        confirm.set('onok', function () { //callbak al pulsar botón positivo
           $.ajax({
				url: link
			}).done(function (response) {
               var obj = JSON.parse(response);
               data = obj;
				if(obj.respuesta == "true"){
					alertify.alert('ServiCreditoS.A. Dice:',obj.mensaje, function () {
						tabla.row( parentTR ).remove().draw();
					});
					
				}else{
					alertify.alert('ServiCreditoS.A. Dice:',obj.mensaje, function () {});
				}
			});
        });
        confirm.set('oncancel', function () { //callbak al pulsar botón negativo
            alertify.error('Se cancelo la transacción! borrado cuota.');
        });
        confirm.set('onnegativo', function () { //callbak al pulsar botón negativo
            alertify.error('Se cancela la transaccion');
        });
}