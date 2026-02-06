var  table = $('#example')
    .addClass('responsive nowrap')/*realizar javascrip  q cuando sea pantalla moviles  se agrege la clase  table.addClass('responsive nowrap');*/
    .dataTable( {
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
$('#example tfoot th').each( function () {
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
} );/**

 * Created by ruiz on 09-22-17.
 */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        // Get date range
        dateMin = $("#fromSelector").attr("value");
        dateMax = $("#toSelector").attr("value");

        if (dateMin == "") {
            dateMin = "01-01-1900";
        }

        if (dateMax == "") {
            dateMax = "'.date('m-d-Y').'";
        }

        dateMin = new Date(dateMin);
        dateMax = new Date(dateMax);

        // 0 here is the column where my dates are.
        var date = data[0];
        date = new Date(date);

        // run through cases
        if ( dateMin == "" && date <= dateMax){
            return true;
        }
        else if ( dateMin =="" && date <= dateMax ){
            return true;
        }
        else if ( dateMin <= date && "" == dateMax ){
            return true;
        }
        else if ( dateMin <= date && date <= dateMax ){
            return true;
        }

        // all failed
        return false;
    }
);
