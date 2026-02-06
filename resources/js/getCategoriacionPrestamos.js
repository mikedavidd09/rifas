$.getScript("resources/js/jsDatatable.js", function(){
	var id_cliente= $("#id_cliente").val();
    DatatableJs('#categorizacionPrestamo',"index.php?controller=Cliente&action=listadoCategorizacionCliente&id_cliente="+id_cliente,0,[{}])
});