/**
 * Created by fleming on 1/6/22.
 */


$( "#btn_imprimir" ).click(function() {



    var ficha = document.getElementById('myPrintArea');
    var ventimp = window.open('', 'PRINT');
   // ventimp.document.write('<html><head><title>' + document.title + '</title>');
    ventimp.document.write('<link rel="stylesheet" href="../resources/css/print-invoice-style.css">');
    ventimp.document.write('</head><body >');
    ventimp.document.write( ficha.innerHTML );
    ventimp.document.write('</body></html>');
    ventimp.document.close();
    ventimp.focus();
    ventimp.onload = function() {
        ventimp.print();
        //ventimp.close();
    };
    return true;
});


$( "#saveCuota" ).on( "click", function() {
    var abono = $("#monto").val();
    $(".monto").val(abono);
});

