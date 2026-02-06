<?php
class PermisosModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="permisos";
        parent::__construct($this->table,$adapter);
    }


    public function getModulesPermisos($name){
        //extraer los modulos conforme al usuario en la tabla permisos permiso de crear ,actualizar borrar
        $query="SELECT * FROM permisos p  inner join modules m on m.id_modules = p.id_modules 
        INNER  JOIN usuarios u on u.id_user = p.id_usuarios where u.id_colaborador= '$name'";
        $modules=$this->ejecutarSql($query);
        return is_object($modules)? [$modules]: $modules;
    }
    public function getPerfilByIdColaborador($id){
        //extraer los modulos conforme al usuario en la tabla permisos permiso de crear ,actualizar borrar
        $query="SELECT *,p.perfil as perfil_u FROM  perfiles p  
        INNER  JOIN usuarios u on u.id_user = p.id_usuario where u.id_colaborador= '$id'";
        $modules=$this->ejecutarSql($query);
        return is_object($modules)? [$modules]: $modules;
    }
}
?>
