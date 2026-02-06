$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#clientes',"index.php?controller=Cliente&action=listado",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return '<a class="link" href="index.php?controller=Cliente&action=ver_datos&obj=' + itemID + '">' + data + '</a>';
        }
    }])
});

