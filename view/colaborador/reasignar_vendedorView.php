<link href="resources/css/dataTableFiltrerByfield.css" rel="stylesheet"/>

<script type='text/javascript'>


 function setCarteraGeneral(){

$("#my-vendedores-reasignar option").each(function(){

    $(this).attr("selected","selected");

  });

  validarEnviar('asignacion_form','colaborador','ReAsignacion','');

}

</script>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-exchange text-primary"></i> <strong>ReAsignar Vendedor a Supervisor</strong>
        </h3>
    </div>
    
    <div class="panel-body">
        <form id="asignacion_form" method="POST">
            <div class="row">
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Escoja el Supervisor Origen:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <select name="mi-supervisor-asignado" id="mi-supervisor-asignado" class="form-control">
                                <option value="0">-- Elige Supervisor --</option>
                            </select>
                        </div>
                        <p class="help-block text-success">
                            <strong><i class="fa fa-users"></i> Vendedores: <span class="numero">0</span></strong>
                        </p>
                    </div>
                </div>

                <div class="hidden-xs col-md-2">
                    </div>

                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Escoja el Supervisor Destino:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                            <select name="mi-supervisor-reasignado" id="mi-supervisor-reasignado" class="form-control">
                                <option value="0">-- Elige el supervisor a ReAsignar --</option>
                            </select>
                        </div>
                        <p class="help-block text-info">
                            <strong><i class="fa fa-share"></i> Transferidos: <span class="numerotransf">0</span></strong>
                        </p>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-xs-12 col-sm-5">
                    <label class="visible-xs mt-10">Vendedores Actuales:</label>
                    <select multiple="multiple" id="my-select-vendedores" name="myvendedores[]" class="form-control noValidated" size="12" style="height: 300px;">
                        </select>
                </div>

                <div class="col-xs-12 col-sm-2 text-center">
                    <div class="btn-group-vertical btn-group-lg hidden-xs" style="margin-top: 50px;">
                        <button type="button" class="btn btn-default" id="asigna_select" title="Asignar seleccionado">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                        <button type="button" class="btn btn-primary" id="asigna_todos" title="Asignar todos">
                            <i class="fa fa-angle-double-right"></i>
                        </button>
                        <button type="button" class="btn btn-default" id="designa_select" title="Quitar seleccionado">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-danger" id="designa_todos" title="Quitar todos">
                            <i class="fa fa-angle-double-left"></i>
                        </button>
                    </div>

                    <div class="visible-xs text-center" style="margin: 15px 0;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="asigna_select_mob"><i class="fa fa-chevron-down"></i></button>
                            <button type="button" class="btn btn-default" id="designa_select_mob"><i class="fa fa-chevron-up"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-5">
                    <label class="visible-xs">Vendedores a Reasignar:</label>
                    <select multiple="multiple" id="my-vendedores-reasignar" name="my_reasignar[]" class="form-control" size="12" style="height: 300px;">
                        </select>
                </div>
            </div>
        </form>
    </div>

    <div class="panel-footer text-right">
        <!--button type="button" class="btn btn-success btn-lg btn-block-xs" onclick="setCarteraGeneral();">
            <i class="fa fa-save"></i> Guardar Cambios
        </button -->
    </div>
</div>

<div id="inferior">
     <button type="button" class="btn btn-success btn-lg" onclick="setCarteraGeneral();return false;">
            <i class="fa fa-save"></i> Guardar Cambios
        </button>
</div>
<style>
    /* Estilos extra para pulir el diseño */
    .btn-group-vertical > .btn { margin-bottom: 5px; border-radius: 4px !important; }
    .input-group-addon { background-color: #f9f9f9; }
    .mt-10 { margin-top: 10px; }
    @media (max-width: 767px) {
        .btn-block-xs { width: 100%; }
    }
</style>

<script src="assets/js/bootstrap-notify.js"></script>
  <script src="assets/js/pace.min.js"></script>
<script type="text/javascript"  src="resources/js/reasignacion.js"  ></script>

