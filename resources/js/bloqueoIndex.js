$.getScript("resources/js/jsDatatable.js", function(){
    DatatableJs('#permisos',"index.php?controller=Permisos&action=listadoBloqueo",-2,[{
        "targets": 4,
        "render": function ( data, type, row, meta ) {
            var bloqueado = row[4];
            return "<div class='toggle'><label><input type=\"checkbox\" name =\"c_$x\" "+bloqueado+" ><span class='button-indecator'></span></label></div>";
        }
    }])
});

$(document).on('change','input[type="checkbox"]' ,function(e) {
    var id = $(this).parents('tr').find('td:eq(0)').text();
    console.log(id);
    if($(this).is(':checked') ) {
        cambiarEstado('registrarRegistroSession_',id);
    } else {
        cambiarEstado('borrarRegistroSession_',id);
    }

});

function cambiarEstado(estado,id){
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Usuarios&action='+estado,
        data: {
            'id_usuario':id
        },
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            console.log(obj.mensaje);
            if(obj.respuesta == "true"){
                showNotify('success' ,'Correcto' ,obj.mensaje);
            }else{
                showNotify('warning' ,'Error' ,obj.mensaje);
            }
        }
    });
}

