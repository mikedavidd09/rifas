<?php
class Usuario extends EntidadBase{
    public $id_usuario;
    public $usuario;
    public $clave;
    public $permiso;
    public $imagen;
    public $id_colaborador;
    public $id_role;
    public function __construct($adapter){
        $table="usuarios";
        parent::__construct($table,$adapter);
    }

    /**
     * @return mixed
     */
    public function getIdRole()
    {
        return $this->id_role;
    }

    /**
     * @param mixed $id_role
     */
    public function setIdRole($id_role)
    {
        $this->id_role = $id_role;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;   
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function setClave($clave)
    {
        $this->clave = $clave;
    }

    public function getPermiso()
    {
        return $this->permiso;
    }

    public function setPermiso($permiso)
    {
        $this->permiso = $permiso;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    public function getIdColaborador()
    {
        return $this->id_colaborador;
    }

    public function setIdColaborador($id_colaborador)
    {
        $this->id_colaborador = $id_colaborador;
    }
    }
?>
