<?php
class tiposeleccionprestamo extends EntidadBase{
    public $id_tiposelecto;
    public $id_prestamo;
    public $id_tbestado;
	public $id_asignado_old;
	public $id_asignado_new;
    public $saldo;
	public $total_abonado;
    public $fecha_seleccion;
    public $estado;
	public $creado;
	public $autorizado;
    public $fecha_creacion;
    
    public function __construct($adapter){
        $table="tiposeleccionprestamo";
        parent::__construct($table,$adapter);
    }
    public function getIdTipoSelecto()
    {
        return $this->id_tiposelecto;
    }
    public function setIdTipoSelecto($id_tiposelecto)
    {
        $this->id_tiposelecto = $id_tiposelecto;
    }
    public function getIdPrestamo()
    {
        return $this->id_prestamo;
    }
    public function setIdPrestamo($id_prestamo)
    {
        $this->id_prestamo = $id_prestamo;
    }
    public function getIdTbestado()
    {
        return $this->id_tbestado;
    }
    public function setIdTbestado($id_tbestado)
    {
        $this->id_tbestado = $id_tbestado;
    }
    public function getSaldo()
    {
        return $this->saldo;
    }
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    }
	public function getTotalAbonado()
    {
        return $this->total_abonado;
    }
    public function setTotalAbonado($total_abonado)
    {
        $this->total_abonado = $total_abonado;
    }
    public function getFechaSeleccion()
    {
        return $this->fecha_seleccion;
    }
    public function setFechaSeleccion($fecha_seleccion)
    {
        $this->fecha_seleccion = $fecha_seleccion;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function getFechaCracion(){
        return $this->fecha_creacion;
    }
    public function setFechaCracion($fecha_creacion){
        $this->fecha_creacion=$fecha_creacion;
    }
	public function setIdColaboradorAsigando($id_asignado_old){
		$this->id_asignado_old=$id_asignado_old;
	}
	public function setIdColaboradorSaneado($id_asignado_new){
		$this->id_asignado_new=$id_asignado_new;
	}
	public function getIdColaboradorAsigando(){
		return $this->id_asignado_old;
	}
	public function getIdColaboradorSaneado(){
		return $this->id_asignado_new;
	}
	public function getAutorizado(){
		return $this->autorizado;
	}
	public function setAutorizado($autorizado){
		$this->autorizado=$autorizado;
	}
	 public function getSaneadoPor()
    {
        return $this->creado;
    }
    public function setSaneadoPor($creado)
    {
        $this->creado = $creado;
    }
	
}
?>
