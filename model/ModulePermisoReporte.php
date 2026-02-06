<?php
class ModulePermisoReporte extends EntidadBase{
    public $id_permiso;
    public $id_modulos_parent;
    public $id_modulos_child;
    public $id_usuario;
    

    public function __construct($adapter){
        $table="permiso_reporte";
        parent::__construct($table,$adapter);
    }
	 public function getIdPermiso(){
        return $this->id_permiso;
    }
    public function setIdPermiso($id_permiso){
        $this->id_permiso = $id_permiso;
    }
	public function getIdModuloParent(){
        return $this->id_modulos_parent;
    }
    public function setIdModuloParent($id_modulos_parent){
        $this->id_modulos_parent = $id_modulos_parent;
    }
	public function getIdModuloChild(){
        return $this->id_modulos_child;
    }
    public function setIdModuloChild($id_modulos_child){
        $this->id_modulos_child = $id_modulos_child;
    }
	public function getIdUsuario(){
        return $this->id_usuario;
    }
    public function setIdUsuario($id_usuario){
        $this->id_usuario = $id_usuario;
    }
    
}
?>
