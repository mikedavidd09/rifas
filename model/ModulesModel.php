<?php
class ModulesModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="modules";
        parent::__construct($this->table,$adapter);
    }

    //Metodos de consulta
    public function getParent($id_user = 1){

        $query=" select *from permisos p  inner join modules m on m.id_modules = p.id_modules where p.id_usuarios= $id_user
                 and  type='parent' ORDER BY sort ";
        $modules=$this->ejecutarSql($query);
        return is_object($modules)? [$modules]: $modules;
    }

    public function getChildren(){
        //extraer los modulos conforme al usuario en la tabla permisos permiso de crear ,actualizar borrar
        $query="SELECT * FROM $this->table WHERE type='children' ORDER BY sort ";
        $modules=$this->ejecutarSql($query);
        return is_object($modules)? [$modules]: $modules;
    }

    public function getChildrenByIdParents($id){
        //extraer los modulos conforme al usuario en la tabla permisos permiso de crear ,actualizar borrar
        $query="SELECT * FROM $this->table WHERE id_parent=$id ORDER BY sort ";
        $modules=$this->ejecutarSql($query);
        return is_object($modules)? [$modules]: $modules;
    }
    public function getModuloReporte(){
        //extraer los modulos conforme al usuario en la tabla permisos permiso de crear ,actualizar borrar
        $query="SELECT * FROM modulo_reporte ";
        $modules=$this->ejecutarSql($query);
        return is_object($modules)? [$modules]: $modules;
    }
}
?>
