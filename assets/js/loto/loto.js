
function updateTable() {
  const tableBody = $('#dataTable tbody');
  tableBody.empty();


venta.numeros.sort((a, b) => a.numero - b.numero);

  venta.numeros.forEach((item,index) => {

    if([1,5,7,9,10,11].includes(venta.id_juego) ){ //  0 -99
      if(item. numero >=0 && item.numero <10 )
        numero = '0' + item.numero.toString();
      else numero = item.numero.toString();
    }else if([2,6,8].includes(venta.id_juego) ){ //  0 -999
      if(item. numero <10 && item.numero >=0 )
        numero = '00' + item.numero.toString();
      else if(item. numero >=10 && item.numero <100 )
        numero = '0' + item.numero.toString();
      else numero = item.numero.toString();
    }else if([4].includes(venta.id_juego) ){ //  0 -9999
      if(item. numero >=0 && item.numero <10 )
        numero = '000' + item.numero.toString();
      else if(item. numero >=10 && item.numero <100 )
        numero = '00' + item.numero.toString();
      else if(item. numero >=100 && item.numero <1000 )
        numero = '0' + item.numero.toString();
      else numero = item.numero.toString();
    }

    const newRow = $('<tr>');
    newRow.append($('<td>').html('<span class="badge badge-center rounded-pill text-bg-danger">' + (index+1) + '</span> ' ));

    newRow.append($('<td>').html('<span class="badge badge-center rounded-pill text-bg-warning">#</span> ' + numero ));
    newRow.append($('<td>').html('<span class="badge badge-center rounded-pill text-bg-danger">C$</span> ' + item.monto));
    newRow.append($('<td>').html('<span class="badge badge-center rounded-pill text-bg-danger">C$</span> ' + item.monto  * 80));
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
    let monto = $('#monto').val().trim();
    const numeroInput = $('#numero');

   if (!monto || !numero || !venta.nombre) {
      show_Notify("danger", "Error", "Todos los campos son obligatorios");
      return;
    }
  
     numero= parseInt(numero);
     monto= parseInt(monto);

     if(monto == 0){
      show_Notify("danger","Error","Monto no puede ser Cero");
      return;
     }


  if (numero < venta.min || numero > venta.max) {
    show_Notify("danger", "Error", 'Números válidos entre ' + venta.min + ' y ' + venta.max);
    return;
  }

  venta.numeros.push({numero,monto});

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

  console.log(montoRandom);

  for (let i = 0; i < cantidadRandom; i++) {

    do{
     numero = Math.floor(Math.random() * venta.max);
  
      }while(numerosTemp.find(item => item.numero === numero));

      numerosTemp.push({numero: numero, monto: montoRandom});
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
      numero: i,
      monto: montoLinea,
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
      numero: parseInt(pares[i]),
      monto: parseInt(montoPar),
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

  let nombre = $('#nombre').val();

  if (!nombre) {
    show_Notify("danger", "Error", 'El nombre del cliente no puede estar vacío');
    return;
  }

  if (!venta.numeros.length) {
    show_Notify("danger", "Error", 'No hay números para la venta');
    return;
  }

  totalVenta = venta.numeros.reduce(function(a, b) {
    return a + b.monto;
  }, 0);
  venta.total = totalVenta;
  venta.nombre = $('#nombre').val();

  $.ajax({
    url: 'index.php?controller=juegos&action=store',
    type: 'POST',
    data: venta,
    success: function(response) {
      console.log(response);
      const data = JSON.parse(response);
      console.log(data);
      if (!data.status) {
        show_Notify("danger", "Error", data.message);
        return;
      }
      show_Notify("success", "Correcto",data.message);


      let html = '';
      $('#heder-receipt').html('');
      html +=
        '<h3> RIFAS EL REGALON </h3>' +
        '<div> JUEGO:'+venta.juego+'</div>' +
        '<div> SORTEO:'+data.sorteo+'</div>' +
        '<div> Consecutivo:' + data.consecutivo + '</div>' +
        '<div> VENDEDOR: Marcela Lopez </div>' +
        '<div>CLIENTE: ' + venta.nombre + '</div>' +
        '<div>DIRECCION:pizarra del estadio 2 c al Norte</div>' +
        '<div>Tel: 8325-4510</div>' +
        '<div>' +
        fecha.toLocaleDateString('es-ES', opciones) +
        ' ' +
        new Date().toLocaleTimeString() +
        '</div>';

      $('#heder-receipt').append(html);

      $('#rows_item').html('');
      let total = 0;
       let premio =0;
      venta.numeros.forEach((item) => {
       premio  = parseInt(item.monto) * 80;
        total += parseInt(item.monto);
        console.log(total);
        $('#rows_item').append(
          '<tr><td >' +
            item.numero +
            '</td><td >' +
            item.monto +
            '</td>' +
            '<td >' +
            premio +
            '</td></tr>'
        );
      });
      $('#total').html('C$ ' + total);

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
  const receiptContent = document.querySelector('#receiptModal .modal-body').innerHTML;
  
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

