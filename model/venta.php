<?php
class Venta extends EntidadBase{
    public $id_venta;
    public $consecutivo;
    public $nombre;
    public $fecha;
    public $hora;
    public $id_sorteo;
    public $id_colaborador;
    public $id_juego;
    public $total;
    public $estado;
    public function __construct($adapter){
        $table="ventas";
        parent::__construct($table,$adapter);
    }

    function getIdVenta()
    {
        return $this->id_venta;
    }
    function setIdVenta($id_venta)
    {
        $this->id_venta = $id_venta;
    }
    function getConsecutivo()
    {
        return $this->consecutivo;
    }
    function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;
    }
    function getNombre()
    {
        return $this->nombre;
    }
    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function getFecha()
    {
        return $this->fecha;
    }
    function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    function getHora()
    {
        return $this->hora;
    }
    function setHora($hora)
    {
        $this->hora = $hora;
    }
    function getIdColaborador()
    {
        return $this->id_colaborador;
    }
    function setIdColaborador($id_colaborador)
    {
        $this->id_colaborador = $id_colaborador;
    }
    function getIdJuego()
    {
        return $this->id_juego;
    }
    function setIdJuego($id_juego)
    {
        $this->id_juego = $id_juego;
    }
    function getTotal()
    {
        return $this->total;
    }
    function setTotal($total)
    {
        $this->total = $total;
    }
    function getIdSorteo()
    {
        return $this->id_sorteo;
    }
    function setIdSorteo($id_sorteo)
    {
        $this->id_sorteo = $id_sorteo;
    }

    public function getEstado(){
        return $this->estado;
    }
    public function setEstado($estado){
        $this->estado = $estado;
    }
}