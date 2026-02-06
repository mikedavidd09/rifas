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


}
?>