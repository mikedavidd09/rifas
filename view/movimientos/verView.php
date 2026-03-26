<div class="card">
    <!-- Inicio del contenedor -->
    <div class="row p-3">
        <div class="col-xs-8">
            <ul class="nav nav-tabs mb-0">
                <li class="active">
                    <a data-toggle="tab" href="#cobro">Cobros</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#ajustes">Ajustes de premios</a>
                </li>
            </ul>
        </div>
        <div class="col-xs-4 text-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMovimiento">Agregar movimiento</button>
        </div>
    </div>
    <!-- Fin del contenedor -->
</div>

<div class="card">
    <div class="tab-content">
        <div id="cobro" class="tab-pane fade in active">
            <div class="card-body" style="padding: 15px;">
                Ver premios
            </div>
        </div>
        <div id="ajustes" class="tab-pane fade">
            <div class="card-body" style="padding: 15px;">
                Ver ajustes
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="modalAgregarMovimiento" tabindex="-1" role="dialog" aria-labelledby="modalAgregarMovimientoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarMovimientoLabel">Agregar movimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                 <div class="row">
                    <div class="col-md-6">
                
                    <div class="form-group">
                        <label for="monto">Monto</label>
                        <input type="number" class="form-control" id="monto" placeholder="Monto">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" placeholder="Descripción"></textarea>
                    </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select class="form-control" id="tipo">
                            <option value="cobro">Cobro</option>
                            <option value="ajuste">Ajuste</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" placeholder="Fecha">
                    </div>
                       </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
 </div>


<style>
.d-flex {
    display: flex;
}
.justify-content-between {
    justify-content: space-between;
}
.align-items-center {
    align-items: center;
}
.tab-content {
    padding: 0;
    border: none;
}
.tab-pane {
    padding: 0;
}

</style>

