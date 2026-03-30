<?php

class NumeroGanadorModel extends ModeloBase{

    private $table;
    public function __construct($adapter)
    {
        $this->table = 'numeros_ganadores';
        parent::__construct($this->table, $adapter);
    }

    public function getListadoIndex($query){
        $listado=$this->ejecutarSqlRow($query);
        return $listado;
    }

    public function existeNumeroGanador($id_numero_ganador){
        $sql = "SELECT EXISTS(SELECT 1 FROM numeros_ganadores WHERE id_numero_ganador = $id_numero_ganador) as existe";
        $result  = $this->ejecutarSql($sql);
        return $result->existe ? true : false;
    }
}
?>