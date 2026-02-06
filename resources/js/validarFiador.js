ImagenPreview('imagen_cedula_frontal_fiador','imagencedfronfiador');
ImagenPreview('imagen_fiador_reversa','imagenreversacedf');
$("#cedula_fiador").mask("999-999999-9999S");
$("#cedulafiador").mask("999-999999-9999S");

$("#cedula_fiador").on("blur",function(){
    var celd = $(this).val();
    if($(this).val() != ""){
        if(parseInt(celd.length) == 16){
            $.ajax({
                type: 'GET',
                url: 'index.php?controller=Cliente&action=ValidarCedulaFiador&cedula='+$(this).val(),
                beforeSend: function () {
                    $('.pace').removeClass('pace-inactive');
                    $('.pace').addClass('pace-active');
                    $('#cover-spin').show();

                },
                success: function (response) {
                    $('.pace').removeClass('pace-active');
                    $('.pace').addClass('pace-inactive');
                    var obj = JSON.parse(response);
                    $('#cover-spin').hide();
                    console.log(response);
                    if(obj.respuesta == "true"){

                        //alertify.alert('Wolf Financiero S.A. Dice:',"Fiador existe en el sistema, deje el numero de cedula en la casilla para poder enlazarlo, No es necesario llenar el Formulario fiador ", function () {});
                        //console.log(obj.data);
                        //return false;
                        var data = JSON.parse(obj.data);
                        console.log(data);
                        $("#id_fiador_exist").val(data[0].id_fiador);
                         $("#nombre_fiador").val(data[0].nombre);
                         $("#direccion_fiador").val(data[0].direccion);
                         $("#telefono_fiador").val(data[0].telefono);
                         $("#imagen_fiador_frontal").val(data[0].cedula_img_frontal);
                         $("#imagen_fiador_reversa_ced").val(data[0].cedula_img_reversa);
                        $("#imagencedfronfiador").attr("src","assets/img/fotos_clientes/"+data[0].cedula_img_frontal);
                         $("#imagenreversacedf").attr("src","assets/img/fotos_clientes/"+data[0].cedula_img_reversa);

                    }else{
                        alertify.alert('Wolf Financiero S.A. Dice:',"Fiador no existe en el sistema por favor rellene los campo del formulario", function () {});
                       // $("#cedulafiador").val("");
                       $("#id_fiador_exist").val(0);
                         $("#nombre_fiador").val("");
                         $("#direccion_fiador").val("");
                         $("#telefono_fiador").val("");
                         $("#imagen_fiador_frontal").val("");
                         $("#imagen_fiador_reversa_ced").val("");
                    }
                }
            });
        }else{
            alertify.alert('Wolf Financiero S.A. Dice:',"Debes introducir cedula correcta Para Verifica si ya existe el fiador en el sistema", function () {});
        }
    }
    return false;
});
//
// $("#guardarCliente").on("click",function() {
//     if($("#cedula_fiador").val().length <= 0){
//         alert("No se asocio fiador al prestamo");
//     }
// });