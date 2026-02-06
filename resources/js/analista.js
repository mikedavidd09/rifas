getOptionCombo('Sucursal', 'dataByComboSucursal', 'id_sucursal', 'id_sucursal', 'sucursal', '', '');

$("#cedula").mask("999-999999-9999S");
$("#telefono").mask("9999-9999");
$("#edad").mask("99");
$("#inss").mask("999999999");


//para que no se ingrese ningun caracter especial en los inputs
$(".form-control").keypress(function (e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) return true; // 3
    if (tecla == 32) return true;
    patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
});
