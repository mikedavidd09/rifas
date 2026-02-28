
function updateTable() {
  const tableBody = $('#dataTable tbody');
  tableBody.empty();


venta.numeros.sort((a, b) => a.numero - b.numero);

  venta.numeros.forEach((item,index) => {

    const newRow = $('<tr class="text-center">');
    newRow.append($('<td>').html('<span class="badge bg-info rounded-pill">' + (index+1) + '</span> ' ));
    newRow.append($('<td>').html('<span class="badge bg-info rounded-pill">Nº</span> ' + item.numero ));
    newRow.append($('<td>').html('<span class="badge bg-info rounded-pill">C$</span> ' + item.monto));
    newRow.append($('<td>').html('<span class="badge bg-info rounded-pill">C$</span> ' + item.monto  * 80));
    newRow.append($('<td>').html('<a class="" href="#" onclick="deleteRow(this); return false;" style="color: red;"><i class="fa fa-trash fa-lg"></i></a>'));
    tableBody.append(newRow);
  });
}

function deleteRow(button) {
  const row = $(button).closest('tr');
  const rowIndex = row.index();
  venta.numeros.splice(rowIndex, 1);
  updateTable();
}

  $('#addButton').on('click', function() {
    venta.nombre = $('#nombre').val().trim();
    let numero = $('#numero').val().trim();
    let numeroInt = parseInt(numero,10);
    let monto = $('#monto').val().trim();
    let maxdigits = $('#maxdigits').val();
    let numeroLength =  numero.length;
    const numeroInput = $('#numero');

    if(numeroLength != venta.maxdigits){
      show_Notify("danger","Error","El numero debe tener exactamente " + venta.maxdigits + " digitos");
      return;
    }

    monto= parseInt(monto);
    premio = parseInt(monto * venta.factor);


    if(monto == 0){
      show_Notify("danger","Error","Monto no puede ser Cero");
      return;
    }

  venta.numeros.push({numero,monto,premio});

  updateTable();

  $('#numero').val('');
  $('#monto').val('');
  numeroInput.focus();


  });


$('#addRandomNumber').on('click', function() {

  let montoRandom = $('#montoRandom').val().trim();
  let cantidadRandom = $('#cantidadRandom').val().trim();

  if (!montoRandom || !cantidadRandom) {
    show_Notify("danger", "Error", "Todos los campos son obligatorios");
    return;
  }

  cantidadRandom = parseInt(cantidadRandom);
  montoRandom = parseInt(montoRandom);

  if(cantidadRandom < venta.min || cantidadRandom > venta.max){
    show_Notify("danger", "Error", "Solo se pueden agregar numeros entre " + venta.min + " y " + venta.max);
    return;
  }

if(montoRandom == 0){
  show_Notify("danger","Error","Monto no pueder ser cero");
  return;
}

  if((venta.numeros.length + cantidadRandom) > venta.max){
    show_Notify("danger", "Error", "Cantidad de numeros no puede exceder de " + venta.max);
    return;
  }

  let numero =0;

  let numerosTemp = [];
  for (let i = 0; i < cantidadRandom; i++) {

    do{
     numero = Math.floor(Math.random() * venta.max);

      let cantidadDigitos = String(numero).length;
     // console.log('numero='+numero + ' cantidadDigitos='+cantidadDigitos + ' venta.maxdigits='+venta.maxdigits);
    
        numero = String(numero).padStart(venta.maxdigits, '0');
      
      }while(numerosTemp.find(item => item.numero === numero));
      

      numerosTemp.push({numero: numero, monto: montoRandom, premio: montoRandom * venta.factor});
  }

    venta.numeros.push(...numerosTemp);
    show_Notify("success", "Correcto", "Se agregaron " + cantidadRandom + " numeros aleatorios");
    updateTable();

});

$('#addLineaNumber').on('click', function() {

  let montoLinea = $('#montoLinea').val().trim();
  let inicioLinea = $('#inicioLinea').val().trim();
  let finalLinea = $('#finalLinea').val().trim();

  let cantidadLinea  = finalLinea - inicioLinea + 1;

  if (!montoLinea || !inicioLinea || !finalLinea) {
    show_Notify("danger", "Error", "Todos los campos son obligatorios");
    return;
  }

  montoLinea = parseInt(montoLinea);
  inicioLinea = parseInt(inicioLinea);
  finalLinea = parseInt(finalLinea);

  if(inicioLinea > finalLinea){
    show_Notify("danger", "Error", "Valor inicial debe ser menor que valor final");
    return;
  }

  if(cantidadLinea + venta.numeros.length > venta.max){
    show_Notify("danger", "Error", "Numero de linea no puede exceder de " + venta.max);
    return;
  }

  for (let i = inicioLinea; i <= finalLinea; i++) {
    venta.numeros.push({
      numero: String(i).padStart(venta.maxdigits, '0'),
      monto: montoLinea,
      premio: montoLinea * venta.factor,
    });

  }

    show_Notify("success", "Correcto", "Se agrego la linea del " + inicioLinea + " al " + finalLinea + "correctamente");
    updateTable();

});


$('#addparNumber').on('click', function() {

  let montoPar = $('#montoPar').val().trim();

  if (!montoPar) {
    show_Notify("danger", "Error", "Todos los campos son obligatorios");
    return;
  }

  montoPar = parseInt(montoPar);
  if(montoPar == 0){
    show_Notify("danger","Error","Monto no puede ser Cero");
    return;
  }

let pares = [];
  if([1,5,7,9,10,11].includes(venta.id_juego) ) // juega diaria 0 -99
  pares = [0,11,22,33,44,55,66,77,88,99]; 
else if([2,6,8].includes(venta.id_juego) ) // juega 3 0 -999
  pares = [0,11,22,33,44,55,66,77,88,99,111,222,333,444,555,666,777,888,999];
   else if(venta.id_juego ==4 ) //  0 - 9999
  pares = [0,11,22,33,44,55,66,77,88,99,111,222,333,444,555,666,777,888,999,1111,2222,3333,4444,5555,6666,7777,8888,9999];

  if(venta.numeros.length + pares.length > venta.max){
    show_Notify("danger", "Error", "Numero de linea no puede exceder mas de " + venta.max);
    return;
  }

  for (let i = 0; i < pares.length; i++) {
    
    venta.numeros.push({
      numero: String(pares[i]).padStart(venta.maxdigits, '0'),
      monto: parseInt(montoPar),
      premio: parseInt(montoPar * 80),
    });

  }

  updateTable();
    show_Notify("success", "Correcto", "Se agregaron los numeros pares");

 } );


 $('#deleteAll').on('click', function() {
  venta.numeros = [];
  venta.total = 0;
  updateTable();
  show_Notify("success", "Correcto", "Se borraron todos los numeros");
 });


  $('.onlynumber').keypress(function(event) {
    var charCode = event.which;
    if ((charCode < 48 || charCode > 57) && charCode != 8) {
        return false;
    }
});


function showReceipt() {
  const fecha = new Date();
  const opciones = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  };


  if (!venta.numeros.length) {
    show_Notify("danger", "Error", 'No hay números para la venta');
    return;
  }

  totalVenta = venta.numeros.reduce(function(a, b) {
    return a + b.monto;
  }, 0);
  venta.total = totalVenta;
  venta.nombre_cliente = $('#nombre').val().length>0?$('#nombre').val():'';

  
  const todosLosCheckboxes = document.querySelectorAll('input[name="checkSorteos[]"]');
  if(todosLosCheckboxes.length != 0){
  const checkboxesMarcados = document.querySelectorAll('input[name="checkSorteos[]"]:checked');
  if(checkboxesMarcados.length == 0){
    show_Notify("danger", "Error", "Debe seleccionar al menos un sorteo");
    return;
  }
  venta.sorteos = Array.from(checkboxesMarcados).map(cb => parseInt(cb.value));

  } 


  $.ajax({
    url: 'index.php?controller=Venta&action=store',
    type: 'POST',
    data: venta,
    dataType: 'json',                // Clave
    contentType: 'application/json', // Clave
    data: JSON.stringify(venta),     // Clave
  
    success: function(data) {
  
      if (!data.status) {
        show_Notify("danger", "Error", data.message);
        return;
      }
      show_Notify("success", "Correcto",data.message);

      let vendedor = document.getElementById('vendedor').value;

      let sorteos_html = '';
      data.sorteos.forEach(item => {  
          sorteos_html += '<div> <p style="text-align: center; margin: 10px 0;"> SORTEO:' + item.etiqueta + '</p> </div>';
          sorteos_html += '<div> <p style="text-align: center; margin: 10px 0;"> VENTA Nº:' + item.consecutivo + '</p> </div>';
      });

      console.log(sorteos_html);
 
      let html = '';

      $('#rows_header').html(''); 

      html +='<div> <p style="text-align: center; font-weight: bold; margin: 10px 0;">RIFAS EL REGALON</p> </div>' +
        '<div> <p style="text-align: center;"> JUEGO:' + venta.nombre_juego + '</p> </div>' +
        sorteos_html +
        '<div> <p style="text-align: center; margin: 10px 0;"> VENDEDOR: ' + vendedor + '</p> </div>' +
        '<div> <p style="text-align: center; margin: 10px 0;"> CLIENTE:' + venta.nombre_cliente + '</p> </div>' +
        '<div> <p style="text-align: center; margin: 10px 0;"> TELEFONO: 8325-4510</p> </div>' +
        '<div> <p style="text-align: center; margin: 10px 0;"> PUESTO: san felipe</p> </div>' +
        '<div> <p style="text-align: center; margin: 10px 0;"> ' + fecha.toLocaleDateString('es-ES', opciones) + ' ' +  new Date().toLocaleTimeString() +  '</div>';

      $('#heder-receipt').html(html);

      $('#table_header').html('<tr> <th scope="col"> Apuesta   </th> <th scope="col">Monto</th> <th scope="col">Premio</th> </tr>');

      $('#rows_item').html('');
      let total = 0;
      let premio =0;
      venta.numeros.forEach((item) => {
      
        total += parseInt(item.monto);
  
        $('#rows_item').append(
          '<tr><td >' +
          item.numero +
          '</td> <td >' +
          item.monto +
          '</td> <td >' +
          item.premio +
          '</td></tr>'
        );
      });
      $('#total').html('TOTAL: C$ ' + total);

      printReceipt();
      $('#numero, #monto, #nombre').val('');
      venta.numeros = [];
      updateTable();
    
    },
    error: function(error) {
      console.error('Error al enviar datos:', error);
    }
  });
}


function printReceipt() {
  const receiptContent = document.querySelector('#printableReceipt').innerHTML;
  
  // 1. Construye el HTML del recibo
  const htmlContent = `
    <!DOCTYPE html>
    <html>
     <meta charset="UTF-8">
      <head>
        <title>Recibo - Rifa la Bendición</title>
        <style>
          body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            font-size: 14px;
            max-width: 800px;
          }
          table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
          }
          th, td { 
            padding: 8px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
          }
          .text-right { text-align: right; }
          .text-center {text-align: center; font-weight: bold; margin: 10px 0;}
          .total { font-weight: bold; }
        </style>
      </head>
      <body>
        ${receiptContent}
      </body>
    </html>
  `;

  // 2. Crea un Blob (objeto binario) con el HTML
  const blob = new Blob([htmlContent], { type: 'text/html' });
  
  // 3. Genera una URL temporal para el Blob
  const url = URL.createObjectURL(blob);
  
  // 4. Abre la ventana de impresión con la URL
  const printWindow = window.open(url, '_blank', 'height=600,width=800');
  
  // 5. Imprime cuando el contenido se cargue
  printWindow.onload = function() {
    printWindow.print();
    // 6. Limpia la URL temporal después de imprimir
    URL.revokeObjectURL(url);
  };
}



