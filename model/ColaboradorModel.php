<?php

class ColaboradorModel extends ModeloBase
{
    private $table;

    public function __construct($adapter)
    {
        $this->table = "colaboradores";
        parent::__construct($this->table, $adapter);
    }

    //Metodos de consulta
    public function getUnEmpleado()
    {
        $query = "SELECT * FROM analistas WHERE nombre='Eddy'";
        $usuario = $this->ejecutarSql($query);
        return $usuario;
    }

    public function getColaboradores()
    {
        $query = "SELECT * FROM colaboradores WHERE cargo='Analista'";
        $usuario = $this->ejecutarSql($query);
        return is_object($usuario) ? [$usuario] : $usuario;
    }
    public function getColaboradoresSucursal($id_sucursal){
        $query = "SELECT c.* FROM colaboradores c  inner join usuarios u on c.id_colaborador=u.id_colaborador inner join perfiles p on u.id_user=p.id_usuario WHERE (c.cargo='Analista' and p.perfil !='Saneado') and c.id_sucursal=$id_sucursal and c.estado=1";
        $usuario = $this->ejecutarSql($query);
        return is_object($usuario) ? [$usuario] : $usuario;
    }

    public function getColaboradoresAll()
    {
        $query = "SELECT * FROM colaboradores WHERE estado=1";
        $usuario = $this->ejecutarSql($query);
        return is_object($usuario) ? [$usuario] : $usuario;
    }

    public function getColaboradoresAsalariado()
    {
        $query = "SELECT * FROM colaboradores c inner join salarios s on c.id_colaborador=s.id_colaborador WHERE estado=1";
        $usuario = $this->ejecutarSql($query);
        return is_object($usuario) ? [$usuario] : $usuario;
    }

    public function getDataColaborador($id_col)
    {
        $query = "SELECT col.*,u.* FROM  colaboradores col inner join usuarios u on col.id_colaborador=u.id_colaborador inner join roles r on u.id_role=r.id_role WHERE col.id_colaborador=$id_col";
        $data = $this->ejecutarSql($query);
        return  $data;
    }

    public function getAllAnalista($objet)
    {
        $analistas = $objet->getAll();
        return $analistas;
    }
     public function getSucursalColaborador($id_colaborador){
         $query = "select s.sucursal,c.id_sucursal,concat(c.nombre,' ',c.apellido) as analista from sucursal s inner join colaboradores c  on s.id_sucursal=c.id_sucursal where id_colaborador=$id_colaborador";
        $data = $this->ejecutarSql($query);
        return is_object($data) ? [$data] : $data;
    }
    public function getColaboradorSaneadoDeSucursal($id_sucursal){
		$query = "SELECT c.id_colaborador from sucursal s 
						   INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
						   INNER JOIN usuarios u on c.id_colaborador=u.id_colaborador
						   INNER JOIN perfiles p on u.id_user=p.id_usuario 
						   where p.perfil='Saneado' and s.id_sucursal=$id_sucursal";
        $data = $this->ejecutarSql($query);
        return is_object($data) ? [$data] : $data;
	}
    public function getCarteradelDia($dia,$fecha){
    
        $query="SELECT cl.id_colaborador,
                       COUNT(p.cuota) as ncuotas,
                       SUM(IF(p.fecha_vencimiento>='$fecha' ,ROUND(p.cuota,2),0 ) ) as metactivos,
                       SUM(IF(p.fecha_vencimiento<'$fecha'  ,round(p.cuota,2),0 ) ) as metavencidos,
                       SUM(ROUND(p.cuota,2)) as metaglobal
                from prestamos p 
                INNER JOIN clientes c ON p.id_cliente = c.id_cliente
                INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
                INNER JOIN sucursal s ON s.id_sucursal = cl.id_sucursal
                LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on p.id_prestamo=sa.id_prestamo
                where   p.estado = 'Aceptado' and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$dia' or (p.dia_desembolso='Quincenal' and (IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)))*15) DAY)) = 5,
        DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)))*15) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)))*15) DAY))=6,DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)))*15) DAY), INTERVAL 1 DAY),DATE_ADD(p.fecha_desembolso, INTERVAL ((IF(round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0) = 0, 1,round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/15),0)))*15) DAY))))='$fecha') or (p.dia_desembolso = 'Mensual' and (IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY)) = 5,
        DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY))=6,DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY), INTERVAL 1 DAY),DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$fecha')/30),0)*30) DAY))))='$fecha'))
                and p.fecha_desembolso != '$fecha' and sa.sigla is null GROUP by cl.id_colaborador order by s.id_sucursal";
                
               // print_r($query);die();
    
        $data = $this->ejecutarSql($query);
       return is_object($data) ? [$data] : $data;
    
    }
    public function getCarteradelDiaMora($fecha){
    
        $query="SELECT cl.id_colaborador,
                      sum(IF(p.fecha_vencimiento>='$fecha' ,1,0) ) as ncuotas,
                       SUM(IF(p.fecha_vencimiento>='$fecha' ,ROUND(p.cuota,2),0 ) ) as metactivos,
                       SUM(IF(p.fecha_vencimiento<'$fecha'  ,round(p.cuota,2),0 ) ) as metavencidos,
                       SUM(ROUND(p.cuota))  as metaglobal
                from prestamos p 
                INNER JOIN clientes c ON p.id_cliente = c.id_cliente
                INNER JOIN colaboradores cl ON cl.id_colaborador = c.id_colaborador
                INNER JOIN sucursal s ON s.id_sucursal = cl.id_sucursal
                LEFT JOIN (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old FROM saneo ti INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) sa on p.id_prestamo=sa.id_prestamo
                where   p.estado = 'Aceptado' and p.mora > 0
                and p.fecha_desembolso != '$fecha' and sa.sigla is null GROUP by cl.id_colaborador order by s.id_sucursal";
    
        $data = $this->ejecutarSql($query);
       return is_object($data) ? [$data] : $data;
    
    }

    public function getEstadisticaMetasDeldia($fecha_bengin,$id_sucursal){
        
        $groud_by_ = "";
        $morosos = "";
        $range_date = "pn.estado = 'Pagado' and (pn.fecha_pago >='$fecha_bengin' and pn.fecha_pago <='$fecha_bengin') and tp.sigla is null";
        
        $groud_by_= " GROUP BY c.id_colaborador order by c.id_colaborador desc";
       
            $query="SELECT m.sucursal,
                           m.id_colaborador,
                           m.Colaborador,
                           m.metatotal as Meta,
                           r.Recaudo,
                           concat ( ROUND(((r.Recaudo * 100)/ m.metatotal ) ,2) , ' %') as cumplimiento,
                           r.por_capital,
                           r.por_interes 
                           from ((SELECT c.id_colaborador,
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
                                        left join (SELECT ti.id_prestamo,tb.sigla,ti.id_tipo,ti.fecha_registro,ti.id_asignado_old 
                                                    FROM saneo ti 
                                                        INNER JOIN estado tb on ti.id_estado=tb.id_estado where ti.estado=1) tp on p.id_prestamo=tp.id_prestamo
                    where $range_date $id_sucursal $groud_by_) r RIGHT JOIN (SELECT mt.id_colaborador,s.sucursal,CONCAT(col.nombre,' ',col.apellido) as Colaborador,sum(mt.metatotal) as metatotal FROM metasdeldia mt INNER JOIN colaboradores col on mt.id_colaborador=col.id_colaborador INNER JOIN sucursal s on col.id_sucursal=s.id_sucursal where mt.fecha>='$fecha_bengin' and mt.fecha <= '$fecha_bengin'and mt.estado=1 $id_sucursal GROUP BY mt.id_colaborador) m on r.id_colaborador=m.id_colaborador)";
        //$range_date1 = "(p.estado = 'Aceptado' ) and  (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$weekday')
        //and p.fecha_desembolso < '$fecha_bengin' and tp.sigla is null $id_sucursal";

        $data = $this->ejecutarSql($query);
       return is_object($data) ? [$data] : $data;

    }

}

?>
