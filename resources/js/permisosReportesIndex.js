$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#permisoreporte',"index.php?controller=Permisos&action=listado",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return '<a class="link" href="index.php?controller=Permisos&action=viewEditReporte&id_colaborador=' + itemID + '">' + data + '</a>';
        }
    }])
});
