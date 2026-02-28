<?php 
class SorteoModel extends ModeloBase{
    private $table;
    public function __construct($adapter)
    {
        $this->table = 'sorteos';
        parent::__construct($this->table, $adapter);
    }

    public function getSorteo($id_juego,$hora){
        $query = "SELECT id_sorteo, etiqueta FROM sorteos WHERE id_juego = " . $id_juego . " AND '" . $hora . "' BETWEEN inicio AND fin";
        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:NULL;
    }

    public function getSorteoIds($id_juego,$ids){
        $query = "SELECT id_sorteo, etiqueta FROM sorteos WHERE id_juego = " . $id_juego . " AND id_sorteo IN (" . implode(',',$ids) . ")";
        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;
    }   
}

?>