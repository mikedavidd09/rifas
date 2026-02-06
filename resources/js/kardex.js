/**
 * Created by Eddy Ruiz on 10/28/18.
 */
$.getScript("assets/js/searche-case-insensitive.js", function(){
    getSearcheCaseInsensitive('Cliente','searcheCaseInsensitevi','nombre','codigo_cliente','id_cliente');
});
function CallAjax(){
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Pagos&action=new_fast_abono_',
        data: $("#abonar_form").serialize(),
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

    $("input[name='codigo_prestamo_']").mask("P_9999999999999");

$("#ver").on("click",function(){
    var codigo_prestamo = $("input[name='codigo_prestamo']");
    var codigo_prestamo_ = $("input[name='codigo_prestamo_']");
    var codigo_prestamo_valido = (codigo_prestamo.val() == "" || codigo_prestamo_.val() == "" )? true : false ;
    if(!codigo_prestamo_valido){
        codigo_prestamo.addClass('is-invalid');
    }
    $('tbody').empty();
    var $this = $(this);
    getDatos($this);
    getDatosClientes($this);
    $('#nombre-cliente').text($('#busqueda').val());
    $('#codigo-prestamo').text($('#codigo_prestamo').val());
});

$("#actualizar").on("click",function(){
    $('tbody').empty();
    var $this = $(this);
    getDatos($this);
});
$("tbody").on("click",'.eliminar',function(){
    var id_fila = $(this).parents('tr').find('td:nth-child(1)').text();
    console.log(id_fila);
    borrar_registro(id_fila);
    $(this).parents('tr').hide();
});

function borrar_registro(id_pago) {

    var confirm = alertify.confirm('Esta Seguro de Borrar este pago!..','borraras el pago Nro:'+id_pago, null, null).set('labels', {
        ok: 'Si',
        cancel: 'No'
    });
    confirm.set({transition: 'slide'});
    confirm.set('onok', function () { //callbak al pulsar botón positivo
        var params = {
            id_pago            : id_pago
        };
        $.ajax({
            type: 'GET',
            url: 'index.php?controller=Pagos&action=borrado_rapido',
            data: params,
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
    });
    confirm.set('oncancel', function () { //callbak al pulsar botón negativo
        alertify.error('Se cancelo la transaccions');
    });
    confirm.set('onnegativo', function () { //callbak al pulsar botón negativo
        alertify.error('Se cancela la transaccion');
    });

}
$("#busqueda").on("change",function(){
    var codigo_cliente = $('body').find("input[name='codigo_cliente']").val();
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Prestamo&action=getPrestamosByCodigoCliente&codigo_cliente=' + codigo_cliente,
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(Array.isArray(obj));
           $data = "";
            if(Array.isArray(obj)){
                $.each(obj, function(i, item) {
                    $data += "<option>";
                    $data +="Codigo:"+item.codigo_prestamo+"-Capital:"+item.capital+"-Estado:"+item.estado;
                    $data += "</option>"
                });
            } else {
                $data += "<option>";
                $data +="Codigo:"+obj.codigo_prestamo+"-Capital:"+obj.capital+"-Estado:"+obj.estado;
                $data += "</option>"            }
            console.log($data);
            $('#codigo_prestamo').html($data);
        }
});
});

$("#id_cliente_mobile").on("change",function(){
    var cliente = $("#id_cliente_mobile").val();
    $("#busqueda").val(cliente);
    var codigo_cliente = $('body').find("input[name='codigo_cliente']").val();
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Prestamo&action=getPrestamosByCodigoCliente&codigo_cliente=' + codigo_cliente,
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(Array.isArray(obj));
            $data = "";
            if(Array.isArray(obj)){
                $.each(obj, function(i, item) {
                    $data += "<option>";
                    $data +="Codigo:"+item.codigo_prestamo+"-Capital:"+item.capital;
                    $data += "</option>"
                });
            } else {
                $data += "<option>";
                $data +="Codigo:"+obj.codigo_prestamo+"-Capital:"+obj.capital;
                $data += "</option>"            }
            console.log($data);
            $('#codigo_prestamo').html($data);
        }
    });
});

function getDatos($this) {
    var codigo_prestamo = $("input[name='codigo_prestamo']");
    var codigo_prestamo_ = $("input[name='codigo_prestamo_']");
    var codigo_prestamo_valido = (codigo_prestamo.val() == "" || codigo_prestamo_.val() == "" )? true : false ;
    if(codigo_prestamo_valido){
        codigo_prestamo = codigo_prestamo.val() == ""?"none:"+codigo_prestamo_+"-none":codigo_prestamo;
    }
    $this.button('loading');
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Pagos&action=getAllByCodigoPrestamo&codigo_prestamo=' + codigo_prestamo,
        data: $("#abonar_form").serialize(),
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(Array.isArray(obj));
            $data = "";
            if(Array.isArray(obj)){
                $.each(obj, function(i, item) {
                    $data += "<tr>";
                    $.each(item, function (key, dato) { // Cambiado 'i' por 'key' para acceder a la clave
                        let tdClass = "";
                        if (key === "ROUND(pg.por_interes,2)" || key === "ROUND(pg.por_capital,2)") {
                            tdClass = " class='no_imprimir'";
                        }
                        $data += "<td" + tdClass + ">" + dato + "</td>";
                    });
                    $data +="<td class='no_imprimir'><button class='btn-danger eliminar' ><i class='fa fa-trash'></i></button></td>";
                    $data += "</tr>";
                });
            }else {

                console.log(obj);
                if(obj){
                    $data += "<tr>";
                    $.each(obj, function(i, item) {
                        $data += "<td>"+item+"</td>";
                    });
                    $data +="<td class='no_imprimir'><button class='btn-danger eliminar'><i class='fa fa-trash'></i></button></td>";
                    $data += "</tr>"
                } else {
                    $data += "<tr>";
                    $data += "<td colspan='12' align='center' class='text-info'>No hay registros de pagos en este prestamo</td>";
                    $data += "<tr>";

                }

            }

            $('tbody').html($data);
            $this.button('reset');
        }
    });
}

function getDatosClientes($this) {
    var codigo_prestamo = $("input[name='codigo_prestamo']");
    var codigo_prestamo_ = $("input[name='codigo_prestamo_']");
    var codigo_prestamo_valido = (codigo_prestamo.val() == "" || codigo_prestamo_.val() == "" )? true : false ;
    if(codigo_prestamo_valido){
        codigo_prestamo = codigo_prestamo.val() == ""?"none:"+codigo_prestamo_+"-none":codigo_prestamo;
    }
    $this.button('loading');
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Pagos&action=getAllByCodigoCodigoPrestamo&codigo_prestamo=' + codigo_prestamo,
        data: $("#abonar_form").serialize(),
        success: function (response) {
            var obj = JSON.parse(response);
			console.log(obj);
			
            var fecha_d = obj.fecha_desembolso.split('-');
            var fecha_v = obj.fecha_vencimiento.split('-');
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var fecha_desembolso  = new Date(fecha_d[0],(fecha_d[1]-1),fecha_d[2]);
            var fecha_vencimiento = new Date(fecha_v[0],(fecha_v[1]-1),fecha_v[2]);
            var tipo = (obj.npnew == null) ? obj.tiponota:obj.npnew;
			tipo = (tipo != null) ? tipo:obj.tiponota1;
			
            $("#nombre").replaceWith("<input type='text'  id='nombre' class='form-control' value='"+obj.cliente+"' readOnly>");
            $("#codigo").replaceWith("<input type='text'  id='codigo' class='form-control' value="+obj.codigo_prestamo+" readOnly>");
            $("#cuota").replaceWith("<input type='text'  id='cuota' class='form-control' value="+parseFloat(obj.cuota).toFixed(2)+" readOnly>");
            $("#notaCredito").replaceWith("<input type='text'  id='notaCredito' class='form-control' value='"+tipo+"' readOnly>");           
            $("#numero_cuota").replaceWith("<input type='text'  id='numero_cuota' class='form-control' value='"+parseFloat(obj.numero_cuotas).toFixed(2)+" ("+obj.equivalen_mes+")' readOnly>");
            $("#modalidad").replaceWith("<input type='text'  id='modalidad' class='form-control' value="+obj.modalidad+" readOnly>");
            $("#capital").replaceWith("<input type='text'  id='capital' class='form-control' value="+obj.capital+" readOnly>");
		   $("#deuda_total").replaceWith("<input type='text'  id='deuda_total' class='form-control' value="+parseFloat(obj.deuda_total).toFixed(2)+" readOnly>");
            $("#porcentaje").replaceWith("<input type='text'  id='porcentaje' class='form-control' value="+obj.porcentaje+" readOnly>");
            $("#mora").replaceWith("<input type='text'  id='mora' class='form-control' value="+obj.mora+" readOnly>");
            $("#monto_favor").replaceWith("<input type='text'  id='monto_favor' class='form-control' value="+parseFloat(obj.monto_favor).toFixed(2)+" readOnly>");
            $("#saldo_pendiente").replaceWith("<input type='text'  id='saldo_pendiente' class='form-control' value="+parseFloat(obj.saldo_pendiente).toFixed(2)+" readOnly>");
            $("#cuotas_pendientes").replaceWith("<input type='text'  id='cuotas_pendientes' class='form-control' value="+(parseFloat(obj.saldo_pendiente )/ parseFloat(obj.cuota)).toFixed(2)+" readOnly>");
            $("#catrasadas").replaceWith("<input type='text'  id='catrasadas' class='form-control' value="+(parseFloat(obj.mora)/parseFloat(obj.cuota)).toFixed(2)+" readOnly>");
            $("#fecha_desembolso").replaceWith("<input type='text'  id='fecha_desembolso' class='form-control' value='"+fecha_desembolso.toLocaleDateString("es-ES", options)+"' readOnly>");
            $("#fecha_vencimiento").replaceWith("<input type='text'  id='fecha_vencimiento' class='form-control' value='"+fecha_vencimiento.toLocaleDateString("es-ES", options)+"' readOnly>");
            $("#diadesembolso").replaceWith("<input type='text'  id='diadesembolso' class='form-control' value="+obj.dia_desembolso+" readOnly>");
            //$("#direccionNegocio").replaceWith( "<textarea class='form-control rounded-0 noValidated' name='direccionNegocio' id='direccionNegocio' rows='3' readonly='readonly'>"+obj.direccion_negocio+"</textarea>");
            //$("#direccion").replaceWith("<textarea class='form-control rounded-0' name='direccion' id='direccion' rows='3' readonly=readonly>"+obj.direccion+"</textarea>");


// Reemplazar el textarea de dirección del negocio por un párrafo
            $("#direccionNegocio").replaceWith(
                "<p class='form-control-static' id='direccionNegocio'>" + (obj.direccion_negocio || "No registrada") + "</p>"
            );

// Reemplazar el textarea de dirección personal por un párrafo
            $("#direccion").replaceWith(
                "<p class='form-control-static' id=?'direccion'>" + (obj.direccion || "No registrada") + "</p>"
            );
// Seleccionamos el h3 por su ID y le asignamos un nuevo texto.
            $('#estado_prestamo').replaceWith("<h3 id='estado_prestamo'>"+obj.estado+"</h3> ");

            actualizarColorEstado();
        }
    });
}

/*function confirmarAbono(codigo_prestamo,$this) {
    //todo obtener los datos del liente
    var data;

    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Prestamo&action=GetDatosByIdCodigoPrestamo&codigo_prestamo=' +  _prestamo,
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
                    getDatos($this);
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


}*/

// Y en alguna parte de ese mismo archivo, defines la función
function actualizarColorEstado() {
    const $estadoH3 = $('#estado_prestamo');
    const estadoActual = $estadoH3.text().trim().toLowerCase();

    $estadoH3.removeClass('estado-alerta estado-info');

    if (estadoActual === 'vencido' || estadoActual === 'saneado') {
        $estadoH3.addClass('estado-alerta');
    } else if (estadoActual === 'activo') {
        $estadoH3.addClass('estado-info');
    }
}
$( "#btn_imprimir" ).click(function() {
    var ficha = document.getElementById('imprimir');
    var ventimp = window.open('', 'PRINT');
    ventimp.document.write('<html><head><title>' + document.title + '</title>');
    ventimp.document.write('<link rel="stylesheet" href="assets/css/main.css">');
    ventimp.document.write('<link rel="stylesheet" href="resources/css/imprecion_kardex.css">');
    ventimp.document.write('</head><body >');
    ventimp.document.write( ficha.innerHTML );
    ventimp.document.write('</body></html>');
    ventimp.document.close();
    ventimp.focus();
    ventimp.onload = function() {
        ventimp.print();
        ventimp.close();
    };
    return true;
});
