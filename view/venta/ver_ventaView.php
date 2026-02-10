
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3 text-center">
                    <h3><?php echo $venta->juego; ?></h3>
                    <div>VENDEDOR: <?php echo $venta->vendedor; ?></div>
                    <div>CLIENTE: <?php echo $venta->cliente; ?></div>
                    <div>CONSECUTIVO: <?php echo $venta->consecutivo; ?></div>
                    <div>SORTEO: <?php echo $venta->sorteo; ?></div>
                    <div>FECHA: <?php echo $venta->fecha; ?></div>
                    <div>HORA: <?php echo $venta->hora; ?></div>
                    <div>TOTAL: C$ <?php echo $venta->total; ?></div>
                    <hr>
                    <div classs="table-responsive text-nowrap">
                        <table id="dataTable" class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th> Nº </th>
                                    <th> Numero </th>
                                    <th> Monto C$</th>
                                    <th> Premio C$</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php $i=1; foreach ($numeros as $item) { ?>
                                    <tr class="text-center">
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $item->numero; ?></td>
                                        <td>C$ <?php echo $item->monto; ?></td>
                                        <td>C$ <?php echo $item->monto * 80; ?></td>
                                    </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-primary " onclick="showReceipt()">
                                    <i class="fa fa-print"></i>
                                    <span class="align-middle">IMPRIMIR</span>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button id="deleteAll" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                    <span class="align-middle">ELIMINAR</button>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-secondary link" href="index.php?controller=Venta&action=index">
                                    <i class="fa fa-arrow-left"></i>
                                    <span class="align-middle">VOLVER</span>
                                </a>
        </div>
    </div>

