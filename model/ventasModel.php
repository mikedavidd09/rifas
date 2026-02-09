<?php 
class VentaModel extends ModeloBase{
    private $table;

    public function __construct($adapter){
        $this->table = "ventas";
        parent::__construct($this->table,$adapter);
    }

    public function getVentas(){

        $query = "SELECT concat(col.nombre,' ',col.apellido) as vendedor,
	    j.nombre as juego, 
        s.etiqueta as sorteo,
        v.total
        FROM colaboradores col
        INNER JOIN ventas v ON col.id_colaborador = v.id_colaborador 
        INNER JOIN sorteos s ON v.id_sorteo = v.id_sorteo
        INNER JOIN juegos j ON v.id_juego = j.id_juego
        INNER JOIN usuarios u on col.id_colaborador = u.id_colaborador
        INNER JOIN roles r on u.id_role = r.id_role
        ORDER BY col.id_colaborador, s.id_sorteo, j.id_juego 
        ";
    }

       public function getListadoIndex($query){
        $listado=$this->ejecutarSqlRow($query);
        return $listado;
    }

    public function getVenta($id_venta){
       $query = "SELECT 
       v.id_venta,
       v.consecutivo,
       concat(col.nombre,' ',col.apellido) as vendedor,
        v.nombre as cliente,
	    j.nombre as juego, 
        s.etiqueta as sorteo,
        v.fecha,
        v.hora,
        v.total
        FROM colaboradores col
        INNER JOIN ventas v ON col.id_colaborador = v.id_colaborador 
        INNER JOIN sorteos s ON v.id_sorteo = s.id_sorteo
        INNER JOIN juegos j ON v.id_juego = j.id_juego
        WHERE v.id_venta = $id_venta
        ";
        $obj=$this->ejecutarSqlRow($query);
        return $obj;
    }

    public function getNumeros($id_venta){
        $query = "SELECT 
        n.numero, n.monto, n.monto * 80 as premio
        FROM numeros n
        INNER JOIN ventas v ON n.id_venta = v.id_venta
        WHERE v.id_venta = $id_venta
        ";
        $obj=$this->ejecutarSqlRow($query);
        return $obj;
    }


}

?>