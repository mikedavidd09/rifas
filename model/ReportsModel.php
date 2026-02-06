<?php

class ReportsModel extends ModeloBase
{
    private $table;

    public function __construct($adapter,$table ="")
    {
        $this->table = $table;
        parent::__construct($this->table, $adapter);
    }
    public function generarReportsAumentoDeCapitalForMonht($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        //$range_date = "ps.estado in('Activo','Cancelado') and (ps.fecha_desembolso >='$fecha_bengin' and ps.fecha_desembolso <='$fecha_end') $moneda";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                        c.codigo_cliente,
                        c.nombre,
                        c.apellido,
                        p_actual.codigo_prestamo AS prestamo_activo,
                        p_actual.capital AS capital_actual,
                        p_anterior.codigo_prestamo AS prestamo_anterior,
                        p_anterior.capital AS capital_anterior,
                        col.codigo_colaborador,
                        col.nombre AS nombre_colaborador,
                        col.apellido AS apellido_colaborador,
                        s.sucursal as Sucursal,
                        (p_actual.capital - p_anterior.capital) AS monto_aumentado

                    FROM
                        clientes c
                    JOIN prestamos p_actual 
                        ON p_actual.id_cliente = c.id_cliente 
                        AND p_actual.estado = 'Aceptado'
                        AND MONTH(p_actual.fecha_desembolso) = MONTH(CURRENT_DATE())
                        AND YEAR(p_actual.fecha_desembolso) = YEAR(CURRENT_DATE())
                    JOIN (
                        -- Último préstamo anterior al actual por cliente
                        SELECT 
                            p1.id_cliente,
                            MAX(p1.fecha_desembolso) AS fecha_anterior
                        FROM 
                            prestamos p1
                        WHERE 
                            p1.estado != 'Aceptado'
                        GROUP BY 
                            p1.id_cliente
                    ) ultima_anterior 
                        ON ultima_anterior.id_cliente = c.id_cliente
                    JOIN prestamos p_anterior 
                        ON p_anterior.id_cliente = c.id_cliente 
                        AND p_anterior.fecha_desembolso = ultima_anterior.fecha_anterior
                    LEFT JOIN colaboradores col 
                        ON col.id_colaborador = c.id_colaborador
                    INNER JOIN sucursal s
                    on s.id_sucursal = col.id_sucursal  $id_sucursal
                    WHERE 
                        p_actual.capital > p_anterior.capital
                    ORDER BY 
                        monto_aumentado DESC;
                                      ";
       // print_r($query);exit;
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByDataSinRiesgoClientes($parametros)
    {
        $parametros = explode(',',$parametros);
        $sucursal =  ($parametros[0] == "All") ? '':"and c.id_sucursal=".$parametros[0];
        $fecha_bengin = $parametros[1];
        $fecha_end    = $parametros[2];
        $groud_by_ = "";
        $morosos = "";
            $range_date = "p.estado IN ('Aceptado','Cancelado') and p.fecha_desembolso <= '$fecha_end' $sucursal";

        $groud_by_= " GROUP by IDENTIFICACION";
        $query="SELECT 
						1 AS ENTIDAD, 
						DATE_FORMAT('$fecha_end','%d/%m/%Y') AS FECHA_REPORTE, 
						REPLACE(cl.cedula,'-','') as IDENTIFICACION, 
						'NATURAL' AS TIPO_DE_PERSONA,
						'NICARAGUENSE' AS NACIONALIDAD, 
						cl.sexo, '01/01/1990' as FECHA_NACIMIENTO, 
						'SOLTERO' AS ESTADO_CIVIL,
						cl.direccion as DIRECCION_DOMICILIO,
						IF(c.id_sucursal = 1,'LEON',IF(c.id_sucursal = 2,'CHINANDEGA','ESTELI')) as DEPARTAMENTO_DE_DOMICILIO,
						 IF(c.id_sucursal = 1,'LEON',IF(c.id_sucursal = 2,'CHINANDEGA','ESTELI')) as MUNICIPIO_DOMICILIO,
						 IF(c.id_sucursal = 1,'LEON',IF(c.id_sucursal = 2,'CHINANDEGA','ESTELI')) as DIRECCION_DE_TRABAJO,
						 IF(c.id_sucursal = 1,'LEON',IF(c.id_sucursal = 2,'CHINANDEGA','ESTELI')) as DEPARTAMENTO_DE_TRABAJO,
						 IF(c.id_sucursal = 1,'LEON',IF(c.id_sucursal = 2,'CHINANDEGA','ESTELI')) as MUNICIPIO_DE_TRABAJO,
						 '' as TELEFONO_DOMICILIAR,
						 '' as TELEFONO_DE_TRABAJO,
						cl.telefono as CELULAR, 
						'NO TIENE' AS CORREO_ELECTRONICO, 
						'COMERCIANTE' AS OCUPACION, 
						cl.tipo_negocio as ACTIVIDAD_ECONOMICA,
						'COMERCIO' AS SECTOR
					from colaboradores c
					INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
					INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
					where $range_date $groud_by_";
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }


    public function generarReportsByDataSinRiesgoPrestamos($parametros)
    {
        $parametros = explode(',',$parametros);
        $sucursal =  ($parametros[0] == "All") ? "":"and c.id_sucursal=".$parametros[0];
        $fecha_bengin = $parametros[1];
        $fecha_end    = $parametros[2];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "p.estado IN ('Aceptado','Cancelado') and p.fecha_desembolso <= '$fecha_end' $sucursal";
        $groud_by_= " ";
        $query="SELECT 
						6 AS ENTIDAD, 
						'123bb' AS NUMERO_CORRELATIVO, 
						DATE_FORMAT('$fecha_end','%d/%m/%Y') AS FECHA_DE_REPORTE, 
						IF(c.id_sucursal = 1,8,IF(c.id_sucursal = 2,3,5)) AS DEPARTAMENTO, 
						REPLACE(cl.cedula,'-','') as CEDULA, 
						CONCAT(cl.nombre,' ',cl.apellido) AS NOMBRE, 
						7 as TIPO_DE_CREDITO, 
						p.fecha_desembolso as FECHA_DE_DESEMBOLSO, 
						1 as TIPO_DE_OBLIGACION, 
						p.capital as  MONTO_AUTORIZADO, 
						IF(p.modalidad = 'Diario',pl.numero_cuotas/20,pl.numero_cuotas/4) as PLAZO, 
						9 as FRECUENCIA_DE_PAGO, 
						p.saldo_pendiente as  SALDO_DEUDA, 
						IF(p.estado = 'Cancelado',3,IF(p.fecha_vencimiento <= '$fecha_end',2,1)) as ESTADO, 
						IF(p.fecha_vencimiento < '$fecha_end',p.saldo_pendiente,0) as  MONTO_VENCIDO, 
						if(p.fecha_vencimiento < '$fecha_end',IF(p.estado = 'Cancelado',0,DATEDIFF('$fecha_end',p.fecha_vencimiento)),0)  as ANTIGUEDAD_DE_MORA, 
						5 as TIPO_DE_GARANTIA,
						1 AS FORMA_DE_RECUPERACION,
						p.codigo_prestamo as NUMERO_DE_CREDITO, 
						p.cuota as  VALOR_DE_LA_CUOTA
					from colaboradores c
					INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
					INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
					INNER JOIN plazos pl on p.id_plazo=pl.id_plazo
					where $range_date $groud_by_";
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    //Metodos de consulta
    public function generarReportsPrestamosDesembolsos($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "ps.estado ='Aceptado' and (ps.fecha_desembolso >='$fecha_bengin' and ps.fecha_desembolso <='$fecha_end')";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  ps.codigo_prestamo as Prestamo,
                  CONCAT(c.nombre,' ',c.apellido) AS Cliente,
                  c.codigo_cliente as C_Cliente,
                  c.telefono as Telefono,
                  c.direccion as Direccion,
                  c.tipo_negocio as Tipo_negocio,
                  cs.codigo_colaborador as C_Colaborador,
                  cs.nombre as Colaborador,
                  ROUND(ps.capital,2) AS Capital,
                  ROUND(ps.deuda_total,2) AS Deuda,
                  ROUND(ps.saldo_pendiente,2) Saldo_pendiente,
                  ROUND(ps.cuota ,2) Cuota,
                  ps.Estado as Estado,
                  ps.tipo_prestamo as Tipo_Prestamo,
                  pz.numero_cuotas as Numero_cuotas,
                  pz.equivalen_mes as Equivalente_Mes,
                  ps.modalidad as Modalidad,
                  ps.monto_favor as Monto_Favor,
                  ps.fecha_desembolso as Fecha_Desemboldo,
                  ps.fecha_vencimiento as Fecha_Vencimiento,
                  (SELECT col.nombre from historial h 
                    inner join colaboradores col on h.id_colaborador=col.id_colaborador and h.id_colaborador=hist.id_colaborador
                    where h.submodulo='Crear' limit 1) as Creado_Por
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps ON ps.id_cliente = c.id_cliente
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  LEFT JOIN  historial hist on ps.id_prestamo=hist.id_prestamo and hist.submodulo='Crear' 
                  $morosos
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
        //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsPrestamosDesembolsosAgrupado($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "p.estado = 'Aceptado' and p.fecha_desembolso >='$fecha_bengin' and p.fecha_desembolso <='$fecha_end'";

        $groud_by_= " GROUP BY c.id_colaborador";

        $query = "SELECT
                  c.codigo_colaborador as Codigo,
                  CONCAT(c.nombre,' ',c.apellido) as Colaborador,
                  sum(p.capital) as Monto_Desembolsado_$,
                  round(sum(((p.capital/p.deuda_total) * p.deuda_total)),2) as por_capital_C$,
                  sum(p.deuda_total - p.capital) as por_interes_C$
                  FROM sucursal s inner join colaboradores c on s.id_sucursal=c.id_sucursal
                                  inner join clientes cl on c.id_colaborador=cl.id_colaborador
                                  inner join prestamos p on cl.id_cliente=p.id_cliente
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
                  
                  
                //  print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsPrestamosVencidos($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "ps.estado = 'Aceptado' and (ps.fecha_vencimiento >='$fecha_bengin' and ps.fecha_vencimiento <='$fecha_end')";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido) AS Cliente,
                    cs.nombre as Colaborador,
                  ROUND(ps.capital,2) AS Capital,
                  ROUND(ps.deuda_total,2) AS Deuda,
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(ps.saldo_pendiente,2) Saldo_pendiente,
                  ps.dia_desembolso,
                  ROUND(ps.cuota ,2) Cuota,
                  ROUND(ps.mora ,2) Mora_actual,
                  ps.tipo_prestamo as Tipo_Prestamo,
                  ps.modalidad as Modalidad,
                  ps.fecha_vencimiento as Fecha_Vencimiento
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps ON ps.id_cliente = c.id_cliente
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                                    $morosos
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarClientesNoAbonosCartera($groud_by,$filter_by,$date_range,$id_sucursal,$dia_semana,$dia_semanaf){
      date_default_timezone_set('America/Managua');
      $diaInicio="Monday";
      $strFecha = strtotime($date_range['bengin']);
      $subconsulta="";
     
     $fecha_actual = date("Y-m-d");
      $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];

      $subconsulta = (($dia_semanaf == "Lunes") ? "pr.dia_desembolso ='$dia_semanaf'":(($dia_semanaf == "Martes") ? "pr.dia_desembolso ='$dia_semana' or pr.dia_desembolso ='$dia_semanaf'":(($dia_semanaf == "Miercoles") ? "pr.dia_desembolso ='$dia_semana' or pr.dia_desembolso ='Martes' or pr.dia_desembolso ='$dia_semanaf'":(($dia_semanaf == "Jueves") ? "pr.dia_desembolso ='$dia_semana' or pr.dia_desembolso ='Martes' or pr.dia_desembolso ='Miercoles' or pr.dia_desembolso ='$dia_semanaf'":("pr.dia_desembolso ='$dia_semana' or pr.dia_desembolso ='Martes' or pr.dia_desembolso ='Miercoles' or pr.dia_desembolso ='Jueves' or pr.dia_desembolso ='$dia_semanaf'")))));
      
      $subconsulta = ($dia_semana == $dia_semanaf) ? "pr.dia_desembolso ='$dia_semanaf'":$subconsulta;

      $fdesembolso = ($dia_semana == $dia_semanaf) ? "(pr.fecha_desembolso != '$fecha_bengin')":"(pr.fecha_desembolso not between '$fecha_bengin' and '$fecha_end')";

    $fechaInicio = date('Y-m-d',strtotime('last '.$diaInicio,$strFecha));
      

     
        $groud_by_ = "";
        $morosos = "";
        $range_date = "(pr.estado='Aceptado') and (($subconsulta) or (pr.dia_desembolso ='Diario' and TIMESTAMPDIFF(DAY, ultaboefectivo.fecha_pago, CURDATE()) > 2) or (pr.dia_desembolso='Quincenal' and (IF(WEEKDAY(DATE_ADD(pr.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0)))*15) DAY)) = 5,
        DATE_ADD(DATE_ADD(pr.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0)))*15) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(pr.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0)))*15) DAY))=6,DATE_ADD(DATE_ADD(pr.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0)*15) DAY), INTERVAL 1 DAY),DATE_ADD(pr.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/15),0)))*15) DAY))))<='$fecha_end') or (pr.dia_desembolso='Mensual' and IF(WEEKDAY(DATE_ADD(pr.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/30),0)*30) DAY)) = 5,DATE_ADD(DATE_ADD(pr.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/30),0)*30) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(pr.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/30),0)*30) DAY))=6,DATE_ADD(DATE_ADD(pr.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/30),0)*30) DAY), INTERVAL 1 DAY),DATE_ADD(pr.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,pr.fecha_desembolso,'$fecha_end')/30),0)*30) DAY))) <='$fecha_end') and pr.primera_cuota <'$fecha_end') and ($fdesembolso and (pa.monto is NULL or pa.monto=0)) and sa.sigla is null and pr.fecha_desembolso < CURDATE()";//and (pa.pagado is NULL or pa.pagado=0)

        $groud_by_= " GROUP BY pr.codigo_prestamo";

      $query="SELECT 
              concat(cl.nombre,' ',cl.apellido) as NombreCliente,
              col.nombre as Gestor,
              pr.codigo_prestamo as CP,
              pr.saldo_pendiente as saldo,
              sum(pr.cuota) as cuota,
              pr.mora,
              round((pr.mora/pr.cuota),2) as CAtrasadas,
              pr.fecha_desembolso as FDSEM,
              pr.fecha_vencimiento as FVEC,
              pr.dia_desembolso as Dia_pago,
               ultaboefectivo.monto Ult_monto,
               ultaboefectivo.fecha_pago ult_fecha_pago
              FROM sucursal s 
              INNER join colaboradores col on s.id_sucursal=col.id_sucursal
              INNER join clientes cl on col.id_colaborador=cl.id_colaborador
              INNER join prestamos pr on cl.id_cliente=pr.id_cliente
              LEFT JOIN (select max(pa.fecha_pago) as fecha_pago,pa.monto,pa.id_prestamo,max(pa.id_pago) as id_pago from pagos_new pa where pa.monto > 0 and pa.estado='Pagado' group by pa.id_prestamo order by pa.fecha_pago desc) as  ultaboefectivo on pr.id_prestamo=ultaboefectivo.id_prestamo
              LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=pr.id_prestamo
              LEFT JOIN (SELECT DISTINCT sum(pa.monto_cuota) as monto,pa.id_prestamo from pagos pa where pa.fecha_cuota between '$fecha_bengin' and '$fecha_end' and pa.estado='pagado' and pa.monto_cuota > 0  Group by pa.id_prestamo) pa on pa.id_prestamo=pr.id_prestamo
              where $range_date $id_sucursal $groud_by_";
           
       //print_r($query);
             $reporte = $this->ejecutarSqlReports($query);
             
        return $reporte;
    }
    public function generarReportsPrestamosFullField($groud_by,$filter_by,$date_range,$id_sucursal,$dia_semana)
    {
      date_default_timezone_set('America/Managua');
        $fecha_actual= date("Y-m-d");
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "(ps.estado='Aceptado') and ((ps.fecha_desembolso != '$fecha_bengin') and (ps.dia_desembolso = 'Diario' or ps.dia_desembolso = '$dia_semana')) and sa.sigla is null";
         //(weekday(ps.fecha_desembolso) = weekday('$fecha_bengin')) and (week(ps.fecha_desembolso) <> week('$fecha_actual')) or ps.modalidad='Diario')";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  ps.codigo_prestamo as Prestamo,
                  CONCAT(c.nombre,' ',c.apellido) AS Cliente,
                  c.telefono as Telefono,
                  c.direccion as Direccion_Domicilio,
                  c.tipo_negocio as Tipo_negocio,
                  cs.nombre as Colaborador,
                  ROUND(ps.capital,2) as  Capital,
                  ROUND(ps.deuda_total,2) as Deuda,
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(ps.saldo_pendiente,2) as Saldo_pendiente,
                  ROUND((ps.saldo_pendiente/ps.cuota),0) as Cuotas_faltantes,
                  ROUND(ps.cuota,2) as Cuota,
                  ROUND(ps.mora,2) AS Mora_actual,
                   ROUND((ps.mora/ps.cuota),3) as Cuotas_Atrasadas,
				  ps.dia_desembolso AS Dia_Pago,
                  IF('$fecha_actual'>ps.fecha_vencimiento,  'Vencido', 'Activo')  as Estado,
                  ps.fecha_desembolso as Fecha_Desemboldo,
                  ps.fecha_vencimiento as Fecha_Vencimiento,
                 
                  if('$fecha_bengin' > ps.fecha_vencimiento,'Mora Vencida',if(ROUND((ps.mora/ps.cuota),3) > 0 and ROUND((ps.mora/ps.cuota),3) < 4,'Mora Temprana',if(ROUND((ps.mora/ps.cuota),3) > 4 and ROUND((ps.mora/ps.cuota),3) < 8,'Mora Avanzada',if(ROUND((ps.mora/ps.cuota),3) > 8,'Mora Riesgo','Al Dia')))) as Tipo_de_Mora,
				  date_format(pg.Fecha_ult_abono,'%d/%m/%Y') as Fecha_ult_abono
                  FROM sucursal s 
				  inner join  colaboradores cs on s.id_sucursal=cs.id_sucursal
                  INNER JOIN clientes c ON cs.id_colaborador = c.id_colaborador
                  INNER JOIN prestamos ps ON ps.id_cliente = c.id_cliente
                  LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=ps.id_prestamo
				  LEFT JOIN (select pag.id_pago,pag.id_prestamo,pag.usuario,
                                    pag.fecha_cuota as Fecha_ult_abono ,
                                    pag.monto_cuota as Ultima_cuota 
                            from pagos pag where pag.id_pago=(select max(pago.id_pago) from pagos pago where pago.id_prestamo=pag.id_prestamo and pago.monto_cuota > 0 and pago.estado='pagado')) pg on ps.id_prestamo=pg.id_prestamo
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  $morosos
                  WHERE $range_date $id_sucursal
                  $groud_by_
                  ";
				// print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
     public function generarReportsCarterarresumida($groud_by,$filter_by,$date_range,$id_sucursal,$dia_semana)
    {
      date_default_timezone_set('America/Managua');
        $fecha_actual= date("Y-m-d");
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
         $range_date = "(ps.estado='Aceptado') and ((ps.fecha_desembolso != '$fecha_bengin') and (ps.dia_desembolso = 'Diario' or ps.dia_desembolso = '$dia_semana' or (ps.dia_desembolso='Quincenal' and (IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0)))*15) DAY)) = 5,
        DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0)))*15) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0)))*15) DAY))=6,DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0)*15) DAY), INTERVAL 1 DAY),DATE_ADD(ps.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/15),0)))*15) DAY))))='$fecha_bengin') or (ps.dia_desembolso='Mensual' and IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/30),0)*30) DAY)) = 5,DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/30),0)*30) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/30),0)*30) DAY))=6,DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/30),0)*30) DAY), INTERVAL 1 DAY),DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_bengin')/30),0)*30) DAY)))='$fecha_bengin'))) and sa.sigla is null";
        //$range_date = "(ps.estado='Aceptado') and ((ps.fecha_desembolso != '$fecha_bengin') and (weekday(ps.fecha_desembolso) = weekday('$fecha_bengin')) and (week(ps.fecha_desembolso) <> week('$fecha_actual')) or ps.modalidad='Diario')";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido) AS Cliente,
                  cs.nombre as Colaborador,
                  ROUND(ps.saldo_pendiente,2) as Saldo,
                   ROUND(ps.cuota,2) as Cuota,
                   ROUND(ps.mora,2) AS Mora,
                   date_format(pg.Fecha_ult_abono,'%d/%m/%Y') as Fecha_ult_abono,
                   ps.dia_desembolso AS Dia_Pago,
                  IF('$fecha_actual'>ps.fecha_vencimiento,  'Vencido', 'Activo')  as Estado,
                   ROUND((ps.mora/ps.cuota),3) as CAtrasadas,
                  ROUND((ps.saldo_pendiente/ps.cuota),0) as Cfaltantes,
                  ps.fecha_desembolso as FDesemboldo,
                  ps.fecha_vencimiento as FVencimiento,
                  
                  if('$fecha_bengin' > ps.fecha_vencimiento,'Mora Vencida',if(ROUND((ps.mora/ps.cuota),3) > 0 and ROUND((ps.mora/ps.cuota),3) < 4,'Mora Temprana',if(ROUND((ps.mora/ps.cuota),3) > 4 and ROUND((ps.mora/ps.cuota),3) < 8,'Mora Avanzada',if(ROUND((ps.mora/ps.cuota),3) > 8,'Mora Riesgo','Al Dia')))) as Tipo_de_Mora
                  FROM sucursal s 
          inner join  colaboradores cs on s.id_sucursal=cs.id_sucursal
                  INNER JOIN clientes c ON cs.id_colaborador = c.id_colaborador
                  INNER JOIN prestamos ps ON ps.id_cliente = c.id_cliente
                  LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=ps.id_prestamo

          LEFT JOIN (select pag.id_pago,pag.id_prestamo,pag.usuario,
                                    pag.fecha_cuota as Fecha_ult_abono ,
                                    pag.monto_cuota as Ultima_cuota 
                            from pagos pag where pag.id_pago=(select max(pago.id_pago) from pagos pago where pago.id_prestamo=pag.id_prestamo and pago.monto_cuota > 0 and pago.estado='pagado')) pg on ps.id_prestamo=pg.id_prestamo
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  $morosos
                  WHERE $range_date $id_sucursal
                  $groud_by_
                  ";
         //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByEmpleadoRecaudado($groud_by,$filter_by,$date_range,$id_sucursal,$weekday)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "pn.estado = 'Pagado' and (pn.fecha_pago >='$fecha_bengin' and pn.fecha_pago <='$fecha_end') and tp.sigla is null";
        
        $groud_by_= " GROUP BY c.id_colaborador order by c.id_colaborador desc";
       
            $query="SELECT if(m.sucursal is null, r.sucursal,m.sucursal) as sucursal, 
if(m.Colaborador is null, r.Colaborador,m.Colaborador) as colaborador,
 if(m.metatotal is null, 0,m.metatotal) as Meta,
                           r.Recaudo,
                           concat ( ROUND(((r.Recaudo * 100)/ m.metatotal ) ,2) , ' %') as cumplimiento,
                           r.por_capital,
                           r.por_interes 
                           from ((SELECT mt.id_colaborador,s.sucursal,CONCAT(col.nombre,' ',col.apellido) as Colaborador,sum(mt.metatotal) as metatotal FROM metasdeldia mt INNER JOIN colaboradores col on mt.id_colaborador=col.id_colaborador INNER JOIN sucursal s on col.id_sucursal=s.id_sucursal where mt.fecha>='$fecha_bengin' and mt.fecha <= '$fecha_end'and mt.estado=1 $id_sucursal GROUP BY mt.id_colaborador) m RIGHT JOIN (SELECT c.id_colaborador,
                                         s.sucursal, 
                                         concat(if(instr(c.nombre,' ')=0,c.nombre,SUBSTRING(c.nombre,1,INSTR(cl.nombre,' '))),' ',if(instr(c.apellido,' ')=0,c.apellido,SUBSTRING(c.apellido,1,INSTR(cl.apellido,' ')))) as Colaborador, 
                                         ROUND(SUM(pn.monto),2) as Recaudo, 
                                         ROUND(SUM(pn.por_capital),2) as por_capital, 
                                         ROUND(SUM(pn.por_interes),2) as por_interes 
                                FROM sucursal s 
                                        INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
                                        INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador 
                                        INNER JOIN prestamos p on cl.id_cliente=p.id_cliente 
                                        INNER JOIN pagos_new pn on p.id_prestamo=pn.id_prestamo 
                                        left join (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old 
                                                    FROM saneo ti 
                                                        INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on p.id_prestamo=tp.id_prestamo
                    where $range_date $id_sucursal $groud_by_) r on m.id_colaborador=r.id_colaborador)";
        $range_date1 = "(p.estado = 'Aceptado' ) and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday')
        and p.fecha_desembolso < '$fecha_bengin' and tp.sigla is null $id_sucursal";



 print_r($query);
     /*$query1="
        ";
        echo"<pre>";
     print_r($query);
     echo"</pre>";*/
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByEmpleadoRecaudadoSaneados($groud_by,$filter_by,$date_range,$id_sucursal,$weekday)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "pn.estado = 'Pagado' and (pn.fecha_pago >='$fecha_bengin' and pn.fecha_pago <='$fecha_end') and tp.sigla is null";
        
        $groud_by_= " GROUP BY c.id_colaborador order by c.id_colaborador desc";
       
            $query="SELECT
                                         s.sucursal, 
                                         concat(if(instr(cl.nombre,' ')=0,cl.nombre,SUBSTRING(cl.nombre,1,INSTR(cl.nombre,' '))),' ',if(instr(cl.apellido,' ')=0,cl.apellido,SUBSTRING(cl.apellido,1,INSTR(cl.apellido,' ')))) as Colaborador, 
                                         ROUND(SUM(pn.monto),2) as Recaudo, 
                                         ROUND(SUM(pn.por_capital),2) as por_capital, 
                                         ROUND(SUM(pn.por_interes),2) as por_interes 
                                FROM sucursal s 
                                        INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
                                        INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador 
                                        INNER JOIN prestamos p on cl.id_cliente=p.id_cliente 
                                        INNER JOIN pagos_new pn on p.id_prestamo=pn.id_prestamo 
                                        INNER join (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old 
                                                    FROM saneo ti 
                                                        INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on p.id_prestamo=tp.id_prestamo
                    where $range_date $id_sucursal $groud_by_";
        $range_date1 = "(p.estado = 'Aceptado' ) and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday')
        and p.fecha_desembolso < '$fecha_bengin' $id_sucursal";


     /*$query1="
        ";
        echo"<pre>";
     print_r($query);
     echo"</pre>";*/
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByEmpleadoRecaudadoSup($groud_by,$filter_by,$date_range,$id_sucursal,$weekday)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
         $range_date = "pn.estado = 'Pagado' and (pn.fecha_pago >='$fecha_bengin' and pn.fecha_pago <='$fecha_end') and tp.sigla is null";
        
        $groud_by_= " GROUP BY c.id_colaborador order by c.id_colaborador desc";
       
           /* $query="SELECT m.sucursal,m.Colaborador,if(m.metatotal is null,0,m.metatotal) as Meta,r.Recaudo,concat ( ROUND(((r.Recaudo * 100)/ m.metatotal ) ,2) , ' %') as cumplimiento from ((SELECT c.id_colaborador,s.sucursal, concat(if(instr(cl.nombre,' ')=0,cl.nombre,SUBSTRING(cl.nombre,1,INSTR(cl.nombre,' '))),' ',if(instr(cl.apellido,' ')=0,cl.apellido,SUBSTRING(cl.apellido,1,INSTR(cl.apellido,' ')))) as Colaborador, ROUND(SUM(pn.monto),2) as Recaudo, ROUND(SUM(pn.por_capital),2) as por_capital, ROUND(SUM(pn.por_interes),2) as por_interes 
FROM sucursal s 
INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador 
INNER JOIN prestamos p on cl.id_cliente=p.id_cliente 
INNER JOIN pagos_new pn on p.id_prestamo=pn.id_prestamo 
left join (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old 
FROM saneo ti 
INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on p.id_prestamo=tp.id_prestamo
 where $range_date $id_sucursal $groud_by_) r RIGHT JOIN (SELECT mt.id_colaborador,s.sucursal,CONCAT(col.nombre,' ',col.apellido) as Colaborador,sum(mt.metatotal) as metatotal FROM metasdeldia mt INNER JOIN colaboradores col on mt.id_colaborador=col.id_colaborador INNER JOIN sucursal s on col.id_sucursal=s.id_sucursal where mt.fecha>='$fecha_bengin' and mt.fecha <= '$fecha_end'and mt.estado=1 $id_sucursal GROUP BY mt.id_colaborador) m on r.id_colaborador=m.id_colaborador)";
        $range_date1 = "(p.estado = 'Aceptado' ) and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday')
        and p.fecha_desembolso < '$fecha_bengin' and tp.sigla is null $id_sucursal";*/
        
        $query="SELECT if(m.sucursal is null, r.sucursal,m.sucursal) as sucursal, 
if(m.Colaborador is null, r.Colaborador,m.Colaborador) as colaborador,
 if(m.metatotal is null, 0,m.metatotal) as Meta,
                           r.Recaudo,
                           concat ( ROUND(((r.Recaudo * 100)/ m.metatotal ) ,2) , ' %') as cumplimiento
                           
                           from ((SELECT mt.id_colaborador,s.sucursal,CONCAT(col.nombre,' ',col.apellido) as Colaborador,sum(mt.metatotal) as metatotal FROM metasdeldia mt INNER JOIN colaboradores col on mt.id_colaborador=col.id_colaborador INNER JOIN sucursal s on col.id_sucursal=s.id_sucursal where mt.fecha>='$fecha_bengin' and mt.fecha <= '$fecha_end'and mt.estado=1 $id_sucursal GROUP BY mt.id_colaborador) m RIGHT JOIN (SELECT c.id_colaborador,
                                         s.sucursal, 
                                         concat(if(instr(c.nombre,' ')=0,c.nombre,SUBSTRING(c.nombre,1,INSTR(cl.nombre,' '))),' ',if(instr(c.apellido,' ')=0,c.apellido,SUBSTRING(c.apellido,1,INSTR(cl.apellido,' ')))) as Colaborador, 
                                         ROUND(SUM(pn.monto),2) as Recaudo, 
                                         ROUND(SUM(pn.por_capital),2) as por_capital, 
                                         ROUND(SUM(pn.por_interes),2) as por_interes 
                                FROM sucursal s 
                                        INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
                                        INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador 
                                        INNER JOIN prestamos p on cl.id_cliente=p.id_cliente 
                                        INNER JOIN pagos_new pn on p.id_prestamo=pn.id_prestamo 
                                        left join (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old 
                                                    FROM saneo ti 
                                                        INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on p.id_prestamo=tp.id_prestamo
                    where $range_date $id_sucursal $groud_by_) r on m.id_colaborador=r.id_colaborador)";
        $range_date1 = "(p.estado = 'Aceptado' ) and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday')
        and p.fecha_desembolso < '$fecha_bengin' and tp.sigla is null $id_sucursal";

        
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
  public function generarReportsByPrimeraCuota($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "p.estado LIKE 'Aceptado' and p.primera_cuota='$fecha_bengin' $id_sucursal";
        
        $groud_by_= "";
       
$query = "SELECT  
                if(((SELECT 1 as pagado from pagos pa where pa.fecha_cuota <='$fecha_bengin' and pa.estado='pagado' and pa.monto_cuota > 0 and pa.id_prestamo=p.id_prestamo LIMIT 1)) = 1,'Si','No')as pagado,
                concat(cl.nombre,' ',cl.apellido)as analista,
                concat(c.nombre,' ',c.apellido)as cliente,        
                s.sucursal,
                p.codigo_prestamo,              
                p.fecha_desembolso,                
                p.tipo_prestamo,
                p.modalidad,
                p.cuota as basecuota,
                (SELECT sum(pa.monto_cuota) from pagos pa where pa.fecha_cuota <='$fecha_bengin' and pa.estado='pagado' and pa.monto_cuota > 0 and pa.id_prestamo=p.id_prestamo LIMIT 1) as cuotaAplicada,
                if((p.cuota >= (SELECT sum(pa.monto_cuota) from pagos pa where pa.fecha_cuota <='$fecha_bengin' and pa.estado='pagado' and pa.monto_cuota > 0 and pa.id_prestamo=p.id_prestamo LIMIT 1)),'Total','Parcial') as mensja
        
                FROM colaboradores cl
                INNER JOIN clientes c ON cl.id_colaborador=c.id_colaborador
                INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
                inner join sucursal s on s.id_sucursal = cl.id_sucursal 

              where  $range_date";
        
     //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByEmpleadoRecaudadoPorSemanas($groud_by,$filter_by,$date_range,$id_sucursal,$week){
       $contador = count($week);
       $array_semanas = array_keys($week);
       $array_valores = array_values($week);
       $campos = "concat(s1.nombre,'',s1.apellido) as Analista,";
       $sql="";
       $i=1;
        $j=1;
       foreach($week as $item => $valor){
           $campos .= "s$i.monto as $item,";
           $i++;
            $sql .=" $valor";
       }
      $sql1 = "select $campos from ($sql)";
      print_r($sql1);
       
       exit;
    }
    public function generarReportsByClientesRecaudado($groud_by,$filter_by,$date_range,$id_sucursal,$gestor = "")
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "pg.estado = 'Pagado' and (pg.fecha_pago >='$fecha_bengin' and pg.fecha_pago <='$fecha_end') $gestor";

        $groud_by_= " GROUP BY ps.codigo_prestamo ORDER BY cs.codigo_colaborador";

        $query = "SELECT
                  cs.nombre as Colaborador,
                  cs.codigo_colaborador as C_Colaborador,
                  ps.codigo_prestamo as Prestamo,
                  c.codigo_cliente as C_cliente,
                  CONCAT(c.nombre,' ',c.apellido)  as Cliente,
                  c.cedula as Cedula,
                  ROUND(ps.capital,2) as Capital,
                  ROUND(ps.deuda_total,2) as Deuda,
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(pg.saldo_pendiente,2) as Saldo_pendiente,
                  ROUND(ps.cuota,2) as Cuota,
                  ps.Estado as Estado,
                  ROUND(ps.mora ,2) Mora_actual,
                  ps.tipo_prestamo as Tipo_Prestamo,
                  pz.numero_cuotas as Numero_cuotas,
                  ps.modalidad as modalidad,
                  ROUND(ps.monto_favor,2) as Monto_Favor,

                  ps.fecha_desembolso as Fecha_Desemboldo,
                  ps.fecha_vencimiento as Fecha_Vencimiento,

                  ROUND(SUM(pg.monto),2) as Recaudado_C$,
                  round(((ps.capital/ps.deuda_total) * SUM(pg.monto)),2) as por_capital_C$,
                  round((SUM(pg.monto)  - round(((ps.capital/ps.deuda_total) * SUM(pg.monto)),2)),2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps on ps.id_cliente=c.id_cliente
                  LEFT JOIN pagos_new pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
                // print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByClientesActivos($groud_by,$filter_by,$date_range,$id_sucursal,$gestor = "")
    {
        date_default_timezone_set('America/Managua');
       $fecha_actual = date("Y-m-d");
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = " ps.estado = 'Aceptado' and sa.sigla is null $gestor";

        $groud_by_= " GROUP BY ps.codigo_prestamo ORDER BY f.numero_folder";

        $query = "SELECT
                   cs.nombre as Gestor,
                  CONCAT(c.nombre,' ',c.apellido)  as Cliente,
                  ROUND(ps.capital,2) as Capital,
                  ROUND(ps.deuda_total,2) as Deuda,
                  ROUND(ps.saldo_pendiente,2) as Saldo_C$,
                  ROUND(ps.mora,2) AS Mora,
                  ROUND(ps.cuota,2) as Cuota,
                  ROUND((ps.mora/ps.cuota),3) as Cuotas_Atrasadas,
                  if(ps.fecha_vencimiento < CURDATE() ,'Mora Vencida',if(ROUND((ps.mora/ps.cuota),3) > 0 and ROUND((ps.mora/ps.cuota),3) < 4,'Mora Temprana',if(ROUND((ps.mora/ps.cuota),3) > 4 and ROUND((ps.mora/ps.cuota),3) < 8,'Mora Avanzada',if(ROUND((ps.mora/ps.cuota),3) > 8,'Mora Riesgo','Al Dia')))) as Tipo_de_Mora,
                  pg.Fecha_ult_abono as F_ultima_cuota,
                   pg.Ultima_cuota as M_ultima_cuota,
                   ps.tipo_prestamo as Tipo, 
                  
                  ps.dia_desembolso AS Dia_Pago,
                 
                  
				  ps.fecha_desembolso as Fecha_Desemboldo,
                  ps.fecha_vencimiento as Fecha_Vencimiento,
                  if(ps.modalidad='Diario',CURDATE(),IF(ps.modalidad = 'Semanal',(IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/7),0)*7) DAY)) = 5, DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/7),0)*7) DAY), INTERVAL 2 DAY) ,IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/7),0)*7) DAY))=6,DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/7),0)*7) DAY), INTERVAL 1 DAY),DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/7),0)*7) DAY)))),if(ps.modalidad = 'Mensual',(IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/30),0)*30) DAY)) = 5, DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/30),0)*30) DAY), INTERVAL 2 DAY) ,IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/30),0)*30) DAY))=6,DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/30),0)*30) DAY), INTERVAL 1 DAY),DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/30),0)*30) DAY)))),(IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/15),0)*15) DAY)) = 5, DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/15),0)*15) DAY), INTERVAL 2 DAY) ,IF(WEEKDAY(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/15),0)*15) DAY))=6,DATE_ADD(DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/15),0)*15) DAY), INTERVAL 1 DAY),DATE_ADD(ps.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,ps.fecha_desembolso,'$fecha_actual')/15),0)*15) DAY))))))) as Fecha_Pago
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps ON ps.id_cliente = c.id_cliente
                  INNER JOIN intereses ist  on ist.id_interes = ps.id_interes
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=ps.id_prestamo
                  LEFT JOIN folder f on c.id_cliente=f.id_cliente
				  LEFT JOIN (select pag.id_prestamo,pag.fecha_cuota as Fecha_ult_abono,pag.monto_cuota as Ultima_cuota 
                            from pagos pag 
                            where pag.id_pago=(select max(pago.id_pago) from pagos pago where pago.id_prestamo=pag.id_prestamo and pago.monto_cuota > 0 and pago.estado='pagado')) pg on ps.id_prestamo=pg.id_prestamo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
           // print_r($query); 
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByClientesRecaudadoByPagos($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "pg.estado = 'Pagado' and (pg.fecha_pago >='$fecha_bengin' and pg.fecha_pago <='$fecha_end')";

        $groud_by_= " GROUP BY pg.id_pago ORDER BY  cs.nombre,pg.hora_cuota asc";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido)  as Cliente,
                  CONCAT(cs.nombre,cs.apellido) as Gestor,
                  ps.modalidad,
                  ROUND(pg.monto,2) as Recaudado_C$,
                  pg.saldo_pendiente,
                  DATE_FORMAT(pg.hora_cuota,'%r'),
                  if(ps.estado='Cancelado',if(CA.activo is null,'No','Si'),'') as Represtado
                                    FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador 
                  INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps ON ps.id_cliente=c.id_cliente
                  INNER JOIN pagos_new pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  LEFT JOIN Clientes_Activos CA ON c.id_cliente=CA.id_cliente 
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByHistorialCliente($groud_by,$filter_by,$date_range,$id_sucursal ,$c_cliente)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $c_c = $this->db()->real_escape_string($c_cliente);
        $c_c = explode("-",$c_c);
        $c_c = $c_c[0];
        $sql = $c_c? "and ps.codigo_prestamo ='$c_c' " : " ";
        $range_date = "pg.estado = 'pagado' and (pg.fecha_pago >='$fecha_bengin' and pg.fecha_pago <='$fecha_end') $sql";

        $groud_by_= " GROUP BY pg.id_pago ORDER BY  pg.fecha_pago";
        $this->db()->query("SET @saldo:=(SELECT ps.deuda_total   FROM clientes c INNER JOIN prestamos ps  ON ps.id_cliente = c.id_cliente
          WHERE ps.codigo_prestamo ='$c_c' )");
        $query = "SELECT
                        pg.fecha_pago AS Fecha_abono,
                        c.codigo_cliente AS C_cliente,
                        CONCAT(c.nombre, ' ', c.apellido) AS Cliente,
                        c.cedula AS Cedula,
                        cs.nombre AS Colaborador,
                        ps.codigo_prestamo AS Prestamo,
                        ROUND(ps.capital, 2) AS Capital,
                        ROUND(ps.mora, 2) Mora_actual,
                        ROUND(ps.cuota, 2) Cuota,
                        ps.tipo_prestamo AS Tipo,
                        ROUND(pg.monto, 2) AS Recaudado_C$,
                        ROUND(
                            @saldo :=(@saldo - pg.monto),
                            2
                        ) AS Saldo_C$,
                        ROUND(pg.por_capital, 2) AS por_capital_C$,
                        ROUND(pg.por_interes, 2) AS por_interes_C$
                    FROM
                        clientes c
                    INNER JOIN colaboradores cs ON
                        cs.id_colaborador = c.id_colaborador
                    INNER JOIN sucursal s ON
                        s.id_sucursal = cs.id_sucursal
                    INNER JOIN prestamos ps ON
                        ps.id_cliente = c.id_cliente
                    INNER JOIN pagos_new pg ON
                        pg.id_prestamo = ps.id_prestamo
                    INNER JOIN plazos pz ON
                        pz.id_plazo = ps.id_plazo
                    WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

       // print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByHistorialPagosAfter7pm($groud_by,$filter_by,$date_range,$id_sucursal ,$c_cliente)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        ////$c_c = $this->db()->real_escape_string($c_cliente);
        //$c_c = explode("-",$c_c);
        //$c_c = $c_c[0];
        //$sql = $c_c? "and ps.codigo_prestamo ='$c_c' " : " ";
        $range_date = "hora_cuota>='19:00:00' and pa.estado='pagado' and pa.monto_cuota > 0 and (pa.fecha_cuota >='$fecha_bengin' and pa.fecha_cuota <='$fecha_end')";

        $groud_by_= "";
        //$this->db()->query("SET @saldo:=(SELECT ps.deuda_total   FROM clientes c INNER JOIN prestamos ps  ON ps.id_cliente = c.id_cliente
          //WHERE ps.codigo_prestamo ='$c_c' )");
        $query = "SELECT cl.nombre, cl.apellido,p.codigo_prestamo,p.estado, pa.monto_cuota,pa.fecha_cuota,DATE_FORMAT(pa.hora_cuota,'%r') as hora,pa.usuario as aplicoPago,concat(c.nombre,' ',c.apellido) as analistaAsigando from sucursal s 
        INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
        INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
        INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
        INNER JOIN pagos pa on p.id_prestamo=pa.id_prestamo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByEmpleadoMoras($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = " ps.mora > 0 and ps.estado = 'Aceptado' and sa.sigla is null";

        $groud_by_= " GROUP BY cs.codigo_colaborador";

        $query = "SELECT
                 concat( cs.nombre,' ',cs.apellido) as Colaborador,
                  cs.codigo_colaborador as C_Colaborador,
                  ROUND(SUM(ps.mora),2) as Mora_C$,
                  ROUND(SUM((ps.capital/ps.deuda_total)*ps.mora),2) as por_capital_C$,
                  ROUND(SUM(ps.mora - ((ps.capital/ps.deuda_total)*ps.mora)),2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador 
                  INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps  ON ps.id_cliente = c.id_cliente
                  LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=ps.id_prestamo

                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByClientesMoras($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = " ps.mora > 0 and ps.fecha_vencimiento >= '".date('Y-m-d')."' and ps.estado = 'Aceptado' and sa.sigla is null";

        $groud_by_= " GROUP BY c.codigo_cliente";

        $query = "SELECT
                      ps.codigo_prestamo as Prestamo,
                      CONCAT(c.nombre,' ',c.apellido)  AS Cliente,
                      c.telefono as Telefono,
                      c.codigo_cliente as C_Cliente,
                      c.direccion as Direccion,
                      c.tipo_negocio as Tipo_negocio,
                      concat(cs.nombre,' ',cs.apellido) as Colaborador,
                      ROUND(ps.capital,2) AS Capital,
                      ROUND(ps.deuda_total,2) AS Deuda,
                      ROUND(ps.total_abonado,2) as Total_abonado,
                      ROUND(ps.saldo_pendiente,2) Saldo_pendiente,
                      ROUND(ps.cuota ,2) Cuota,
                      ps.Estado as Estado,
                      ROUND(ps.mora ,2) Mora_actual,
                      ROUND(ps.mora/ps.cuota ,0) Cuotas_Atrasadas,
                      ps.tipo_prestamo as Tipo_Prestamo,
                      pz.numero_cuotas as Numero_cuotas,
                      pz.equivalen_mes as Equivalente_Mes,
                      ps.modalidad as modalidad,
                      ps.monto_favor as Monto_Favor,


                      ps.fecha_desembolso as Fecha_Desemboldo,
                      ps.fecha_vencimiento as Fecha_Vencimiento,

                      ROUND(SUM(ps.mora),2) as Mora_C$,
                      ROUND(SUM(sp.porcentaje_capital),2) as por_capital_C$,
                      ROUND(SUM(sp.porcentaje_interes),2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN subpagos sp ON sp.id_pago=pg.id_pago) ON ps.id_cliente = c.id_cliente
                  LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=ps.id_prestamo
                  LEFT JOIN moras m ON m.id_pago = pg.id_pago
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByPrestamosPorDiaSemana($groud_by,$filter_by,$date_range,$id_sucursal){
        $id_sucursal = ($id_sucursal == "") ? '':"where $id_sucursal";
        $query="select 
                      s.sucursal,
                      concat(s.nombre,' ',s.apellido) as colaborador,
                      s.diario as Diario_1,
                      s.Semanal as Semanal_1,
                      s.total as Total_1,
                      t2.diario as Diario_2,
                      t2.Semanal as Semanal_2,
                      t2.total as Total_2,
                      t3.diario as Diario_3,
                      t3.Semanal as Semanal_3,
                      t3.total as Total_3,
                      t4.diario as Diario_4,
                      t4.Semanal as Semanal_4,
                      t4.total as Total_4,
                      t5.diario as Diario_5,
                      t5.Semanal as Semanal_5,
                      t5.total as Total_5 
            FROM( SELECT 
                        s.id_sucursal,
                        s.sucursal,
                        col.id_colaborador,
                        col.nombre,
                        col.apellido,
                        sum(if(p.modalidad = 'Diario',1,0)) as diario,
                        sum(if(p.modalidad = 'Semanal',1,0)) as Semanal,
                        COUNT(p.id_prestamo) as total
                  from sucursal s 
                    INNER JOIN colaboradores col on s.id_sucursal=col.id_sucursal
                    INNER JOIN clientes c on col.id_colaborador=c.id_colaborador
                    INNER JOIN prestamos p on c.id_cliente=p.id_cliente
                    LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN     estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=p.id_prestamo
                  where p.estado='Aceptado' and (p.modalidad='Diario' or p.dia_desembolso='Lunes') and sa.sigla is null  GROUP by col.id_colaborador) s 
                  inner join
                ( SELECT 
                        s.id_sucursal,
                        s.sucursal,
                        col.id_colaborador,
                        col.nombre,
                        col.apellido,
                        sum(if(p.modalidad = 'Diario',1,0)) as diario,
                        sum(if(p.modalidad = 'Semanal',1,0)) as Semanal,
                        COUNT(p.id_prestamo) as total 
                 from sucursal s 
                    INNER JOIN colaboradores col on s.id_sucursal=col.id_sucursal
                    INNER JOIN clientes c on col.id_colaborador=c.id_colaborador
                    INNER JOIN prestamos p on c.id_cliente=p.id_cliente
                 where p.estado='Aceptado' and (p.modalidad='Diario' or p.dia_desembolso='Martes') GROUP by col.id_colaborador) t2
                    inner join
                ( SELECT 
                        s.id_sucursal,
                        s.sucursal,
                        col.id_colaborador,
                        col.nombre,
                        col.apellido,
                        sum(if(p.modalidad = 'Diario',1,0)) as diario,
                        sum(if(p.modalidad = 'Semanal',1,0)) as Semanal,
                        COUNT(p.id_prestamo) as total 
                from sucursal s 
                    INNER JOIN colaboradores col on s.id_sucursal=col.id_sucursal
                    INNER JOIN clientes c on col.id_colaborador=c.id_colaborador
                    INNER JOIN prestamos p on c.id_cliente=p.id_cliente
                where p.estado='Aceptado' and (p.modalidad='Diario' or p.dia_desembolso='Miercoles') GROUP by col.id_colaborador) t3
                inner join
            ( SELECT 
                    s.id_sucursal,
                    s.sucursal,
                    col.id_colaborador,
                    col.nombre,
                    col.apellido,
                    sum(if(p.modalidad = 'Diario',1,0)) as diario,
                        sum(if(p.modalidad = 'Semanal',1,0)) as Semanal,
                    COUNT(p.id_prestamo) as total 
             from sucursal s 
                INNER JOIN colaboradores col on s.id_sucursal=col.id_sucursal
                INNER JOIN clientes c on col.id_colaborador=c.id_colaborador
                INNER JOIN prestamos p on c.id_cliente=p.id_cliente
            where p.estado='Aceptado' and (p.modalidad='Diario' or p.dia_desembolso='Jueves') GROUP by col.id_colaborador) t4
            inner join
        ( SELECT 
                s.id_sucursal,
                s.sucursal,
                col.id_colaborador,
                col.nombre,
                col.apellido,
                sum(if(p.modalidad = 'Diario',1,0)) as diario,
                        sum(if(p.modalidad = 'Semanal',1,0)) as Semanal,
                COUNT(p.id_prestamo) as total 
        from sucursal s 
            INNER JOIN colaboradores col on s.id_sucursal=col.id_sucursal
            INNER JOIN clientes c on col.id_colaborador=c.id_colaborador
            INNER JOIN prestamos p on c.id_cliente=p.id_cliente
        where p.estado='Aceptado' and (p.modalidad='Diario' or p.dia_desembolso='Viernes') GROUP by col.id_colaborador) t5
    on s.id_colaborador=t2.id_colaborador and t3.id_colaborador=t2.id_colaborador  and t4.id_colaborador=t3.id_colaborador and t4.id_colaborador=t5.id_colaborador  $id_sucursal  GROUP BY s.id_colaborador";
  //print_r($query);
  $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByClienteDia($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "(m.fecha_mora >='$fecha_bengin' and m.fecha_mora <='$fecha_end') or ps.mora > 0 and ps.fecha_vencimiento >= '".date('Y-m-d')."' and ps.estado <> 'Cancelado'";

        $groud_by_= " GROUP BY c.codigo_cliente";

        $query = "SELECT
                    ps.codigo_prestamo as Prestamo,
                    CONCAT(c.nombre,' ',c.apellido)  AS Cliente,
                    c.telefono as Telefono,
                    c.direccion as Direccion,
                    c.tipo_negocio as Tipo_negocio,
                    cs.nombre as Colaborador,
                    ROUND(ps.capital,2) AS Capital,
                    ROUND(ps.deuda_total,2) AS Deuda,
                    ROUND(ps.total_abonado,2) as Total_abonado,
                    ROUND(ps.saldo_pendiente,2) Saldo_pendiente,
                    ROUND(ps.cuota ,2) Cuota,
                    ps.Estado as Estado,
                    ROUND(ps.mora ,2) Mora_actual,
                    ROUND(SUM(ps.mora),2) as Mora_C$,
                    ROUND((ps.capital/ps.deuda_total)*ps.total_abonado,2) as por_capital_C$,
                    ROUND(ps.total_abonado-((ps.capital/ps.deuda_total)*ps.total_abonado),2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN subpagos sp ON sp.id_pago=pg.id_pago) ON ps.id_cliente = c.id_cliente
                  LEFT JOIN moras m ON m.id_pago = pg.id_pago
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByClientesMorasMayor25($groud_by,$filter_by,$date_range,$id_sucursal)
    {
       date_default_timezone_set('America/Managua');
     $fecha_actual = date("Y-m-d");
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = " ps.mora >0 and  ps.estado = 'Aceptado' and ps.fecha_vencimiento>CURDATE() AND round((ps.mora/ps.saldo_pendiente)*100,3) >= 25 and sa.sigla is null";
        //concat(SUBSTRING_INDEX(c.nombre,' ',-1),' ',SUBSTRING_INDEX(c.apellido,' ',-1)) as Nombre,
        $groud_by_= "";

        $query = "SELECT
                  concat(cs.nombre,' ',cs.apellido) as Colaborador,
                  concat(if(instr(c.nombre,' ')=0,c.nombre,substring(c.nombre,1,instr(c.nombre,' ')-1)),' ',if(instr(c.apellido,' ') = 0, c.apellido,substring(c.apellido,1,instr(c.apellido,' ')-1))) as Nombre,
                  ps.modalidad,
                 ps.capital as Capital,
                  ps.deuda_total as Deuda_Total,
                   ps.saldo_pendiente as Saldo_Pendiente,
                    ps.mora as Mora,
                  pg.Fecha_ult_abono,
                   if($fecha_actual > ps.fecha_vencimiento,'Mora Vencida',if((ps.mora/ps.cuota) > 0 and (ROUND(ps.mora/ps.cuota ,0)) <=3,'Mora Temprana',
                      if((ROUND(ps.mora/ps.cuota ,0)) >= 4 and (ROUND(ps.mora/ps.cuota ,0)) <=7,'Mora Avanzada','Mora Riesgo'))) as Tipo_Mora,
                    concat(round((ps.mora/ps.saldo_pendiente)*100,3),'%') as Porcent_Mora
                  
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador
                  INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps on c.id_cliente = ps.id_cliente
                  LEFT JOIN(SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.saldo,ti.total_abonado,ti.id_asignado_old,ti.fecha_registro FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on ps.id_prestamo=sa.id_prestamo
                  LEFT JOIN (select pag.id_pago,pag.id_prestamo,pag.usuario,
                                    pag.fecha_cuota as Fecha_ult_abono ,
                                    pag.monto_cuota as Ultima_cuota 
                            from pagos pag where pag.id_pago=(select max(pago.id_pago) from pagos pago where pago.id_prestamo=pag.id_prestamo and pago.monto_cuota > 0 and pago.estado='pagado')) pg on ps.id_prestamo=pg.id_prestamo


                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
                  //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByPrestamosMoras($groud_by,$filter_by,$date_range,$id_sucursal)
    {
       date_default_timezone_set('America/Managua');
     $fecha_actual = date("Y-m-d");
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = " ps.mora >0 and  ps.estado = 'Aceptado' and sa.sigla is null";
        //concat(SUBSTRING_INDEX(c.nombre,' ',-1),' ',SUBSTRING_INDEX(c.apellido,' ',-1)) as Nombre,
        $groud_by_= "";

        $query = "SELECT
                  concat(cs.nombre,' ',cs.apellido) as Colaborador,
                  concat(if(instr(c.nombre,' ')=0,c.nombre,substring(c.nombre,1,instr(c.nombre,' ')-1)),' ',if(instr(c.apellido,' ') = 0, c.apellido,substring(c.apellido,1,instr(c.apellido,' ')-1))) as Nombre,
                  ps.modalidad,
                 ps.capital as Capital,
                  ps.deuda_total as Deuda_Total,
                   ps.saldo_pendiente as Saldo_Pendiente,
                    ps.mora as Mora,
                  pg.Fecha_ult_abono,
                   if($fecha_actual > ps.fecha_vencimiento,'Mora Vencida',if((ps.mora/ps.cuota) > 0 and (ROUND(ps.mora/ps.cuota ,0)) <=3,'Mora Temprana',
                      if((ROUND(ps.mora/ps.cuota ,0)) >= 4 and (ROUND(ps.mora/ps.cuota ,0)) <=7,'Mora Avanzada','Mora Riesgo'))) as Tipo_Mora,
                    concat(round((ps.mora/ps.saldo_pendiente)*100,3),'%') as Porcent_Mora
                  
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador
                  INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps on c.id_cliente = ps.id_cliente
                  LEFT JOIN(SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.saldo,ti.total_abonado,ti.id_asignado_old,ti.fecha_registro FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on ps.id_prestamo=sa.id_prestamo
                  LEFT JOIN (select pag.id_pago,pag.id_prestamo,pag.usuario,
                                    pag.fecha_cuota as Fecha_ult_abono ,
                                    pag.monto_cuota as Ultima_cuota 
                            from pagos pag where pag.id_pago=(select max(pago.id_pago) from pagos pago where pago.id_prestamo=pag.id_prestamo and pago.monto_cuota > 0 and pago.estado='pagado')) pg on ps.id_prestamo=pg.id_prestamo


                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
                  //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByPrestamosDia($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "(m.fecha_mora >='$fecha_bengin' and m.fecha_mora <='$fecha_end') or ps.mora > 0 and ps.fecha_vencimiento >= '".date('Y-m-d')."' and ps.estado <> 'Cancelado'";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido)  as Nombre,
                  c.codigo_cliente as C_Cliente,
                  c.telefono as Telefono,
                  c.direccion as Direccion,

                  cs.nombre as Colaborador,
                  cs.codigo_colaborador as C_Colaborador,
                  ps.codigo_prestamo as Prestamo,

                  ps.tipo_prestamo as Tipo_Prestamo,
                  ps.modalidad as modalidad,
                  ROUND(SUM(ps.mora),2) as Mora_C$,
                  ROUND(SUM(sp.porcentaje_capital),2) as por_capital_C$,
                  ROUND(SUM(sp.porcentaje_interes),2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN subpagos sp ON sp.id_pago=pg.id_pago) ON ps.id_cliente = c.id_cliente
                  LEFT JOIN moras m ON m.id_pago = pg.id_pago
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByPrestamosActivos($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "ps.estado <> 'Cancelado' and ps.estado <> 'Rechazado' and ps.fecha_vencimiento >='".date('Y-m-d')."'";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido)  as Nombre,
                  c.cedula as Cedula,
                  c.telefono as Telefono,
                  c.direccion as Direccion,

                  ROUND(ps.capital,2) AS Capital,
                  ROUND(ps.deuda_total,2) AS Deuda_Total,
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(SUM(sp.porcentaje_capital),2) as por_capital_C$,
                  ROUND(SUM(sp.porcentaje_interes),2) as por_interes_C$,
                  ROUND(ps.saldo_pendiente,2) Saldo_Pendiente,
                  ROUND(ps.cuota ,2) Cuota,
                  ROUND(ps.mora ,2) Mora,
                  cs.nombre as Gestor,

                  ps.fecha_desembolso as Fecha_Desemboldo,
                  ps.fecha_vencimiento as Fecha_Vencimiento
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN subpagos sp ON sp.id_pago=pg.id_pago) ON ps.id_cliente = c.id_cliente
                  LEFT JOIN moras m ON m.id_pago = pg.id_pago
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
                  //print_r($query);
                  //exit;
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByPrestamosVencidos($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "ps.estado= 'Aceptado' and ps.fecha_vencimiento < '".date('Y-m-d')."' and sa.sigla is null";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  concat(cs.nombre,' ',cs.apellido) as Gestor,
                  CONCAT(c.nombre,' ',c.apellido)  as Nombre,
                  c.localidad,
                  
                  ROUND(ps.capital,2) as Capital,
                  ROUND(ps.deuda_total,2) as Deuda_Total,
                 
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(ps.saldo_pendiente,2) as Saldo_Pendiente,

                   ROUND(ps.cuota,2) as Cuota,
                    datediff(CURDATE(),.ps.fecha_vencimiento) as Dias_Vencido,

                  ps.fecha_desembolso as Fecha_Desemboldo,
                  ps.fecha_vencimiento as Fecha_Vencimiento,
                  ps.dia_desembolso,
                   DATE_FORMAT(IF(ult.Fecha_ult_abono IS NULL,ps.fecha_desembolso,ult.Fecha_ult_abono), '%d/%m/%Y') as Fecha_Ultimo_abono
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo) ON ps.id_cliente = c.id_cliente
                  LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=ps.id_prestamo
                LEFT JOIN  ultimo_abono ult on ps.id_prestamo=ult.id_prestamo
                  LEFT JOIN moras m ON m.id_pago = pg.id_pago
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
            
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByPrestamosCancelados($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "ps.estado = 'Cancelado'";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido)  as Nombre,
                  c.cedula as Cedula,
                  c.telefono as Telefono,
                  c.direccion as Direccion,

                  ROUND(ps.capital,2) AS Capital,
                  ROUND(ps.deuda_total,2) AS Deuda_Total,
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND((ps.capital/ps.deuda_total)*ps.total_abonado,2) as por_capital_C$,
                  ROUND(ps.total_abonado-((ps.capital/ps.deuda_total)*ps.total_abonado),2) as por_interes_C$,
                  ROUND(ps.saldo_pendiente,2) Saldo_Pendiente,
                  ROUND(ps.cuota ,2) Cuota,
                  ROUND(ps.mora ,2) Mora,
                  cs.nombre as Gestor,


                  ps.fecha_desembolso as Fecha_Desemboldo,
                  ps.fecha_vencimiento as Fecha_Vencimiento
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo) ON ps.id_cliente = c.id_cliente
                  LEFT JOIN moras m ON m.id_pago = pg.id_pago
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function cancelacion($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = " and p.estado = 'Cancelado'";

        $groud_by_= " GROUP BY p.codigo_prestamo";

        $query = "SELECT concat(c.nombre,' ',c.apellido) as cliente,
                        concat(cl.nombre,' ',cl.apellido) as analista,
                        p.codigo_prestamo,
                        p.capital,
                        p.estado,
                        p.modalidad,
                        DATE_FORMAT(pg.hora_cuota,'%r'),
                        DATE_FORMAT(p.fecha_desembolso, '%d %M %Y') as fecha_desembolso,
                        DATE_FORMAT(p.fecha_vencimiento, '%d %M  %Y') as fecha_vencimiento,
                        DATE_FORMAT(pg.fecha_cuota, '%d %M  %Y') as fecha_cancelacion,
                        if(DATEDIFF(pg.fecha_cuota,p.fecha_vencimiento) > 0,DATEDIFF(pg.fecha_cuota,p.fecha_vencimiento),0) as diasvencidos,
                        if(CA.activo is null,'No','Si') as Represtado,
                       if(l.id_flags = 1, 'Si','No') AS listaNegra
                from clientes c 
                inner JOIN prestamos p on p.id_cliente = c.id_cliente 
                inner join colaboradores cl on cl.id_colaborador = c.id_colaborador 
                inner join pagos pg on p.id_prestamo = pg.id_prestamo 
                inner join sucursal s on s.id_sucursal = cl.id_sucursal
                LEFT JOIN listanegra l on c.id_cliente=l.id_cliente
                LEFT JOIN Clientes_Activos CA ON c.id_cliente=CA.id_cliente 
                where pg.fecha_cuota >= '$fecha_bengin' and pg.fecha_cuota <= '$fecha_end' and pg.saldo_pendiente = '0' and pg.estado = 'pagado'  $range_date $id_sucursal $groud_by_
                  ";
    //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }


    public function generarReportsByRecaudadoFechas($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        date_default_timezone_set('America/Managua');
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $fechas= '';
        $campos = '';
        $counter = 1;
        for($i=$fecha_bengin;$i<=$fecha_end;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $fechas .= ",'$i'";
            $campos .=",round(sum(if(pg.fecha_cuota = '$i',pg.monto_cuota,0)),2) as Recaudado_$counter,round(sum(if(pg.fecha_cuota = '$i',sp.porcentaje_capital,0)),2) as Capital_$counter,round(sum(if(pg.fecha_cuota = '$i',sp.porcentaje_interes,0)),2) as Interes_$counter";
            //aca puedes comparar $i a una fecha en la bd y guardar el resultado en un arreglo
            $counter++;
        }
        $myString1 = trim($fechas, ',');
        $myString2 = trim($campos, ',');
        $query = "SELECT cs.nombre as Colaborador,$myString2
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN subpagos sp ON sp.id_pago=pg.id_pago) ON ps.id_cliente = c.id_cliente
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE pg.fecha_cuota in($myString1) and pg.estado = 'pagado'
                  $id_sucursal
                  GROUP BY cs.id_colaborador";
                  //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
       /* echo"<pre>";
         print_r($usuario);
         echo"</pre>";*/
        return $usuario;
    }
	public function generarReportsByPrestamosUtimaCuota($groud_by,$filter_by,$date_range,$gestor = "")
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = "p.estado in('Aceptado','Activo') and sa.sigla is null";

      $groud_by_= " GROUP BY p.codigo_prestamo";

        $query = "SELECT CONCAT(c.nombre,' ',c.apellido) as colaborador, CONCAT(cl.nombre,' ',cl.apellido) AS cliente, 
                    p.codigo_prestamo,
                    DATE_FORMAT(p.fecha_desembolso, '%d/%m/%Y') as Fecha_Desembolso,
                    DATE_FORMAT(p.fecha_vencimiento, '%d/%m/%Y') as Fecha_Vencimiento,
                    DATE_FORMAT(IF(ult.Fecha_ult_abono IS NULL,p.fecha_desembolso,ult.Fecha_ult_abono), '%d/%m/%Y') as Fecha_Ultimo_abono,
                    p.capital,
                    p.saldo_pendiente,
                    IF(ult.Ultima_cuota IS NULL,0,ult.Ultima_cuota) as Ultimo_abono,
                    ult.usuario
                  from colaboradores c 
                  INNER join clientes cl on c.id_colaborador=cl.id_colaborador
                  INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
                  LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=p.id_prestamo
                  LEFT JOIN  ultimo_abono ult on p.id_prestamo=ult.id_prestamo
                  WHERE $range_date
                  $groud_by_";
				 
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
	 public function generarReportsByPrestamosCarteraTotal($groud_by,$filter_by,$date_range,$id_sucursal)
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = "(p.estado='Aceptado' or p.estado='Activo') and sa.sigla is null";

      $groud_by_= " GROUP by colaborador";

       $query = "select concat(c.nombre,' ',c.apellido) as colaborador,
          round(sum(p.capital),2) as CarteraTotal, 
          round(sum((p.deuda_total - p.capital)),2) as InteresTotal,
          round(sum(p.deuda_total),2) as DeudaTotal,
          round((sum(p.saldo_pendiente)-SUM(if(p.mora >0, p.mora,0))),2) as SanidadMonto,
           concat(round(((sum(p.saldo_pendiente)-SUM(if(p.mora >0, p.mora,0)))/sum(p.saldo_pendiente))*100,2),'%') as PorcentajeSanidad,
          round(sum(p.mora),2) as MoraTotal,
          concat(round((sum(p.mora)/sum(p.saldo_pendiente))*100,2),'%') as PorcentajeMora,
          concat(round((sum(p.mora)/sum(p.saldo_pendiente))/sum(if(p.mora > 0, 1,0))*100,2),'%') as PromedioMoraPorcentaje,
          concat(round(((sum(p.saldo_pendiente)-SUM(if(p.mora >0, p.mora,0)))/sum(p.saldo_pendiente))/sum(if(p.mora = 0, 1,0))*100,2),'%') as PromedioSnaidadPorcentaje,
          count(p.id_prestamo) as CantidadCliente,
          sum(if(p.mora = 0, 1,0)) as ClientesSinMora,
          sum(if(p.mora > 0, 1,0)) as ClientesConMora,
          round(sum(p.total_abonado)) as Total_Recaudo,
          round(sum((p.capital/p.deuda_total)*p.saldo_pendiente),2)as Cartera_Real,
          round(sum(p.saldo_pendiente - ((p.capital/p.deuda_total)*p.saldo_pendiente)),2) as Interes_Real,
          round(sum(p.saldo_pendiente),2) as Deuda_Real
        from sucursal s 
        inner join colaboradores as c on s.id_sucursal=c.id_sucursal
        inner join clientes as cl on c.id_colaborador=cl.id_colaborador
        inner join prestamos as p on p.id_cliente=cl.id_cliente
        LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=p.id_prestamo

                  where $range_date $id_sucursal
                  $groud_by_";
               //print_r($query);   
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByMetricasRetencion($groud_by,$filter_by,$date_range,$id_sucursal)
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = "pg.fecha_cuota >= '$fecha_bengin' and pg.fecha_cuota <= '$fecha_end' and pg.saldo_pendiente = '0' and pg.estado = 'pagado' and p.estado = 'Cancelado' and sa.sigla is null";
      $range_date_1 = "ps.estado ='Aceptado' and (ps.fecha_desembolso >='$fecha_bengin' and ps.fecha_desembolso <='$fecha_end')";

      $groud_by_= " GROUP BY cl.id_colaborador";

       $query = "SELECT C.analista,
C.Total_Prestamo_Cancelado as P_cancelado,
D.Total_Prestamos_desembolsado as P_Desembolsado,
concat(round(((D.Total_Prestamos_desembolsado/C.Total_Prestamo_Cancelado)* 100),2),'%') as Por_Retencion,
C.Represtado as Cant_Clien_cancelado_represtado,
C.Capital_Represtable_cancelado as C_Cancela_Repres,
D.Capital_Represtamo_Desembolsado,

C.No_Represtado as Cant_Clien_cancelado_Noreprestado,
C.Capital_No_Represtable_cancelado as C_No_Represtable_Cancelado,
concat(round(((C.Capital_No_Represtable_cancelado/C.Capital_Represtable_cancelado)* 100),2),'%') as Porc_Perdida_Retencion_Capital, 
C.Capital_Cancelado,
D.Capital_Desembolsado,
(if(D.Capital_Desembolsado is null,0,D.Capital_Desembolsado) - C.Capital_Cancelado) as Dif_Capital,
C.listaNegra,
C.Cantidad_Semanal_Cancelado,
D.No_Cli_Semanal_Desembolsado,
(D.No_Cli_Semanal_Desembolsado - C.Cantidad_Semanal_Cancelado) as Dif_Clientes_Semanal,
C.Cantidad_Diario_Cancelado,
D.No_Cli_Diario_Desembolsado,
(D.No_Cli_Diario_Desembolsado - C.Cantidad_Diario_Cancelado) as Dif_Clientes_Diario,
C.Cantidad_Quincenal_Cancelado,
D.No_Cli_Quincenal_Desembolsado,
(D.No_Cli_Quincenal_Desembolsado - C.Cantidad_Quincenal_Cancelado) as Dif_Clientes_Quincenal,
C.Cantidad_mensual_Cancelado,
D.No_Cli_Mensual_Desembolsado,
(D.No_Cli_Mensual_Desembolsado - C.Cantidad_mensual_Cancelado) as Dif_Clientes_Mensual,
C.Capital_Semanal_Cancelado,
D.Capital_Semanal_Desembolsado,
(D.Capital_Semanal_Desembolsado - C.Capital_Semanal_Cancelado) as Dif_Capital_Semanal,
C.Capital_Diario_Cancelado,
D.Capital_Diario_Desembolsado,
(D.Capital_Diario_Desembolsado - C.Capital_Diario_Cancelado) as Dif_capital_Diario,
C.Capital_Quincenal_Cancelado,
D.Capital_Quincenal_Desembolsado,
(D.Capital_Quincenal_Desembolsado - C.Capital_Quincenal_Cancelado) as Dif_Capital_Quincenal,
C.Capital_Mensual_Cancelado,
D.Capital_mensual_Desembolsado,
(D.Capital_mensual_Desembolsado - C.Capital_Mensual_Cancelado) as Dif_Capital_Mensual,
D.No_Cli_Nuevo_Desembolsado,
D.Capital_Nuevo_Desembolsado,
D.No_Cli_Reactivacion_Desembolsado,
D.Capital_Reactivacion_Desembolsado,
D.No_Cli_Represtamo_Desembolsado,

D.No_Cli_Reestructurado_Desembolsado,
D.Capital_Reestructurado_Desembolsado
FROM ((SELECT 
                cl.id_colaborador,
                concat(cl.nombre,' ',cl.apellido) as analista, 
COUNT(p.codigo_prestamo) as Total_Prestamo_Cancelado, 
SUM(p.capital) as Capital_Cancelado, 
SUM(if(CA.activo is null,0,1)) as Represtado,
SUM(if(CA.activo is null,1,0)) as No_Represtado,
sum(if(l.id_flags = 1, 1,0)) AS listaNegra,
sum(if(CA.activo is null,0,p.capital)) as Capital_Represtable_cancelado,
sum(if(CA.activo is null,p.capital,0)) as Capital_No_Represtable_cancelado,
sum(if(p.modalidad='Semanal',1,0)) as Cantidad_Semanal_Cancelado,
sum(if(p.modalidad='Diario',1,0)) as Cantidad_Diario_Cancelado,
sum(if(p.modalidad='Quincenal',1,0)) as Cantidad_Quincenal_Cancelado,
sum(if(p.modalidad='Mensual',1,0)) as Cantidad_mensual_Cancelado,
sum(if(p.modalidad='Semanal',p.capital,0)) as Capital_Semanal_Cancelado,
sum(if(p.modalidad='Diario',p.capital,0)) as Capital_Diario_Cancelado,
sum(if(p.modalidad='Quincenal',p.capital,0)) as Capital_Quincenal_Cancelado,
sum(if(p.modalidad='Mensual',p.capital,0)) as Capital_Mensual_Cancelado
from clientes c 
inner JOIN prestamos p on p.id_cliente = c.id_cliente 
inner join colaboradores cl on cl.id_colaborador = c.id_colaborador 
inner join pagos pg on p.id_prestamo = pg.id_prestamo 
inner join sucursal s on s.id_sucursal = cl.id_sucursal
LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=p.id_prestamo
LEFT JOIN listanegra l on c.id_cliente=l.id_cliente 
LEFT JOIN Clientes_Activos CA ON c.id_cliente=CA.id_cliente 
where  $range_date $id_sucursal  $groud_by_) C LEFT JOIN 
(SELECT
 cs.id_colaborador,
cs.nombre as C_Colaborador,
COUNT(ps.codigo_prestamo) as Total_Prestamos_desembolsado, 
SUM(ROUND(ps.capital,2)) AS Capital_Desembolsado, 
SUM(if(ps.tipo_prestamo ='Nuevo',1,0)) as No_Cli_Nuevo_Desembolsado,
SUM(if(ps.tipo_prestamo ='Reactivacion',1,0)) as No_Cli_Reactivacion_Desembolsado,
SUM(if(ps.tipo_prestamo ='Represtamo' or ps.tipo_prestamo ='Reactivacion',1,0)) as No_Cli_Represtamo_Desembolsado,
SUM(if(ps.tipo_prestamo ='Reestructurado',1,0)) as No_Cli_Reestructurado_Desembolsado,
SUM(if(ps.tipo_prestamo ='Nuevo',ps.capital,0)) as Capital_Nuevo_Desembolsado,
SUM(if(ps.tipo_prestamo ='Reactivacion',ps.capital,0)) as Capital_Reactivacion_Desembolsado,
SUM(if(ps.tipo_prestamo ='Represtamo' or ps.tipo_prestamo ='Reactivacion',ps.capital,0)) as Capital_Represtamo_Desembolsado,
SUM(if(ps.tipo_prestamo ='Reestructurado',ps.capital,0)) as Capital_Reestructurado_Desembolsado,
SUM(if(ps.modalidad ='Diario',1,0)) as No_Cli_Diario_Desembolsado,
SUM(if(ps.modalidad ='Semanal',1,0)) as No_Cli_Semanal_Desembolsado,
SUM(if(ps.modalidad ='Quincenal',1,0)) as No_Cli_Quincenal_Desembolsado,
SUM(if(ps.modalidad ='Mensual',1,0)) as No_Cli_Mensual_Desembolsado,
SUM(if(ps.modalidad ='Diario',ps.capital,0)) as Capital_Diario_Desembolsado,
SUM(if(ps.modalidad ='Semanal',ps.capital,0)) as Capital_Semanal_Desembolsado,
SUM(if(ps.modalidad ='Quincenal',ps.capital,0)) as Capital_Quincenal_Desembolsado,
SUM(if(ps.modalidad ='Mensual',ps.capital,0)) as Capital_Mensual_Desembolsado
FROM clientes c INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal INNER JOIN prestamos ps ON ps.id_cliente = c.id_cliente INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo 
WHERE  $range_date_1 $id_sucursal GROUP BY cs.id_colaborador) D on C.id_colaborador=D.id_colaborador)";
              // print_r($query);   
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByMetricasGeneral($groud_by,$filter_by,$date_range,$id_sucursal)
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = "p.estado= 'Aceptado'  $id_sucursal and sa.sigla is null";
     

      $groud_by_= " GROUP BY cl.id_colaborador";

       $query = "SELECT concat(cl.nombre,' ',cl.apellido)as analista,
       s.sucursal as sucursal,
COUNT(DISTINCT c.id_cliente)as nclientes,
COUNT(p.id_prestamo)as nprestamos,
COUNT(IF(p.mora=0,1,NULL))as aldia,
round(SUM(p.monto_favor),2)as adelanto,
round(COUNT(IF(p.mora=0,1,NULL) ) * 100 / COUNT(p.id_prestamo),2)as pcj_aldia,

COUNT(IF(p.mora>0,1,NULL))as enmora,
round(SUM(p.mora),2)as montomora,
round(COUNT(IF(p.mora>0,1,NULL) ) * 100 / COUNT(p.id_prestamo),2)as pcj_enmora,

COUNT(IF(p.mora>0 and p.fecha_vencimiento>= curdate(),1,NULL))as mvigente,

round(SUM(if (p.fecha_vencimiento>= curdate(),p.mora,NULL)),2)as montomvigente,

round(COUNT(IF(p.mora>0 and p.fecha_vencimiento>= curdate() ,1,NULL) ) * 100 / COUNT(IF(p.mora>0 ,1,NULL)),2)as pcj_mvigente,

COUNT(IF(p.mora>0 and p.fecha_vencimiento< curdate(),1,NULL))as mvencida,

round(SUM(if (p.fecha_vencimiento< curdate(),p.mora,NULL)),2)as montomvencida,

round(COUNT(IF(p.mora>0 and p.fecha_vencimiento< curdate() ,1,NULL) ) * 100 / COUNT(IF(p.mora>0 ,1,NULL)),2)as pcj_mvencida,


round((round(SUM(p.saldo_pendiente),2) / COUNT(p.id_prestamo)),2)as creditopromedio,
round(SUM(p.saldo_pendiente),2)as saldocartera

from clientes c inner JOIN colaboradores cl on c.id_colaborador = cl.id_colaborador
inner join prestamos p on p.id_cliente = c.id_cliente
inner JOIN sucursal s on s.id_sucursal = cl.id_sucursal
LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=p.id_prestamo
where $range_date
GROUP by cl.id_colaborador
order BY sucursal DESC  ";
              // print_r($query);   
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByPrestamosCarteraGeneral($groud_by,$filter_by,$date_range,$id_sucursal)
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = "(p.estado='Aceptado' or p.estado='Activo') and sa.sigla is null";

      $groud_by_= " GROUP by c.codigo_colaborador";

       $query = "SELECT c.codigo_colaborador,
        concat(c.nombre,' ',c.apellido)as Gestor,
        count(cl.id_cliente) Nº_Cliente,
        SUM(if(p.mora >0, 0,1)) N_Cliente_Aldia,
        concat(round(((SUM(p.saldo_pendiente) - SUM(if(p.mora >0, p.mora,0))) / SUM(p.saldo_pendiente))* 100,2),'%') as Porc_Sanidad_Saldo_Cartera,
        SUM(if(p.mora >0, 1,0)) Nº_Cliente_Enmora,
        concat(round((SUM(if(p.mora >0, 1,0))/count(cl.id_cliente))*100,2),'%') as Porc_Cliente_Enmora,
        round(SUM(if(p.mora >0, p.mora,0)),2) Monto_Saldo_cartera_Mora,
        concat(round((SUM(if(p.mora >0, p.mora,0))/SUM(p.saldo_pendiente))*100,2),'%') as Porc_Saldo_Cartera_Enmora,
        SUM(if(CURDATE() >p.fecha_vencimiento,1,0)) Nº_Cliente_Vencido,
        concat(round((SUM(if(CURDATE() >p.fecha_vencimiento,1,0))/count(cl.id_cliente))*100,2),'%') Porc_Cliente_Vencido,
        SUM(if(CURDATE() >p.fecha_vencimiento,p.saldo_pendiente,0)) Monto_Vencido,
        concat(round ((SUM(if(CURDATE() >p.fecha_vencimiento,p.saldo_pendiente,0))/SUM(p.saldo_pendiente)) *100,2),'%') as Porc_Monto_vencido,
        round(sum((p.capital/p.deuda_total)*p.saldo_pendiente),2) as Saldo_Cartera
        FROM sucursal s 
        INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
        INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
        INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
                LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=p.id_prestamo

                  where $range_date $id_sucursal
                  $groud_by_";
               //print_r($query);   
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByPrestamosSaldoCartera($groud_by,$filter_by,$date_range,$id_sucursal)
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = "(p.estado='Aceptado' or p.estado='Activo') and sa.sigla is null";

      $groud_by_= " GROUP by c.codigo_colaborador";

       $query = "SELECT c.codigo_colaborador,
        concat(c.nombre,' ',c.apellido)as Gestor,
        count(cl.id_cliente) Nº_Cliente,
        SUM(if(p.mora >0, 0,1)) Nº_Cliente_Aldia,
        concat(round(((SUM(p.saldo_pendiente) - SUM(if(p.mora >0, p.mora,0))) / SUM(p.saldo_pendiente))* 100,2),'%') as Porc_Sanidad_Saldo_Cartera,
        SUM(if(p.mora >0, 1,0)) Nº_Cliente_Enmora,
        concat(round((SUM(if(p.mora >0, 1,0))/count(cl.id_cliente))*100,2),'%') as Porc_Cliente_Enmora,
        round(SUM(if(p.mora >0, p.mora,0)),2) Monto_Saldo_cartera_Mora,
        concat(round((SUM(if(p.mora >0, p.mora,0))/SUM(p.saldo_pendiente))*100,2),'%') as Porc_Saldo_Cartera_Enmora,
        SUM(if(CURDATE() >p.fecha_vencimiento,1,0)) N_Cliente_Vencido,
        SUM(if(CURDATE() >p.fecha_vencimiento,p.saldo_pendiente,0)) Monto_Vencido,
        concat(round ((SUM(if(CURDATE() >p.fecha_vencimiento,p.saldo_pendiente,0))/SUM(p.saldo_pendiente)) *100,2),'%') as Porc_Monto_vencido,
        SUM(p.saldo_pendiente) as Saldo_Cartera
        FROM sucursal s 
        INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
        INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
        INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
                LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on sa.id_prestamo=p.id_prestamo

                  where $range_date $id_sucursal
                  $groud_by_";
               //print_r($query);   
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByClienesMenor10Porcent($groud_by,$filter_by,$date_range,$id_sucursal)
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = " p.estado='Aceptado' and i.porcentaje < 10";

      $groud_by_= " GROUP by c.codigo_colaborador";

       $query = "SELECT concat(cl.nombre,' ',cl.apellido) as cliente,
        cl.cedula,
        p.capital,
        p.deuda_total,
        p.saldo_pendiente,
        p.fecha_desembolso,
        p.fecha_vencimiento,
       if(p.fecha_vencimiento > curdate(),'Activo','Vencido') as estado,
       i.porcentaje,
       pl.numero_cuotas,
       pl.equivalen_mes,
       concat(c.nombre,' ',c.apellido) as gestor
    FROM sucursal s 
       INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
       INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
       INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
       INNER JOIN plazos pl on p.id_plazo=pl.id_plazo
       INNER JOIN intereses i on p.id_interes=i.id_interes
       where $range_date $id_sucursal";
               //print_r($query);   
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByClienesMenorIgual20Porcent($groud_by,$filter_by,$date_range,$id_sucursal)
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = " p.estado='Aceptado' and  p.saldo_pendiente<=(p.deuda_total*0.2) and tp.sigla is null";

      $groud_by_= " GROUP by p.id_prestamo";

       $query = "SELECT 
       concat(c.nombre,' ',c.apellido) as gestor,
       concat(cl.nombre,' ',cl.apellido) as cliente,
        p.capital,
        p.cuota,
        p.deuda_total,
        p.saldo_pendiente,
        p.fecha_vencimiento,
        (COUNT(pr.id_prestamo) + 1) as ciclos,
       if(p.fecha_vencimiento > curdate(),'Activo','Vencido') as estado
    FROM sucursal s 
       INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
       INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
       INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
       INNER JOIN plazos pl on p.id_plazo=pl.id_plazo
       INNER JOIN intereses i on p.id_interes=i.id_interes
       INNER JOIN prestamos pr on cl.id_cliente=pr.id_cliente and pr.estado='Cancelado'
       LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.saldo,ti.total_abonado,ti.id_asignado_old,ti.fecha_registro FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on tp.id_prestamo=p.id_prestamo
       where $range_date $id_sucursal $groud_by_";
               //print_r($query);   
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByClienesContabilidad($groud_by,$filter_by,$date_range,$id_sucursal)
    {
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $groud_by_ = "";
      $morosos = "";
      $range_date = " p.estado='Aceptado'";

      $groud_by_= " GROUP by c.codigo_colaborador";

       $query = "SELECT concat(cl.nombre,' ',cl.apellido) as cliente,
        cl.cedula,
        p.capital,
        p.deuda_total,
        p.saldo_pendiente,
        p.fecha_desembolso,
        p.fecha_vencimiento,
       if(p.fecha_vencimiento > curdate(),'Activo','Vencido') as estado,
       i.porcentaje,
       pl.numero_cuotas,
       pl.equivalen_mes,
       concat(c.nombre,' ',c.apellido) as gestor
    FROM sucursal s 
       INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
       INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
       INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
       INNER JOIN plazos pl on p.id_plazo=pl.id_plazo
       INNER JOIN intereses i on p.id_interes=i.id_interes
       where $range_date $id_sucursal";
               //print_r($query);   
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function Rpts_cumplimiento_metas($groud_by,$filter_by,$date_range,$id_sucursal,$weekday){
      $fecha_bengin = $date_range['bengin'];
      $fecha_end = $date_range['end'];
      $morosos = "";
      $range_date = " pg.fecha_pago = '$fecha_bengin' AND pg.estado =  'Pagado' $id_sucursal";
 //print_r($range_date);exit;
      $groud_by_= " GROUP by col.id_colaborador order by col.id_colaborador";
      $query=" SELECT
    m.sucursal,
    concat(m.nombre,' ',m.apellido) as Analista,
    m.metatotal as Meta,
    m.metavigente as Meta_Vigente,
    m.metavencido as Meta_Vencida,
    round(sum(if(p.fecha_vencimiento>= '$fecha_bengin',pg.monto,0)),2) as recaudo_vigente,
round(sum(if(p.fecha_vencimiento< '$fecha_bengin',pg.monto,0)),2) as recaudo_vencido,
SUM(round(pg.monto,2) ) as Recaudo,
concat ( ROUND(((SUM(round(pg.monto,2) ) * 100)/ m.metatotal ) ,2) , ' %') as cumplimiento,
concat ( ROUND((round(sum(if(p.fecha_vencimiento>= '$fecha_bengin',pg.monto,0)),2) * 100)/ m.metavigente ,2) , ' %') as cumpl_vigente,
concat ( ROUND((round(sum(if(p.fecha_vencimiento< '$fecha_bengin',pg.monto,0)),2) * 100)/ m.metavencido ,2) , ' %') as cumpl_vencido
    
FROM clientes c
    
INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
INNER JOIN colaboradores col ON col.id_colaborador = c.id_colaborador
INNER JOIN sucursal s ON s.id_sucursal = col.id_sucursal
INNER JOIN pagos_new pg ON pg.id_prestamo = p.id_prestamo
RIGHT JOIN (SELECT sc.sucursal,cl.nombre,cl.apellido,mt.id_colaborador,mt.metatotal,mt.metavigente,mt.metavencido FROM metasdeldia mt INNER JOIN colaboradores cl on mt.id_colaborador=cl.id_colaborador INNER JOIN sucursal sc on cl.id_sucursal=sc.id_sucursal where mt.fecha='$fecha_bengin' and mt.estado=1) m on col.id_colaborador=m.id_colaborador
WHERE  $range_date $groud_by_
        ";
       
      $usuario = $this->ejecutarSqlReports($query);
        return $usuario;

    }
    public function getModule($id_user){
	 $query="SELECT p.id_modulos_parent, m.label,p.id_modulos_child from modulo_reporte as m INNER JOIN permiso_reporte as p on m.id_module=p.id_modulos_parent where id_usuario=$id_user";
        //print_r($query);exit;
      $usuario = $this->ejecutarSql($query);
        return is_object($usuario) ? [$usuario]:$usuario;
	}
	public function getModuleChild($child){
	 $query="SELECT * from modulo_reporte where id_module in($child)";
       // print_r($query);exit;
      $usuario = $this->ejecutarSql($query);
        return $usuario;
	}
	 public function generarReportsByCarteraSaneada($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_actual=date("Y-m-d");

        $fecha_bengin = $fecha_actual;
        $fecha_end = $fecha_actual;
        $groud_by_ = "";
        $morosos = "";
        $range_date = "(ps.estado='Aceptado' or ps.estado='Activo')";

        $groud_by_= " GROUP by tbsaneado.Gestor_Asignado";

        $query = "SELECT 
            tbsaneado.Gestor_Asignado as Gestor,
            count(tbsaneado.codigo_prestamo) as Cantidad_Prestamo_Saneado,
            sum(tbsaneado.Capital_origen) as Capital_origen,
            sum(tbsaneado.Interes_origen) as Interes_origen,
            sum(tbsaneado.Capital_a_la_fecha) as Capital_a_la_fecha,
            sum(tbsaneado.Interes_a_la_fecha) as Interes_a_la_fecha,
            tbpagossaneados.monto as Total_abonado_saneado,
            round(tbpagossaneados.capital_a,2) as Capital_abonado_saneado,
            round(tbpagossaneados.interes_a,2) as Interes_abonado_saneado,
            if(tbsaneado.tipo_nota is null,'--',if(tbsaneado.tipo_nota = 1,'Aplico a deduccion de interes','nota de credito')) as Aplicado
            FROM(SELECT ps.id_prestamo,ps.codigo_prestamo,
            concat(cl.nombre,' ',cl.apellido) as cliente,
            cl.cedula,
            cl.tipo_negocio,
            concat(c.nombre,'',c.apellido) as Gestor_origen,
            (SELECT concat(cla.nombre,' ',cla.apellido) as nombre from colaboradores cla where cla.id_colaborador=cl.id_colaborador) as Gestor_Asignado,
            round(((ps.capital/ps.deuda_total)*tp.saldo),2) as Capital_origen,
            round((tp.saldo - ((ps.capital/ps.deuda_total)*tp.saldo)),2) as Interes_origen,
            if(nt.tipo_nota is null,round(((ps.capital/ps.deuda_total)*ps.saldo_pendiente),2),round(((nt.capital/nt.neto_pagar)*ps.saldo_pendiente),2)) as Capital_a_la_fecha,
            if(nt.tipo_nota is null,round((ps.saldo_pendiente - ((ps.capital/ps.deuda_total)*ps.saldo_pendiente)),2),round((ps.saldo_pendiente - ((nt.capital/nt.neto_pagar)*ps.saldo_pendiente)),2)) as Interes_a_la_fecha,
            round((ps.total_abonado - tp.total_abonado),2) as Total_abonado_saneado,
            round(((ps.capital/ps.deuda_total)*(ps.total_abonado - tp.total_abonado)),2) as Capital_abonado_saneado,
            round((ps.total_abonado - tp.total_abonado) - ((ps.capital/ps.deuda_total)*(ps.total_abonado - tp.total_abonado)),2) as Interes_abonado_saneado,
            nt.tipo_nota
            from sucursal s 
            INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
            INNER JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.saldo,ti.total_abonado,ti.id_asignado_old,ti.fecha_registro FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on c.id_colaborador=tp.id_asignado_old
            INNER JOIN prestamos ps on tp.id_prestamo=ps.id_prestamo
            LEFT JOIN nota_credito nt on ps.id_prestamo=nt.id_prestamo
            INNER JOIN clientes cl on ps.id_cliente=cl.id_cliente
            where $range_date $id_sucursal) tbsaneado 
            LEFT JOIN 
            (SELECT tp.id_prestamo,sUM(pg.monto) as monto, SUM(pg.por_capital) as capital_a,SUM(pg.por_interes) as interes_a from sucursal s 
            INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
            INNER JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.saldo,ti.total_abonado,ti.id_asignado_old,ti.fecha_registro FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on c.id_colaborador=tp.id_asignado_old
            INNER JOIN pagos_new pg on tp.id_prestamo=pg.id_prestamo
            where pg.estado='Pagado' and pg.fecha_pago >= tp.fecha_registro $id_sucursal GROUP by tp.id_prestamo) tbpagossaneados ON tbsaneado.id_prestamo=tbpagossaneados.id_prestamo $groud_by_ order by tbsaneado.Gestor_Asignado asc";
            //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByDetallePrestamoSaneado($groud_by,$filter_by,$date_range,$id_sucursal){
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "ps.estado='Aceptado'";

        $groud_by_= "";

        $query = "SELECT 
            tbsaneado.codigo_prestamo,
            tbsaneado.cliente,
            tbsaneado.cedula,
            tbsaneado.tipo_negocio,
            tbsaneado.direccion,
            tbsaneado.fecha_desembolso,
            tbsaneado.fecha_vencimiento,
            tbsaneado.capital,
            tbsaneado.deuda_total,
            tbsaneado.saldo_pendiente,
            tbsaneado.mora,
            tbsaneado.porce_mora,
            tbsaneado.Cuotras_Atrasada,
            if(tbsaneado.tipo_nota is null,'--',if(tbsaneado.tipo_nota = 1,'Aplico a deduccion de interes','nota de credito')) as Aplicado
            FROM(SELECT ps.id_prestamo,ps.codigo_prestamo,
            concat(cl.nombre,' ',cl.apellido) as cliente,
            cl.cedula,
            cl.tipo_negocio,
            cl.direccion,
            ps.capital,
            ps.deuda_total,
            ps.saldo_pendiente,
            ps.mora,
            ps.fecha_vencimiento,
            ps.fecha_desembolso,
            concat(round((ps.mora/ps.deuda_total)*100,2),'%') as porce_mora,
            round((ps.mora/ps.cuota),2) AS Cuotras_Atrasada,
            concat(c.nombre,'',c.apellido) as Gestor_origen,
            (SELECT concat(cla.nombre,' ',cla.apellido) as nombre from colaboradores cla where cla.id_colaborador=cl.id_colaborador) as Gestor_Asignado,
            round(((ps.capital/ps.deuda_total)*tp.saldo),2) as Capital_origen,
            round((tp.saldo - ((ps.capital/ps.deuda_total)*tp.saldo)),2) as Interes_origen,
            if(nt.tipo_nota is null,round(((ps.capital/ps.deuda_total)*ps.saldo_pendiente),2),round(((nt.capital/nt.neto_pagar)*ps.saldo_pendiente),2)) as Capital_a_la_fecha,
            if(nt.tipo_nota is null,round((ps.saldo_pendiente - ((ps.capital/ps.deuda_total)*ps.saldo_pendiente)),2),round((ps.saldo_pendiente - ((nt.capital/nt.neto_pagar)*ps.saldo_pendiente)),2)) as Interes_a_la_fecha,
            round((ps.total_abonado - tp.total_abonado),2) as Total_abonado_saneado,
            round(((ps.capital/ps.deuda_total)*(ps.total_abonado - tp.total_abonado)),2) as Capital_abonado_saneado,
            round((ps.total_abonado - tp.total_abonado) - ((ps.capital/ps.deuda_total)*(ps.total_abonado - tp.total_abonado)),2) as Interes_abonado_saneado,
            nt.tipo_nota
            from sucursal s 
            INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
            INNER JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.saldo,ti.total_abonado,ti.id_asignado_old,ti.fecha_registro FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on c.id_colaborador=tp.id_asignado_old
            INNER JOIN prestamos ps on tp.id_prestamo=ps.id_prestamo
            LEFT JOIN nota_credito nt on ps.id_prestamo=nt.id_prestamo
            INNER JOIN clientes cl on ps.id_cliente=cl.id_cliente
            where $range_date $id_sucursal) tbsaneado 
            LEFT JOIN 
            (SELECT tp.id_prestamo,sUM(pg.monto) as monto, SUM(pg.por_capital) as capital_a,SUM(pg.por_interes) as interes_a from sucursal s 
            INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
            INNER JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.saldo,ti.total_abonado,ti.id_asignado_old,ti.fecha_registro FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on c.id_colaborador=tp.id_asignado_old
            INNER JOIN pagos_new pg on tp.id_prestamo=pg.id_prestamo
            where pg.estado='Pagado' and pg.fecha_pago >= tp.fecha_registro $id_sucursal GROUP by tp.id_prestamo) tbpagossaneados ON tbsaneado.id_prestamo=tbpagossaneados.id_prestamo";
         //print_r($query);

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;

    }
    public function generarReportsByDetallePrestamoSaneadoFecha($groud_by,$filter_by,$date_range,$id_sucursal){
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "p.estado='Aceptado' and sa.fecha_registro >='$fecha_bengin' and sa.fecha_registro <=' $fecha_end' ";

        $groud_by_= "";

        $query = "SELECT concat(cl.nombre,' ',cl.apellido)as cliente,
     (SELECT concat(cla.nombre,' ',cla.apellido) as nombre from colaboradores cla where cla.id_colaborador=sa.id_asignado_old) as Gestor_Origen,
         concat(c.nombre,' ',c.apellido) as Gestor_Destino,
         p.capital,
         p.saldo_pendiente,
         p.fecha_vencimiento,
         sa.fecha_registro as fecha_saneamiento,
         p.cuota
         FROM sucursal s 
         INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
         INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
         INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
         INNER JOIN saneo sa on p.id_prestamo=sa.id_prestamo
         where $range_date $id_sucursal";
        //print_r($query);

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;

    }
   public function generarReportsClientesInactivo($groud_by,$filter_by,$date_range,$id_sucursal){
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "prt.estado in('Cancelado') and tp.sigla is null";

        $groud_by_= " GROUP by cli.codigo_cliente order by s.id_sucursal";

        $query = "SELECT
               
                sum(IF(p.estado <>'Rechazado' ,1,0)) as N_Ciclo,
                concat(col.nombre,' ',col.apellido) as gestor,
                cli.codigo_cliente,
                concat(cli.nombre,' ',cli.apellido) as cliente,
                 cli.tipo_negocio as tipo_negocio,
                   cli.direccion as dir1,
                    if((select pa.fecha_pago from pagos_new pa where pa.id_prestamo=prt.id_prestamo and pa.estado='Pagado' ORDER by pa.fecha_pago desc limit 1) is null ,'',if(prt.fecha_vencimiento > (select pa.fecha_pago from pagos_new pa where pa.id_prestamo=prt.id_prestamo and pa.estado='Pagado' ORDER by pa.fecha_pago desc limit 1),'Vigente','Vencido')) as estadoCancelacion,
          concat(round(((SELECT MAX(pa.cuotas_atrasadas) from  pagos_new pa where pa.estado='Pagado' and pa.id_prestamo=prt.id_prestamo ORDER by pa.fecha_pago desc limit 1)/prt.numero_cuotas)*100,2),'%') as porc_atrazo,
                
                if(lis.id_flags = 1, 'Si','No') AS listaNegra,  
                cli.telefono as tel1
                from clientes cli 
                inner join colaboradores col on cli.id_colaborador = col.id_colaborador 
                inner join sucursal s on s.id_sucursal = col.id_sucursal 
                inner join prestamos p on p.id_cliente = cli.id_cliente
                LEFT JOIN listanegra lis on cli.id_cliente=lis.id_cliente
                LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.saldo,ti.total_abonado,ti.id_asignado_old,ti.fecha_registro FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on tp.id_prestamo=p.id_prestamo
                LEFT JOIN (SELECT pr.id_prestamo,pr.id_cliente,pr.codigo_prestamo,pr.estado,pr.fecha_vencimiento,pla.numero_cuotas from prestamos pr INNER JOIN plazos pla on pr.id_plazo=pla.id_plazo where pr.id_prestamo=(SELECT MAX(pres.id_prestamo) from prestamos pres where pr.id_cliente=pres.id_cliente)) as prt ON cli.id_cliente=prt.id_cliente
                where   $range_date $id_sucursal $groud_by_";
        //print_r($query);

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;

       
   }
   public function generarReportsByClienteCitasAgendada($groud_by,$filter_by,$date_range,$id_sucursal){
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "prt.estado in('Cancelado') and tp.sigla is null";

        $groud_by_= " GROUP by cli.codigo_cliente order by s.id_sucursal";

        $query = "SELECT concat(c.nombre,' ',c.apellido) as cliente,
                              ac.observaciones,
                              concat(col.nombre,' ',col.apellido) as colaborador
                               from agenda a
                                 inner join citas ac on a.id_agenda=ac.id_agenda
                                 inner join clientes c on a.id_cliente=c.id_cliente
                                 inner join colaboradores col on a.id_colaborador_asignado=col.id_colaborador
                                 inner join sucursal s on col.id_sucursal=s.id_sucursal  where  date(ac.fecha_agendada) = $fecha_bengin  ";
        //print_r($query);

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;

       
   }

}

?>
