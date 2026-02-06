$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#tablefiador',"index.php?controller=Fiador&action=listado",0,[{
        "targets": 1,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            return "<div class='toggle'><label><input type=\"checkbox\" name =\"c_$x\" value="+itemID+" checked><span class='button-indecator'></span></label></div>";
        }
    }])
});

$(document).on('change','input[type="checkbox"]' ,function(e) {
    var id = $(this).val();
    console.log(id);
    if($(this).is(':checked') ) {
        alert("Fiador ya fue desasociado del prestamo");
    }else{
		cambiarEstado(id);
	}

});
function cambiarEstado(id){
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Fiador&action=DeAssociate',
        data: {
            'id_fiadorRelPrestamo':id
        },
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            console.log(obj.mensaje);
            if(obj.respuesta == "true"){
                showNotify('success' ,'Desvinculado correctamente' ,obj.mensaje);
            }else{
                showNotify('warning' ,'Error' ,obj.mensaje);
            }
        }
    });
}