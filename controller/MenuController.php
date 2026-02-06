<?php
class MenuController extends ControladorBase{
    public $conectar;
    public $adapter;
    public function __construct(){
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }
    public function index(){
       
        if(isset($_SESSION["Login_View"])){
            $countSolic = new PrestamoModel($this->adapter);
            $count = empty($countSolic->getCantidadPrestamoSolicitud()) ? 0:$countSolic->getCantidadPrestamoSolicitud();
            $this->view("index2",array("Solicitudes"=>$count));
        }
        //$this->vie    w("index2",[]);


    }
    public function indexSudo(){
        if(isset($_SESSION["Login_View"])){
            $this->view("indexSudo",array());
        }
        //$this->vie    w("index2",[]);

    }
    public function indexAdmin(){
        if(isset($_SESSION["Login_View"])){
            $this->view("indexAdmin",array());
        }
        //$this->vie    w("index2",[]);
    }
    public function indexSuper(){
        if(isset($_SESSION["Login_View"])){
            $this->view("indexSuper",array());
        }
        //$this->vie    w("index2",[]);
    }
    public function indexVendedor(){
        if(isset($_SESSION["Login_View"])){
            $this->view("indexVendedor",array());
        }
        //$this->vie    w("index2",[]);
    }

    public function ModulosPermisos(){
       if(isset($_SESSION["Login_View"])){
            $this->view("home",array());
        }
    }
}
?>
