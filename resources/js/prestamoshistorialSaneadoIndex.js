$.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#prestamoHistsaneado',"index.php?controller=Prestamo&action=listadoPrestamoHistorialaSanear","0,1,12,13,14",[{

        "targets": 2,

        "render": function ( data, type, row, meta ) {

            var itemID = row[0];
			 var itemIDCol = row[1];
			 var itemIDSur = row[12];
			 var itemSucur = row[13];
			  var itemIDcliente = row[14];
			var itemIDSa = row[2];
			var saldo = row[8];
			var totalabonad = row[9];
			var check ="";
			
			if(itemIDSa > 0 && itemIDSa != null){
				check="checked";
			}
            return "<a class='linksaneado' href='index.php?controller=Prestamo&action=SanearPrestamo&cargar=dataTable&obj="+itemID+"&saldo="+saldo+"&abonado="+totalabonad+"&id_colaborador="+itemIDCol+"&id_sucursal="+itemIDSur+"&sucursal="+itemSucur+"&id_cliente="+itemIDcliente+"&method=sanear'><input type='checkbox' "+check+" id='clscheckbox'></a>";
           // return '<a class='link' href='index.php?controller=Pagos&action=getPagosHistorial&obj=' + itemID + >' + data + '</a>';

        }

    }])
});
