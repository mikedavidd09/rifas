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
        col.telefono,
        col.direccion,
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

        public function getFacturadoByColaborador($fechaInicio,$fechaFin,$id_colaborador){
        $query = "SELECT coalesce(sum(v.total),0) as facturado
        FROM ventas v
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0 and v.id_colaborador = $id_colaborador
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

        public function getFacturadoByJuegoByColaborador($fechaInicio,$fechaFin,$id_colaborador){
        $query = "SELECT 
        j.id_juego,
        j.nombre as juego,
        coalesce(sum(v.total),0) as facturado
        FROM ventas v
        INNER JOIN juegos j 
            ON v.id_juego = j.id_juego
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0  and v.id_colaborador = $id_colaborador
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

        public function getPagadoByColaborador($fechaInicio,$fechaFin,$id_colaborador){
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
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0 and v.id_colaborador = $id_colaborador

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

        public function getPagadoByJuegoByColaborador($fechaInicio,$fechaFin,$id_colaborador){
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
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0  and v.id_colaborador = $id_colaborador
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

    public function getMontoVendidoBynumero($id_juego,$id_sorteo,$numero,$fecha){
        $query = "SELECT SUM(monto) as monto from ventas v
        inner join numeros n on v.id_venta = n.id_venta 
        where n.numero = '$numero' 
        and v.id_juego = $id_juego 
        and v.id_sorteo = $id_sorteo 
        and v.fecha = '$fecha' 
        and v.borrado = 0";
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? $obj->monto : 0;
    }   

    public function getIdVentaByConsecutivo($consecutivo){
        $query = "SELECT id_venta from ventas where consecutivo = '$consecutivo'";
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? $obj->id_venta : 0;
    }

    public function getFacturadoByUsuarios($fechaInicio,$fechaFin){
        $query = "SELECT 
        concat(col.nombre,' ',col.apellido) as vendedor,
        coalesce(sum(v.total),0) as facturado
        FROM ventas v
        INNER JOIN colaboradores col 
            ON v.id_colaborador = col.id_colaborador
        INNER JOIN sorteos s 
            ON v.id_sorteo = s.id_sorteo
        INNER JOIN juegos j 
            ON v.id_juego = j.id_juego
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0 
        GROUP BY col.id_colaborador, col.nombre, col.apellido
        ORDER BY facturado DESC   
        ";
    
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? [$obj] : $obj;
    }

        public function getPagadoByUsuarios($fechaInicio,$fechaFin){
        $query = "SELECT 
        concat(col.nombre,' ',col.apellido) as vendedor,
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
        WHERE v.fecha between '$fechaInicio' and '$fechaFin' and v.borrado = 0 
        GROUP BY col.id_colaborador, col.nombre, col.apellido
        ORDER BY pagado DESC   
        ";
    
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? [$obj] : $obj;
    }

    public function getFacturadoPagadoGbyColaborador($fechaInicio,$fechaFin){
        $query = "SELECT
    v.vendedor,
    v.facturado,
    COALESCE(p.pagado, 0) as pagado
FROM (
    SELECT
        concat(col.nombre,' ',col.apellido) as vendedor,
        coalesce(sum(v.total),0) as facturado,
        col.id_colaborador
    FROM ventas v
    INNER JOIN colaboradores col ON v.id_colaborador = col.id_colaborador
    INNER JOIN sorteos s ON v.id_sorteo = s.id_sorteo
    INNER JOIN juegos j ON v.id_juego = j.id_juego
    WHERE v.fecha BETWEEN '$fechaInicio' AND '$fechaFin' AND v.borrado = 0
    GROUP BY col.id_colaborador, col.nombre, col.apellido
) v
LEFT JOIN (
    SELECT
        concat(col.nombre,' ',col.apellido) as vendedor,
        coalesce(sum(n.premio),0) as pagado,
        col.id_colaborador
    FROM ventas v
    INNER JOIN colaboradores col ON v.id_colaborador = col.id_colaborador
    INNER JOIN sorteos s ON v.id_sorteo = s.id_sorteo
    INNER JOIN juegos j ON v.id_juego = j.id_juego
    INNER JOIN numeros n ON v.id_venta = n.id_venta
    WHERE v.fecha BETWEEN '$fechaInicio' AND '$fechaFin' AND v.borrado = 0
    GROUP BY col.id_colaborador, col.nombre, col.apellido
) p ON v.id_colaborador = p.id_colaborador
ORDER BY v.facturado DESC;
        ";
    
        $obj=$this->ejecutarSql($query);
        return is_object($obj) ? [$obj] : $obj;
    }
}

?>