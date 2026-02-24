<?php
class numeroGanador extends EntidadBase{
    public $id_numero_ganador;
    public $fecha;
    public $numero;
    public $id_sorteo;
    public $id_juego;

    public function __construct($adapter)
    {
        $table = "numeros_ganadores";
        parent::__construct($table, $adapter);
    }  

    public function getIdNumeroGanador(){
        return $this->id_numero_ganador;
    }

    public function setIdNumeroGanador($id_numero_ganador){
        $this->id_numero_ganador = $id_numero_ganador;
    }

    public function getFecha(){
        return $this->fecha;
    }

    public function setFecha($fecha){
        $this->fecha = $fecha;
    }

    public function getNumero(){
        return $this->numero;
    }

    public function setNumero($numero){
        $this->numero = $numero;
    }

    public function getIdSorteo(){
        return $this->id_sorteo;
    }

    public function setIdSorteo($id_sorteo){
        $this->id_sorteo = $id_sorteo;
    }

    public function getIdJuego(){
        return $this->id_juego;
    }

    public function setIdJuego($id_juego){
        $this->id_juego = $id_juego;
    }

    public function setAllNone(){
        $vars = get_object_vars($this);
        foreach($vars as $key => $value){
            $this->$key = 'none';
        }
    }
}
?>