<?php
class BuscadordinamicoModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="clientes";
        parent::__construct($this->table,$adapter);
    }
    public function executeQuery($query){
        $result=$this->ejecutarSql($query);
        return is_array($result)?$result:[$result];
    }
    public function executeQueryTotal($query){
        $result=$this->ejecutarSql($query);
        return $result;
    }
    public function getdatosFromTable($tabla,$start,$end){
        $query="SELECT * FROM $tabla limit $start,$end";
        $result=$this->ejecutarSql($query);
        return is_array($result)?$result:[$result];
    }
    public function getTotalFromTable($tabla){
        $query="SELECT count(*) as total FROM $tabla";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function getTablas(){
        $query="SHOW TABLES";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    public function getCampos($tabla){
        $query="SHOW COLUMNS FROM $tabla";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    public function getDatos($tabla,$campos){
        $query="SELECT $campos FROM $tabla";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    public function getDatosByCampo($tabla,$campo,$valor){
        $query="SELECT * FROM $tabla WHERE $campo='$valor'";
        $result=$this->ejecutarSql($query);
        return $result;
    }   

    public function getdatosFromTableFilter($tabla,$start,$perPage,$buscador,$filter){
        $query="SELECT * FROM $tabla WHERE $filter like '%$buscador%' limit $start,$perPage";
        $result=$this->ejecutarSql($query);
        return is_array($result)?$result:[$result];
    }
    public function getotalFromTableFilter($tabla,$buscador,$filter){
        $query="SELECT count(*) as total FROM $tabla WHERE $filter like '%$buscador%'";
        $result=$this->ejecutarSql($query);
        return $result;
    }

     public function updateRecord($tabla, $data, $id_field, $id_value)
    {
        $set = "";
        foreach ($data as $key => $value) {
            if ($key == array_key_first($data)) {
                $set .= $key . "='" . $value . "'";
            } else {
                $set .= ", " . $key . "='" . $value . "'";
            }
         
        }
            
        $where = $id_field . "='" . $id_value . "'";
        $query = "UPDATE $tabla SET $set WHERE $where";
      
        $result = $this->ejecutarSqlUpdate($query);
        return $result;
    }





}