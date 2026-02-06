<?php
class JuegoModel extends ModeloBase{

    private $table;
    public function __construct($adapter)
    {
        $this->table = 'juegos';
        parent::__construct($this->table, $adapter);
    }

    public function getJuegos($where){
        $sql = "SELECT * FROM juegos WHERE id_juego <> 1 ";
        if(isset($where) && $where != ''){
            $sql .= " AND ".$where;
        }
        $sql .= " ORDER BY id_juego ASC";
     
    }

}




?>