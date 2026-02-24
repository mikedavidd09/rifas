<?php
class Numero extends EntidadBase{
    public $id_numero;
    public $numero;
    public $monto;
    public $premio;
    public $id_venta;

    public function __construct($adapter)
    {
        $table = "numeros";
        parent::__construct($table, $adapter);
    }

    public function getIdNumero(){
        return $this->id_numero;
    }

    public function setIdNumero($id_numero){
        $this->id_numero = $id_numero;
    }

    public function getNumero(){
        return $this->numero;
    }

    public function setNumero($numero){
        $this->numero = $numero;
    }

    public function getMonto(){
        return $this->monto;
    }

    public function setMonto($monto){
        $this->monto = $monto;
    }
    function getPremio()
    {
        return $this->premio;
    }
    function setPremio($premio)
    {
        $this->premio = $premio;
    }
    public function getIdVenta(){
        return $this->id_venta;
    }

    public function setIdVenta($id_venta){
        $this->id_venta = $id_venta;
    }
    

}

?>