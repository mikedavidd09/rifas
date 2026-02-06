$("#viewSucursal").on("change",function(){
	var estado = $("#estado").val();
	var id_sucursal = $(this).val();
	getPrestamoSucursal(estado,id_sucursal);
});

function getPrestamoSucursal(estado,id_sucursal){
    if ( $.fn.DataTable.isDataTable("#bandeja"+estado) ) {
        $("#bandeja"+estado).DataTable().destroy();
    }
$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#bandeja'+estado,"index.php?controller=Prestamo&action=listadoBandeja&estado="+estado+"&id_sucursal="+id_sucursal,0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return '<a class="link" href="index.php?controller=Prestamo&action=getDetallesSolicitudPrestamo&obj=' + itemID + '&bandeja='+estado+'">' + data + '</a>';
        }
    }])
});

}