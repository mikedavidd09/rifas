/**
 * Created by fleming on 11/8/18.
 */
$('.card').on('click','#generar_token',function () {
    var user = $('#user').val();
    var pass = $('#pass').val();
    var params = {
        user            : user,
        pass            : pass,
        p               : 'login'
    };
    $.ajax({
        type: 'GET',
        url: 'webservice.php',
        data: params,
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            console.log(obj);
            $('#token').val(obj.token);
            if(obj.respuesta == "true"){
                showNotify('success' ,'Correcto' ,'Token generado de forma correcta');
            }else{
                showNotify('warning' ,'Error' ,"Usuario o Password invalido");
            }
        }
    });
});
$('.card').on('click','#copy',function () {
    var elemento = $('#token');
    copyToClipboard(elemento);
});

function copyToClipboard(elemento) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(elemento).val()).select();
    document.execCommand("copy");
    $temp.remove();
    showNotify('info' ,'Correcto' ,'Se copio el token correctamente en el portapapeles');
}
