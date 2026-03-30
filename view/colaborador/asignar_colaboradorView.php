<link href="resources/css/dataTableFiltrerByfield.css" rel="stylesheet"/>

<script type='text/javascript'>

 getOptionCombo('Sucursal', 'dataByComboSucursal2', 'id_sucursal', 'id_sucursal', 'sucursal', '', '');
getOptionCombo('Sucursal', 'dataByComboSucursal2', 'id_sucursal2', 'id_sucursal', 'sucursal', '', '');

 function setCarteraGeneral(){

$("#my-asignacion option").each(function(){

    $(this).attr("selected","selected");

  });

  validarEnviar('asignacion_form','colaborador','Asignacion','');

}

</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-users"></i> Asignación de Vendedores a Supervisor</h3>
    </div>
    
    <div class="panel-body">
        <form enctype="multipart/form-data" id="asignacion_form" method="POST"> 
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="Vendedores"><i class="fa fa-list"></i> Vendedores Disponibles:</label>
                        <select multiple="multiple" id="Vendedores" name="Vendedores[]" class="form-control" size="15"> 
                            <?php foreach($colaborador as $nombre): ?>
                                <?php if($nombre->cargo == "vendedor" && $nombre->id_parent == null): ?>
                                    <option value="<?=$nombre->id_colaborador;?>">
                                        <?=$nombre->nombre." ".$nombre->apellido;?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 text-center">
                    <div style="margin-top: 60px;">
                        <div class="btn-group-vertical">
                            <button type="button" class="btn btn-default" id="asigna_select" title="Asignar seleccionado">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                            <button type="button" class="btn btn-primary" id="asigna_todos" title="Asignar todos">
                                <i class="fa fa-angle-double-right"></i>
                            </button>
                            <hr />
                            <button type="button" class="btn btn-default" id="designa_select" title="Quitar seleccionado">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button type="button" class="btn btn-danger" id="designa_todos" title="Quitar todos">
                                <i class="fa fa-angle-double-left"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="Asignacion_super"><i class="fa fa-user-tie"></i> Seleccionar Supervisor:</label>
                        <select id="id_colaborador_super" name="id_colaborador_super" class="form-control">
                            <option value="0">-- Seleccione un Supervisor --</option>
                            <?php foreach($colaborador as $nombre): ?>
                                <?php if($nombre->cargo == "super"): ?>
                                    <option value="<?=$nombre->id_colaborador;?>">
                                        <?=$nombre->nombre." ".$nombre->apellido;?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="my-asignacion"><i class="fa fa-check-square"></i> Vendedores Asignados:</label>
                        <select multiple="multiple" id="my-asignacion" name="myasignature[]" class="form-control" size="11">
                            </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--div class="panel-footer text-right">
        <button type="button" class="btn btn-success btn-lg" onclick="validarEnviar('colaborador_form','Colaborador','crear');">
            <i class="fa fa-save"></i> Guardar Asignación
        </button>
    </div -->
</div>

<div id="inferior">
     <button type="button" class="btn btn-success btn-lg" onclick="setCarteraGeneral();return false;">
            <i class="fa fa-save"></i> Guardar Asignación
        </button>
</div>
<script src="assets/js/bootstrap-notify.js"></script>
<script type="text/javascript"  src="resources/js/asignar.js"></script>
<script src="assets/js/pace.min.js"></script>
<script type="text/javascript">
    var $input = $( '.datepicker' ).pickadate({
        format: 'dd-mmmm-yyyy',
        formatSubmit: 'yyyy/mm/dd',
        // min: [2015, 7, 14],
        container: '#contenedor',
        //editable: true,
        closeOnSelect: false,
        closeOnClear: false,
    })
    var picker = $input.pickadate('picker')
    // picker.set('select', '14 October, 2014')
    // picker.open()

    // $('button').on('click', function() {
    //     picker.set('disable', true);
    // });
</script>

