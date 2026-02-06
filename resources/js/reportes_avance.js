var table_r = "";
$(document).ready(function() {
    var loader = $('#cover-spin');

    console.log('Entra Al datatable');
    if ($.fn.dataTable.isDataTable('#reportes')) {
        table.destroy();
    }
   // $('#cover-spin').show();

    table_r = $('#reportes').DataTable( {
        responsive:false,
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns: true,
        "ordering": false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                text:      '<i class="fa fa-print"></i>',

            },
            {
                extend:    'copyHtml5',
                text:      '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel'
            },
            {
                extend:    'csvHtml5',
                text:      '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV'
            }
        ],"language": {
            "lengthMenu": "Mostrar _MENU_ registros por paginas",
            "zeroRecords": "No se encontró nada, sin registros",
            "info": "Mostrando la página _PAGE_ de _PAGES_ (Total:_TOTAL_)",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(Filtrado de  _MAX_ total de registros)",
            "search": "Buscar en la Tabla"
        },
        initComplete: function(settings, json) {
            // Ocultar el loader una vez que el DataTable haya terminado de renderizarse
            $('#cover-spin').hide();
            console.log("Termino");
        }
    } );


} );
$("#reportes").addClass( 'compact responsive').css( 'font-size', '9pt');
if ( $.fn.DataTable.isDataTable('#reportes') ) {
    $('#reportes').DataTable().cleanData();
}

