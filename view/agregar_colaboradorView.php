<div class="card">
    <h3>Agregar Colaborador</h3>
    <hr/>
    <form enctype="multipart/form-data" id="colaborador_form" method="POST"> 
        <div class="row">
            <div class="col-lg-4">
			
                <label> Nombre: </label> <span class="txtname"></span><INPUT type="text" name="nombre" class="form-control"/>
                <label> Apellido:</label> <INPUT type="text" name="apellido" class="form-control "/>
                <label> Sexo:</label>
                <div id="div_btn_radio" class="div_btn_button">
                    <label class="radio-inline"><input type="radio" value="M"  name="sexo">Masculino</label>
                    <label class="radio-inline"><input type="radio" value="F" name="sexo">Femenino</label>
                </div>
                <label><span class="txRed">*</span>Cedula:</label>   <input type="text" placeholder="000-000000-0000A"  id="cedula" name="cedula" size="15" minlength="15" maxlength="15" class="numero form-control"/>
                <label><span class="txRed">*</span>Telefono:</label> <input type="tel" placeholder="0000-0000" id="telefono" name="telefono" class="numero form-control"/>
                <label><span class="txRed">*</span>Direccion:</label><textarea class="form-control" name="direccion" rows="4" id="direccion"></textarea>
            </div>
            <div class="col-lg-4">
                <label>Nombre Usuario:</label> <INPUT type="text" name="usuario" class="form-control">
                <label>Contraseña: </label> <INPUT type="password" name="password" class="form-control">
            
                <label> Cargo:</label>
                <SELECT name="id_role" class=" form-control">
                   <option value="0">-Seleccione-</option>
                    <?php
                   
                    foreach ($roles as $role) {
                        echo "<OPTION VALUE=\"".$role->id_role."\">".$role->nombre."</OPTION>";
                    }
                    ?>

                </SELECT>
               
                </SELECT>
              
                <label> Fecha Ingreso: </label>
                <div class='input-group date'>
                    <input id="fecha_ingreso" name="fecha_ingreso" class="datepicker form-control"   type="text" autofocus  >
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                </div>
                <br>
            </div>
            <div class="col-lg-4">
                <label>Foto Colaborador: </label><input class="file-input " name="imagen" id="imagen" accept="image/*"  type="file" />
              <img id="img_colaborador" src="assets/img/user.png" width="73%" height="73%" class="img-thumbnail" /><br>
            </div>
        </div>
    </form>
</div>
<div id="contenedor"></div>

<div id="inferior">
    <input href="javascript:;" type="submit" value="Guardar" class="btn btn-success" onclick="validarEnviar('colaborador_form','Colaborador','crear');return false;"/>
</div>
<script src="assets/js/bootstrap-notify.js"></script>
<script type="text/javascript"  src="resources/js/colaborador.js"></script>
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

