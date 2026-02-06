<?php
class Permisos extends EntidadBase{
    public $id_permisos;
    public $permisos;
    public $id_modules;
    public $id_usuarios;
    public function __construct($adapter){
        $table="permisos";
        parent::__construct($table,$adapter);
    }

    /**
     * @return mixed
     */
    public function getIdPermisos()
    {
        return $this->id_permisos;
    }

    /**
     * @param mixed $id_permisos
     */
    public function setIdPermisos($id_permisos)
    {
        $this->id_permisos = $id_permisos;
    }

    /**
     * @return mixed
     */
    public function getPermisos()
    {
        return $this->permisos;
    }

    /**
     * @param mixed $permisos
     */
    public function setPermisos($permisos)
    {
        $this->permisos = $permisos;
    }

    /**
     * @return mixed
     */
    public function getIdModules()
    {
        return $this->id_modules;
    }

    /**
     * @param mixed $id_modules
     */
    public function setIdModules($id_modules)
    {
        $this->id_modules = $id_modules;
    }

    /**
     * @return mixed
     */
    public function getIdUsuarios()
    {
        return $this->id_usuarios;
    }

    /**
     * @param mixed $id_usuarios
     */
    public function setIdUsuarios($id_usuarios)
    {
        $this->id_usuarios = $id_usuarios;
    }


}
?>
