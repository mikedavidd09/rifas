function getPagosHistorial(id_prestamo){
$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#detalles_Abonos',"index.php?controller=Pagos&action=listadoPagoHistorial&id_prestamo="+id_prestamo,0,[{}]);
});



}