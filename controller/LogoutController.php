<?php
class LogoutController extends ControladorBase{
    public $conectar;
    public $adapter;
    public function __construct(){
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }
   public function salir(){

       $login=$_SESSION["Login_View"];
       $obj =  new ModeloBase('sessions',$this->adapter);

       $obj->borrarRegistroSession($login->id_user);

       date_default_timezone_set("america/managua");
							$user =$login->usuario;
							$accion="Cerrar Session";
							$hora= date ("h:i:s");
							$fecha= date ("d/m/Y");
							$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Estado: Session Finalizada | \n";
							$fichero = fopen('logfile.txt', 'a+');
							
							fwrite($fichero, $string);						
							fclose($fichero);
	   
	   
     $this->DestruirSession();
     $this->redirect();
 }
}
?>
