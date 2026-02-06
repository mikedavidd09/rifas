<?php
class ModulePermisoReporteModel extends ModeloBase{
    public $id_permiso;
    public $id_modulos_parent;
    public $id_modulos_child;
    public $id_usuario;
    

    public function __construct($adapter){
        $table="permiso_reporte";
        parent::__construct($table,$adapter);
    }
	 public function getDataReporteAsignado($id_colaborador){
					$sql="SELECT p.*,u.usuario from colaboradores c 
				INNER JOIN usuarios u on c.id_colaborador=u.id_colaborador
				INNER JOIN permiso_reporte p on u.id_user=p.id_usuario
				
				where c.id_colaborador=$id_colaborador";
		 $modules=$this->ejecutarSql($sql);
        return is_object($modules)? [$modules]: $modules;
	 }
    
}
?>
