$( "#btn_imprimir" ).click(function() {
    var ficha = document.getElementById('myPrintArea');
    var ventimp = window.open('', 'PRINT');
    ventimp.document.write('<html><head><title>' + document.title + '</title>');
    ventimp.document.write('<link rel="stylesheet" href="assets/css/main.css">');
    ventimp.document.write('<link rel="stylesheet" href="resources/css/notadecreditoImpresion.css">');
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