<?php

class FiadorModel extends ModeloBase
{
    private $table;

    public function __construct($adapter)
    {
        $this->table = "fiador";
        parent::__construct($this->table, $adapter);
    }

    //Metodos de consulta
    public function getFiador($id_prestamo)
    {
        $query = "SELECT f.* FROM prestamos p inner join relfiadorprestamo r on p.id_prestamo=r.id_prestamo inner join fiador f on r.id_fiador=f.id_fiador where p.id_prestamo=$id_prestamo";
        $usuario = $this->ejecutarSql($query);
        return $usuario;
    }

   

}

?>
