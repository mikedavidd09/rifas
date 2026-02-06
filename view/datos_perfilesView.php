<link rel="stylesheet" href="assets/css/responsiveTable.css"/>
<style></style>
<?php
echo "<script type='text/javascript'>
    checkedRadioButton('$perfil','perfil');
</script>";
?>
<div class="card">
    <h3>Perfiles de usuarios</h3>
    <hr>
    <form id="perfil_form" method="post">
        <div class="box">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4 ">
                        <!--img class="card-img-top" src="assets/img-avisos/admin.png" alt="Card image cap" width="300" height="200"-->
                        <div class="card-body">
                            <h5 class="card-title">Administrador</h5>
                            <p class="card-text">Permisos Asociados al administrador acceso total.</p>
                            <input type="radio" id="perfil" name="perfil" value="Administrador">
                        </div>
                    </div>
				
                </div>
				<div class="col-md-4">
                    <div class="card mb-4 ">
                        <!--img class="card-img-top" src="assets/img-avisos/admin.png" alt="Card image cap" width="300" height="200"-->
                        <div class="card-body">
                            <h5 class="card-title">Gerente</h5>
                            <p class="card-text">Permisos Asociados al administrador acceso total.</p>
                            <input type="radio" id="perfil" name="perfil" value="Gerente"> 

                            

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 border-dark">
                        <!--img class="card-img-top" src="assets/img-avisos/supervisor.png" alt="Card image cap" width="300" height="200"-->
                        <div class="card-body">
                            <h5 class="card-title">Supervisor</h5>
                            <p class="card-text">Permisos avanzados acceso parcial a la applicacion.</p>
                            <input type="radio" name="perfil" value="Supervisor"> 

                        </div>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="card mb-4 text-white bg-dark">
                        <!--img class="card-img-top" src="assets/img-avisos/analista.png" alt="Card image cap" width="300" height="200"-->
                        <div class="card-body">
                            <h5 class="card-title">Analista</h5>
                            <p class="card-text">Permisos asociados al cobro de los prestamos del sistema.</p>
                            <input type="radio" name="perfil" value="Analista"> 

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 text-white bg-dark">
                        <!--img class="card-img-top" src="assets/img-avisos/operativo.png" alt="Card image cap" width="300" height="200"-->
                        <div class="card-body">
                            <h5 class="card-title">Operativo</h5>
                            <p class="card-text">Permisos asociados al desembolso de los prestamos del sistema.</p>
                            <input type="radio" name="perfil" value="Operativo"> 


                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 text-white bg-dark">
                        <!--img class="card-img-top" src="assets/img-avisos/operativo.png" alt="Card image cap" width="300" height="200"-->
                        <div class="card-body">
                            <h5 class="card-title">Saneado</h5>
                            <p class="card-text">Permisos asociados a Carteras Saneadas.</p>
                            <input type="radio" name="perfil" value="Saneado">


                        </div>
                    </div>
                </div>
            </div>
        </div>

            <input  value="<?php echo $user[0]->id_user?>" id="codigo" name="id_user" class="form-control" type="hidden"/>
            <input  value="<?php echo $id_perfil?>" id="codigo" name="id_perfil" class="form-control" type="hidden"/>
            <input  value="<?php echo $count?>" name="count" class="form-control" type="hidden"/>
    </form>
</div>
<div id="contenedor"></div>
<script type="text/javascript"  src="assets/js/bootstrap-notify.js"></script>

<script src="assets/js/pace.min.js"></script>
<div id="inferior" class="botones"><a href="index.php?controller=permisos&action=index" type="button" class=" link btn btn-default">Cerrar</a>
    <input id="add" href="controller=permisos&action=updatePerfil" type="submit" value="Actualizar" class="btn btn-success" onclick="validarEnviar('perfil_form','permisos','updatePerfil',<?php echo $user[0]->id_user?>);return false;">
    <input id="delete" href="controller=permisos&action=deletePerfil" type="submit" value="Borrar" class="btn btn-danger" onclick="realizarBorrado('perfiles',<?php echo $user[0]->id_user?>);return false;">
</div>
<div id="inferior" class="botones">

</div>