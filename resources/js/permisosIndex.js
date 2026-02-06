$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#modulotb',"index.php?controller=Permisos&action=listado",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return '<a class="link" href="index.php?controller=Permisos&action=viewEdit&id_colaborador=' + itemID + '">' + data + '</a>';
        }
    }])
});
