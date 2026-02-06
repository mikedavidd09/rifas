<?php
class Sucursal extends EntidadBase{
 	public $id_sucursal;
    public $sucursal;
 
    public function __construct($adapter){
        $table="sucursales";
        parent::__construct($table,$adapter);
    }
	
		public function getIdSucursal(){
		return $this->id_sucursal;
		
	}
	
		public function setId_Sucursal($id_sucursal){
		return $this->id_sucursal=$id_sucursal;
		
		}
	
	public function getSucursal(){
		return $this->sucursal;
		
	}
	
		public function setSucursal($sucursal){
		return $this->sucursal=$sucursal;
		
	}
	

}
?>

