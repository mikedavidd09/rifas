$("#cedula_fiador").mask("999-999999-9999S");
ImagenPreview('imagen_cedula_frontal_fiador','imagencedfronfiador');
ImagenPreview('imagen_fiador_reversa','imagenreversacedf');
$("#cedula_fiador").mask("999-999999-9999S");
$('#busqueda').on('change', function () {
	$(".mensaje").html("");
     verificarClientePrestamo('prestamo','getPrestamoAsociar','prestamo_asociar','id_prestamo','codigo_prestamo','busqueda');
});
$('#id_cliente_mobile').on('change', function () {
    $(".mensaje").html("");
    verificarClientePrestamo('prestamo','getPrestamoAsociar','prestamo_asociar','id_prestamo','codigo_prestamo','id_cliente_mobile');
});
function verificarClientePrestamo(controller, action, id_select, value, text1, id_select_cliente){
		 var id_cliente = $('#'+id_select_cliente).val();
         var cadena = id_cliente.split('-');
         var id_cliente = cadena[0].replace(/[^0-9]/ig,"");


        var parametros = {"id_cliente": id_cliente};


        $.ajax({
            url: 'index.php?controller=' + controller + '&action=' + action + '',
            type: 'POST',
            data: parametros,
            success: function (result) {
                if(result!=false){
					var data = JSON.parse(result);
					console.log(data.length);
					$("#"+id_select).empty();
					$("#"+id_select).append("<option value ='0'>--Seleccione--</option>");
					for (var x = 0; x < data.length; x++) {
							$("#"+id_select).append("<option value ='"+data[x][value] +"'>"+ data[x][text1] +"</option>");
					}
				}

            }


        });
}
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
                },
                success: function (response) {
                    $('.pace').removeClass('pace-active');
                    $('.pace').addClass('pace-inactive');
                    var obj = JSON.parse(response);
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
						 $("#Associate").show();
						  $("#Update").show();

                    }else{
						$("#id_fiador_exist").val(0);
						 $("#Associate").hide();
						  $("#Update").hide();
                        /*alertify.alert('Wolf Financiero S.A. Dice:',"Fiador no existe en el sistema por favor rellene los campo del formulario", function () {});
                       // $("#cedulafiador").val("");
                       $("#id_fiador_exist").val(0);
                         $("#nombre_fiador").val("");
                         $("#direccion_fiador").val("");
                         $("#telefono_fiador").val("");
                         $("#imagen_fiador_frontal").val("");
                         $("#imagen_fiador_reversa_ced").val("");*/
                    }
                }
            });
        }else{
            alertify.alert('Wolf Financiero S.A. Dice:',"Debes introducir cedula correcta Para Verifica si ya existe el fiador en el sistema", function () {});
        }
    }
    return false;
});

function upload_image(id_form,controller,action,input_file_image,input_photoname,carpeta,divloading) {

console.log(id_form);
console.log("controlador= "+controller);
console.log("action= "+action);
console.log("nombre file= "+input_file_image);
console.log("nombre del input foto guardada= "+input_photoname);
console.log("nombre carpeta a guardar= "+carpeta);
console.log("nombre div de carga= "+divloading);

    var formData = new FormData($('#' + id_form)[0]);
    $.ajax({
        type: 'POST',
        url: 'index.php?controller='+controller+'&action='+action+'&input_file_image='+input_file_image+'&carpeta='+carpeta+'&input_photoname='+input_photoname+'&divloading='+divloading,
        data: formData,
        contentType : false,
        processData : false,
        cache : false,
        beforeSend: function () {
           /* $('.pace').removeClass('pace-inactive');
            $('.pace').addClass('pace-active');
            */
            
            $('#'+divloading).empty();
            $('#'+divloading).append("<div class='col text-success'><strong>Subiendo foto, por favor espere ...</strong></div> <div calss='col'><i class='fa fa-spinner fa-spin' style='font-size:24px;color:Green'></i></col>");

        },
        success: function (response) {
            var obj = JSON.parse(response);
            data = obj;
            /*
            $('.pace').removeClass('pace-active');
            $('.pace').addClass('pace-inactive');             
           */

            if(obj.respuesta == "true"){
                console.log("---------------------------")
                console.log("ajax response"+obj.photoname);
                console.log(obj.input_photoname);
                console.log(obj.divloading);
                console.log("input file a limpiar"+obj.input_file_image);
                console.log(obj.size);

                    var input = $('#' + obj.input_file_image);
                    var clon = input.clone();  // Creamos un clon del elemento original
                    input.replaceWith( input.val('').clone( true ) );; 

                $('#'+obj.input_photoname).val(obj.photoname);
                $('#'+obj.divloading).empty();
                $('#'+obj.divloading).append("<i class='fa fa-check-circle' style='font-size:20px ;color:Green'>Tamano Imagen:"+ obj.size +" </i>");

                showNotify('success' ,'Correcto' ,obj.mensaje)
            }else{
                showNotify('warning' ,'Error' ,'El registro no se puedo guardar ' + obj.mensaje)
            }
        }
    });
   if(action=='crear'){
        $('#' + id_form)[0].reset();
        limpiarFotosGarantia(controller);
    }
}