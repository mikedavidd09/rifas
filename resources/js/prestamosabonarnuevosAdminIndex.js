$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#pagos',"index.php?controller=pagos&action=listado_primeracuota",0,[{
	 "targets": 1,
        "render": function ( data, type, row, meta ) {
			console.log(type);
            var itemID = row[1];
			var check ="";
			
			if(itemID == 1){
				check="checked";
			}
            return "<input type='checkbox' "+check+" disabled>";
        }
	},
	{
        "targets": 2,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
			var abonado = row[1];
           return '<a class="link" href="index.php?controller=pagos&nuevos=nuevos&action=ver_datos&obj=' + itemID +'&abonado='+abonado+'">' + data + '</a>';
        }
    }])

});