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
        v.total,
        s.id_sorteo
        FROM colaboradores col
        INNER JOIN ventas v ON col.id_colaborador = v.id_colaborador 
        INNER JOIN sorteos s ON v.id_sorteo = s.id_sorteo
        INNER JOIN juegos j ON v.id_juego = j.id_juego
        WHERE v.id_venta = $id_venta
        ";
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? $obj : [$obj];
    }

    public function getNumeros($id_venta){
        $query = "SELECT 
        n.numero, n.monto, n.monto * 80 as premio
        FROM numeros n
        INNER JOIN ventas v ON n.id_venta = v.id_venta
        WHERE v.id_venta = $id_venta
        ";
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? [$obj] : $obj;
    }

    public function getSorteo($id_sorteo){
        $query = "SELECT 
        *
        FROM sorteos s
        WHERE s.id_sorteo = $id_sorteo
        ";
        $obj=$this->ejecutarSql($query);
        return $obj;
    }

    public function getFacturadoRangoFecha($fechaInicio,$fechaFin){
        $query = "SELECT coalesce(sum(v.total),0) as facturado
        FROM ventas v
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0
        ";
    
        $obj=$this->ejecutarSql($query);
        return $obj->facturado;
    }

    public function getFacturadoRangoFechaByJuego($fechaInicio,$fechaFin){
        $query = "SELECT 
        j.id_juego,
        j.nombre as juego,
        coalesce(sum(v.total),0) as facturado
        FROM ventas v
        INNER JOIN juegos j 
            ON v.id_juego = j.id_juego
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0 
        GROUP BY j.id_juego, j.nombre 
        ORDER BY facturado DESC   
        ";
    
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? [$obj] : $obj;
    }

    public function getPagadoDiaRangoFecha($fechaInicio,$fechaFin){
        $query = "SELECT coalesce(sum(n.premio),0) as pagado
        FROM ventas v
    INNER JOIN colaboradores col 
        ON v.id_colaborador = col.id_colaborador
    INNER JOIN sorteos s 
        ON v.id_sorteo = s.id_sorteo
    INNER JOIN juegos j 
        ON v.id_juego = j.id_juego
    INNER JOIN numeros n 
        ON v.id_venta = n.id_venta
    INNER JOIN numeros_ganadores ng 
        ON ng.numero = n.numero 
        AND ng.id_sorteo = s.id_sorteo 
        AND ng.fecha = v.fecha
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0

        ";
        $obj=$this->ejecutarSql($query);
        return $obj->pagado;
    }

    public function getPagadoDiaRangoFechaByJuego($fechaInicio,$fechaFin){
        $query = "SELECT
        j.id_juego,
        j.nombre as juego,
        coalesce(sum(n.premio),0) as pagado
        FROM ventas v
    INNER JOIN colaboradores col 
        ON v.id_colaborador = col.id_colaborador
    INNER JOIN sorteos s 
        ON v.id_sorteo = s.id_sorteo
    INNER JOIN juegos j 
        ON v.id_juego = j.id_juego
    INNER JOIN numeros n 
        ON v.id_venta = n.id_venta
    INNER JOIN numeros_ganadores ng 
        ON ng.numero = n.numero 
        AND ng.id_sorteo = s.id_sorteo 
        AND ng.fecha = v.fecha
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0 
        group by j.id_juego, j.nombre
        order by pagado DESC

        ";
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? [$obj] : $obj;
    }

    public function getTotalUsuarios(){
        $query = "SELECT count(col.id_colaborador) as vendedores
        FROM colaboradores col inner join usuarios u  on u.id_colaborador = col.id_colaborador
        inner join roles r on r.id_role = u.id_role
        WHERE r.nombre = 'vendedor' and col.estado = 1";
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? $obj->vendedores : 0;
    }

}

?>