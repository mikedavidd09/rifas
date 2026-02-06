<link rel="stylesheet" href="assets/css/responsiveTable.css"/>
<div class="card">
    <form id="permisos_form" method="post">
        <div class="row">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-6">
                <label><span class="txRed">*</span>Modulo:</label> <input type="text" value="<?php  echo $nombre?>" name="modulo_nombre" class="form-control" disabled/>
                <table>
                    <caption>Sub Modulo (Habilitar/Desabilitar)  </caption>
                    <thead>
                    <tr>
                        <th scope="col">Modulo</th>
                        <th scope="col">Activo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    if(is_array($modules)){
                        foreach ($modules as $x =>$item){
                            $permisos = explode(',',$item->type);
                            $activo = $item->type != ''? 'checked':'';
                            echo "<tr>
                                <td data-label='Modulos'>$item->label<input type=\"hidden\" value='$item->id_modules' name=\"id_modules_$x\" class=\"form-control\"/><input type=\"hidden\" value='$item->id_permisos' name=\"id_permisos_$x\" class=\"form-control\"/></td>
                                <td data-label='Activo'><div class='toggle'><label><input type=\"checkbox\" name =\"r_$x\" $activo ><span class='button-indecator'></span></label></div></td>
                                </tr>";
                            $count++;
                        }
                    } else {
                        echo "<tr><td colspan=\"2\" >No existen sub modulos</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <input  value="<?php echo $count?>" name="count" class="form-control" type="hidden"/>
            </div>
    </form>
</div>
<div id="contenedor"></div>
<script type="text/javascript"  src="assets/js/bootstrap-notify.js"></script>

<script src="assets/js/pace.min.js"></script>


<div id="inferior" class="botones">
    <a href='index.php?controller=Permisos&action=viewEdit&id_colaborador=<?php echo $id_colaborador?>'  type='button' class=' link btn btn-default'>Cerrar</a><input id='add' href='index.php?controller=Permisos&action=updateSubModules' type='submit' value='Guardar' class='btn btn-success' onclick='validarEnviar("permisos_form" ,"permisos","updateSubModules","");return false;'/>

</div>
