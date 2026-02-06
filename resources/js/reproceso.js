/**
 * Created by fleming on 1/26/19.
 */
//$("input[name='codigo_prestamo']").mask("P_9999999999999");
$("#reproceso").on("click",function(){
    console.log(1);
    var codigo_prestamo = $("input[name='codigo_prestamo']");
    var codigo_prestamo_valido = (codigo_prestamo.val() == "")? false : true ;
    if(!codigo_prestamo_valido){
        console.log(1);
        codigo_prestamo.addClass('is-invalid');
    }
    reprocesar(codigo_prestamo.val());

});
function reprocesar(codigo_prestamo) {
    $.ajax({
        url: 'index.php?controller=Pagos&action=insercionManual&id_prestamo='+codigo_prestamo,
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
$("#reproceso_multiple").on("click",function(){
    console.log(1);
    var codigo_prestamo = $("input[name='codigo_prestamo']");
    var codigo_prestamo_valido = (codigo_prestamo.val() == "")? false : true ;
    if(!codigo_prestamo_valido){
        console.log(1);
        codigo_prestamo.addClass('is-invalid');
    }
    reprocesar_multiple(codigo_prestamo.val());

});
function reprocesar_multiple(codigo_prestamo) {
    $.ajax({
        url: 'index.php?controller=Pagos&action=insercionManualMultiple&id_prestamo='+codigo_prestamo,
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
