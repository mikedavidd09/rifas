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
        $sql = "SELECT juegos.nombre, juegos.id_juego,juegos.max, juegos.monto_limite FROM juegos order by juegos.id_juego ASC";
        $result  = $this->ejecutarSql($sql);
        return $result;
    }

    public function getJuegoById($id_juego){
        $sql = "SELECT * FROM juegos WHERE id_juego = $id_juego";
        $result  = $this->ejecutarSql($sql);
        return $result;
    }

    public function getSorteos($id_juego){
        $sql = "SELECT s.etiqueta, s.inicio, s.fin, s.id_sorteo FROM sorteos s where s.id_juego = $id_juego";
        $result  = $this->ejecutarSql($sql);
        return is_object($result) ? [$result]: $result;
    }

    public function getNumerosGanadores($id_juego,$fecha){
        $sql = "SELECT s.etiqueta, s.inicio, s.fin, s.id_sorteo, ng.numero,ng.id_numero_ganador FROM sorteos s left join numeros_ganadores ng on s.id_sorteo = ng.id_sorteo and ng.fecha = '$fecha' where s.id_juego = $id_juego ";
        $result  = $this->ejecutarSql($sql);
        return is_object($result) ? [$result]: $result;
    }

    public function getAllNumerosGanadores($fecha){
        $sql = "SELECT j.nombre, s.etiqueta, ng.numero,ng.id_numero_ganador
                from juegos j inner join sorteos s on j.id_juego = s.id_juego
                inner join numeros_ganadores ng on s.id_sorteo = ng.id_sorteo and ng.fecha ='$fecha'
                and j.id_juego = ng.id_juego
                order by j.nombre, s.etiqueta
                ";
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
        $sql = "SELECT concat(col.nombre,' ',col.apellido)as nombre , col.telefono as telefono, col.direccion as direccion FROM colaboradores col WHERE col.id_colaborador = $id_colaborador";
        $result  = $this->ejecutarSql($sql);
        return is_object($result) ? $result : false;
    }

    public function getMontoLimite($id_juego){
        $query = "SELECT j.monto_limite FROM juegos j WHERE j.id_juego = $id_juego";
        $obj=$this->ejecutarSql($query);
        return $obj->monto_limite;
    }

}




?>