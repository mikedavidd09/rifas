$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#prestamo',"index.php?controller=Prestamo&action=listado",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
           var permisos =$("#permisos").val();
            return '<a class="link" href="index.php?controller=Prestamo&action=ver_datos&permisos='+permisos+'&id_prestamo=' + itemID + '">' + data + '</a>';
        }
    }])
});