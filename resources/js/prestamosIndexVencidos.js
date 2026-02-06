$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#prestamo',"index.php?controller=Prestamo&action=listadoVencidos",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return '<a class="link" href="index.php?controller=Prestamo&origen=vencidos&action=verDatosPrestamoVencidos&id_prestamo=' + itemID + '">' + data + '</a>';
        }
    }])
});
