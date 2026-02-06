$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#clientesInactivos',"index.php?controller=Cliente&action=listadoClienteInactivo",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return '<a class="link" href="index.php?controller=Pagos&action=getPrestamos&obj=' + itemID + '">' + data + '</a>';
        }
    }])
});

