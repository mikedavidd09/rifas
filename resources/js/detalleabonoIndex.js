function getDataHistPagos(id_prestamo){

$.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#delete',"index.php?controller=Pagos&action=listadoBorrar&id_prestamo="+id_prestamo,0,[{

        "targets": 4,

        "render": function ( data, type, row, meta ) {

            var itemID = row[4];
            var dias = row[0];

            if(dias <= 2){
            	return '<a class="link" href="index.php?controller=Pagos&action=setBorrarPago&obj=' + itemID + '"><span class="btn btn-warning" data-title="Delete"> <em class="fa fa-trash"></em> </span></a>';
            }else{
            	return "<span class='btn btn-warning' data-toggle = 'tooltip' title='Esta opcion se encuentra Bloqueada  por que  ya paso los 2 dias de vigencia para realizar el borrado de esta cuota.' ><em class='fa fa-lock'></em></span>";
            }

        }
    }])
    setTimeout(datatoggle, 1000)
    function datatoggle(){$("span[data-toggle='tooltip']").tooltip();}
});

}
