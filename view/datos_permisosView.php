<link rel="stylesheet" href="assets/css/responsiveTable.css"/>
<div class="card">
    <form id="cliente_form" method="post">
        <div class="row">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-6">
                <label><span class="txRed">*</span>Usuario:</label> <input type="text" value="<?php  echo $user[0]->usuario?>" name="usuario" class="form-control" disabled/>
                <table>
                    <caption>Permisos Por Modulo  </caption>
                    <thead>
                    <tr>
                        <th scope="col">Modulo</th>
                        <th scope="col">Activo</th>
                        <th scope="col">Crear</th>
                        <th scope="col">Actualizar</th>
                        <th scope="col">Borrar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    if(is_array($modules)){
                        foreach ($modules as $x =>$item){
                            $permisos = explode(',',$item->permisos);
                            $c = $permisos[0]? 'checked':'';
                            $r = $permisos[1]? 'checked':'';
                            $u = $permisos[2]? 'checked':'';
                            $d = $permisos[3]? 'checked':'';
                            echo "<tr>
                                <td data-label='Modulos'><a class='link' href='index.php?controller=Permisos&action=viewEditSubModulo&id=$item->id_modules&nombre=$item->label&id_colaborador=$id_colaborador'>$item->label</a><input type=\"hidden\" value='$item->id_modules' name=\"id_modules_$x\" class=\"form-control\"/><input type=\"hidden\" value='$item->id_permisos' name=\"id_permisos_$x\" class=\"form-control\"/></td>
                                <td data-label='Activo'><div class='toggle'><label><input type=\"checkbox\" name =\"r_$x\" $r ><span class='button-indecator'></span></label></div></td>
                                <td data-label='Crear'><div class='toggle'><label><input type=\"checkbox\" name =\"c_$x\" $c ><span class='button-indecator'></span></label></div></td>
                                <td data-label='Actualizar'><div class='toggle'><label><input type=\"checkbox\" name =\"u_$x\" $u ><span class='button-indecator'></span></label></div></td>
                                <td data-label='Borrar'><div class='toggle'><label><input  type=\"checkbox\" name =\"d_$x\" $d ><span class='button-indecator'></span></label></div></td>
                            </tr>";
                            $count++;
                        }
                    } else {
                        echo "<tr><td colspan=\"5\" >Este usuario no se le an creado permisos presione en nuevo para agregar</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        <div class="col-lg-6">
            <input  value="<?php echo $user[0]->id_user?>" id="codigo" name="id_user" class="form-control" type="hidden"/>
            <input  value="<?php echo $count?>" name="count" class="form-control" type="hidden"/>
        </div>
    </form>
</div>
<div id="contenedor"></div>
<script type="text/javascript"  src="assets/js/bootstrap-notify.js"></script>

<script src="assets/js/pace.min.js"></script>
<?php
require_once 'core/Utils.php';
$test = new Utils();
$option = $test->getButtonOptions('permisos','1,1,1,1','edit');
?>
<div id="inferior" class="botones">

</div>