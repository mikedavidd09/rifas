function getPrestamo(id_cliente,search){

$.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#prestamopagos',"index.php?controller=Pagos&action=listadoPrestamos&id_cliente="+id_cliente,0,[{

        "targets": 1,

        "render": function ( data, type, row, meta ) {

            var itemID = row[5];
			if(itemID == 0){
				return 'SI';
			}else{
				return 'NO';
			}
        }

    },
	{

        "targets": 2,

        "render": function ( data, type, row, meta ) {

            var itemID = row[0];

            return '<a class="link" href="index.php?controller=Pagos&action=getPagosBorrar&id_prestamo=' + itemID +search +'">' + data + '</a>';

        }

    }])

});

}