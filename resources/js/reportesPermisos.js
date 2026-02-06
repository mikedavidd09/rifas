/*cleanChartEspecial();*/
$('.header_reports').prepend("<thead>" +
    "<tr>"+
    "<th></th>"+
    "<th colspan='3'>2018-10-01</th>"+
    "<th colspan='3'>2018-10-02</th>"+
    "<th colspan='3'>2018-10-03</th>"+
    "<th colspan='3'>2018-10-04</th>"+
    "</tr>"+
    "</thead>");
$.getScript("./assets/js/searche-case-insensitive.js", function(){
	getSearcheCaseInsensitive('prestamo','searcheCaseInsensitevi','nombre','codigo_prestamo','codigo_cliente');
});
getOptionCombo('sucursal', 'dataByComboSucursal', 'id_sucursal', 'id_sucursal', 'sucursal', '', '');

$('#filter_by').on('change', function () {
    if ($("#filter_by option:selected").text() === "Recaudado Clientes" || $("#filter_by option:selected").text() === "Prestamos Activos"  ) {
          $("#colaboradores_div").show('200');
    } else {
			$("#colaboradores_div").hide('200');
    }
    if($("#filter_by option:selected").text() === "Morosos" || $("#filter_by option:selected").text() === "Saldos de Cartera" || $("#filter_by option:selected").text() === "Cartera Total" || $("#filter_by option:selected").text() === "Ultimo Abono" || $("#filter_by option:selected").text() === "CPPDDIASEMANA" || $("#filter_by option:selected").text() === "Moras acumulada"  || $("#filter_by option:selected").text() === "Prestamos Activos"  || $("#filter_by option:selected").text() === "Activos" || $("#filter_by option:selected").text() === "Vencidos" || $("#filter_by option:selected").text() === "Cancelados"  ){
        $("#fecha_bengin").hide('200').addClass("noValidated");
        $("#fecha_end").hide('200').addClass("noValidated");
    } else {
        $("#fecha_bengin").show('200');
        $("#fecha_end").show('200');
    }

    if($("#filter_by option:selected").text() == "Historial de Pago" ){
        $("#codigo_cliente_div").show('200');
        siValidarHidden('codigo_cliente_div');
    } else {
        $("#codigo_cliente_div").hide('200');
        noValidarHidden('codigo_cliente_div');
    }
});

$('#groud_by').on('change', function () {

    $("#fecha_bengin").show('200').removeClass("noValidated");
    $("#fecha_end").show('200').removeClass("noValidated");
    $("#codigo_cliente_div").hide('200');
    noValidarHidden('codigo_cliente_div');

   CallAjax($(this).val());
    
    
  $("#id_colaborador").prop('disabled', true);
    $('#all_colaborador').change(function () {
        if ($(this).prop('checked')) {
            noValidarHidden('colaboradores_div');
            $('#colaborador').hide('500');
            $("#id_colaborador").prop('disabled', true);

        } else {
            siValidarHidden('colaboradores_div');
            $('#colaborador').show('500');
            $("#id_colaborador").prop('disabled', false);
        }
    })

});
function CallAjax(child){
	$.ajax({
        type: 'POST',
        url: 'index.php?controller=Reports&action=getChild&child='+child,
        data: $("#abonar_form").serialize(),
        beforeSend: function () {
            $('.pace').removeClass('pace-inactive');
            $('.pace').addClass('pace-active');
        },
        success: function (response) {
			$('.pace').removeClass('pace-active');
            $('.pace').addClass('pace-inactive');
        	
            var data = JSON.parse(response);
            var option = $('#filter_by option');
            var select = $('#filter_by');
            var op_selected;
            option.each(function () {if (this.value != 0 && this.value != 'All' ) {this.remove();}});
           if(data.length !== undefined){
            for (var x = 0; x < data.length; x++) {
                
               select.append("<option  value ='"+data[x]['value']+"'>"+data[x]['label']+"</option>");
            }
			}else{
			
			 select.append("<option  value ='"+data['value']+"'>"+data['label']+"</option>");
			}
        }
    });
}