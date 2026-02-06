<?php
class SucursalModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="sucursal";
        parent::__construct($this->table,$adapter);
    }
    //Metodo listado del index
    public function getListadoIndex($query){
        $listado=$this->ejecutarSqlRow($query);
        return $listado;
    }

public function ComboSucursales($cargo,$id_sucursal,$id_colaborador){

if($cargo=='Administrador' OR $cargo=='Gerente')
    $query="(SELECT 'All'as id_sucursal,'Todas'as sucursal) UNION  (SELECT id_sucursal, sucursal from sucursal)";
else 
   $query="SELECT * from sucursal s where s.id_sucursal = '$id_sucursal'";

    //echo $query;

      $sucursal=$this->ejecutarSql($query);
      return (is_object($sucursal))? [$sucursal] : $sucursal;
}

    //Metodos de consulta
public function getIdSucursal($id_colaborador){
        $query="SELECT s.id_sucursal FROM sucursal s INNER JOIN colaboradores c ON s.id_sucursal=c.id_sucursal where c.id_colaborador=$id_colaborador";
        $sucursal=$this->ejecutarSql($query);
        return (is_object($sucursal))? [$sucursal] : $sucursal;
    }

    public function getAllSucursales(){
        $query="SELECT * FROM $this->table";
        $sucursal=$this->ejecutarSql($query);
        return (is_object($sucursal))? [$sucursal] : $sucursal;
    }

	public function getSucursalById($id_sucursal){
		 $query="SELECT * FROM $this->table where id_sucursal='$id_sucursal'";
        $sucursal=$this->ejecutarSql($query);
        return $sucursal;

	}


	  public function getAllSucursalesById($id_sucursal){
        $query="SELECT * FROM $this->table where id_sucursal = '$id_sucursal'";
        $sucursal=$this->ejecutarSql($query);
        return $sucursal;
    }

	 public function getClientesByIdSucursal($id_sucursal){
        $query="SELECT
c.id_cliente,
c.nombre,
c.apellido,
c.direccion,
c.imagen,
c.codigo_cliente,
c.sexo,
c.cedula,
c.telefono,
c.tipo_negocio,
cl.id_colaborador

from clientes c
inner JOIN colaboradores
cl ON c.id_colaborador=cl.id_colaborador
INNER JOIN sucursal s ON cl.id_sucursal = s.id_sucursal
where s.id_sucursal= '$id_sucursal'
order by c.nombre";

        $sucursal=$this->ejecutarSql($query);
        return $sucursal;
    }

	public function getColaboradoresByIdSucursal($id_sucursal){
        $query="SELECT * from colaboradores c INNER JOIN sucursal s ON c.id_sucursal = s.id_sucursal where s.id_sucursal ='$id_sucursal'";
        $sucursal=$this->ejecutarSql($query);
        return $sucursal;
    }

		public function getPrestamosByIdSucursal($id_sucursal){
        $query="
		SELECT  *,c.nombre from clientes c
		inner JOIN prestamos p ON
		c.id_cliente=p.id_cliente
		INNER JOIN colaboradores cl on
		cl.id_colaborador = c.id_colaborador
		INNER JOIN intereses i on
		p.id_interes = i.id_interes
		INNER JOIN plazos pl on
		p.id_plazo = pl.id_plazo
		INNER JOIN sucursal s ON
		cl.id_sucursal = s.id_sucursal
		where s.id_sucursal= '$id_sucursal'";


        $sucursal=$this->ejecutarSql($query);
        return $sucursal;
    }

    public function getPrestamosEstadoByIdSucursal($estado,$id_sucursal){
        $query="
    SELECT  *,
    c.nombre,
    c.apellido,
    c.direccion,
            cl.nombre as nombreanalista,
            cl.apellido as apellidoanalista
      from clientes c
    inner JOIN prestamos p ON
    c.id_cliente=p.id_cliente
    INNER JOIN colaboradores cl on
    cl.id_colaborador = c.id_colaborador
    INNER JOIN intereses i on
    p.id_interes = i.id_interes
    INNER JOIN plazos pl on
    p.id_plazo = pl.id_plazo
    INNER JOIN sucursal s ON
    cl.id_sucursal = s.id_sucursal
    where estado = '$estado' $id_sucursal";


        $sucursal=$this->ejecutarSql($query);
        return $sucursal;
    }

    public function Get_sucursales_stats(){

      $query = "select *,
(select COUNT(*) from colaboradores cl INNER join sucursal s on s.id_sucursal = cl.id_sucursal where s.id_sucursal = sl.id_sucursal ) as analistas,
(SELECT COUNT(*) from sucursal s inner join colaboradores col on s.id_sucursal = col.id_sucursal inner join clientes c on col.id_colaborador = c.id_colaborador where sl.id_sucursal = s.id_sucursal and col.cargo = 'Analista') as clientes,
(SELECT COUNT(*) from sucursal s inner join colaboradores col on s.id_sucursal = col.id_sucursal inner join clientes c on col.id_colaborador = c.id_colaborador  inner join prestamos p on p.id_cliente = c.id_cliente where sl.id_sucursal = s.id_sucursal and p.estado = 'Aceptado') as prestamosActivos,
(SELECT COUNT(*) from sucursal s inner join colaboradores col on s.id_sucursal = col.id_sucursal inner join clientes c on col.id_colaborador = c.id_colaborador  inner join prestamos p on p.id_cliente = c.id_cliente where sl.id_sucursal = s.id_sucursal and p.estado = 'Aceptado' and p.fecha_vencimiento < date(now())) as prestamosVencidos,
(SELECT COUNT(*) from sucursal s inner join colaboradores col on s.id_sucursal = col.id_sucursal inner join clientes c on col.id_colaborador = c.id_colaborador  inner join prestamos p on p.id_cliente = c.id_cliente where sl.id_sucursal = s.id_sucursal and p.estado = 'Aceptado' and p.fecha_vencimiento >= date(now())) as prestamosNoVencidos
from sucursal sl

";

$statistics = $this->ejecutarSql($query);
      return is_object($statistics)?[$statistics]:$statistics;
    }

    public function Get_analistas_stats(){

      $query = "
      select cl.nombre,cl.apellido, cl.cargo, sl.sucursal,
(SELECT COUNT(*) from sucursal s inner join colaboradores col on s.id_sucursal = col.id_sucursal inner join clientes c on col.id_colaborador = c.id_colaborador where cl.id_colaborador = col.id_colaborador) as clientes,

(SELECT COUNT(*) from sucursal s inner join colaboradores col on s.id_sucursal = col.id_sucursal inner join clientes c on col.id_colaborador = c.id_colaborador  inner join prestamos p on p.id_cliente = c.id_cliente where p.estado = 'Aceptado' and cl.id_colaborador = col.id_colaborador ) as prestamosActivos,

(SELECT COUNT(*) from sucursal s inner join colaboradores col on s.id_sucursal = col.id_sucursal inner join clientes c on col.id_colaborador = c.id_colaborador  inner join prestamos p on p.id_cliente = c.id_cliente where p.estado = 'Aceptado' and p.fecha_vencimiento < date(now()) and cl.id_colaborador = col.id_colaborador) as prestamosVencidos,

(SELECT COUNT(*) from sucursal s inner join colaboradores col on s.id_sucursal = col.id_sucursal inner join clientes c on col.id_colaborador = c.id_colaborador  inner join prestamos p on p.id_cliente = c.id_cliente where p.estado = 'Aceptado' and p.fecha_vencimiento >= date(now()) and cl.id_colaborador = col.id_colaborador) as prestamosNoVencidos

from colaboradores cl inner join sucursal sl on sl.id_sucursal = cl.id_sucursal  where cl.cargo = 'Analista' order by sl.sucursal DESC, prestamosActivos DESC




";

$statistics = $this->ejecutarSql($query);
      return is_object($statistics)?[$statistics]:$statistics;
    }


    public function Get_metasXsucursal($weekday){

      $query="SELECT s.sucursal,
SUM(IF(p.fecha_vencimiento>=date(now()) ,ROUND(p.cuota,2),0 ) ) as metactivos,
recupera.Recaudo_Pvigente,
SUM(IF(p.fecha_vencimiento<date(now()) ,round(p.cuota,2),0 ) ) as metavencidos,
recupera.Recaudo_vencidos,
SUM(ROUND(p.cuota,2)) as metaglobal,
recupera.Recaudo_Global

from 
prestamos p 
inner JOIN clientes c on p.id_cliente = c.id_cliente
INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
INNER JOIN sucursal s ON s.id_sucursal = cl.id_sucursal

left JOIN(

SELECT
    col.id_colaborador,
    sl.id_sucursal,
SUM( IF( p.fecha_vencimiento >= DATE( NOW() ) , round(pg.monto_cuota,2), 0 ) ) as Recaudo_Pvigente,
SUM( IF( p.fecha_vencimiento < DATE( NOW() ) , round(pg.monto_cuota,2), 0 ) ) as Recaudo_vencidos,
SUM(round(pg.monto_cuota,2) ) as Recaudo_Global
    
FROM clientes c
    
INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
INNER JOIN colaboradores col ON col.id_colaborador = c.id_colaborador
INNER JOIN sucursal sl ON sl.id_sucursal = col.id_sucursal
INNER JOIN pagos pg ON pg.id_prestamo = p.id_prestamo
WHERE pg.fecha_cuota = DATE( NOW( ) )
AND pg.estado =  'pagado' GROUP by sl.id_sucursal

) as recupera on recupera.id_sucursal = s.id_sucursal


        where   p.estado = 'Aceptado' and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday')
        and p.fecha_desembolso != date(now())


        GROUP by s.sucursal
        order by s.id_sucursal
        ";
        

        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;
    }

    public function get_metasXanalista($weekday){
      $query="SELECT 
s.sucursal,
concat(cl.nombre,' ',cl.apellido) as analista,

SUM(IF(p.fecha_vencimiento>=date(now()) ,ROUND(p.cuota,2),0 ) ) as metactivos,
recupera.Recaudo_Pvigente,
SUM(IF(p.fecha_vencimiento<date(now())  ,round(p.cuota,2),0 ) ) as metavencidos,
recupera.Recaudo_vencidos,
SUM(ROUND(p.cuota,2)) as metaglobal,
recupera.Recaudo_Global

from 
prestamos p 
inner JOIN clientes c on p.id_cliente = c.id_cliente
INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
INNER JOIN sucursal s ON s.id_sucursal = cl.id_sucursal

left JOIN(

SELECT
    col.id_colaborador,
SUM( IF( p.fecha_vencimiento >= DATE( NOW() ) , round(pg.monto_cuota,2), 0 ) ) as Recaudo_Pvigente,
SUM( IF( p.fecha_vencimiento < DATE( NOW() ) , round(pg.monto_cuota,2), 0 ) ) as Recaudo_vencidos,
SUM(round(pg.monto_cuota,2) ) as Recaudo_Global
    
FROM clientes c
    
INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
INNER JOIN colaboradores col ON col.id_colaborador = c.id_colaborador
INNER JOIN sucursal sl ON sl.id_sucursal = col.id_sucursal
INNER JOIN pagos pg ON pg.id_prestamo = p.id_prestamo
WHERE pg.fecha_cuota = DATE( NOW( ) )
AND pg.estado =  'pagado' GROUP by col.id_colaborador

) as recupera on recupera.id_colaborador = cl.id_colaborador


        where   p.estado = 'Aceptado' and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday')
        and p.fecha_desembolso != date(now())

        GROUP by cl.id_colaborador
        order by s.id_sucursal
        ";

      $result = $this->ejecutarSql($query);
      return is_object($result)?[$result]:$result;

    }

    public function get_recaudoXsucursal(){

    $query = "SELECT s.sucursal,
CONCAT( col.nombre,  ' ', col.apellido ) AS analista,
ROUND( SUM( IF( p.fecha_vencimiento >= DATE( NOW() ) , pg.monto_cuota, 0 ) ) , 3 ) AS Recaudo_Pvigente,
ROUND( SUM( IF( p.fecha_vencimiento <  DATE( NOW() ) , pg.monto_cuota, 0 ) ) , 3 ) AS Recaudo_vencidos,
ROUND( SUM(pg.monto_cuota) , 3 ) AS Recaudo_Global
FROM clientes c
INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
INNER JOIN colaboradores col ON col.id_colaborador = c.id_colaborador
INNER JOIN plazos pl ON p.id_plazo = pl.id_plazo
INNER JOIN intereses i ON i.id_interes = p.id_interes
INNER JOIN sucursal s ON s.id_sucursal = col.id_sucursal
INNER JOIN pagos pg ON pg.id_prestamo = p.id_prestamo
WHERE pg.fecha_cuota = DATE( NOW( ) )
AND pg.estado =  'pagado'

        GROUP by s.sucursal";
    $result = $this->ejecutarSql($query);
    return is_object($result)?[$result]:$result;

    }

    public function get_recaudoXanalista(){

    $query = "SELECT CONCAT(col.nombre,' ', col.apellido ) AS analista,
ROUND( SUM( IF( p.fecha_vencimiento >= DATE( NOW() ) , pg.monto_cuota, 0 ) ) , 3 ) AS Recaudo_Pvigente,
ROUND( SUM( IF( p.fecha_vencimiento <  DATE( NOW() ) , pg.monto_cuota, 0 ) ) , 3 ) AS Recaudo_vencidos,
ROUND( SUM(pg.monto_cuota) , 3 ) AS Recaudo_Global
FROM clientes c
INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
INNER JOIN colaboradores col ON col.id_colaborador = c.id_colaborador
INNER JOIN plazos pl ON p.id_plazo = pl.id_plazo
INNER JOIN intereses i ON i.id_interes = p.id_interes
INNER JOIN sucursal s ON s.id_sucursal = co.id_sucursal
INNER JOIN pagos pg ON pg.id_prestamo = p.id_prestamo
WHERE pg.fecha_cuota = DATE( NOW( ) )
AND pg.estado =  'pagado'
GROUP BY analista";

    $result = $this->ejecutarSql($query);
    return is_object($result)?[$result]:$result;


    }
public function getMetasCuotasDelDia($dia,$fecha){
    $query="SELECT s.id_sucursal, COUNT(p.cuota) as mcuotas FROM sucursal s 
              INNER JOIN colaboradores col on s.id_sucursal=col.id_sucursal
              INNER JOIN clientes cl on col.id_colaborador=cl.id_colaborador
              INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
              LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on p.id_prestamo=sa.id_prestamo
              WHERE p.estado='Aceptado' and (p.dia_desembolso='Diario' or p.dia_desembolso='$dia' or (p.dia_desembolso='Quincenal' and (IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)*15) DAY)) = 5,
        DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)*15) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)*15) DAY))=6,DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)*15) DAY), INTERVAL 1 DAY),DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)*15) DAY))))='$fecha') or (p.dia_desembolso='Mensual' and (IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY)) = 5,
        DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY))=6,DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY), INTERVAL 1 DAY),DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY))))='$fecha')) and p.fecha_desembolso != '$fecha' and sa.sigla is null GROUP by s.sucursal order by s.id_sucursal ";
        
        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;
       
}
public function getMetasCuotasDelDiaMora($fecha){
    $query="SELECT s.id_sucursal, sum(IF(p.fecha_vencimiento>='$fecha' ,1,0) ) as mcuotas FROM sucursal s 
              INNER JOIN colaboradores col on s.id_sucursal=col.id_sucursal
              INNER JOIN clientes cl on col.id_colaborador=cl.id_colaborador
              INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
              LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on p.id_prestamo=sa.id_prestamo
              WHERE p.estado='Aceptado' and p.mora > 0 and p.fecha_desembolso != '$fecha' and sa.sigla is null GROUP by s.sucursal order by s.id_sucursal ";
        
        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;
       
}

public function getMetasCuotasXsucursal($weekday,$login,$visionSucursal,$fecha){
    $condicion = ""; //empty($visionSucursal[0]->id_colaborador) ? (($login->cargo == "Administrador" || $login->cargo == "Gerente General") ? "":"and s.id_sucursal=".$login->id_sucursal):"and s.id_sucursal in(".$visionSucursal[0]->sucursal.")";
    $query="SELECT s.id_sucursal,s.sucursal, cuo.cantidad_cuotas as mcuotas,cefectiva.ecuotas
    FROM sucursal s 
  INNER JOIN cuotasdeldia cuo on s.id_sucursal=cuo.id_sucursal
  LEFT JOIN (SELECT s.id_sucursal,s.sucursal,COUNT(DISTINCT pa.id_prestamo) as ecuotas from sucursal s 
INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
INNER JOIN prestamos pr on cl.id_cliente=pr.id_cliente
INNER JOIN pagos pa on pr.id_prestamo=pa.id_prestamo
where pa.estado='pagado' and pa.fecha_cuota='$fecha' and pa.monto_cuota > 0  GROUP by s.sucursal ORDER by s.sucursal ASC) AS cefectiva on cefectiva.id_sucursal=s.id_sucursal
 WHERE cuo.fecha_dia='$fecha' $condicion GROUP by s.sucursal order by s.id_sucursal";
  $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;

}


}
?>
