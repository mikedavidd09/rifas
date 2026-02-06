
$.getScript("assets/js/searche-case-insensitive.js", function(){
	getSearcheCaseInsensitive('Cliente','searcheCaseInsensitevi','nombre','codigo_cliente','id_cliente');
});
//para que no se ingrese ningun caracter especial en los inputs
$(".form-control").keypress(function (e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) return true; // 3
    if (tecla == 32) return true;
    patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
});


$("#capital").blur(function(){
    var capital_asignado = $("#capital_max").val();
    var capital_solicitado = $(this).val();
    if(capital_solicitado > capital_asignado){
        $(this).css("background-color", "#e27070");
        alert("El capital del prestamo supera el maximo para este cliente");
    }else{
        $(this).css("background-color", "white");
    }
});
