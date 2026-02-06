$("#add_row").on("click",function () {
    var typo=$("#typeForm").val();
    garantias_add(typo);
});

$("#sub_row").on("click",function () {
    garantias_delete();
});


function garantias_add(typo) {
    var i =parseInt($("#counter").val());
    if(i<15){
        $('#prendasG').append("<div class=\"card "+i+"\" ><div  class=\"row "+i+"\">\n" +
            "        <div class=\"col-lg-4\">\n" +
            "                    <label><span class=\"txRed\">*</span>Descripcion:</label>\n" +
            "                    <textarea class=\"form-control noValidated\" name=\"descripcion_garantias"+i+"\" rows=\"5\" cols=\"30\"></textarea>\n" +
            "            </div>\n" +
            "            <div class=\"col-lg-2\">\n" +
            "                    <label><span class=\"txRed\">*</span>Precio Estimado:</label>\n" +
			"                	<input type=\"text\"  name=\"precio"+i+"\" class=\"form-control noValidated\"/>\n"+
            "            </div>\n" +
            "           <div class=\"col-lg-2\">\n" +
            "<label>Foto Garantia "+(i+1)+": </label><input name=\"prenda"+i+"\" id=\"prenda"+i+"\" accept=\"image/*\"  type=\"file\" onchange=\"upload_image('"+typo+"','Cliente','save_image','prenda"+i+"','garan"+i+"','fotos_garantias','update"+i+"');return false;\" />\n" +
            "<div class=\"row\" id=\"update"+i+"\"></div>\n" +
            "<img id=\"pren"+i+"\" src=\"\" width=\"80%\" height=\"80%\" class=\"img-thumbnail\" />\n" +
            "<input type=\"hidden\" name=\"garan"+i+"\" id=\"garan"+i+"\">\n" +
            "</div>\n" +
            "            </div></div>");
        ImagenPreview('prenda'+i,'pren'+i);
        i=i+1;
    }
    $("#counter").val(i);
}

function garantias_delete() {
	//var clase = $("#counter").val();
	var j=parseInt($("#counter").val())-1;
	if(j > 0){
	$("div").remove("."+j);
	 $("#counter").val(j);
	 i=j;
	}
}





