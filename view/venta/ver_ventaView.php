<div class="visor">
<div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 text-center" id="printableReceipt">
                <div> <p style="text-align: center; font-weight: bold; margin: 2px 0;">RIFAS EL REGALON</p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> RECIBO DE COPIA </p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> JUEGO: <?php echo $venta->juego; ?> </p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> SORTEO: <?php echo $venta->sorteo; ?> </p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> VENTA Nº: <?php echo $venta->consecutivo; ?> </p></div>
                <div> <p style="text-align: center; margin: 2px 0;"> VENDEDOR: <?php echo $venta->vendedor; ?></p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> CLIENTE: <?php echo $venta->cliente; ?></p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> TELEFONO: 8325-4510</p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> PUESTO: san felipe</p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> <?php echo $fecha; ?> </p> </div>
                <div> <p style="text-align: center; margin: 2px 0;"> <?php echo $hora; ?> </p> </div>
                <hr />
                <div classs="table-responsive text-nowrap">
                    <table id="dataTable" class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Apuesta</th>
                                <th>Monto</th>
                                <th>Premio</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php
                            $i=1; foreach ($numeros as $item) { ?>
                            <tr class="text-center">
                                <td> <?php echo $item->numero; ?></td>
                                <td> <?php echo $item->monto; ?></td>
                                <td> <?php echo $item->monto * 80; ?></td>
                            </tr>
                            <?php $i++; } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                            <th></th> <th>Total: C$ <?php echo $venta->total ?></th> <th></th>                            
                            </tr>
                        </tfoot>
                    </table>
                    <div class="receipt-footer">

                    <div> <p style="text-align: center; margin: 1px 0;"> ¡ Valido para un sorteo ! </div>
                    <div> <p style="text-align: center; margin: 1px 0;"> ¡Porfavor revise su ticket ! </div>
                    <div> <p style="text-align: center; margin: 1px 0;"> ¡ Premio valido por 7 dias ! </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-primary" onclick="printReceipt()">
                                    <i class="fa fa-print"></i>
                                    <span class="align-middle">IMPRIMIR</span>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button
                                    id="borrar"
                                    class="btn btn-danger"
                                    data-toggle="modal"
                                    data-target="#borrarModal"
                                >
                                    <i class="fa fa-trash"></i>
                                    <span class="align-middle">ELIMINAR</span>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-secondary link" href="index.php?controller=Venta&action=index">
                                    <i class="fa fa-arrow-left"></i>
                                    <span class="align-middle">VOLVER</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div
                id="borrarModal"
                class="modal fade"
                tabindex="-1"
                role="dialog"
                aria-labelledby="myModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Borrar Venta</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span class="align-middle">¿Está seguro de borrar esta venta?</span>
                            <i class="fa fa-warning"></i>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" onclick="deleteVenta()">Borrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function printReceipt() {
        const receiptContent = document.querySelector("#printableReceipt").innerHTML;



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
        const blob = new Blob([htmlContent], { type: "text/html" });

        // 3. Genera una URL temporal para el Blob
        const url = URL.createObjectURL(blob);

        // 4. Abre la ventana de impresión con la URL
        const printWindow = window.open(url, "_blank", "height=600,width=800");

        // 5. Imprime cuando el contenido se cargue
        printWindow.onload = function () {
            printWindow.print();
            // 6. Limpia la URL temporal después de imprimir
            URL.revokeObjectURL(url);
        };
    }

function deleteVenta() {
    console.log("borrando venta");
    const url = 'index.php?controller=Venta&action=delete&id=' + <?php echo $venta->id_venta; ?> + '&id_sorteo=' + <?php echo $venta->id_sorteo; ?>;
    const link = document.createElement("a");
    link.href = "index.php?controller=Venta&action=index";
    

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (response.status) {
                show_Notify("success", "Correcto", "Se elimino la venta correctamente");
                    $("#borrarModal").modal('hide');
                timer = setTimeout(function () {
                $( ".visor" ).load( "index.php?controller=Venta&action=index" );
                }, 1000);
            } else {
                show_Notify("danger", "Error", response.message);
            }
        },
        error: function (error) {
            console.error('Error al enviar datos:', error);
        }
    });
}

</script>
