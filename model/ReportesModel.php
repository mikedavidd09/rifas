<?php

class ReportesModel extends ModeloBase
{
    private $datos_tablas;
    public function __construct($table,$adapter){
        $this->datos_tablas=(string) $table;
        parent::__construct($table,$adapter);
    }

    public function ReportsByClientesMora($fecha_bengin,$fecha_end,$id_sucursal){
        $estado=" and p.estado <> 'Cancelado' and p.estado <> 'Rechazado' ";
        $query = "  SELECT *,
					concat(c.nombre,' ',c.apellido) as cliente,
                    concat(cl.nombre,' ' ,cl.apellido) as colaborador,
                    p.estado as estado


                    FROM        clientes c
                    INNER join  prestamos p  on c.id_cliente = p.id_cliente
					INNER join 	colaboradores cl on cl.id_colaborador = c.id_colaborador
					INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                    INNER JOIN  pagos pg     on pg.id_prestamo = p.id_prestamo
                    INNER JOIN	moras m 	 on	m.id_pago = pg.id_pago
                    WHERE (m.fecha_mora>= '$fecha_bengin' and m.fecha_mora<='$fecha_end') and m.monto_mora>0
                   $id_sucursal
                   $estado ";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function ReportsByClientesMoraActual($by_colaborador){
       

        $query = " SELECT c.codigo_cliente,
                    concat(c.nombre,' ',c.apellido )as cliente,
                    c.telefono,
                    concat(cl.nombre,' ',cl.apellido )as colaborador,
                    p.codigo_prestamo,
                    round(p.mora,3)as mora,
                    p.capital,
                    p.deuda_total,
                    p.modalidad,
               
                    if(p.fecha_vencimiento>=curdate(),'Activo','Vencido')as estado,
                    p.tipo_prestamo,
                    p.fecha_desembolso,
                    p.fecha_vencimiento,
                    round(p.cuota,3) as cuota,
                    round((p.mora/p.cuota),3) as nc_atrasadas,
                    if(  (p.mora/p.cuota > '0' and p.mora/p.cuota < 5),'Temprana',
                    if(  (p.mora/p.cuota>=5    and p.mora/p.cuota < 9),'avanzada',
                    if(  (p.mora/p.cuota>=9   and p.mora/p.cuota < 13),'Riesgo','Riesgo Perdido') ) )as tipomora,
               
                    p.total_abonado,
                    p.saldo_pendiente
                    FROM        clientes c
                    INNER join  prestamos p  on c.id_cliente = p.id_cliente
                    INNER join  colaboradores cl on cl.id_colaborador = c.id_colaborador
                    INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                    WHERE p.mora > '0' and p.estado = 'aceptado'

                    $by_colaborador
                
                    order by p.mora desc" ;

                  //  echo $query;
                                      

        $result=$this->ejecutarSql($query);
        return $result;
    }

public function ReportsByAnalistaMoraXrango($id_sucursal){
       

        $query = "SELECT 
                    concat(cl2.nombre,' ',cl2.apellido )as analista,

                    (select count(*) from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
                                                       inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
                     where (p.mora/p.cuota) > '0' and (p.mora/p.cuota) < 5 and cl2.id_colaborador = cl.id_colaborador and
                     p.fecha_vencimiento>= curdate()  and p.estado = 'Aceptado'

                    )as temprana,

                    (select round((sum(p.mora)),3) from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
                                                       inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
                     where (p.mora/p.cuota) > '0' and (p.mora/p.cuota) < 5 and cl2.id_colaborador = cl.id_colaborador
                     and
                     p.fecha_vencimiento>= curdate() and p.estado = 'Aceptado'


                    )as mora1,


                    (select count(*) from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
                                                       inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
                     where (p.mora/p.cuota) >=5 and (p.mora/p.cuota) < 9 and cl2.id_colaborador = cl.id_colaborador
                     and
                     p.fecha_vencimiento>= curdate() and p.estado = 'Aceptado'


                    )as avanzada,


                    (select round((sum(p.mora)),3) from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
                                                       inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
                     where (p.mora/p.cuota) >=5 and (p.mora/p.cuota) < 9 and cl2.id_colaborador = cl.id_colaborador

                     and
                     p.fecha_vencimiento>= curdate() and p.estado = 'Aceptado'


                    )as mora2,


                     (select count(*) from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
                                                       inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
                     where (p.mora/p.cuota) >=9 and (p.mora/p.cuota) < 13 and cl2.id_colaborador = cl.id_colaborador

                     and
                     p.fecha_vencimiento>= curdate() and p.estado = 'Aceptado'


                    )as riesgo,


                    (select round((sum(p.mora)),3) from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
                                                       inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
                     where (p.mora/p.cuota) >=9 and (p.mora/p.cuota) < 13 and cl2.id_colaborador = cl.id_colaborador

                     and
                     p.fecha_vencimiento>= curdate() and p.estado = 'Aceptado'
 

                    ) as mora3,



                     (select count(*) from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
                                                       inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
                     where (p.mora/p.cuota) >=13  and cl2.id_colaborador = cl.id_colaborador

                     and
                     p.fecha_vencimiento>= curdate() and p.estado = 'Aceptado'


                    )as perdido,


                    (select round((sum(p.mora)),3) from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
                                                       inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
                     where (p.mora/p.cuota) >=13 and cl2.id_colaborador = cl.id_colaborador

                     and
                     p.fecha_vencimiento>= curdate()  and p.estado = 'Aceptado'


                    ) as mora4                      

                    FROM        clientes c
                    INNER join  prestamos p  on c.id_cliente = p.id_cliente
                    INNER join  colaboradores cl2 on cl2.id_colaborador = c.id_colaborador
                    INNER JOIN sucursal s on s.id_sucursal = cl2.id_sucursal
                    WHERE p.mora > '0'  and  cl2.cargo = 'analista' 
                    $id_sucursal
                
                    group by cl2.id_colaborador";

          

        $result=$this->ejecutarSql($query);
        return $result;
    }





    public function ReportsByClientesMoraActualByCol($id){

        $query = "  SELECT c.codigo_cliente, c.nombre,
                    c.apellido,c.telefono,p.codigo_prestamo,p.mora,
                    p.capital,
                    p.deuda_total,
                     p.total_abonado,
                    p.saldo_pendiente
                    FROM        clientes c
                    INNER join  prestamos p  on c.id_cliente = p.id_cliente
                    WHERE p.mora > '0' and c.id_colaborador = $id";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    public function ReportsByClientesMontoFavor($by_colaborador){
        $estado=" and p.estado <> 'Cancelado' and p.estado <> 'Rechazado' ";
        $query = "SELECT
                    c.codigo_cliente,
                    c.nombre,
                    c.apellido,
                    c.telefono,
                    concat(cl.nombre,'',cl.apellido) as colaborador,
                    p.codigo_prestamo,
                    round(p.monto_favor,2) as monto_favor,
                    p.mora,
                    p.capital,
                    p.deuda_total,
                    p.total_abonado,
                    if(nota.monto_interes is null,p.total_abonado,p.total_abonado-nota.monto_interes) as total_abonado,
                    p.saldo_pendiente,
                    nota.monto_interes as descuento_interes
                    from clientes c INNER JOIN prestamos p on c.id_cliente= p.id_cliente
						        	INNER join 	colaboradores cl on cl.id_colaborador = c.id_colaborador
					                INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                                    LEFT JOIN nota_credito nota on p.id_prestamo=nota.id_prestamo
                    where monto_favor >0
                     $by_colaborador
                     $estado
                    ORDER by mora ASC";
        $result=$this->ejecutarSql($query);
        return $result;

    }
    public function ReportsByClientesTasaInteres($by_colaborador){

        $query = "SELECT c.codigo_cliente ,
                         c.nombre,
                         c.apellido,
                         concat(cl.nombre,'',cl.apellido) as colaborador,
                         c.telefono,
                         c.direccion ,
                         p.codigo_prestamo ,
                         p.modalidad,
                         i.porcentaje,
                         p.capital,
                         p.deuda_total,
                         p.total_abonado,
                         p.saldo_pendiente
            from clientes c
            inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
            INNER JOIN prestamos p on c.id_cliente = p.id_cliente
            INNER JOIN intereses i ON i.id_interes = p.id_interes
            inner join sucursal s on s.id_sucursal = cl.id_sucursal
            where p.estado='aceptado'
            $by_colaborador
            order by porcentaje";

        $result=$this->ejecutarSql($query);
        return $result;

    }

	    public function ReportsByClientesTasaInteresBySucursal($id_sucursal){
            $estado=" and p.estado <> 'Cancelado' and p.estado <> 'Rechazado' ";
        $query = "SELECT c.codigo_cliente ,
                         c.nombre,
						 c.apellido,
                         c.telefono,
                         cl.nombre as colaborador,
						 c.direccion ,
                         p.codigo_prestamo,
						 p.modalidad,
                         i.porcentaje,
						 p.capital,
						 p.deuda_total,
                         p.total_abonado,
						 p.saldo_pendiente
            from clientes c
            INNER JOIN prestamos p on  c.id_cliente = p.id_cliente
			inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
			inner join sucursal s on s.id_sucursal  = cl.id_sucursal
            INNER JOIN intereses i ON i.id_interes = p.id_interes
			where s.id_sucursal = $id_sucursal
			$estado
			order by i.porcentaje DESC ";

        $result=$this->ejecutarSql($query);
        return $result;

    }

    public function ReportsByClientesAlDiaActual($by_colaborador){
        $estado=" and p.estado <> 'Cancelado' and p.estado <> 'Rechazado' ";
        $query = "  SELECT
                    c.codigo_cliente, c.nombre,
                    c.apellido,
                    c.telefono,
                    concat(cl.nombre ,'',cl.apellido)as colaborador ,
                    p.codigo_prestamo,
                    p.mora,
                    p.capital,
                    p.estado,
                    p.deuda_total, 
                    if(nota.monto_interes is null,p.total_abonado,p.total_abonado-nota.monto_interes) as total_abonado,
                    p.saldo_pendiente,
                    nota.monto_interes as descuento_interes
                    FROM        clientes c
                    INNER join  prestamos p  on c.id_cliente = p.id_cliente
					INNER join 	colaboradores cl on cl.id_colaborador = c.id_colaborador
					INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                    LEFT JOIN nota_credito nota on p.id_prestamo=nota.id_prestamo
                    WHERE p.mora = '0'
                    $by_colaborador
                    $estado
                    ";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function ReportsByClientesAlDiaActualByCol($id){
        $estado=" and p.estado <> 'Cancelado' and p.estado <> 'Rechazado' ";
        $query = "  SELECT c.codigo_cliente, c.nombre,
                    c.apellido,c.telefono,p.codigo_prestamo,p.mora,
                    p.capital,
                    p.deuda_total, p.total_abonado,
                    p.saldo_pendiente
                    FROM        clientes c
                    INNER join  prestamos p  on c.id_cliente = p.id_cliente
                    WHERE p.mora = '0' and c.id_colaborador =$id
                     $estado
                    ";
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function ReportsByClientesFechaDesem($fecha_bengin,$fecha_end,$id_sucursal){
        $query = "SELECT *,
                  concat(c.nombre,'',c.apellido) as cliente ,
                  concat(cl.nombre,' ',cl.apellido) as colaborador

                    FROM clientes c
				    INNER join colaboradores cl on cl.id_colaborador = c.id_colaborador
					INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                    INNER join prestamos p on c.id_cliente = p.id_cliente

                  WHERE (p.fecha_desembolso>='$fecha_bengin' and p.fecha_desembolso<='$fecha_end') and p.estado='Aceptado'
                    $id_sucursal";

        $result=$this->ejecutarSql($query);

        return $result;
    }

    public function ReportsByClientesFechavencim($fecha_bengin,$fecha_end,$id_sucursal){
        $query = "SELECT p.*,cl.*,s.*,
                           concat(c.nombre,' ' ,c.apellido) as cliente,
                           concat(cl.nombre,' ' ,cl.apellido) as colaborador,
                           nota.estado as estadonota,
                           nota.monto_interes as descuento_interes,
                           nota.saldo_pendiente as saldonota,
                           if(nota.monto_interes is null,p.total_abonado,p.total_abonado - nota.monto_interes) as Tabonado
                           from clientes c
						   INNER JOIN prestamos p on c.id_cliente= p.id_cliente
						   INNER join 	colaboradores cl on cl.id_colaborador = c.id_colaborador
						   INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                           LEFT JOIN  nota_credito nota on p.id_prestamo=nota.id_prestamo
                    where (p.fecha_vencimiento >='$fecha_bengin' and p.fecha_vencimiento <='$fecha_end')
                     $id_sucursal and p.estado = 'Aceptado'
                    ORDER by p.fecha_vencimiento";
        $result=$this->ejecutarSql($query);

        return $result;
    }

    public function ReportsByAldia($fecha_bengin,$fecha_end,$id_sucursal){
        $estado=" and p.estado <> 'Cancelado' and p.estado <> 'Rechazado' ";
        $query = "SELECT    *,
                                concat(c.nombre,' ' ,c.apellido) as cliente,
                                concat(cl.nombre,' ' ,cl.apellido) as colaborador

                    FROM        clientes c
                    INNER join  prestamos p  on c.id_cliente = p.id_cliente
					INNER join 	colaboradores cl on cl.id_colaborador = c.id_colaborador
					INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                    INNER JOIN  pagos pg     on pg.id_prestamo = p.id_prestamo
                    LEFT JOIN	moras m 	 on m.id_pago = pg.id_pago
                    WHERE (pg.fecha_cuota>= '$fecha_bengin' and pg.fecha_cuota<='$fecha_end') and  m.monto_mora=0  $id_sucursal $estado";
        $result=$this->ejecutarSql($query);

        return $result;
    }

    public function ReportsByPagos($fecha_bengin,$fecha_end,$id_sucursal){
        $query = " SELECT *,
                          concat(c.nombre ,' ',c.apellido) as cliente,
                          concat(cl.nombre ,' ',cl.apellido) as colaborador

                  from clientes c
                  INNER JOIN prestamos p on
                  c.id_cliente = p.id_cliente
				  INNER join 	colaboradores cl on cl.id_colaborador = c.id_colaborador
				  INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                  INNER JOIN pagos pg  on p.id_prestamo = pg.id_prestamo
                  where (pg.fecha_cuota >='$fecha_bengin' and pg.fecha_cuota <='$fecha_end')
                   $id_sucursal
                   and pg.estado = 'pagado'
				  order by pg.fecha_cuota ,colaborador,pg.hora_cuota

                    ";
                    //and pg.estado = "pagado"
        $result=$this->ejecutarSql($query);
        return $result;
    }

    public function ReportsByClientesAlDia()
    {
        $query = "consulta para los clientes que estan al Dia";
        $result = $this->ejecutarSql($query);
        return $result;
    }


    public function ReportsByClientesMontoAfavor(){
        $query = "consulta para los clientes que estan al Dia";
        $result=$this->ejecutarSql($query);
        return $result;
    }

	public function ReportsByColaboradoresMoraAcum($id_sucursal){
        $estado=" and p.estado = 'Aceptado'";
        $sucursal = $id_sucursal != 'All'? "and s.id_sucursal = $id_sucursal":'' ;
		$query = "SELECT cl.codigo_colaborador,
		cl.nombre,
		cl.apellido,
		cl.cedula,
		round(SUM(p.mora),2) as moras
		from clientes

		c INNER JOIN
		prestamos p on c.id_cliente= p.id_cliente
		INNER JOIN colaboradores cl on
		c.id_colaborador = cl.id_colaborador
		inner join sucursal s on s.id_sucursal = cl.id_sucursal
		where p.mora > 0 $sucursal
		$estado
		GROUP by cl.nombre
		ORDER by moras DESC ";

		 $result = $this->ejecutarSql($query);
        return $result;

	}

	public function ReportsByColaboradorespagos($fecha_bengin,$fecha_end,$id_sucursal){
  $sucursal = $id_sucursal != 'All'? "and s.id_sucursal=$id_sucursal":'';
	$query = "SELECT
		cl.nombre,
		cl.apellido,
		cl.cedula,
		p.codigo_prestamo,
    p.estado,
		c.nombre as nombrecliente,
		c.apellido as apellidocliente,
    pg.saldo_pendiente,
		pg.fecha_cuota,
		pg.hora_cuota,
		pg.monto_cuota ,

		round(sb.porcentaje_capital,2) as porcentaje_capital,
		round(sb.porcentaje_interes,2)as porcentaje_interes

	from clientes
	c INNER JOIN colaboradores cl on
	c.id_colaborador = cl.id_colaborador
	  INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
	INNER JOIN prestamos p on c.id_cliente = p.id_cliente
	INNER JOIN pagos pg on p.id_prestamo = pg.id_prestamo
	LEFT JOIN subpagos sb ON sb.id_pago = pg.id_pago
	 WHERE (pg.fecha_cuota>= '$fecha_bengin' and pg.fecha_cuota<='$fecha_end')
	  $sucursal  and  pg.estado = 'pagado'
	 order by pg.fecha_cuota
 ";

 	 $result = $this->ejecutarSql($query);
        return $result;
	}

	public function ReportsByPagosxColaborador($fecha_bengin,$fecha_end,$id_sucursal){
  
    $sucursal = $id_sucursal != 'All'? "and s.id_sucursal=$id_sucursal":'';
	  $query = "SELECT
		cl.nombre,
		cl.apellido,
		cl.cedula,

		round(SUM(pg.monto_cuota),2)as recaudado ,

		round(SUM(sb.porcentaje_capital),2) as pc,
		round(SUM(sb.porcentaje_interes),2) as pi

	from clientes
	c INNER JOIN colaboradores cl on
	c.id_colaborador = cl.id_colaborador
	  INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
	INNER JOIN prestamos p on c.id_cliente = p.id_cliente
	INNER JOIN pagos pg on p.id_prestamo = pg.id_prestamo
	INNER JOIN subpagos sb ON sb.id_pago = pg.id_pago

	 WHERE (pg.fecha_cuota>= '$fecha_bengin' and pg.fecha_cuota<='$fecha_end')  $sucursal and pg.estado = 'pagado'
	 group by cl.nombre
	 order by recaudado DESC
 ";

 

 	 $result = $this->ejecutarSql($query);
        return $result;
	}

  public function ReportsByTipoPrestamoColaborador($fecha_bengin,$fecha_end,$id_sucursal){


    $query = " SELECT 
s.sucursal,
concat(cl.nombre,' ',cl.apellido) as analista,
count(if(p.tipo_prestamo = 'Nuevo',1,NULL))as nuevo,
sum(if(p.tipo_prestamo = 'Nuevo',p.capital,0))as cnuevo,
count(if(p.tipo_prestamo = 'represtamo',1,NULL))as represtamo,
sum(if(p.tipo_prestamo = 'represtamo',p.capital,0))as creprestamo,
count(p.id_prestamo)as total2,
SUM(p.capital)as ctotal
from prestamos p
inner join clientes c on p.id_cliente = c.id_cliente
inner JOIN colaboradores cl on cl.id_colaborador = c.id_colaborador
inner join sucursal s on s.id_sucursal = cl.id_sucursal


 where  (p.fecha_desembolso >= '$fecha_bengin' and p.fecha_desembolso <= '$fecha_end') 
 and (p.estado='Aceptado' or p.estado = 'Cancelado')  
 and  cl.cargo = 'analista' $id_sucursal

group by cl.id_colaborador";


   $result = $this->ejecutarSql($query);
   return is_object($result)? [$result]:$result;

    }


public function ReportsPrestamos($id_sucursal){

	$query = "
     SELECT concat(c.nombre,' ',c.apellido)as cliente,
	concat(cl.nombre,' ',cl.apellido)as colaborador,
	p.codigo_prestamo,
	p.estado,
  p.capital,
  i.porcentaje,
	p.deuda_total,
	p.total_abonado,
	p.saldo_pendiente,
    round(((p.capital*100/p.deuda_total)/100*p.total_abonado),2)as pc_ta,
    round((((p.capital*i.porcentaje/100)*100/p.deuda_total)/100*p.total_abonado),2)as pi_ta,
    round(((p.capital*100/p.deuda_total)/100*p.saldo_pendiente),2)as pc_sp,
    round((((p.capital*i.porcentaje/100)*100/p.deuda_total)/100*p.saldo_pendiente),2) as pi_sp

from prestamos p INNER join clientes c ON p.id_cliente = c.id_cliente
INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
inner join sucursal s on cl.id_sucursal = s.id_sucursal
INNER JOIN intereses i on i.id_interes = p.id_interes

where p.estado =  'aceptado'
$id_sucursal

order by p.capital DESC

";

 	 $result = $this->ejecutarSql($query);
                return is_object($result)? [$result]:$result;
	}

				public function ReportsPrestamosFecha($id_sucursal,$fecha_bengin,$fecha_end){

	$query = "
  SELECT concat(c.nombre,' ',c.apellido) as cliente,
	       concat(cl.nombre,' ',cl.apellido )AS colaborador,
         p.codigo_prestamo,
         c.localidad,
         c.direccion,
	       p.estado,
	       p.tipo_prestamo,
         p.dia_desembolso,
 	       p.capital,
	       p.cuota,
         p.modalidad,
         i.porcentaje,
         p.total_abonado,
         p.deuda_total,
	     p.saldo_pendiente,
	     p.cuota, p.fecha_desembolso,
	     p.fecha_vencimiento



  FROM prestamos p
  INNER
  JOIN clientes c ON p.id_cliente = c.id_cliente
  INNER JOIN colaboradores cl ON c.id_colaborador = cl.id_colaborador
  INNER JOIN sucursal s ON s.id_sucursal = cl.id_sucursal
  INNER JOIN intereses i ON i.id_interes = p.id_interes
  WHERE s.id_sucursal =  '$id_sucursal'
  AND (p.fecha_desembolso >=  '$fecha_bengin'  AND p.fecha_desembolso <=  '$fecha_end'  )
  ORDER BY total_abonado DESC
";

 	 $result = $this->ejecutarSql($query);
        return $result;
	}




			public function ReportsPrestamosByCliente($id_sucursal){
	$query = "
   SELECT concat(c.nombre,' ',
	 c.apellido)as cliente,
	concat(cl.nombre,' ',
	 cl.apellido)as colaborador,
	 count(c.id_cliente)as nprestamos,
	round(avg(i.porcentaje),2)as porcentaje,
    SUM(p.capital) as capital,
	SUM(p.deuda_total)as deuda_total,
	SUM(p.total_abonado)as total_abonado,
	round(SUM(p.saldo_pendiente),2)as saldo_pendiente,
    SUM(round(((p.capital*100/p.deuda_total)/100*p.total_abonado),2))as pc_ta,
    SUM(round((((p.capital*i.porcentaje/100)*100/p.deuda_total)/100*p.total_abonado),2))as pi_ta,
    SUM( round(((p.capital*100/p.deuda_total)/100*p.saldo_pendiente),2))as pc_sp,
    SUM(round((((p.capital*i.porcentaje/100)*100/p.deuda_total)/100*p.saldo_pendiente),2)) as pi_sp
from prestamos p INNER join clientes c ON p.id_cliente = c.id_cliente
INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
inner join sucursal s on cl.id_sucursal = s.id_sucursal
INNER JOIN intereses i on i.id_interes = p.id_interes

where  p.estado =  'aceptado'
$id_sucursal
group by c.id_cliente
order by p.capital DESC

";

 	 $result = $this->ejecutarSql($query);
                return is_object($result)? [$result]:$result;
	}



					public function ReportsClienteFecha($id_sucursal,$fecha_bengin,$fecha_end){

	$query = "
SELECT c.nombre,
	 c.apellido,
   cl.nombre as colaborador,
	p.codigo_prestamo,
	p.estado,
    sum(p.capital) as capital,
    sum(i.porcentaje)as porcentaje,
	sum(p.deuda_total)as deuda_total,
	SUM(pg.monto_cuota) as total_abonado,
	p.saldo_pendiente


from prestamos p INNER join clientes c ON p.id_cliente = c.id_cliente
INNER JOIN colaboradores cl ON c.id_colaborador = cl.id_colaborador
inner join sucursal s on s.id_sucursal = cl.id_sucursal
INNER JOIN intereses i on i.id_interes = p.id_interes
LEFT join pagos pg on p.id_prestamo = pg.id_prestamo

WHERE s.id_sucursal =  '$id_sucursal'
AND (
p.fecha_desembolso >=  '$fecha_bengin'
AND p.fecha_desembolso <=  '$fecha_end'
)
	group by c.id_cliente
	order by total_abonado DESC

";

 	 $result = $this->ejecutarSql($query);
        return is_object($result)? [$result]:$result;
	}


	public function ReportsPrestamosByColaborador($id_sucursal){

	$query = "
         SELECT concat(cl.nombre,' ',
	 cl.apellido)as colaborador,
	 cl.codigo_colaborador,
	 count(c.id_cliente)as nprestamos,
	round(avg(i.porcentaje),2)as porcentaje,
    SUM(p.capital) as capital,
	SUM(p.deuda_total)as deuda_total,
	SUM(p.total_abonado)as total_abonado,
	SUM(p.saldo_pendiente)as saldo_pendiente,
    SUM(round(((p.capital*100/p.deuda_total)/100*p.total_abonado),2))as pc_ta,
    SUM(round((((p.capital*i.porcentaje/100)*100/p.deuda_total)/100*p.total_abonado),2))as pi_ta,
    SUM( round(((p.capital*100/p.deuda_total)/100*p.saldo_pendiente),2))as pc_sp,
    SUM(round((((p.capital*i.porcentaje/100)*100/p.deuda_total)/100*p.saldo_pendiente),2)) as pi_sp
from prestamos p INNER join clientes c ON p.id_cliente = c.id_cliente
INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
inner join sucursal s on cl.id_sucursal = s.id_sucursal
INNER JOIN intereses i on i.id_interes = p.id_interes

where  p.estado =  'aceptado'
$id_sucursal
group by cl.id_colaborador
order by p.capital DESC
";
 	 $result = $this->ejecutarSql($query);
        return is_object($result) ? [$result]:$result;
	}


			public function ReportsPrestamosByColaboradorFecha($id_sucursal,$fecha_bengin,$fecha_end){

	$query = "
SELECT cl.nombre,
	 cl.apellido,
	 cl.codigo_colaborador,
	sum(i.porcentaje)as porcentaje,
    SUM(p.capital) as capital,
	SUM(p.deuda_total)as deuda_total,
	SUM(pg.monto_cuota)as total_abonado,
	SUM(p.saldo_pendiente)as saldo_pendiente


from prestamos p INNER join clientes c ON p.id_cliente = c.id_cliente
INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
inner join sucursal s on cl.id_sucursal = s.id_sucursal
INNER JOIN intereses i on i.id_interes = p.id_interes
INNER JOIN pagos pg on p.id_prestamo = pg.id_prestamo
where s.id_sucursal = '$id_sucursal' and (p.fecha_desembolso>= '$fecha_bengin' and p.fecha_desembolso<='$fecha_end')  and p.estado like 'Aceptado'

group by cl.id_colaborador
order by total_abonado DESC
";
 	 $result = $this->ejecutarSql($query);
        return $result;
	}


	public function ReportsPrestamosByIdColaborador($id_colaborador){
			$query = "
			SELECT c.nombre,
		c.apellido,cl.nombre as colaborador,
		p.codigo_prestamo,
		p.estado,
		i.porcentaje,
		p.capital,
		p.deuda_total,
		p.total_abonado,
		p.saldo_pendiente


		from colaboradores cl inner join clientes c on
		cl.id_colaborador = c.id_colaborador
		inner join prestamos p on
		c.id_cliente = p.id_cliente
		inner join intereses i on
		p.id_interes = i.id_interes

		where cl.id_colaborador = '$id_colaborador'
		order by total_abonado DESC

	";
 	 $result = $this->ejecutarSql($query);
        return $result;

	}

	public function ReportsAllColaboradores($id_sucursal){

		$query = "
SELECT c.nombre,
	   c.apellido,
	   c.codigo_colaborador,
	   c.cargo,
	   c.cedula,
	   c.inss,
	   c.telefono,
	   c.fecha_ingreso,
	   c.direccion


from colaboradores c INNER join sucursal s ON s.id_sucursal = c.id_sucursal

where s.id_sucursal = '$id_sucursal'

order by fecha_ingreso ASC

";
		$result = $this->ejecutarSql($query);
        return $result;

	}

public function ReportsByColaboradoresArqueo($fecha_time_begin,$fecha_time_end,$id_sucursal){
$query="SELECT concat(c.nombre,' ', c.apellido) as colaborador,
mil_c,
quinientos_c,
doscientos_c ,
cien_c,
cincuenta_c,
veinte_c ,
diez_c,
cinco_c,
uno_c,
cien_usd,
cincuenta_usd,
veinte_usd,
diez_usd,
cinco_usd,
uno_usd,
total_recaudo,
total_efectivo,
minuta_cheque,
monto_cheque,
desembolso,
(a.total_efectivo - a.total_recaudo ) as diferencia
FROM arqueo a
inner join colaboradores c on a.id_colaborador = c.id_colaborador
inner join sucursal s on s.id_sucursal = c.id_sucursal
where (a.fecha_time_begin >='$fecha_time_begin'and a.fecha_time_end <= '$fecha_time_end') and a.estado='guardado'
$id_sucursal
    ";

    $result = $this->ejecutarSql($query);
    return $result;

  }

  public function ReportsByColaboradoresArqueoGroup($fecha_time_begin,$fecha_time_end,$id_sucursal){
$query="SELECT concat(c.nombre,' ', c.apellido) as colaborador,
    sum(a.mil_c) as mil_c,
    sum(a.quinientos_c) as quinientos_c,
    sum(a.doscientos_c) as doscientos_c ,
    sum(a.cien_c) as cien_c,
    sum(a.cincuenta_c) as cincuenta_c,
    sum(a.veinte_c) as veinte_c ,
    sum(a.diez_c)as diez_c,
    sum(a.cinco_c)as cinco_c,
    sum(a.uno_c)as uno_c,
    sum(a.cien_usd )as cien_usd,
    sum(a.cincuenta_usd)as cincuenta_usd,
    sum(a.veinte_usd) as veinte_usd,
    sum(a.diez_usd) as diez_usd,
    sum(a.cinco_usd) as cinco_usd,
    sum(a.uno_usd) as uno_usd,
    sum(a.total_recaudo) as total_recaudo,
    sum(a.total_efectivo) as total_efectivo,
    sum( a.total_efectivo - a.total_recaudo) as diferencia
    FROM arqueo a
    inner join colaboradores c on a.id_colaborador = c.id_colaborador
    inner join sucursal s on s.id_sucursal = c.id_sucursal
    where (a.fecha_time_begin >='$fecha_time_begin'and a.fecha_time_end <= '$fecha_time_end') and a.estado='guardado'
    $id_sucursal
    group by colaborador
    ";

    $result = $this->ejecutarSql($query);
    return $result;

  }


public function  RptsDatosClientes($id_sucursal){

$query = "SELECT *,concat(cli.nombre,' ',cli.apellido) as cliente, concat(col.nombre,' ',col.apellido) as analista, cli.telefono as tel1,col.telefono as tel2, cli.cedula as ced1, col.cedula as ced2, cli.direccion as dir1,cli.sexo as sex1,
(select if(count(*)>0,'Activo','Inactivo')
from clientes c
inner join colaboradores col on c.id_colaborador = col.id_colaborador
inner join prestamos p on p.id_cliente = c.id_cliente
 where cli.id_cliente = c.id_cliente and p.estado ='Aceptado'
)as estado

from clientes cli
inner join colaboradores col on cli.id_colaborador = col.id_colaborador
inner join sucursal s on s.id_sucursal = col.id_sucursal
$id_sucursal";

echo $query;



$result = $this->ejecutarSql($query);
return $result;

}



public function cancelacion($fecha_begin,$fecha_end,$id_sucursal){

$query = "SELECT 
 concat(c.nombre,' ',c.apellido) as cliente,
 concat(cl.nombre,' ',cl.apellido) as analista,
  p.codigo_prestamo,
  p.estado,
  pg.hora_cuota,
  DATE_FORMAT(p.fecha_desembolso, '%d %M %Y') as fecha_desembolso,
  DATE_FORMAT(p.fecha_vencimiento, '%d %M  %Y') as fecha_vencimiento,
  DATE_FORMAT(pg.fecha_cuota, '%d %M  %Y') as fecha_cancelacion,
  DATEDIFF(pg.fecha_cuota,p.fecha_vencimiento) as diasvencidos
from clientes c inner JOIN prestamos p on p.id_cliente = c.id_cliente 
inner join colaboradores cl on cl.id_colaborador = c.id_colaborador 
inner join pagos pg on p.id_prestamo = pg.id_prestamo 
inner join sucursal s on s.id_sucursal = cl.id_sucursal 
where pg.fecha_cuota >= '$fecha_begin' and pg.fecha_cuota <= '$fecha_end' and pg.saldo_pendiente = '0' and pg.estado = 'pagado' $id_sucursal
order by cl.id_colaborador";


$result = $this->ejecutarSql($query);
return $result;


}

public function RptsPrimerasCuotas($id_sucursal,$fecha){

$query = "SELECT  
                if(pagadod.pagado = 1,'Si','No')as pagado,
                concat(cl.nombre,' ',cl.apellido)as analista,
                concat(c.nombre,' ',c.apellido)as cliente,        
                s.sucursal,
                p.codigo_prestamo,              
                p.fecha_desembolso,                
                p.tipo_prestamo,
                p.modalidad
        
                FROM colaboradores cl
                INNER JOIN clientes c ON cl.id_colaborador=c.id_colaborador
                INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
                inner join sucursal s on s.id_sucursal = cl.id_sucursal 

                 LEFT JOIN (SELECT 1 as pagado ,pa.id_prestamo from pagos pa where pa.estado='pagado' and pa.monto_cuota > 0) pagadod on  pagadod.id_prestamo =p.id_prestamo

              where  p.estado LIKE 'Aceptado' 
and ((datediff('$fecha',p.fecha_desembolso) = 1 and p.modalidad = 'Diario')
or  ( p.modalidad = 'Diario' and datediff('$fecha',p.fecha_desembolso) =3 and  weekday(p.fecha_desembolso)=4 )
or  ( p.modalidad = 'Diario' and datediff('$fecha',p.fecha_desembolso) =2 and  weekday(p.fecha_desembolso)=5 )     
or  ( p.modalidad = 'Semanal' and  datediff('$fecha',p.fecha_desembolso) = 7 )
or  ( p.modalidad = 'Semanal'and  datediff('$fecha',p.fecha_desembolso) = 6 and weekday(p.fecha_desembolso)=5 )    
    ) $id_sucursal              
                ";

$result = $this->ejecutarSql($query);
return $result;

}

public function RptsSaldoPendiente($id_sucursal){

$query = "SELECT
concat(cl.nombre,' ',cl.apellido )as analista,
concat(c.nombre,' ',c.apellido )as cliente,
s.sucursal,
p.codigo_prestamo,
p.fecha_desembolso,
p.fecha_vencimiento,
p.modalidad,
p.tipo_prestamo,
if(datediff(curdate(),p.fecha_vencimiento)>0,'Vencido','Vigente') as estado,
datediff(curdate(),p.fecha_vencimiento)as ndiasv,
p.capital,
p.deuda_total,
round(p.saldo_pendiente,2) as saldo_pendiente

FROM       clientes c
           INNER join  prestamos p  on c.id_cliente = p.id_cliente
           INNER join  colaboradores cl on cl.id_colaborador = c.id_colaborador
           INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
           WHERE p.saldo_pendiente <= (p.deuda_total*0.20)  and  cl.cargo = 'analista' and p.estado = 'Aceptado'

           $id_sucursal

   order by estado DESC, analista ASC 

   ";


$result = $this->ejecutarSql($query);
return $result;

}


public function RptsSaldoPendienteMenores($id_sucursal){

$query = "SELECT
concat(cl.nombre,' ',cl.apellido )as analista,
concat(c.nombre,' ',c.apellido )as cliente,
s.sucursal,
p.codigo_prestamo,
p.fecha_desembolso,
p.fecha_vencimiento,
p.modalidad,
p.tipo_prestamo,
if(datediff(curdate(),p.fecha_vencimiento)>0,'Vencido','Vigente') as estado,
datediff(curdate(),p.fecha_vencimiento)as ndiasv,
p.capital,
p.deuda_total,
round(p.saldo_pendiente,2) as saldo_pendiente

FROM       clientes c
           INNER join  prestamos p  on c.id_cliente = p.id_cliente
           INNER join  colaboradores cl on cl.id_colaborador = c.id_colaborador
           INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
           WHERE p.saldo_pendiente <= 1500  and  cl.cargo = 'analista' and p.estado = 'Aceptado'

           $id_sucursal

   order by estado DESC, analista ASC 

   ";


$result = $this->ejecutarSql($query);
return $result;

}

    public function Rpts_cumplimiento_metas($id_sucursal,$weekday,$fecha){
      $query="SELECT 
s.sucursal,
concat(cl.nombre,' ',cl.apellido) as analista,

SUM(IF(p.fecha_vencimiento>=' $fecha' ,ROUND(p.cuota,2),0 ) ) as metactivos,
recupera.Recaudo_Pvigente,

concat ( ROUND( ((recupera.Recaudo_Pvigente * 100)/ SUM(IF(p.fecha_vencimiento>=' $fecha' ,p.cuota,0 ) )) ,2) , ' %') as cumplimiento_vigente,

SUM(IF(p.fecha_vencimiento< '$fecha'  ,round(p.cuota,2),0 ) ) as metavencidos,
recupera.Recaudo_vencidos,

concat ( ROUND( ((recupera.Recaudo_vencidos * 100)/ SUM(IF(p.fecha_vencimiento < ' $fecha' ,p.cuota,0 ) )) ,2) , ' %') as cumplimiento_vencido,


SUM(ROUND(p.cuota,2)) as metaglobal,
recupera.Recaudo_Global,

concat ( ROUND(((recupera.Recaudo_Global * 100)/ SUM(p.cuota) ) ,2) , ' %') as cumplimiento_total


from 
prestamos p 
inner JOIN clientes c on p.id_cliente = c.id_cliente
INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
INNER JOIN sucursal s ON s.id_sucursal = cl.id_sucursal

left JOIN(

SELECT
    col.id_colaborador,
SUM( IF( p.fecha_vencimiento >= '$fecha' , round(pg.monto_cuota,2), 0 ) ) as Recaudo_Pvigente,
SUM( IF( p.fecha_vencimiento < '$fecha' , round(pg.monto_cuota,2), 0 ) ) as Recaudo_vencidos,
SUM(round(pg.monto_cuota,2) ) as Recaudo_Global
    
FROM clientes c
    
INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
INNER JOIN colaboradores col ON col.id_colaborador = c.id_colaborador
INNER JOIN sucursal sl ON sl.id_sucursal = col.id_sucursal
INNER JOIN pagos pg ON pg.id_prestamo = p.id_prestamo
WHERE pg.fecha_cuota = '$fecha'
AND pg.estado =  'pagado' GROUP by col.id_colaborador

) as recupera on recupera.id_colaborador = cl.id_colaborador

 
        where   p.estado = 'Aceptado' and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday')
        and p.fecha_desembolso < '$fecha' $id_sucursal

        GROUP by cl.id_colaborador
        order by s.id_sucursal
        ";

      $result = $this->ejecutarSql($query);
      return is_object($result)?[$result]:$result;

    }



       public function Rpts_bosquesdemora_x_analistas($id_sucursal){

       $query = "	SELECT 
concat(cl.nombre,' ',cl.apellido )as analista,
count( if(  (p.mora/p.cuota) > '0' and (p.mora/p.cuota) < 5 and  p.fecha_vencimiento>= curdate(),1,NULL )
     )as temprana,

SUM(if( (p.mora/p.cuota) > '0' and (p.mora/p.cuota) < 5 and p.fecha_vencimiento>= curdate(), round(p.mora,3),0 ))as mora1,


count( if(  (p.mora/p.cuota) >=5 and (p.mora/p.cuota) < 9 and  p.fecha_vencimiento>= curdate(),1,NULL )
     )as avanzada,

SUM(if( (p.mora/p.cuota) >5 and (p.mora/p.cuota) < 9 and p.fecha_vencimiento>= curdate(), round(p.mora,3),0 ))as mora2,


count( if(  (p.mora/p.cuota) >=9 and (p.mora/p.cuota) < 13 and  p.fecha_vencimiento>= curdate(),1,NULL )
     )as riesgo,

SUM(if( (p.mora/p.cuota) >=9 and (p.mora/p.cuota) < 13 and p.fecha_vencimiento>= curdate(), round(p.mora,3),0 ))as mora3,

count( if(  (p.mora/p.cuota) > 13 and  p.fecha_vencimiento>= curdate(),1,NULL )
     )as perdido,

SUM(if( (p.mora/p.cuota) > 13 and p.fecha_vencimiento>= curdate(), round(p.mora,3),0 ))as mora4,


count( if(  p.fecha_vencimiento < curdate(),1,NULL )
     )as vencido,

SUM(if( p.fecha_vencimiento < curdate(), round(p.mora,3),0 ))as mora5


FROM   	   clientes c
		   INNER join  prestamos p  on c.id_cliente = p.id_cliente
		   INNER join  colaboradores cl on cl.id_colaborador = c.id_colaborador
           INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
           WHERE p.mora > '0'  and  cl.cargo = 'analista' and p.estado = 'Aceptado'
           $id_sucursal
                   
                
                    group by cl.id_colaborador";

            // echo $query;

        $result = $this->ejecutarSql($query);
     	return is_object($result)?[$result]:$result;



       }



       public function Rpts_cuotas_del_dia($id_sucursal,$weekday,$fecha){

       $query = "	SELECT DISTINCT                              
                concat(c.nombre,' ',c.apellido)as cliente,
                concat(cl.nombre,' ',cl.apellido)as analista,
                s.sucursal,
                p.codigo_prestamo,
                if(pagadod.pagado=1,'SI','NO')as pagado,
                p.estado,
                p.dia_desembolso,
                pagadod.fecha_cuota,
                round(p.cuota,2) as cuota,
                round(pagadod.monto_cuota,2)as monto_cuota,
                pagadod.saldo_pendiente
           
            
                FROM colaboradores cl
                INNER JOIN clientes c ON cl.id_colaborador=c.id_colaborador
                INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
                inner join sucursal s on s.id_sucursal = cl.id_sucursal
                LEFT JOIN (SELECT 1 as pagado ,pa.id_prestamo ,pa.fecha_cuota, pa.saldo_pendiente ,pa.monto_cuota from pagos pa where pa.fecha_cuota='$fecha' and pa.estado='pagado' and pa.monto_cuota > 0) pagadod on
                pagadod.id_prestamo =p.id_prestamo
                
where  (p.estado = 'aceptado' or (p.estado = 'Cancelado' and pagadod.saldo_pendiente = 0 )) 
AND (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday') and p.fecha_desembolso != '$fecha'
$id_sucursal

order by analista ASC";


        $result = $this->ejecutarSql($query);
     	return is_object($result)?[$result]:$result;



       }


}
?>
