<?php
class Clientes extends EntidadBase{
    public $id_cliente;
    public $codigo_cliente;
    public $nombre;
    public $apellido;
	public $estado_civil;//NUEVA
	public $condicion_vivienda;//NUEVA
    public $sexo;
    public $cedula;
    public $localidad;
    public $direccion;
	public $direccion_negocio;//NUEVA
	public $personas_a_cargo; //NUEVA
    public $telefono;
	public $telefono_secundario;//NUEVA
	public $tipo_negocio;
	public $tiempo_en_actividad;//NUEVA
	public $img_cedula_frente;//CAMBIAR
    public $img_cedula_reversa;//NUEVA
	public $foto_vivienda; //NUEVA
    public $id_colaborador;
	public $ventas_maxima_d;//NUEVA//NUEVA
	public $ventas_normales_d;//NUEVA
	public $ventas_baja_d;//NUEVA
	public $capital_max;
	public $fecha_creacion;//NUEVA
	
    public function __construct($adapter){
        $table="clientes";
        parent::__construct($table,$adapter);
    }

    public function getId(){
        return $this->id_cliente;
    }
    public function setId($id){
        $this->id_cliente=$id;
    }
    public function getCodigoCliente(){
        return $this->codigo_cliente;
    }
    public function setCodigoCliente($id){
        $this->codigo_cliente=$id;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function setApellido($apellido){
        $this->apellido = $apellido;
    }
	public function getEstadoCivil(){
        return $this->estado_civil;
    }
    public function setEstadoCivil($estado_civil){
        $this->estado_civil = $estado_civil;
    }
	public function getCondicionDeVivienda(){
        return $this->condicion_vivienda;
    }
    public function setCondicionDeVivienda($condicion_vivienda){
        $this->condicion_vivienda = $condicion_vivienda;
    }
	public function getPersonasACargo(){
        return $this->personas_a_cargo;
    }
    public function setPersonasACargo($personas_a_cargo){
        $this->personas_a_cargo = $personas_a_cargo;
    }
    public function getSexo(){
        return $this->sexo;
    }
    public function setSexo($sexo){
        $this->sexo=$sexo;
    }
    public function getCedula(){
        return $this->cedula;
    }
    public function setCedula($cedula){
        $this->cedula=$cedula;
    }

    public function getlocalidad(){
        return $this->localidad;
    }
    public function setlocalidad($localidad){
        $this->localidad=$localidad;
    }


    public function getDireccion(){
        return $this->direccion;
    }
    public function setDireccion($direccion){
        $this->direccion=$direccion;
    }
	public function getDireccionNegocio(){
        return $this->direccion_negocio;
    }
    public function setDireccionNegocio($direccion_negocio){
        $this->direccion_negocio=$direccion_negocio;
    }
    public function getTelefono(){
        return $this->telefono;
    }
    public function setTelefono($telefono){
        $this->telefono=$telefono;
    }
	 public function getTelefonoSecundario(){
        return $this->telefono_secundario;
    }
    public function setTelefonoSecundario($telefono_secundario){
        $this->telefono_secundario=$telefono_secundario;
    }
    public function getTipoNegocio(){
        return $this->tipo_negocio;
    }
    public function setTipoNegocio($tipo_negocio){
        $this->tipo_negocio=$tipo_negocio;
    }
	public function getVentasMaximaD(){
        return $this->ventas_maxima_d;
    }
    public function setVentasMaximaD($ventas_maxima_d){
        $this->ventas_maxima_d=$ventas_maxima_d;
    }
	public function getVentasNormalesD(){
        return $this->ventas_normales_d;
    }
    public function setVentasNormalesD($ventas_normales_d){
        $this->ventas_normales_d=$ventas_normales_d;
    }
	public function getVentasBajasD(){
        return $this->ventas_baja_d;
    }
    public function setVentasBajasD($ventas_baja_d){
        $this->ventas_baja_d=$ventas_baja_d;
    }
	public function getTiempoEnActividad(){
        return $this->tiempo_en_actividad;
    }
    public function setTiempoEnActividad($tiempo_en_actividad){
        $this->tiempo_en_actividad=$tiempo_en_actividad;
    }
    public function getIdEmpleado(){
        return $this->id_colaborador;
    }
    public function setIdEmpleado($id_colaborador){
        $this->id_colaborador=$id_colaborador;
    }
    public function getIdSucursal(){
        return $this->id_sucursal;
    }
    public function setIdSucursal($id_sucursal){
        $this->id_sucursal=$id_sucursal;
    }
    public function getImagenClienteCedulaFrente(){
        return $this->img_cedula_frente;
    }
    public function setImagenClienteCedulaFrente($img_cedula_frente){
        $this->img_cedula_frente=$img_cedula_frente;
    }
	public function getImagenClienteCedulaReversa(){
        return $this->img_cedula_reversa;
    }
    public function setImagenClienteCedulaReversa($img_cedula_reversa){
        $this->img_cedula_reversa=$img_cedula_reversa;
    }
	public function getFotoVivienda(){
        return $this->foto_vivienda;
    }
    public function setFotoVivienda($foto_vivienda){
        $this->foto_vivienda=$foto_vivienda;
    }
	public function getNombreConyugue(){
        return $this->nombre_conyugue;
    }
    public function setNombreConyugue($nombre_conyugue){
        $this->nombre_conyugue=$nombre_conyugue;
    }
    public function getCedulaConyugue(){
        return $this->cedula_conyugue;
    }
    public function setCedulaConyugue($cedula_conyugue){
        $this->cedula_conyugue=$cedula_conyugue;
    }
	public function getTelefonoConyugue(){
        return $this->telefono_conyugue;
    }
    public function setTelefonoConyugue($telefono_conyugue){
        $this->telefono_conyugue=$telefono_conyugue;
    }
	public function getCapitalMax(){
        return $this->capital_max;
    }
    public function setCapitalMax($capital_max){
        $this->capital_max=$capital_max;
    }
	public function getFechaCracion(){
        return $this->fecha_creacion;
    }
    public function setFechaCracion($fecha_creacion){
        $this->fecha_creacion=$fecha_creacion;
    }
    public function generarCodeCliente($nombre,$apellido,$id){
        $codigo = "C_".substr($nombre,0,1).substr($apellido,0,1).$id;
        return $codigo;
    }
    public function getEdadBycedula($cedula){
        $edad = ((int)('19'.substr($cedula,8,2)))-date('y');
        return $edad;
    }

    public function save(){
        $query="INSERT INTO clientes (id_cliente,codigo_cliente,nombre,apellido,edad,sexo,cedula,direccion,tipo_negocio,departamento,id_empl,id_sucursal) VALUES (NULL,'$this->codigo_cliente','$this->nombre','$this->apellido','$this->edad','$this->sexo','$this->cedula','$this->direccion','$this->tipo_negocio','$this->departamento','$this->id_emp','$this->id_sucursal')";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
}
?>
