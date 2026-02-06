$.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#prestamosaneado',"index.php?controller=Prestamo&action=listadoPrestamoSaneado","0,1 ",[{

        "targets": 2,

        "render": function ( data, type, row, meta ) {

			var itemIDcliente = row[0];
            var itemID = row[1];
			 var itemAsigandoOrigen = row[2];
			 
			var itemIDSa = row[2];
			var itemAutorizado= row[3];
			
			var check ="";
			
			if(itemIDSa > 0 && itemIDSa != null){
				check="checked";
			}
            return "<a class='linksaneado' href='index.php?controller=Prestamo&action=ReactivarPrestamo&cargar=dataTable&obj="+itemID+"&asignadoOrigen="+itemAsigandoOrigen+"&id_cliente="+itemIDcliente+"&method=activar'><input type='checkbox' "+check+" id='clscheckbox'></a>";
           // return '<a class='link' href='index.php?controller=Pagos&action=getPagosHistorial&obj=' + itemID + >' + data + '</a>';

        }

    },
	{

        "targets": 3,

        "render": function ( data, type, row, meta ) {

            var itemID = row[1];
			 var itemAsigandoOrigen = row[1];
			 
			var itemIDSa = row[2];
			var itemAutorizado= row[3];
			
			var check ="";
			
			if(itemAutorizado > 0 && itemIDSa != null){
				check="checked";
			}
            return "<a class='linksaneado' href='index.php?controller=Prestamo&action=ReactivarPrestamo&cargar=dataTable&obj="+itemID+"&autorizado="+itemAutorizado+"&method=autorizar'><input type='checkbox' "+check+" id='clscheckboxAuotizado'></a>";
           // return '<a class='link' href='index.php?controller=Pagos&action=getPagosHistorial&obj=' + itemID + >' + data + '</a>';

        }

    }
	
	
	])
});
