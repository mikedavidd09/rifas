$("#busqueda").on("change",function(){
	var cliente = $(this).val();
	var cl = cliente.split("-");
	CallAjax(cl[0]);
	return false;
});
$("#id_cliente_mobile").on("change",function(){
	var cliente = $(this).val();
	var cl = cliente.split("-");
	CallAjax(cl[0]);
	return false;
});
function CallAjax(cliente){
	$.ajax({
        type: 'GET',
        url: 'index.php?controller=Cliente&action=getPrestamoAnalizarPorAumenteCap&cliente=' + cliente,
        beforeSend: function () {
            $('.pace').removeClass('pace-inactive');
            $('.pace').addClass('pace-active');
        },
        success: function (response) {
			$('.pace').removeClass('pace-active');
            $('.pace').addClass('pace-inactive');
        	$("#analizado").html(response);
        }
    });
}