
function  getSearcheCaseInsensitive(controller,action,value, text1, text2) {
    var consulta;
    //hacemos focus al campo de búsqueda
    $("#busqueda").focus();

    //comprobamos si se pulsa una tecla
    $("#busqueda").keyup(function(tecla){

        //obtenemos el texto introducido en el campo de búsqueda
        consulta = $("#busqueda").val();
        //hace la búsqueda
        if(consulta.length>2 && tecla.keyCode != 8){
               $.ajax({
                type: "GET",
                url: 'index.php?controller='+controller+'&action='+action,
                data: "b="+consulta,
                beforeSend: function(){
                    //imagen de carga
                    $("#msg_error").html("<p align='center'><img src='loader-min.gif' /></p>");
                },
                error: function(){
                    alert("error se perdio la conexion a internet Actualizar pagina");
                },
                success: function(data){
                    if (screen.width > 768){
                        var elemento = $("#resultado");
                        generateCombo(elemento, data, value, text1, text2,consulta);
                    } else {
                        var elemento = $("#id_cliente_mobile");
                        generateComboSelect(elemento, data, value, text1, text2,consulta);
                    }
                }
            });
        }

    });

}
