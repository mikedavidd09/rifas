$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#prestamo',"index.php?controller=Prestamo&action=listadoRechazados",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return '<a class="link" href="index.php?controller=Prestamo&origen=rechazados&action=ver_datosRechazados&id_prestamo=' + itemID + '">' + data + '</a>';
        }
    }])
});
