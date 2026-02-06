$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#bandejaDevuelto',"index.php?controller=Prestamo&action=listadoBandeja&estado=Retornado",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return '<a class="link" href="index.php?controller=Prestamo&action=getDetallesSolicitudPrestamo&obj=' + itemID + '&bandeja=Retornado">' + data + '</a>';
        }
    }])
});