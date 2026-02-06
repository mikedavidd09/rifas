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
    if($("#filter_by option:selected").text() === "Morosos" || $("#filter_by option:selected").text() === "Inactivo"
        || $("#filter_by option:selected").text() === "<= 20%" || $("#filter_by option:selected").text() === "Saneados"
        || $("#filter_by option:selected").text() === "Dellalles Saneados" || $("#filter_by option:selected").text() ==="Cartera General"
        || $("#filter_by option:selected").text() === "Saldos de Cartera" || $("#filter_by option:selected").text() === "Cartera Total"
        || $("#filter_by option:selected").text() === "Ultimo Abono" || $("#filter_by option:selected").text() === "CPPDDIASEMANA"
        || $("#filter_by option:selected").text() === "Moras acumulada"  || $("#filter_by option:selected").text() === "Prestamos Activos"
        || $("#filter_by option:selected").text() === "Activos" || $("#filter_by option:selected").text() === "Vencidos"
        || $("#filter_by option:selected").text() === "Cancelados"
        || $("#filter_by option:selected").text() === "Aumento de Capital" ){
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

    if ($("#groud_by option:selected").text() === "Gestores") {
        $('#filter_by').html("<option value=\"0\">-Seleccione-</option>\n" +
                             "<option value=\"Moras acumulada\">Moras acumulada</option>\n" +
                            "<option value=\"Recaudado\">Recaudado</option>\n"+
                            "<option value=\"Recaudado Saneados\">Recaudado Saneados</option>\n"+
                            "<option value=\"Recaudado Por Semanas\">Recaudado Por Semanas</option>\n"+
                            "<option value=\"Recaudado Fechas\">Recaudado Fechas</option>\n"+
                            "<option value=\"Prestamos Activos\">Prestamos Activos</option>\n"+
                            "<option value=\"Prestamos NoAbonado\">Prestamos No Abonado</option>\n"+
                            "<option value=\"Metas del Dia\">Meta del Dia</option>\n"+
                            "<option value=\"Primera_cuota\">Primera Cuota</option>\n"+
                            "<option value=\"Cartera_General\">Cartera General</option>\n"+
                            "<option value=\"Metricas_Rete\">Metricas De Retencion</option>\n"+
                            "<option value=\"Metricas_general\">Metricas General</option>\n"+
                            "<option value=\"Recaudado Clientes\">Recaudado Clientes</option>\n");
    }
//"<option value=\"Cliente_menor_10\">Clientes Menores a 10%</option>\n"+
    if ($("#groud_by option:selected").text() === "Clientes") {
        $('#filter_by').html("<option value=\"0\">-Seleccione-</option>\n" +
                             "<option value=\"citas_agendada\">Citas Agendadas</option>\n" +
                             "<option value=\"Moroso\">Morosos</option>\n" +
                            "<option value=\"Dia\">Al Dia</option>\n"+
                            "<option value=\"Aumento de Capital\">Aumento de Capital</option>\n"+
                            "<option value=\"Inactivo\">Inactivo</option>\n"+
                            "<option value=\"cartera_saneada\">Saneados</option>\n"+
                             "<option value=\"detalle_saneada\">Dellalles Saneados</option>\n"+
                             "<option value=\"detalle_saneada_fecha\">Saneados Detalles</option>\n"+
                            "<option value=\"Pagos\">Pagos</option>\n"+
                            "<option value=\"Cliente_menorigual_20\"><= 20%</option>\n"+
                            "<option value=\"Reporte_Contabilidad\">Reporte Contabilidad</option>\n"+
                            "<option value=\"Reporte_histAfter7pm\">Historial de Pago After 7 PM</option>\n"+
                            "<option value=\"Historial Cliente\">Historial de Pago</option>\n");
    }

    if ($("#groud_by option:selected").text() === "Prestamos") {
        $('#filter_by').html("<option value=\"0\">-Seleccione-</option>\n" +
                             "<option value=\"Prestamo_PorDia\">CPPDDIASEMANA</option>\n" +
                            "<option value=\"Moroso_p\">Morosos</option>\n" +
                            "<option value=\"Dia_p\">Al Dia</option>\n"+
							"<option value=\"Ultimo_abono\">Ultimo Abono</option>\n"+
							"<option value=\"Cartera_Total\">Cartera Total</option>\n"+
							"<option value=\"Saldos_Cartera\">Saldos de Cartera</option>\n"+
                            "<option value=\"Datos Prestamos\">Datos Prestamos</option>\n"+
                            "<option value=\"Cartera\">Cartera</option>\n"+
                            "<option value=\"Activos\">Activos</option>\n"+
                            "<option value=\"Vencidos\">Vencidos</option>\n"+
                            "<option value=\"Cancelados\">Cancelados</option>\n"+
                            "<option value=\"CanceladosPorFecha\">Cancelados Por Fecha</option>\n"+
                            "<option value=\"Fecha Desembolso\">Fecha Desembolso</option>\n"+
                            "<option value=\"Fecha Desembolso Agrupado\">Fecha Desembolso Agrupado</option>\n"+
                            "<option value=\"Fecha Vencimiento\">Fecha Vencimiento</option>\n");
    }

    if ($("#groud_by option:selected").text() === "Promotores") {
        $('#filter_by').html("<option value='Prestamos por Promotor'>Prestamos por Promotor</option>\n"+
                            "<option value='Prestamo mas Alto'>Prestamo mas alto por promotor</option>");
    }

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
