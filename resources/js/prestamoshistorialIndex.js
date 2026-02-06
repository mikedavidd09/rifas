$.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#prestamoHist',"index.php?controller=Pagos&action=listadoPrestamoHistorial",0,[{

        "targets": 1,

        "render": function ( data, type, row, meta ) {

            var itemID = row[0];

            return '<a class="link" href="index.php?controller=Pagos&action=getPagosHistorial&obj=' + itemID + '">' + data + '</a>';

        }

    }])

});/**
,
    {

        "targets": 2,

        "render": function ( data, type, row, meta ) {

            var saldo = row[2];
            if(saldo == 0){
            	return "Si";
            }else{
            	return "No";
            }

        }

    }
**/