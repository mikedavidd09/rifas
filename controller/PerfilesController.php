<?php
class PerfilesController extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $url ;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }
    public function delete()
    {
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $perfil = new Perfiles($this->adapter);
            $delete = $perfil->deleteById($id, 'usuario');
            if ($delete==1) {
                $obj_u = new UsuarioModel($this->adapter);
                $obj_u->updateTableById($id,'perfil','id_user',0);

                $resp = array(
                    'respuesta' => 'true',
                    'data' => '',
                    'mensaje' => 'Exito, se perfil fue Borrado correctamente.'
                );
                $json = json_encode($resp);

                echo ($json);
            } else {
                $resp = array(
                    'respuesta' => 'false',
                    'data' => '',
                    'mensaje' => 'Error, NO se pudo borrar el perfil para este usuario.'
                );
                $json = json_encode($resp);

                echo ($json);            }
        }
    }

}
?>
