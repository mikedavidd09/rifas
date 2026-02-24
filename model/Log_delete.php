<?php
class Log_delete extends EntidadBase{
    public $id;
    public $fecha;
    public $table_name;
    public $id_record;
    public $id_user;

    public function __construct($adapter){
        $table="logs_delete";
        parent::__construct($table,$adapter);
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this;   
    }
    public function getFecha() {
        return $this->fecha;   
    }
    public function setFecha($fecha) {
        $this->fecha = $fecha;
        return $this;
    }
    public function getTableName() {
        return $this->table_name;   
    }
    public function setTableName($table_name) {
        $this->table_name = $table_name;
        return $this;
    }
    public function getIdRecord() {
        return $this->id_record;
    }
    public function setIdRecord($id_record) {
        $this->id_record = $id_record;
    }
    public function getIdUser() {
        return $this->id_user;
    }
    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }
}
?>
