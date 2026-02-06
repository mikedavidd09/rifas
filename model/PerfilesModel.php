<?php
class PerfilesModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="perfiles";
        parent::__construct($this->table,$adapter);
    }

    public function tienePerfil($id){
        //extraer los modulos conforme al usuario en la tabla permisos permiso de crear ,actualizar borrar
        $query="select 1 from perfiles where id_usuario='$id'";
        $perfil=$this->ejecutarSql($query);
        return is_object($perfil)? true : false;
    }
}

?>
