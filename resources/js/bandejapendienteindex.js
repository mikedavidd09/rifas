$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#bandejaPendiente',"index.php?controller=Prestamo&action=listadoBandeja&estado=pendiente","0,1",[{
        "targets": 2,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            var itemCli = row[1];
            return '<a class="link" href="index.php?controller=Prestamo&action=getDetallesSolicitudPrestamo&obj=' + itemID + '&bandeja=Pendiente&id_cliente='+itemCli+'">' + data + '</a>';
        }
    }])
});