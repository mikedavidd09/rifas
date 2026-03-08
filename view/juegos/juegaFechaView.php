<!-- Basic Bootstrap Table -->

<div class="row g-12">
    <div class="col-md-6">
        <div class="card">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading"><?php echo $juego->nombre; ?></h4>
            </div>
            <div class="card-body">
                <!-- Use flexbox so buttons stay horizontal when space allows -->

                <div class="row mb-3">
                    <div class="col-xs-4">
                        <button id="random" class="btn btn-primary" data-toggle="modal" data-target="#randomModal">
                            <i class="fa fa-random"></i>
                        </button>
                    </div>
                
                </div>

                <hr class="my-4" style="border-top: 1px solid #7b7a7aff !important" />
                <div class="row g-6">
                
                    <input type="hidden" id="vendedor" value="<?php echo $vendedor; ?>" />
                    <div class="col-md-6 col-xs-6">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" />
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <label class="form-label" for="monto">Monto C$</label>
                        <input type="tel" id="monto" name="monto" class="form-control onlynumber" />
                    </div>
                </div>
                <div class="row g-6">
                    <div class="col-md-6 col-xs-6">
                        <label class="form-label" for="numero">Mes</label>
                    <select class="form-control" id="mes" name="mes">
                        <option value="0">Seleccione un mes</option>
                        <option value="Ene">Enero</option>
                        <option value="Feb">Febrero</option>
                        <option value="Mar">Marzo</option>
                        <option value="Abr">Abril</option>
                        <option value="May">Mayo</option>
                        <option value="Jun">Junio</option>
                        <option value="Jul">Julio</option>
                        <option value="Ago">Agosto</option>
                        <option value="Sep">Septiembre</option>
                        <option value="Oct">Octubre</option>
                        <option value="Nov">Noviembre</option>
                        <option value="Dic">Diciembre</option>
                    </select>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <label class="form-label" for="monto">Dia</label>
                        <input type="tel" id="dia" nae="dia" class="form-control onlynumber"  maxlength="<?php echo $maxdigits; ?>" />
                    </div>
                </div>
                <div class="row g-6">

                    <div class="col-md-6 col-xs-6" style="margin-top: 10px;">
                            <button
                                id="addFecha"
                                class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i>
                                <span class="align-middle">Agregar</span>
                            </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <buton id="deleteAll" class="btn btn-danger">Borrar Todo</buton>
            <div class="table-responsive text-nowrap">
                <table id="dataTable" class="table text-nowrap">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Numero</th>
                            <th>Monto C$</th>
                            <th>Premio C$</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

 <div id="inferior" class="footer-buttons">
                    <div class="row">
                  
                    <div class="col-md-6 col-xs-6">
                        <button class="btn btn-primary mb-custom" onclick="showReceipt()">
                            <i class="fa fa-save"></i>
                            <span class="align-middle">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
<!-- Modal  resgistrar numero aleatorios -->
<div
    id="randomModal"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="randomModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="randomModalLabel">Registrar Aleatorio</h5>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="montoRandom">Monto C$</label>
                                        <input type="tel" id="montoRandom" class="form-control onlynumber" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label" for="cantidadRandom">Cantidad de Aleatorios</label>
                                        <input type="tel" id="cantidadRandom" class="form-control onlynumber" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="addFechaRandom" type="button" class="btn btn-primary">Agregar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Imprimir Recibo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="printableReceipt" class="receipt text-center">
                    <div class="receipt-header" id="content-receipt">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">Imprimir</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/loto/loto.js"></script>

<script>

    window.venta = {
        id_juego: <?php echo (int)($juego->id_juego); ?>,
        nombre_juego: <?php echo json_encode($juego->nombre); ?>,
        nombre_cliente:"",
        min: <?php echo (int)($juego->min); ?>,
        max: <?php echo (int)($juego->max); ?>,
        maxdigits: <?php echo (int)($maxdigits); ?>,
        factor: <?php echo (int)($juego->factor); ?>,
        numeros: [],
        sorteos:[],
        total: 0,
        premio: 0
    };

    $('#addFechaRandom').on('click', function() {
        let montoRandom = $('#montoRandom').val().trim();
        let cantidadRandom = $('#cantidadRandom').val().trim();
        if (!montoRandom || !cantidadRandom) {
            show_Notify("danger", "Error", "Todos los campos son obligatorios");
            return;
        }
        montoRandom = parseInt(montoRandom);
        cantidadRandom = parseInt(cantidadRandom);
        if (cantidadRandom < 1 || cantidadRandom > 31) {
            show_Notify("danger", "Error", "Solo se pueden agregar numeros entre 1 y 31");
            return;
        }
        if (montoRandom == 0) {
            show_Notify("danger", "Error", "Monto no puede ser Cero");
            return;
        }
        const meses = ['Ene','Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        let index =0;
        let diaRandom =0;
        let fechaRandom = '';
        let numerosTemp = [];

        for (let i = 0; i < cantidadRandom; i++) {
            do {
                index = Math.floor(Math.random() * 12);
                console.log(index);
                diaRandom = Math.floor(Math.random() * 31) + 1;
                fechaRandom = diaRandom + ' ' + meses[index];
                
            } while (numerosTemp.find(item => item.numero === fechaRandom));
            numerosTemp.push({
                numero: fechaRandom,
                monto: montoRandom,
                premio: montoRandom * venta.factor,
            });
        }
        venta.numeros.push(...numerosTemp);
        show_Notify("success", "Correcto", "Se agregaron " + cantidadRandom + " numeros aleatorios");
        updateTable();

        $('#randomModal').modal('hide');
    });


</script>