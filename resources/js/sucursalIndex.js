

function getDataSucursal(id_sucursal,controller){
	$.getScript("resources/js/jsDatatable.js", function(){
		DatatableJs('#sucursales',"index.php?controller=sucursal&action=listado&id_sucursal="+id_sucursal+"&consulta="+controller,0,[{
			"targets": 1,
			"render": function ( data, type, row, meta ) {
				var itemID = row[0];
				return '<a class="link" href="index.php?controller='+controller+'&action=ver_datos&obj=' + itemID + '&origen=sucursal&sucursal='+id_sucursal+'">' + data + '</a>';
			}
		}])
	});
}