
function CallAjax(){
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Pagos&action=fast_abono',
        data: $("#abonar_form").serialize(),
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(obj.mensaje);

            if(obj.respuesta == "true"){
                showNotify('success' ,'Correcto' ,obj.mensaje);
                $('#abonar_form')[0].reset();
            }else{
                showNotify('warning' ,'Error' ,obj.mensaje);
                $('#abonar_form')[0].reset();
            }
        }
    });
}

$("input[name='codigo_prestamo']").mask("P_9999999999999");

$("#pago_rapido").on("click",function(){
    var validacion = true;
    var codigo_prestamo = $("input[name='codigo_prestamo']");
    var monto           = $("input[name='monto']");
    var regex = /^\d+(\.\d{0,4})?$/g;
    var monto_valido = (!(regex.test(monto.val())) || monto.val() == "" ) ? false : true;
    var codigo_prestamo_valido = codigo_prestamo.val() == "" ? false : true ;

    if(!codigo_prestamo_valido || !monto_valido){
        validacion = false;
    }
    console.log(validacion);
    if(validacion){
        monto.parent('div').removeClass('has-error');
        codigo_prestamo.parent('div').removeClass('has-error');
        confirmarAbono(codigo_prestamo.val());
        return false;
    } else {
        console.log("deded");
        if(!monto_valido){
            monto.parent('div').addClass('has-error');
        }
        if(!codigo_prestamo_valido){
            codigo_prestamo.parent('div').addClass('has-error');
        }
    }
});

function confirmarAbono(codigo_prestamo) {
    //todo obtener los datos del liente
    var data;
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Prestamo&action=GetDatosByIdCodigoPrestamo&codigo_prestamo=' + codigo_prestamo,
        data: $("#abonar_form").serialize(),
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(obj);
            data = obj;
            if(data.nombre != undefined){
                var confirm = alertify.confirm('Seguro de realizar el pago:','Nombre :'+data.nombre+' '+data.apellido+'<br>'+'Cedula :'+data.cedula+'<br>'+'Saldo :'+data.saldo_pendiente, null, null).set('labels', {
                    ok: 'Si',
                    cancel: 'No'
                });
                confirm.set({transition: 'slide'});
                confirm.set('onok', function () { //callbak al pulsar botón positivo
                    CallAjax();
                });
                confirm.set('oncancel', function () { //callbak al pulsar botón negativo
                    alertify.error('Se cancelo la transaccion del abono');
                });
                confirm.set('onnegativo', function () { //callbak al pulsar botón negativo
                    alertify.error('Se cancela la transaccion');
                });
            } else {
                showNotify('warning' ,'Error.!' ,'El codigo que ingreso no existe');
            }
        }
    });


}
