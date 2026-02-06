<?php
class Colaborador extends EntidadBase{
    public $id_colaborador;
    public $codigo;
    public $nombre;
    public $apellido;
    public $sexo;
    public $cedula;
    public $direccion;
    public $telefono;
    public $id_sucursal;
    public $fecha_ingreso;
    public $fecha_egreso;
    public $estado; 
    public function __construct($adapter){
        $table="colaboradores";
        parent::__construct($table,$adapter);
    }
    public function getIdColaborador()
    {
        return $this->id_colaborador;
    }
    public function setIdColaborador($id_colaborador)
    {
        $this->id_colaborador = $id_colaborador;
    }
    public function getFechaIngreso()
    {
        return $this->fecha_ingreso;
    }
    public function setFechaIngreso($fecha_ingreso)
    {
        $this->fecha_ingreso = $fecha_ingreso;
    }

    public function getFechaEgreso()
    {
        return $this->fecha_egreso;
    }
    public function setFechaEgreso($fecha_egreso)
    {
        $this->fecha_egreso = $fecha_egreso;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function getSexo()
    {
        return $this->sexo;
    }
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }
 
    public function getCedula()
    {
        return $this->cedula;
    }
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function setIdSucursal($id_sucursal)
    {
        $this->id_sucursal = $id_sucursal;
    }
    public function getIdSucursal()
    {
        return $this->id_sucursal;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function generarCodeColaborador($nombre,$apellido,$id){
        $codigo = "AC_".substr($nombre,0,1).substr($apellido,0,1).$id;
        return $codigo;
    }
}
?>
