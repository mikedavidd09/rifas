<?php
class RolesModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="roles";
        parent::__construct($this->table,$adapter);
    }
    public function getRoles($where){
        $query="SELECT * FROM $this->table $where";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }

    public function getComisiones(){
        $query="SELECT * FROM comisiones";
        $result=$this->ejecutarSql($query);
        return is_object($result)? [$result]:$result;
    }

}
?>