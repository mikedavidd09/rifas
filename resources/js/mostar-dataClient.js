$("#busqueda").on("change",function(){
	var data = $(this).val();
	var ArrayData  = data.split("-");
	$("#nombre").val(ArrayData[1]);
	$("#id").val(ArrayData[0]);
});