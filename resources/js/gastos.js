ImagenPreview('imagen','img_gastos');
getOptionCombo('Sucursal', 'dataByComboSucursal2', 'id_sucursal', 'id_sucursal', 'sucursal', '', '');
getOptionCombo('Colaborador','dataByComboColaboradores','id_colaborador','id_colaborador','nombre','apellido','');
$("#id_sucursal").on("change",function(){
    var id_sucursal = $(this).val();
    if(id_sucursal > 0){
            $.ajax({
                type: 'POST',
                url: 'index.php?controller=Caja&action=getCierreDiaAnterior',
                data: $("#caja_apertura_form").serialize(),
                beforeSend: function () {
                    $('.pace').removeClass('pace-inactive');
                    $('.pace').addClass('pace-active');
                },
                success: function (response) {
                    $('.pace').removeClass('pace-active');
                    $('.pace').addClass('pace-inactive');
                    console.log(response);
                    var obj = JSON.parse(response);
                    console.log(obj);
                    data = obj;
                    console.log(obj.monto_cierre);
                    if(obj != false){
                        $("#monto_apertura").val(obj.monto_cierre);
                         $("#monto_apertura_dollar").val(obj.monto_cierre_dolares);
                    }else{
                        $("#monto_apertura").val(0);
                         $("#monto_apertura_dollar").val(0);
                    }
                    
                }
            });
        }

});
$("#tipo_movimiento").on("change",function(){
    var tipo = $(this).val();

    if(tipo == "gasto"){
        $("#colaborador").show();
    }else{
        $("#colaborador").hide();
    }

});
$(".form-control").keypress(function (e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) return true; // 3
    if (tecla==32) return true;
    patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
});


