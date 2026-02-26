/**
 * Created by ruiz on 08-24-17.
 */
if ( $.fn.DataTable.isDataTable('#delete') ) {
    $('#delete').DataTable().destroy();
}
 var table = $('#delete')
    .addClass('responsive nowrap')/*realizar javascrip  q cuando sea pantalla moviles  se agrege la clase  table.addClass('responsive nowrap');*/
    .dataTable( {
        responsive: true,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por paginas",
            "zeroRecords": "No se encontró nada, sin registros",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(Filtrado de  _MAX_ total de registros)",
            "search": "Buscar en la Tabla"
        },
		order: [0, "desc"]
    } );

    $('#delete tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );

    table.api().columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
$('#delete tbody').on( 'click', 'a', function () {
var link = $(this).attr("href");
var table = $('#delete').DataTable();
	$(this).parents('tr').hasClass('selected');
	smsConfirmDelete(link,table, $(this).parents('tr') );
		return false;
} );

function smsConfirmDelete(link,tabla,parentTR){
	var confirm = alertify.confirm('Wolf Financiero Dice:', "Estas Seguro!!,Desea Borrar esta Cuota?", null, null).set('labels', {
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
					alertify.alert('Wolf Financiero Dice:',obj.mensaje, function () {
						tabla.row( parentTR ).remove().draw();
					});
					
				}else{
					alertify.alert('Wolf Financiero Dice:',obj.mensaje, function () {});
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



