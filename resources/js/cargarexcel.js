jQuery(function($){
    $(".messages").hide();
    //queremos que esta variable sea global
    var fileExtension = "";
    //función que observa los cambios del campo file y obtiene información
    $("#archivo").change(function()
    {
        //obtenemos un array con los datos del archivo
        var file = $("#archivo")[0].files[0];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensión del archivo
        //fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        //mensaje con la información del archivo
		
        $("#alert").html("<div class='alert alert-info'>Archivo para subir: "+fileName+", peso total: "+fileSize+" bytes.</div>");
    });

    //al enviar el formulario
    $("#enviarexcel").click(function(){
		if($("#archivo").val() != ""){
        //información del formulario
        var formData = new FormData($(".formulario")[0]);
        var message = ""; 
        //hacemos la petición ajax  
        $.ajax({
            url: 'index.php?controller=Pagos&action=setAbonoByExcel',  
            type: 'POST',
            // Form data
            //datos del formulario
            data: formData,
            //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
            //mientras enviamos el archivo
            beforeSend: function(){
				$("#alert").html("<div class='progress progress-striped active'><div class='progress-bar' role='progressbar' aria-valuenow='75' aria-valuemin='0' aria-valuemax='100' style='width: 45%'> <span class='sr-only'>Realizando Transacciones de Cuotas, Espere Por Favor...</span></div> </div>");      
            },
            //una vez finalizado correctamente
            success: function(data){
				 resp=JSON.parse(data);
				 if(resp == "User_Incorrecto"){
					$("#alert").html("<div class='alert alert-warning'>Usuario Incorrecto,<b>Transaccion Cancelada.</b>.</div>");
				 }
				 else if(resp == "Prestamo_Incorrecto"){
					$("#alert").html("<div class='alert alert-warning'>Codigo de Prestamo Errado,<b>Transaccion Cancelada.</div>");
				 }
				 else if(resp == "Correcto"){
					$("#alert").html("<div class='alert alert-success'>Transaccion realizada con exito.</div>");
				 }
				 else if(resp == "Incorrecto"){
					$("#alert").html("<div class='alert alert-danger'>Ha ocurrido un error, intente mas tarde.</div>");
				 }
            },
            //si ha ocurrido un error
            error: function(){
				$("#alert").html("<div class='alert alert-danger'>Ha ocurrido un error, intente mas tarde.</div>");
            }
          });
		}
		else{
				$("#alert").html("<div class='alert alert-warning'>Debes Escoger Un Archvivo.</div>");
		}
		return false;
    });


//como la utilizamos demasiadas veces, creamos una función para 
//evitar repetición de código
function showMessage(message){
    $(".messages").html("").show();
    $(".messages").html(message);
}

//comprobamos si el archivo a subir es una imagen
//para visualizarla una vez haya subido
function isImage(extension)
{
    switch(extension.toLowerCase()) 
    {
        case 'jpg': case 'gif': case 'png': case 'jpeg':
            return true;
        break;
        default:
            return false;
        break;
    }
}
});

