$("#vermetasdia").on("click",function () {
	$(".msghope").html("<p>Por favor espere, se esta generando las metas del Dia.<br /></p>");
				$('.cargando').show(0);
			$.post('index.php?controller=prestamo&action=getMetasDelDia', { fecha_inicio:$("#fecha_inicio").val(),fecha_final:$("#fecha_final").val()}, function(resp) {
				$("#vermetas").html(resp);
				$this.button('reset');
				$('.cargando').hide(0);
				$(".msghope").html("");
			});
});