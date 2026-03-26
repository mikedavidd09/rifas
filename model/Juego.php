<?php
class Juego extends EntidadBase{
    public $id_juego;
    public $nombre;
    public $imagen;
    public $min;
    public $max;
    public $factor;
    public $monto_limite;

        public function __construct($adapter){
        $table="juegos";
        parent::__construct($table,$adapter);
    }

    public function getIdJuego(){
        return $this->id_juego;
    }
    public function setIdJuego($id_juego){
        $this->id_juego=$id_juego;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre=$nombre;
    }
    public function getImagen(){
        return $this->imagen;
    }
    public function setImagen($imagen){
        $this->imagen=$imagen;
    }
    public function getMin(){
        return $this->min;
    }
    public function setMin($min){
        $this->min=$min;
    }
    public function getMax(){
        return $this->max;
    }
    public function setMax($max){
        $this->max=$max;
    }
    public function getFactor(){
        return $this->factor;
    }
    public function setFactor($factor){
        $this->factor=$factor;
    }
    public function getMontoLimite(){
        return $this->monto_limite;
    }
    public function setMontoLimite($monto_limite){
        $this->monto_limite=$monto_limite;
    }

    public function setAllNone(){
        $vars = get_object_vars($this);
        foreach($vars as $key => $value){
            $this->$key = 'none';
        }
    }
}

?>


