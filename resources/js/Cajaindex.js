$.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#gastos',"index.php?controller=Caja&action=listado",0,[{

        "targets": 1,

        "render": function ( data, type, row, meta ) {

            var itemID = row[0];

            return '<a class="link" href="index.php?controller=Caja&action=ver_datos&obj=' + itemID + '">' + data + '</a>';

        }

    }])

});

