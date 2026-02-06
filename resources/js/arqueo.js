
jQuery(function ($) {

    getCambioDollar();

    function getCambioDollar(){

        $.ajax({
            url: 'index.php?controller=CambioDolar&action=dataByComboCambioDolar',
            type: 'POST',

            success: function (result) {

                var data = JSON.parse(result);

                if(data){
                    $('#cambio_dolar').val(parseFloat(data.cambio_dolar));
                    $('#id_cambiodolar').val((data.id_cambiodolar));
                }
                else{
                    $('#cambio_dolar').val('');
                    $('#cambio_dolar').val('');
                }


            }

        });

    }

    $('input[type="text"]').keyup(function (e) {
        var i = 0;
        var inputexto = $(this).attr("id");
        var dato = this.value;



        if($.isNumeric(dato)){

            if(inputexto=='mil_c'){i = 1000;  }
            else if(inputexto=='quinientos_c'){i=500; }
            else if(inputexto=='doscientos_c'){ i=200;}
            else if(inputexto=='cien_c' ){ i=100;}
            else if(inputexto=='cincuenta_c'){ i=50;}
            else if(inputexto=='veinte_c'){ i=20;}
            else if(inputexto=='diez_c'){ i=10;}
            else if(inputexto=='cinco_c'){ i=5;}
            else if(inputexto=='uno_c'){ i=1;}
            else if(inputexto=='cien_usd') {i=100;}
            else if(inputexto=='cincuenta_usd'){ i=50;}
            else if( inputexto=='veinte_usd'){ i=20;}
            else if( inputexto=='diez_usd'){ i=10;}
            else if(inputexto=='cinco_usd'){ i=5;}
            else if(inputexto=='uno_usd'){ i=1;}
            else if(inputexto=='cincuentacentavos_c'){ i=0.5;}

            
            $('#'+inputexto+'_t').val(dato*i);

            var cambio_dolar = parseFloat($('#cambio_dolar').val());

            var subtotal_c = parseFloat($("#mil_c_t").val())+parseFloat($("#quinientos_c_t").val())+parseFloat($("#doscientos_c_t").val())+parseFloat($("#cien_c_t").val())+parseFloat($("#cincuenta_c_t").val())+parseFloat($("#veinte_c_t").val())+parseFloat($("#diez_c_t").val())+parseFloat($("#cinco_c_t").val())+parseFloat($("#uno_c_t").val())+parseFloat($("#cincuentacentavos_c_t").val());
            $("#subtotal_c").val(parseFloat(subtotal_c).toFixed(2));

            var subtotal_d = parseFloat($("#cien_usd_t").val())+parseFloat($("#cincuenta_usd_t").val())+parseFloat($("#veinte_usd_t").val())+parseFloat($("#diez_usd_t").val())+parseFloat($("#cinco_usd_t").val())+parseFloat($("#uno_usd_t").val());
            $("#subtotal_d").val(parseFloat(subtotal_d).toFixed(2));

            $("#subtotal_dd").val(parseFloat(subtotal_d*cambio_dolar).toFixed(2));

            var monto_cheque = parseFloat($("#monto_cheque").val());
            var desembolso = parseFloat($("#desembolso").val());

             var billetera = parseFloat($("#billetera").val());
            var cuenta = parseFloat($("#cuenta").val());

            console.log(parseFloat(subtotal_d*cambio_dolar).toFixed(2));
            console.log(subtotal_c);
            
            $("#total").val((parseFloat(subtotal_c+(subtotal_d*cambio_dolar)+ monto_cheque + desembolso + billetera + cuenta).toFixed(2)));
            var recaudo = parseFloat($('#recaudo').val());
            var total = parseFloat( $("#total").val());

            $('#diferencia').val(total - recaudo);

        }

    });


    $('input[type="text"]').keypress(function (e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if ((tecla == 8)||(tecla==46)) return true; // 3
        patron = /\d/; //   patron =/[A-Za-z\s]/; patron solo letras
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    });



$('#id_colaborador').on('change', function () { // capturar el evento cambio del select modalidad

var id_colaborador= this.value;
var time_begin = $('#time_begin').val();
var time_end = $('#time_end').val();


var parametros = {"id_colaborador": id_colaborador,"time_begin":time_begin,"time_end":time_end};
        $.ajax({
            url: 'index.php?controller=pagos&action=getpagosUser',
            type: 'POST',
            data: parametros,
            success: function (result) {

                    var data = JSON.parse(result);

                    if(data) {
                        $('#recaudo').val(data.recaudo);
                        $("#billetera").val(data.Billetera);
                        $("#cuenta").val(data.Cuenta);

                        var recaudo = parseInt($('#recaudo').val());

                        $('#diferencia').val(recaudo - parseInt($("#total").val()));
                    }
                    else {
                        $('#recaudo').val(parseInt(0));
                        var recaudo = parseInt($('#recaudo').val());
                        var total = parseInt($("#total").val());
                        var monto_cheque = parseFloat($("#monto_cheque").val());
                        var desembolso = parseFloat($("#desembolso").val());
                        $('#diferencia').val(total + monto_cheque + desembolso - recaudo );

            }
          }
              });

    });



  $('input[type="text"]').change(function (e) {
            var i = 0;
            var inputexto = $(this).attr("id");
            var dato = this.value;


    if(inputexto=='time_begin' || inputexto=='time_end')
       if($("#id_colaborador option:selected").text() !== "-Seleccione-"){


         var id_colaborador= $('#id_colaborador').val();
         var time_begin = $('#time_begin').val();
         var time_end = $('#time_end').val();

          if( $('#fecha').length ){
          var fecha = $("#fecha").val();
          var parametros = {"id_colaborador": id_colaborador,"time_begin":time_begin,"time_end":time_end,"fecha":fecha}; }

          else var parametros = {"id_colaborador": id_colaborador,"time_begin":time_begin,"time_end":time_end};

                 $.ajax({
                     url: 'index.php?controller=pagos&action=getpagosUser',
                     type: 'POST',
                     data: parametros,
                     success: function (result) {

                             var data = JSON.parse(result);

                             if(data) {
                                 $('#recaudo').val(data.recaudo);
                                 var recaudo = parseInt($('#recaudo').val());
                                 var total =  parseInt($("#total").val());
                                 var monto_cheque = parseFloat($("#monto_cheque").val());
                                 var desembolso = parseFloat($("#desembolso").val());
                                 $('#diferencia').val(total + monto_cheque + desembolso - recaudo );
                             }
                             else {
                                 $('#recaudo').val(parseInt(0));
                                 var recaudo = parseInt($('#recaudo').val());
                                 var total =  parseInt($("#total").val());
                                 var monto_cheque = parseFloat($("#monto_cheque").val());
                                 var desembolso = parseFloat($("#desembolso").val());
                                 $('#diferencia').val(total + monto_cheque + desembolso- recaudo );
                             }

                     }


                 });


               }


        });

$( "#btn_imprimir" ).click(function() { 
 var ficha = document.getElementById('imprimir');
 var ventimp = window.open('', 'PRINT');
 ventimp.document.write('<html><head><title>' + document.title + '</title>'); 
 ventimp.document.write('<link rel="stylesheet" href="assets/css/main.css">');
 ventimp.document.write('</head><body >');
 ventimp.document.write( ficha.innerHTML );
 ventimp.document.write('</body></html>');
 ventimp.document.close();
 ventimp.focus();
 ventimp.onload = function() {
   ventimp.print();
   ventimp.close();
 };
 return true;
});

});
