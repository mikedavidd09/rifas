<?php

class ReportsModel extends ModeloBase
{
    private $table;

    public function __construct($adapter,$table ="")
    {
        $this->table = $table;
        parent::__construct($this->table, $adapter);
    }
    //Metodos de consulta
    public function generarReportsPrestamosDesembolsos($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "ps.estado in('Activo','Cancelado') and (ps.fecha_desembolso >='$fecha_bengin' and ps.fecha_desembolso <='$fecha_end')";

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
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(ps.saldo_pendiente,2) Saldo_pendiente,
                  ROUND(ps.cuota ,2) Cuota,
                  ps.Estado as Estado,
                  ROUND(ps.mora ,2) Mora_actual,

                  ps.tipo_prestamo as Tipo_Prestamo,
                  pz.numero_cuotas as Numero_cuotas,
                  pz.equivalen_mes as Equivalente_Mes,
                  ps.modalidad as Modalidad,
                  ps.monto_favor as Monto_Favor,
                  ps.fecha_desembolso as Fecha_Desemboldo,
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

    public function generarReportsPrestamosVencidos($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "ps.estado = 'Activo' and (ps.fecha_vencimiento >='$fecha_bengin' and ps.fecha_vencimiento <='$fecha_end')";

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
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(ps.saldo_pendiente,2) Saldo_pendiente,
                  ROUND(ps.cuota ,2) Cuota,
                  ps.Estado as Estado,
                  ROUND(ps.mora ,2) Mora_actual,

                  ps.tipo_prestamo as Tipo_Prestamo,
                  pz.numero_cuotas as Numero_cuotas,
                  pz.equivalen_mes as Equivalente_Mes,
                  ps.modalidad as Modalidad,
                  ps.monto_favor as Monto_Favor,


                  ps.fecha_desembolso as Fecha_Desemboldo,
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
        //print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsPrestamosFullField($groud_by,$filter_by,$date_range,$id_sucursal)
    {
		date_default_timezone_set('America/Managua');
		$fecha_actual=date("Y-m-d");
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "(ps.estado='Activo') and ((ps.fecha_desembolso != '$fecha_bengin') and (weekday(ps.fecha_desembolso) = weekday('$fecha_bengin') or ps.modalidad='Diario' ) and (week(ps.fecha_desembolso) <> week('$fecha_bengin')) )";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  ps.codigo_prestamo as Prestamo,
                  CONCAT(c.nombre,' ',c.apellido) AS Cliente,
                  c.telefono as Telefono,
                  c.direccion as Direccion_Domicilio,

                  c.tipo_negocio as Tipo_negocio,
                  cs.nombre as Colaborador,
                  ROUND(ps.capital,2)  as Capital,

                  ROUND(ps.deuda_total,2) AS Deuda,
				  ROUND(ps.saldo_pendiente,2) Saldo_pendiente,
				  ps.modalidad as modalidad,
				  ps.dia_desembolso as Dia_Pago,
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(((ps.capital/ps.deuda_total)*ROUND(ps.total_abonado,2)),2) as P_Capital_C$,
                  ROUND((ps.total_abonado - ((ps.capital/ps.deuda_total)*ps.total_abonado)),2) as P_Interes_C$,
                  ROUND(ps.cuota ,2) Cuota,
                  ps.Estado as Estado,
                  ROUND(ps.mora ,2) Mora_actual
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

    public function generarReportsByEmpleadoRecaudado($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "pg.estado = 'pagado' and (pg.fecha_pago >='$fecha_bengin' and pg.fecha_pago <='$fecha_end')";

        $groud_by_= " GROUP BY cs.id_colaborador";

        $query = "SELECT
                  CONCAT(cs.nombre,' ',cs.apellido) as Colaborador,
                  ROUND(SUM(pg.monto),2) as Recaudado_C$,
                  ROUND(SUM(pg.por_capital),2) as por_capital_C$,
                  ROUND(SUM(pg.por_interes),2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador 
				  INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps on c.id_cliente=ps.id_cliente
                  inner JOIN pagos_new pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
				 // print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByCartera($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_actual=date("Y-m-d");

        $fecha_bengin = $fecha_actual;
        $fecha_end = $fecha_actual;
        $groud_by_ = "";
        $morosos = "";
        $range_date = "(ps.estado='Aceptado' or ps.estado='Activo') and pg.estado='Pagado'";

        $groud_by_= " GROUP BY cs.id_colaborador";

        $query = "SELECT
                  CONCAT(cs.nombre,' ',cs.apellido) as Gestores,
                  COUNT(c.id_cliente) as Numero_Clientes,
				  COUNT(ps.id_prestamo) as Numero_Creditos,
                  ROUND(SUM(ps.capital),2) as Monto_Otorgado,
				  ROUND(sum((ps.capital / ps.deuda_total) * ps.saldo_pendiente)) as Saldo_de_Capital_Fecha,
                  ROUND(SUM(pg.monto),2) as Abono_Total,
                  ROUND(SUM(pg.por_capital),2) as Abono_Al_Capital,
                  ROUND(SUM(pg.por_interes),2) as Abono_Al_Interes,
				  round(SUM(if((ps.mora / ps.cuota)>=7,ps.mora,0))) as Mora_mayor_7_cuotas,
				  round(((sum(if((ps.mora / ps.cuota) >=7,ps.mora,0))) / sum(ps.mora)) * 100) as Porcentaje_mayor_7_cuotas
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador 
				  INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps on c.id_cliente=ps.id_cliente
                  inner JOIN pagos_new pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
				// print_r($query);
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByClientesRecaudado($groud_by,$filter_by,$date_range,$id_sucursal,$gestor = "")
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "pg.estado = 'pagado' and (pg.fecha_pago >='$fecha_bengin' and pg.fecha_pago <='$fecha_end') $gestor";

        $groud_by_= " GROUP BY ps.codigo_prestamo ORDER BY cs.codigo_colaborador";

        $query = "SELECT
                  max(pg.hora_cuota) as Hora,
                  cs.nombre as Colaborador,
                  cs.codigo_colaborador as C_Colaborador,
                  ps.codigo_prestamo as Prestamo,
                  c.codigo_cliente as C_cliente,
                  CONCAT(c.nombre,' ',c.apellido)  as Cliente,
                  c.cedula as Cedula,
                  ROUND(ps.capital,2) as Capital,
                  ROUND(ps.deuda_total,2) as Deuda,
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND(ps.saldo_pendiente,2) as Saldo_pendiente,
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
                  ROUND(SUM(pg.por_capital),2) as por_capital_C$,
                  ROUND(SUM(pg.por_interes),2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps on c.id_cliente=ps.id_cliente
                  inner JOIN pagos_new pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByClientesActivos($groud_by,$filter_by,$date_range,$id_sucursal,$gestor = "")
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = " ps.estado = 'Activo'  $gestor";

        $groud_by_= " GROUP BY ps.codigo_prestamo ORDER BY cs.codigo_colaborador";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido)  as Cliente,
                  c.codigo_cliente as C_cliente,
                  c.cedula as Cedula,
                  cs.nombre as Gestor,
                  ps.codigo_prestamo as Prestamo,
                  ps.modalidad as modalidad,
                  ps.dia_desembolso as dia_desembolso,
                  ps.tipo_prestamo as Tipo,
                  ps.fecha_desembolso as Fecha_Desemboldo,
                  ps.fecha_vencimiento as Fecha_Vencimiento,
                  ROUND(ps.capital,2) as Monto_Otorgado_C$,
                  ROUND(((ps.capital / ps.deuda_total) * ps.saldo_pendiente),2) as Capital_Al_La_Fecha_C$,
                  ROUND(ps.deuda_total,2) as Deuda,
                  ROUND(ps.saldo_pendiente,2) as Saldo_C$,
                  ROUND(ps.cuota,2) as Cuota,
                  ROUND(ps.mora,2) AS Mora,
                  ROUND(ps.total_abonado,2) as Abonado_C$,
                  ROUND(((ps.capital/ps.deuda_total)*ps.total_abonado),2) as p_Capital_C$,
                  ROUND((ps.total_abonado - ((ps.capital/ps.deuda_total)*ps.total_abonado)),2) as p_Interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps ON ps.id_cliente = c.id_cliente
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }

    public function generarReportsByClientesRecaudadoByPagos($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "pg.estado = 'pagado' and (pg.fecha_cuota >='$fecha_bengin' and pg.fecha_cuota <='$fecha_end')";

        $groud_by_= " GROUP BY pg.id_pago ORDER BY  c.nombre";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido)  as Cliente,
                  c.cedula as Cedula,
                  CONCAT(cs.nombre,cs.apellido) as Gestor,
                  ROUND(pg.monto_cuota,2) as Recaudado_C$,
                  ROUND(sp.porcentaje_capital,2) as por_capital_C$,
                  ROUND(sp.porcentaje_interes,2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN subpagos sp ON sp.id_pago=pg.id_pago) ON ps.id_cliente = c.id_cliente
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
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
        $range_date = "pg.estado = 'pagado' and (pg.fecha_cuota >='$fecha_bengin' and pg.fecha_cuota <='$fecha_end') $sql";

        $groud_by_= " GROUP BY pg.id_pago ORDER BY  pg.fecha_cuota";
        $this->db()->query("SET @saldo:=(SELECT ps.deuda_total   FROM clientes c INNER JOIN prestamos ps  ON ps.id_cliente = c.id_cliente
          WHERE ps.codigo_prestamo ='$c_c' )");
        $query = "SELECT
                  pg.fecha_cuota as Fecha_abono,
                  c.codigo_cliente as C_cliente,
                  CONCAT(c.nombre,' ',c.apellido) as Cliente,
                  c.cedula as Cedula,
                  cs.nombre as Colaborador,
                  ps.codigo_prestamo as Prestamo,
                  ROUND(ps.capital,2) AS Capital,
                  ROUND(ps.mora ,2) Mora_actual,
                  ROUND(ps.cuota ,2) Cuota,
                  ps.tipo_prestamo as Tipo,
                  ROUND(pg.monto_cuota,2) as Recaudado_C$,
                  ROUND(@saldo := (@saldo - pg.monto_cuota),2) as Saldo_C$,
                  ROUND(sp.porcentaje_capital,2) as por_capital_C$,
                  ROUND(sp.porcentaje_interes,2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo
                  INNER JOIN subpagos sp ON sp.id_pago=pg.id_pago) ON ps.id_cliente = c.id_cliente
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
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
        $range_date = "(m.fecha_mora >='$fecha_bengin' and m.fecha_mora <='$fecha_end') or ps.mora > 0 and ps.fecha_vencimiento >= '".date('Y-m-d')."' and ps.estado <> 'Cancelado'";

        $groud_by_= " GROUP BY cs.codigo_colaborador";

        $query = "SELECT
                  cs.nombre as Colaborador,
                  cs.codigo_colaborador as C_Colaborador,
                  ps.codigo_prestamo as Prestamo,
                  ROUND(SUM(ps.mora),2) as Mora_C$,
                  ROUND(SUM(sp.porcentaje_capital),2) as por_capital_C$,
                  ROUND(SUM(sp.porcentaje_interes),2) as por_interes_C$
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN (prestamos ps
                  LEFT JOIN pagos pg ON pg.id_prestamo = ps.id_prestamo INNER JOIN subpagos sp ON sp.id_pago=pg.id_pago) ON ps.id_cliente = c.id_cliente
                  LEFT JOIN moras m ON m.id_pago = pg.id_pago
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
        $range_date = "(m.fecha_mora >='$fecha_bengin' and m.fecha_mora <='$fecha_end') or ps.mora > 0 and ps.fecha_vencimiento >= '".date('Y-m-d')."' and ps.estado <> 'Cancelado' ";

        $groud_by_= " GROUP BY c.codigo_cliente";

        $query = "SELECT
                      ps.codigo_prestamo as Prestamo,
                      CONCAT(c.nombre,' ',c.apellido)  AS Cliente,
                      c.telefono as Telefono,
                      c.codigo_cliente as C_Cliente,
                      c.direccion as Direccion,
                      c.tipo_negocio as Tipo_negocio,
                      cs.codigo_colaborador as C_Colaborador,
                      ROUND(ps.capital,2) AS Capital,
                      ROUND(ps.deuda_total,2) AS Deuda,
                      ROUND(ps.total_abonado,2) as Total_abonado,
                      ROUND(ps.saldo_pendiente,2) Saldo_pendiente,
                      ROUND(ps.cuota ,2) Cuota,
                      ps.Estado as Estado,
                      ROUND(ps.mora ,2) Mora_actual,

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
                  LEFT JOIN moras m ON m.id_pago = pg.id_pago
                  INNER JOIN plazos pz ON pz.id_plazo = ps.id_plazo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
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

    public function generarReportsByPrestamosMoras($groud_by,$filter_by,$date_range,$id_sucursal)
    {
		date_default_timezone_set('America/Managua');
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = " ps.mora >0 and  ps.estado = 'Activo'";
		$fecha_actual= date("Y-m-d");
        $groud_by_= "";

        $query = "SELECT
                  c.nombre as Nombre,
                  c.telefono as Telefono,
                  c.direccion as Direccion,
                  cs.nombre as Colaborador,
                  ps.codigo_prestamo as Prestamo,
                  ps.tipo_prestamo as Tipo_Prestamo,
                  ps.modalidad as modalidad,
                  ps.estado as Estado,
                  s.sucursal as Sucursal,
                  ps.capital as Capital,
                  ps.deuda_total as Deuda_Total,
                  ps.total_abonado as Total_Abonado,
                  ps.saldo_pendiente as Saldo_Pendiente,
				  ps.mora as Mora,
				  round((ps.mora/ps.cuota),0) as N_cuotas_Atrasada,
				  if(ps.fecha_vencimiento < '$fecha_actual','Mora Vencida',if((ps.mora/ps.cuota)>0 and (ps.mora/ps.cuota) <=4,'Mora Temprana',if((ps.mora/ps.cuota)>4 and (ps.mora/ps.cuota) <=8,'Mora Avanzada','Mora Riesgo'))) as Tipo_Mora
                  FROM clientes c
                  INNER JOIN colaboradores cs ON cs.id_colaborador = c.id_colaborador
                  INNER JOIN sucursal s on s.id_sucursal = cs.id_sucursal
                  INNER JOIN prestamos ps on c.id_cliente = ps.id_cliente

                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";

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
        $range_date = "ps.estado= 'Aceptado' and ps.fecha_vencimiento < '".date('Y-m-d')."'";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido)  as Nombre,
                  c.cedula as Cedula,
                  c.telefono as Telefono,
                  c.direccion as Direccion,

                  ROUND(ps.capital,2) as Capital,
                  ROUND(ps.deuda_total,2) as Deuda_Total,
                  ROUND(ps.total_abonado,2) as Total_abonado,
                  ROUND((ps.capital/ps.deuda_total)*ps.total_abonado,2) as por_capital_C$,
                  ROUND(ps.total_abonado-((ps.capital/ps.deuda_total)*ps.total_abonado),2) as por_interes_C$,
                  ROUND(ps.saldo_pendiente,2) as Saldo_Pendiente,
                  ROUND((ps.saldo_pendiente/ps.cuota),2) as Cuotas_Vencidas,
                  ROUND(ps.cuota,2) as Cuota,
                  ROUND(ps.mora,2) as Mora,
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

    public function generarReportsByPrestamosCancelados($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date ="ps.estado = 'Cancelado' and pg.Fecha_ult_abono>='$fecha_bengin' and pg.Fecha_ult_abono <= '$fecha_end'";

        $groud_by_= " GROUP BY ps.codigo_prestamo";

        $query = "SELECT
                  CONCAT(c.nombre,' ',c.apellido)  as Nombre,
                  c.cedula as Cedula,
                  c.telefono as Telefono,
                  c.direccion as Direccion,
          ps.codigo_prestamo as Codigo_Prestamo,
                  ps.capital as Capital,
                  ps.deuda_total as Deuda_Total,
                  ps.total_abonado as Total_abonado,
                  ROUND((ps.capital/ps.deuda_total)*ps.total_abonado,2) as Porcen_Capital_C,
                  ROUND(ps.total_abonado-((ps.capital/ps.deuda_total)*ps.total_abonado),2) as Porcen_Interes_C,
                  ps.cuota as Cuota,ps.dia_desembolso As Dia_Pago,
                  cs.nombre as Gestor,
                  DATE_FORMAT(ps.fecha_desembolso,'%d/%m/%Y') as Fecha_Desemboldo,
                  DATE_FORMAT(ps.fecha_vencimiento,'%d/%m/%Y') as Fecha_Vencimiento,
          DATE_FORMAT(pg.Fecha_ult_abono,'%d/%m/%Y') as Fecha_de_Cancelacion,
          pg.Ultima_cuota as  Monto_de_Cancelacion,
          pg.usuario as  Gestor_Realizo_Pago
                  FROM sucursal s
                  INNER JOIN colaboradores cs ON s.id_sucursal=cs.id_sucursal
                  INNER JOIN clientes c ON cs.id_colaborador = c.id_colaborador
                  INNER JOIN prestamos ps on c.id_cliente=ps.id_cliente
                  LEFT JOIN (select pag.id_pago,pag.id_prestamo,pag.usuario,
                                    pag.fecha_pago as Fecha_ult_abono ,
                                    pag.monto as Ultima_cuota 
                            from pagos_new pag 
                            where pag.id_pago=(select max(pago.id_pago) from pagos_new pago where pago.id_prestamo=pag.id_prestamo and pago.saldo_pendiente= 0 and pago.estado='pagado')) pg on ps.id_prestamo=pg.id_prestamo
                  WHERE $range_date
                  $id_sucursal
                  $groud_by_
                  ";
        //print_r($query);exit;
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
        $usuario = $this->ejecutarSqlReports($query);
        return $usuario;
    }
    public function generarReportsByPrestamos20Porc($groud_by,$filter_by,$date_range,$id_sucursal)
    {
        $fecha_bengin = $date_range['bengin'];
        $fecha_end = $date_range['end'];
        $groud_by_ = "";
        $morosos = "";
        $range_date = "p.saldo_pendiente <= (p.deuda_total*0.20)  and  cl.cargo = 'analista' and p.estado = 'Activo'";

        $groud_by_= "";

            $query ="SELECT
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
                   WHERE  $range_date $id_sucursal   order by estado DESC, analista ASC ";
            
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

}

?>
