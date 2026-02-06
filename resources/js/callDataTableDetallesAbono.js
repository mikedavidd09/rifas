/**
 * Created by ruiz on 08-24-17.
 */

$(".link").click(function () {
    var link = $(this).attr("href");
    $.ajax({
        url: link
    }).done(function (html) {
        $("#page").html(html);
    });
    return false;
});
 
if ( $.fn.DataTable.isDataTable('#detalles_Abonos') ) {
    $('#detalles_Abonos').DataTable().destroy();
}
 var table = $('#detalles_Abonos')
    .addClass('responsive nowrap')/*realizar javascrip  q cuando sea pantalla moviles  se agrege la clase  table.addClass('responsive nowrap');*/
    .dataTable( {
        responsive: true,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por paginas",
            "zeroRecords": "No hay registro que mostrar",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(Filtrado de  _MAX_ total de registros)",
            "search": "Buscar en detalles abono:"
        },
		"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var montAbonado = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
	    var saldExcedente = api
                .column( 3)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
            var montAtrasado = api
                .column( 4)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
	     var pagoAtraso = api
                .column( 5)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
            // Update footer by showing the total with the reference of the column index 
	    //$( api.column( 0 ).footer() ).html('Total');
            $( api.column( 2 ).footer() ).html(montAbonado);
            $( api.column( 3 ).footer() ).html(Math.round(parseFloat(saldExcedente),-2));
            $( api.column( 4 ).footer() ).html(Math.round(parseFloat(montAtrasado),-2));
            $( api.column( 5).footer() ).html(pagoAtraso);
        }
    } );

    /*$('#detalles_Abonos').each( function () {
        var title = $(this).text();
        //$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );*/

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