

<script src="resources/js/tablero.js" type="text/javascript"></script>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <h4 class="card-title">Desembolsos Grafico Lineal</h4>
            <div id="myfirstchart" style="height: 250px;"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <h4 class="card-title">Desembolsos Grafico de barras</h4>
            <div id="test" style="height: 250px;"></div>
        </div>
    </div>
</div>
<div id="container-picker"></div>

<div id="inferior">
    <div class="col-lg-4">
        <label>Seleccione el Rango</label>
        <SELECT name="range_date" id="range_date" class=" form-control">
            <option value="semana">Semana Actual</option>
            <option value="mes">Mes Actual</option>
            <option value="range_custom" data-toggle="modal" data-target="#getRangeCustom">Rango personalizado</option>

        </SELECT>
    </div>
</div>

<div id="getRangeCustom" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rango personalizado</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-4">
                    <div class='input-group date'>
                        <input id="fecha_bengin" name="fecha_bengin" placeholder="Fecha Inicio"
                               class="datepicker form-control" type="text" autofocuss>
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class='input-group date'>
                        <input id="fecha_end" name="fecha_end" placeholder="Fecha Final" class="datepicker form-control"
                               type="text" autofocuss>
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="ok" class="btn btn-default" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

