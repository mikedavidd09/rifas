$.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#arqueo',"index.php?controller=moneda&action=listado","0,6,7",[
    {

        "targets": 6,

        "render": function ( data, type, row, meta ) {

            var itemID = row[6];

            return '<a class="link" href="index.php?controller=moneda&action=ver_datos&obj=' + itemID + '">' + data + '</a>';

        }


    },
    {
       "targets": 7,

        "render": function ( data, type, row, meta ) {

            var itemID = row[7];

            return "<a class='link' href='index.php?controller=moneda&action=update&obj=" + itemID + "' data-toggle='modal' data-target='#myModal'><i class='fa fa-refresh' aria-hidden='true'></i></a>";

        } 
    }
    ])

});