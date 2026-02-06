<?php
class MensajesController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    public function redactar(){
        $this->view("mensajes", array());
    }

    public function enviar(){
        $titulo = $_POST['titulo'];
        $mensaje= $_POST['mensaje'];
        $imagen= $_FILES['file-input'];
        $nombre_imagen=$this->SubirImagenRuta($imagen, 'img-avisos');
        $redireccionar = "NO";
        $url = "vacio";

        $data = ['imagen'=>'assets/img/img-avisos/'.$nombre_imagen,'mensaje'=>$mensaje,'redireccionar'=>$redireccionar,'titulo'=>$titulo,'url'=>$url];
        $data = json_encode($data);
        $url  = "https://wolfDb-d7e4e.firebaseio.com/test/avisos/datos.json";
        $ch   = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:text/plain'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        $response = curl_exec($ch);

        echo true;
        

    }
  
}
?>
