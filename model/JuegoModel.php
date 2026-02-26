<?php
class JuegoModel extends ModeloBase{

    private $table;
    public function __construct($adapter)
    {
        $this->table = 'juegos';
        parent::__construct($this->table, $adapter);
    }

    public function getListadoIndex($query){
        $listado=$this->ejecutarSqlRow($query);
        return $listado;
    }

    public function getJuegos(){
        $sql = "SELECT juegos.nombre, juegos.id_juego,juegos.max FROM juegos order by juegos.id_juego ASC";
        $result  = $this->ejecutarSql($sql);
        return $result;
    }

    public function getJuegoById($id_juego){
        $sql = "SELECT * FROM juegos WHERE id_juego = $id_juego";
        $result  = $this->ejecutarSql($sql);
        return $result;
    }

    public function getSorteos($id_juego){
        $sql = "SELECT s.etiqueta, s.inicio, s.fin, s.id_sorteo FROM sorteos s where s.id_juego = ".$id_juego;
        $result  = $this->ejecutarSql($sql);
        return is_object($result) ? [$result]: $result;
    }

    public function getSorteosDisponibles($id_juego,$now){
        $sql = "SELECT s.etiqueta, s.inicio, s.fin, s.id_sorteo FROM sorteos s where s.id_juego = ".$id_juego." and s.fin >= '$now'";
        $result  = $this->ejecutarSql($sql);
        return is_object($result) ? [$result]: $result;
    }

    public function existeNumeroGanador($id_juego,$id_sorteo,$today){
        $sql = "SELECT id_numero_ganador FROM numeros_ganadores WHERE id_juego = $id_juego AND id_sorteo = $id_sorteo AND fecha = '$today'";
        $result  = $this->ejecutarSql($sql);
        return is_object($result) ? $result->id_numero_ganador : false;
    }

    public function getVendedor($id_colaborador){
        $sql = "SELECT concat(col.nombre,' ',col.apellido) as vendedor FROM colaboradores col WHERE col.id_colaborador = $id_colaborador";
        $result  = $this->ejecutarSql($sql);
        return is_object($result) ? $result->vendedor : false;
    }

}




?>