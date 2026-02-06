ImagenPreview('imagen','img_colaborador');
getOptionCombo('Sucursal', 'dataByComboSucursal', 'id_sucursal', 'id_sucursal', 'sucursal', '', '');
$("#cedula").mask("999-999999-9999S");
$("#telefono").mask("9999-9999");
$("#edad").mask("99");
$("#inss").mask("999999999");

    $(".form-control").keypress(function (e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8) return true; // 3
        if (tecla==32) return true; 
        patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    });


function CallAjax(user_name){

    $.ajax({
        type: 'GET',
        url: 'index.php?controller=Usuarios&action=exitaUserName&user_name='+user_name,
        beforeSend: function () {
            $('.pace').removeClass('pace-inactive');
            $('.pace').addClass('pace-active');
        },
        success: function (response) {
            $('.pace').removeClass('pace-active');
            $('.pace').addClass('pace-inactive');
            var obj = JSON.parse(response);
            console.log(response);
            if(obj.respuesta == "true"){

                alertify.alert('Alvertencia:',"El nombre de Usuario : '"+ user_name+"' ya existe pruebe con otro" , function () {});
                //return false;
                $("input[name='usuario']").val("");
            }else{

            }
        }
    });

}

$("input[name='usuario']").on("blur",function(){
    CallAjax($("input[name='usuario']").val());
    return false;
});