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
                    <div class="col-xs-4">
                        <button id="linea" class="btn btn-primary" data-toggle="modal" data-target="#lineaModal">
                            <i class="fa fa-signal"></i>
                        </button>
                    </div>
                    <div class="col-xs-4">
                        <button id="suerte" class="btn btn-primary" data-toggle="modal" data-target="#paresModal">
                            <i class="fa fa-exchange"></i>
                        </button>
                    </div>
                </div>

                <hr class="my-4" style="border-top: 1px solid #7b7a7aff !important" />
                <div class="row g-6">
                
                    <input type="hidden" id="vendedor" value="<?php echo $vendedor; ?>" />
                    <div class="col-md-6">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" />
                    </div>
                         <div class="col-md-6">
                        <label class="form-label" for="monto">Monto C$</label>
                        <input type="tel" id="monto" class="form-control onlynumber" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="numero">Mes</label>
                    <select class="form-control" id="mes" name="mes">
                        <option value="0">Seleccione un mes</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="monto">Dia</label>
                        <input type="tel" id="dia" class="form-control onlynumber" />
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button
                                id="addButton"
                                class="btn btn-primary"
                                data-repeater-create=""
                                fdprocessedid="7v82oo"
                            >
                                <i class="fa fa-plus-circle"></i>
                                <span class="align-middle">Agregar</span>
                            </button>
                            <button class="btn btn-primary" onclick="showReceipt()">
                                <i class="fa fa-save"></i>
                                <span class="align-middle">Guardar</span>
                            </button>
                        </div>
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
                <button id="addRandomNumber" type="button" class="btn btn-primary">Agregar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div
    id="lineaModal"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="lineaModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="randomModalLabel">Registrar Linea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label" for="montoLinea">Monto C$</label>
                                        <input type="tel" id="montoLinea" class="form-control onlynumber" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label" for="cantidadLinea">Inicio</label>
                                        <input type="tel" id="inicioLinea" class="form-control onlynumber" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label" for="finalLinea">Inicio</label>
                                        <input type="tel" id="finalLinea" class="form-control onlynumber" />
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
                <button id="addLineaNumber" type="button" class="btn btn-primary">Agregar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal  resgistrar todos los numeros pares -->
<div
    id="paresModal"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="paresModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="randomModalLabel">Registrar todos los numeros pares</h5>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="montoPar">Monto C$</label>
                                        <input type="tel" id="montoPar" class="form-control onlynumber" />
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
                <button id="addparNumber" type="button" class="btn btn-primary">Agregar</button>
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
                    <div class="receipt-header" id="heder-receipt"></div>
                
                    <table class="receipt-items">
                        <thead id="table_header" class="thead-default text-center">

                        </thead>
                        <tbody id="rows_item"></tbody>
                        <tfoot>
                            <tr>
                            <th></th> <th id="total"></th> <th></th>                            
                            </tr>
                        </tfoot>
                    </table>
                    <div class="receipt-footer">

                    <div> <p style="text-align: center; margin: 10px 0;"> ¡ Valido para un sorteo ! </div>
                    <div> <p style="text-align: center; margin: 10px 0;"> ¡Porfavor revise su ticket ! </div>
                    <div> <p style="text-align: center; margin: 10px 0;"> ¡ Premio valido por 7 dias ! </div>
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
        total: 0,
        premio: 0
    };
console.log(venta);


</script>