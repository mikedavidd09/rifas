
$(document).ready(function() {
    console.log('entra al js ');
    var table_r = $('#reportes').DataTable( {
        responsive:true,
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
        }
    } );
} );
$("#reportes").addClass( 'compact responsive').css( 'font-size', '9pt');
if ( $.fn.DataTable.isDataTable('#reportes') ) {
    $('#reportes').DataTable().cleanData();
}
