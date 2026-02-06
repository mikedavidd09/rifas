jQuery(function($){

iniciarDatos();

var cola=0;
$("#saveCuota").on("click",function(){
	var saldo_pendiente;
	cola = cola +1;
	if(cola <= 1){
		if($("#monto").val() == ""){
			$("#monto").css("border", "1px solid red");
			cola=0;
			return false;
		}
		else{
			saldo_pendiente =$("#psaldo_pendiente").val();
			var saldo = saldo_pendiente.split("C$");
			if(parseFloat($("#monto").val()) > parseFloat(saldo[1])){
				showNotify('warning' ,'Error' ,'El Abono actual supera la deuda total del cliente.');
				$("#monto").val("");
				cola=0;
				return false;
			}else{
                var $this = $(this);
				CallAjax($this);
				return false;
			}
		}
	}else{
		 alertify.alert('Wolf Financiero Dice:',"Espere porfavor, se esta ejecutando una transaccion", function () {});
	}

});
function CallAjax($this){
    $this.button('loading');
	$.ajax({
        type: 'POST',
        url: 'index.php?controller=Pagos&action=crear',
        data: $("#abonar_form").serialize(),
        beforeSend: function () {
            $('.pace').removeClass('pace-inactive');
            $('.pace').addClass('pace-active');
        },
        success: function (response) {
			$('.pace').removeClass('pace-active');
            $('.pace').addClass('pace-inactive');
            var obj = JSON.parse(response);
            console.log(obj);
            data = obj;
            if(obj.respuesta == "true"){
                showNotify('success' ,'Correcto' ,'Cuota aplicada correctamente al prestamo.');
                ActualizarValores(obj.data);
				cola=0;
            }else{
                showNotify('warning' ,'Error' ,'La cuota no se pude aplicar al prestamo'+obj.mensaje);
				cola=0;
            }
            $this.button('reset');
        }
    });
}
function calcularCuotaDia(abono,cuota,mora,montoFavor){
var $cuotadia=0;
if(abono >=cuota){

		if(mora > 0){
			$cuotadia = mora;
		}else{
			$cuotadia = 0;
		}
	 }else{
		if(mora == 0 && (Math.round(montoFavor*1000)/1000) == 0){
			if(abono >0){
				$cuotadia=0;
			}else{
				$cuotadia = ((mora + cuota) - (montoFavor + abono));
			}
		}else{
				if(montoFavor >= cuota){
					$cuotadia=0;
				}else{
					if(mora >0 && abono > 0){
						$cuotadia=mora;
					}else{
						$cuotadia = ((mora + cuota) - (montoFavor + abono));
					}
				}
		}
	 }
	 return $cuotadia;

}
function ActualizarValores(data){
var data_ =JSON.parse(data);
var cuotaBase = $("#ccuota").val();
var cbase = cuotaBase.split("C$");
var ncuotas_atrasadas = parseFloat(data_.mora)/parseFloat(cbase[1]);
var ndvencidos = parseFloat($("#ndvencidos").val());
var sms="";
var cutotaDia=calcularCuotaDia(parseFloat(data_.acumulado),parseFloat(cbase[1]),parseFloat(data_.mora),parseFloat(data_.saldo_favor));

$("#ccuotadia").val(Math.round(cutotaDia*1000)/1000);
$("#monto").val("");
$("#mpagadoCl").val(data_.acumulado);
$("#cmora").val("C$ "+data_.mora);
$("#ncuotas_atrasadas").val(Math.round(ncuotas_atrasadas*1000)/1000);
$("#cmfavor").val("C$ "+data_.saldo_favor);
$("#psaldo_pendiente").val("C$ "+data_.saldo_pendiente);
$("#ccuotafaltantes").val(Math.round(parseFloat(data_.saldo_pendiente)/parseFloat(cbase[1])));
$("#ncuotaadelantada").val(Math.round((parseFloat(data_.saldo_favor)/parseFloat(cbase[1]))*1000)/1000);

if(ndvencidos > 0){
sms="<div class=\"alert alert-danger\">Mora Vencida , Dias Vencidos = "+ndvencidos+"</div>";
}else{
	if(ncuotas_atrasadas == 0){
		sms= "<div class=\"alert alert-info\">Al dia</div>";
	}else if(ncuotas_atrasadas > 0 && ncuotas_atrasadas < 5 ){
	 	sms= "<div class=\"alert alert-warning\">Mora Temprana</div>";
	}else if(ncuotas_atrasadas >= 5 && ncuotas_atrasadas < 9){
	 	sms= "<div class=\"alert alert-warning\">Mora Avanzada</div>";
	}else if(ncuotas_atrasadas >= 9 && ncuotas_atrasadas < 13){
		sms= "<div class=\"alert alert-warning\">Mora Riesgo</div>";
	}else if(ncuotas_atrasadas > 13){
		sms= "<div class=\"alert alert-danger\">Mora Riesgo Perdido </div>";
	}
}
$("#msgmora").html(sms);
}

function showNotify(type ,label_type, Menssege) {
    $.notify({
        title: '<strong>'+label_type+'!</strong>',
        message: Menssege
    },{
        type: type,
        animate: {
            enter: 'animated bounceInDown',
            exit: 'animated bounceOutUp'
        }
    });
}
function iniciarDatos(){
	$('#monto').mask('999999.99');
	var f = new Date();
	var dia = (f.getDate() > 9) ? f.getDate():'0'+f.getDate();
	var mes = ((f.getMonth() + 1) > 9) ? f.getMonth()+1:'0'+String(f.getMonth()+1);
	var fecha = f.getFullYear()+'-'+mes+'-'+dia;
	$("#pfecha").val(fecha);//f.toISOString().substr(0, 10)
	$("#fechapago").val(fecha);
}

});