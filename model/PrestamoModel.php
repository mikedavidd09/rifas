<?php
class PrestamoModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="prestamos";
        parent::__construct($this->table,$adapter);
    }

     //Metodo listado del index
    public function getListadoIndex($query){
        $listado=$this->ejecutarSqlRow($query);
        return $listado;
    }
    //Metodos de consulta
    public function getAll(){
        $query="SELECT * FROM $this->table";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }

    public function getDataPrestamoRechazadosId($id_prestamo){
        $query="SELECT *,
                        C.nombre,
                        C.apellido,
                        C.direccion,
                        CL.nombre as nombreanalista,
                        CL.apellido as apellidoanalista
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where P.estado='Rechazado' and P.id_prestamo=$id_prestamo";
        $data=$this->ejecutarSql($query);
        return $data;
    }

    public function getDataPrestamoVencidossId($id_prestamo){
        $query="SELECT *,
                        C.nombre,
                        C.apellido,
                        C.direccion,
                        CL.nombre as nombreanalista,
                        CL.apellido as apellidoanalista
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where P.estado<>'Rechazado' and P.id_prestamo=$id_prestamo";
        $data=$this->ejecutarSql($query);
        return $data;
    }

    public function getFeriadosByIdCliente($id_cliente){
        $query = "select  d.id_sucursal , dia, mes ,descripcion from diaferiado d
                    INNER JOIN  colaboradores c on d.id_sucursal = c.id_sucursal
                    INNER JOIN clientes cl on c.id_colaborador= cl.id_colaborador and cl.id_cliente='$id_cliente'
                    ORDER BY d.dia";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    public function getAllPrestamosNew(){
        $query="SELECT
        *,
        C.nombre,
        C.apellido,
        C.direccion,
        CL.nombre as nombreanalista,
        CL.apellido as apellidoanalista
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where  (UPPER(P.estado)=UPPER('Aceptado') or UPPER(P.estado)=UPPER('Cancelado'))";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }
    public function getFeriadosByIdColaborador($id_colaborador){

        $query = "SELECT  d.id_sucursal , dia, mes ,descripcion from diaferiado d
                    INNER JOIN  colaboradores c on d.id_sucursal = c.id_sucursal
                    where  c.id_colaborador='$id_colaborador'
                    ORDER BY d.dia";
        $result=$this->ejecutarSql($query);
        return $result;
    }
    public function getDataPrestamoId($id_prestamo){
         $query="SELECT *,
                        C.nombre,
                        C.apellido,
                        C.direccion,
                        C.telefono as telefono_cliente,
                        CL.nombre as nombreanalista,
                        CL.apellido as apellidoanalista
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where P.id_prestamo=$id_prestamo and P.estado='Aceptado'";
        $data=$this->ejecutarSql($query);
        return $data;
    }
    public function getDataPrestamoEstado($id_prestamo,$estado){
         $query="SELECT *,
                        C.nombre,
                        C.apellido,
                        C.cedula as cedula_cliente,
                        C.telefono as telefono_cliente,
                        C.direccion,
                        C.capital_max,
                        CL.nombre as nombreanalista,
                        CL.apellido as apellidoanalista,
                       
                        C.telefono as cliente_telefono
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where P.estado='$estado' and P.id_prestamo=$id_prestamo";
        $data=$this->ejecutarSql($query);
        return $data;
    }
    public function getAllPrestamos(){
       $query="SELECT
    *,
C.nombre,
C.apellido,
C.direccion,
        CL.nombre as nombreanalista,
        CL.apellido as apellidoanalista



        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where P.estado='Aceptado'";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }

    public function getPrestamosCliente($id){//modificado
        $query="
        select
            p.id_prestamo,
            p.codigo_prestamo,
            p.capital,
            p.cuota,
            p.descripcion_desembolso,
            p.observacion,
            p.deuda_total,
            p.total_abonado,
            p.modalidad,
            p.mora,
        p.estado,
            p.monto_favor,
            DATE_FORMAT(p.fecha_desembolso,'%d/%m/%Y') as fecha_desembolso,
             DATE_FORMAT(p.fecha_vencimiento,'%d/%m/%Y') as fecha_vencimiento,
            p.foto_desembolso,
            i.porcentaje,
            pl.numero_cuotas,
            pl.equivalen_mes
            from prestamos p
            inner join intereses i on p.id_interes = i.id_interes
            inner join plazos pl on pl.id_plazo = p.id_plazo
            where p.id_cliente = $id
        ";
        $prestamos=$this->ejecutarSql($query);
        return $prestamos;
    }

    public function getPrestamoxUserActivo($codprestamo){
        $query ="SELECT *,datediff(curdate(),P.fecha_vencimiento) as ndvencidos,(select SUM(pa.monto_cuota) from pagos pa where pa.id_prestamo=P.id_prestamo and pa.fecha_cuota=CURDATE() and pa.estado='pagado') as abonado FROM clientes AS C INNER JOIN $this->table AS P ON C.id_cliente=P.id_cliente INNER JOIN intereses as I on P.id_interes=I.id_interes INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo WHERE P.id_prestamo=$codprestamo";
        //se devuelve el resultado en forma de ArrayAccess
     //   echo $query;
        $resultSet=$this->ejecutarSql($query);
        return $resultSet;
    }
    public function getPrestamoByIdColaborador($id_colaborador){
        $query ="SELECT * FROM $this->table AS P INNER JOIN clientes AS C ON P.id_cliente=C.id_cliente INNER JOIN intereses as I on P.id_interes=I.id_interes INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo WHERE C.id_colaborador='$id_colaborador'";
        //se devuelve el resultado en forma de ArrayAccess
        $resultSet=$this->ejecutarSql($query);
        return $resultSet;
    }
    public function getIdPrestamoColaboradorByCodigoPrestamo($codigo_Prestamo){
        $query ="SELECT P.id_prestamo, C.id_colaborador FROM clientes as C INNER JOIN $this->table AS P ON C.id_cliente=P.id_cliente WHERE P.codigo_prestamo='$codigo_Prestamo'";
        //se devuelve el resultado en forma de ArrayAccess
        $resultSet=$this->ejecutarSql($query);
        return $resultSet;
    }

    public function getDataPrestamoByCodigoPrestamo($codigo_Prestamo , $id_colaborador){
        $query ="SELECT *
                 FROM clientes as C
                 INNER JOIN $this->table AS P ON C.id_cliente=P.id_cliente
                 WHERE P.codigo_prestamo='$codigo_Prestamo' and C.id_colaborador = $id_colaborador and P.estado = 'Aceptado'";
        $resultSet=$this->ejecutarSql($query);
        return $resultSet;
    }

    public function getDataPrestamoByCodigoPrestamoSoporte($codigo_Prestamo , $id_colaborador){
        $query ="SELECT *
                 FROM clientes as C
                 INNER JOIN $this->table AS P ON C.id_cliente=P.id_cliente
                 WHERE P.codigo_prestamo='$codigo_Prestamo'and P.estado = 'Aceptado'";
                // print_r($query);
        $resultSet=$this->ejecutarSql($query);
        return $resultSet;
    }

    public function getDataPrestamoByCodigoPrestamoNew($codigo_Prestamo , $id_colaborador){
        //C.id_colaborador = $id_colaborador and
        $query ="SELECT C.*,
                        pl.numero_cuotas,
						P.id_prestamo,
						P.capital,
						P.deuda_total,
						P.cuota,
						P.fecha_desembolso,
						P.fecha_vencimiento,
						P.modalidad,
                        P.primera_cuota,
						P.mora,
						P.monto_favor,
						nota.capital as nota_capital,
						nota.neto_pagar,
						nota.monto_interes,
						nota.monto_capital
                 FROM clientes as C
                 INNER JOIN $this->table AS P ON C.id_cliente=P.id_cliente
                 INNER JOIN plazos pl on P.id_plazo=pl.id_plazo
				 LEFT JOIN (SELECT n.id_prestamo,n.neto_pagar,n.capital,n.monto_capital,n.monto_interes FROM prestamos pr INNER JOIN nota_credito n on pr.id_prestamo=n.id_prestamo where n.estado='activo') nota on P.id_prestamo=nota.id_prestamo
                 WHERE P.codigo_prestamo='$codigo_Prestamo'";
                 //print_r($query);
        $resultSet=$this->ejecutarSql($query);
        return $resultSet;
    }

    public function getPrestamosByAbonoByMora($id_prestamo){
        $query="SELECT p.monto_cuota,p.fecha_cuota,p.saldo_excedente,p.saldo_pendiente,m.monto_mora,m.pago_mora, count(p.fecha_cuota) as ncd from pagos as p LEFT join moras as m on p.id_pago=m.id_pago where id_prestamo=$id_prestamo and p.estado='pagado' GROUP BY p.id_pago";
        $resultSet=$this->ejecutarSql($query);
        return $resultSet;

    }
    public function getPrestamoByAbonoBySucursal($id_sucursal,$fecha_actual){
        $query="SELECT P.codigo_prestamo,PA.fecha_cuota FROM colaboradores as C INNER JOIN clientes as CL on C.id_colaborador=CL.id_colaborador INNER JOIN prestamos as P on CL.id_cliente=P.id_cliente INNER JOIN pagos as PA INNER JOIN P.id_prestamo=PA.id_prestamo WHERE P.total_abonado < P.deuda_total AND PA.fecha_cuota='$fecha_actual' AND PA.estado='pagado' AND C.id_sucursal=$id_sucursal";

       //$query="SELECT DISTINCT P.codigo_prestamo,PA.fecha_cuota FROM $this->table AS P INNER JOIN pagos as PA on P.id_prestamo=PA.id_prestamo
                  //WHERE P.total_abonado < P.deuda_total AND PA.fecha_cuota='$fecha_actual' AND PA.estado='pagado'";
        $prestamo=$this->ejecutarSql($query);

        return $prestamo;
    }
    public function getPrestamoByAbonoAll($fecha_actual){
        $query="SELECT DISTINCT P.codigo_prestamo,PA.fecha_cuota FROM $this->table AS P INNER JOIN pagos as PA on P.id_prestamo=PA.id_prestamo
                  WHERE P.total_abonado < P.deuda_total AND PA.fecha_cuota='$fecha_actual' AND PA.estado='pagado'";
        $prestamo=$this->ejecutarSql($query);

        return $prestamo;
    }
    public function getSolicitudesReprestamosEstado($estado,$id_sucursal){
        $query="SELECT
    *,
C.nombre,
C.apellido,
C.direccion,
        CL.nombre as nombreanalista,
        CL.apellido as apellidoanalista
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo
        WHERE estado='$estado' and CL.id_sucursal=$id_sucursal";
        $represtamo=$this->ejecutarSql($query);

        return $represtamo;
    }
    public function getCantidadPrestamoSolicitud(){
        $query="SELECT count(estado) as cantidad FROM $this->table WHERE estado='Pendiente'";
        $count=$this->ejecutarSql($query);

        return $count;
    }
    public function getSolicitudesReprestamos($estado){
        $query="SELECT
    *,
C.nombre,
C.apellido,
C.direccion,
        CL.nombre as nombreanalista,
        CL.apellido as apellidoanalista
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo
        WHERE estado='$estado'";
        $represtamo=$this->ejecutarSql($query);

        return $represtamo;
    }


    public function GetPrestamosEstado($estado,$condicion){

        $query="SELECT
    *,
C.nombre,
C.apellido,
C.direccion,
        CL.nombre as nombreanalista,
        CL.apellido as apellidoanalista
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo
        WHERE estado='$estado' $condicion";


        $prestamos=$this->ejecutarSql($query);

        return $prestamos;
    }



    public function getPrestamosByCliente($nombre,$id_sucursal,$cargo){

      $sql = $cargo!='Administrador'?'and s.id_sucursal ='.$id_sucursal:'';

        $query ="SELECT c.id_cliente,ps.codigo_prestamo,concat(c.nombre,' ',c.apellido) as nombre
                FROM clientes c
                INNER JOIN colaboradores cs ON c.id_colaborador = cs.id_colaborador
                INNER JOIN sucursal s ON s.id_sucursal = cs.id_sucursal
                INNER JOIN prestamos ps ON ps.id_cliente = c.id_cliente
                WHERE upper(concat(c.nombre,' ',c.apellido)) LIKE upper('%$nombre%')";
                //--  WHERE (c.nombre LIKE '%$nombre%' or c.apellido LIKE '%$nombre%')  $sql ";
        $resultSet=$this->ejecutarSql($query);
        return is_object($resultSet) ? [$resultSet]:$resultSet;
    }

    public function PrestamosActivosByIdCliente($id_cliente){

        $query = "SELECT * from prestamos p inner join clientes c on c.id_cliente = p.id_cliente where c.id_cliente = '$id_cliente' and p.estado = 'Aceptado'";

        $result = $this->ejecutarSql($query);

        return is_object($result) ? [$result]:$result;

    }

    public function DatosByIdPrestamo($id_prestamo){

        $query = " SELECT *,
  CONCAT(c.nombre,' ',c.apellido) as cliente,
  concat(cl.nombre ,'',cl.apellido)as analista ,
  round(((p.capital*100/p.deuda_total)/100*p.saldo_pendiente),2)as pcsp,
  round((((p.capital*i.porcentaje/100)*100/p.deuda_total)/100*p.saldo_pendiente),2) as pisp
  from prestamos p inner join clientes c on c.id_cliente = p.id_cliente
  inner join colaboradores cl on cl.id_colaborador = c.id_colaborador
  inner join intereses i on p.id_interes = i.id_interes
  where p.id_prestamo =  '$id_prestamo'";

        $result = $this->ejecutarSql($query);

        return $result;
    }

    public function PrestamosActivosNoVencidosByIdSucursal($id_sucursal,$diasemana){
        date_default_timezone_set('America/Managua');

        $today = date('Y-m-d');

        $query = "SELECT *,
                CONCAT(c.nombre,' ',c.apellido) as cliente,
                concat(col.nombre ,' ',col.apellido)as analista from clientes c
                inner join prestamos p ON c.id_cliente = p.id_cliente
                inner join colaboradores col on col.id_colaborador=c.id_colaborador
                inner join plazos pl on p.id_plazo=pl.id_plazo
                inner join intereses i on i.id_interes = p.id_interes
                inner join sucursal s on s.id_sucursal = col.id_sucursal
                where   p.estado = 'Aceptado'
                and p.fecha_vencimiento>='$today'
                and p.fecha_desembolso != '$today'
                and s.id_sucursal = '$id_sucursal'
                and (p.dia_desembolso = 'Diario' or p.dia_desembolso = '$diasemana'  or (p.dia_desembolso = 'Quincenal' and (IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/15),0)*15) DAY)) = 5,
        DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/15),0)*15) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/15),0)*15) DAY))=6,DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/15),0)*15) DAY), INTERVAL 1 DAY),DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/15),0)*15) DAY))))='$today') or (p.dia_desembolso = 'Mensual' and  (IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/30),0)*30) DAY)) = 5,
        DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/30),0)*30) DAY), INTERVAL 2 DAY)
        ,IF(WEEKDAY(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/30),0)*30) DAY))=6,DATE_ADD(DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/30),0)*30) DAY), INTERVAL 1 DAY),DATE_ADD(p.fecha_desembolso, INTERVAL (round((TIMESTAMPDIFF(DAY,p.fecha_desembolso,'$today')/30),0)*30) DAY))))='$today'  ))
                order by analista,p.dia_desembolso";

        $result = $this->ejecutarSql($query);
        
        return is_object($result) ? [$result]:$result;


    }

    public function updatePrestamoById($id,$data)
    {
        $this->Query("UPDATE prestamos SET $data WHERE id_prestamo=$id");
    }
    /*************NUEVO NOTA DE CREDITO************************/
        public function getDataPrestamoNotaCredito($id_cliente){

        $query = "SELECT c.id_cliente as id_cliente,
                        CONCAT(c.nombre,' ',c.apellido) as cliente,
                        c.cedula as cedula,
                        IF(c.direccion is null,0,IF(length(c.direccion) = 0,0,c.direccion) ) as direccion,
                        IF(c.localidad is null,0,c.localidad) as localidad,
                        CONCAT(col.nombre,' ',col.apellido) as gestor,
						col.id_sucursal,
                        p.capital as capital,
                        p.deuda_total as deuda_total,
                        CONCAT(i.porcentaje,' %') as porcentaje,
                        CONCAT(pl.numero_cuotas,' Cuotas (',pl.equivalen_mes,')') as plazo,
                        p.modalidad as modalidad,
                        p.cuota as cuota,
                        p.codigo_prestamo as codigo_prestamo,
                        p.id_prestamo as id_prestamo,
                        (p.deuda_total - p.capital) as montointeres,
                        ROUND(((p.capital/p.deuda_total) * p.saldo_pendiente),2) as porcapital,
                        ROUND((p.saldo_pendiente -((p.capital/p.deuda_total) * p.saldo_pendiente)),2) as porinteres,
                        p.saldo_pendiente as saldo_pendiente,
                        (IF(CURDATE() > p.fecha_vencimiento,'VENCIDO','ACTIVO' )) as estado,
                        IF(p.modalidad = 'Semanal',DATE_FORMAT(DATE_ADD(p.fecha_desembolso, INTERVAL (round((p.total_abonado/ p.cuota),0) * 7) DAY),'%d/%m/%Y'),DATE_FORMAT(DATE_ADD(p.fecha_desembolso, INTERVAL (round((p.total_abonado/ p.cuota))+(round((round((p.total_abonado/ p.cuota),0)/5),0)*2)) DAY),'%d/%m/%Y')) as fecha_ultimo_abono,
                        IF(p.modalidad = 'Semanal',(DATEDIFF(CURDATE(),DATE_ADD(p.fecha_desembolso, INTERVAL (round((p.total_abonado/ p.cuota),0) * 7) DAY))),(DATEDIFF(CURDATE(),DATE_ADD(p.fecha_desembolso, INTERVAL (round((p.total_abonado/ p.cuota))+(round((round((p.total_abonado/ p.cuota),0)/5),0)*2)) DAY)))) as dia_a_pagar,
                        ROUND((p.capital / pl.numero_cuotas),2) as cuotacapital, 
                        ROUND(((p.deuda_total - p.capital) / pl.numero_cuotas),2) as cuotainteres,
                        ROUND(IF(p.modalidad = 'Diario',((p.deuda_total - p.capital) / pl.numero_cuotas),((p.deuda_total - p.capital) / pl.numero_cuotas)/7),2) as cuotainterespordia,
                        DATE_FORMAT(p.fecha_desembolso,'%d/%m/%Y') as fecha_desembolso,
                        DATE_FORMAT(p.fecha_vencimiento,'%d/%m/%Y') as fecha_vencimiento,
                        p.total_abonado as total_abonado,
                        p.tipo_prestamo
                            from colaboradores col 
                            inner join clientes c on col.id_colaborador=c.id_colaborador 
                            inner join prestamos p on c.id_cliente = p.id_cliente 
                            inner join plazos pl on p.id_plazo=pl.id_plazo 
                            inner join intereses i on p.id_interes=i.id_interes where c.id_cliente = '$id_cliente' and p.estado = 'Aceptado' and p.id_nota = 0";

        $result = $this->ejecutarSql($query);

        return is_object($result) ? [$result]:$result;

    }
	 public function CicloPrestamoCliente($condicion){
		$query = "select count(pr.id_prestamo) as ciclo from clientes cl inner join prestamos pr on cl.id_cliente=pr.id_cliente where pr.estado in('Aceptado','Cancelado') and $condicion";
		$result = $this->ejecutarSql($query);

		return is_object($result) ? [$result]:$result;
	}
	public function getIdClienteForIdPrestamo($id_prestamo){
        $query = "select cl.id_cliente from clientes cl inner join prestamos p on cl.id_cliente=p.id_cliente where p.id_prestamo=$id_prestamo";
        $result = $this->ejecutarSql($query);

        return is_object($result) ? [$result]:$result;

    }
    public function getByPrestamoForNota($columna,$tipo){
        $query="SELECT 
                c.id_cliente as id_cliente,
                CONCAT(c.nombre,' ',c.apellido) as cliente, 
                c.cedula,
                DATE_FORMAT(p.fecha_desembolso,'%d/%m/%Y') as fecha_desembolso,
                DATE_FORMAT(p.fecha_vencimiento,'%d/%m/%Y') as fecha_vencimiento,
                CONCAT(col.nombre,' ',col.apellido) as gestor,
                p.capital,
                i.porcentaje,
                pl.equivalen_mes,
                pl.numero_cuotas,
                p.deuda_total,
                p.cuota,
                p.saldo_pendiente,
                round(((p.capital / p.deuda_total ) * p.saldo_pendiente),2) as capital,
                round((p.saldo_pendiente - ((p.capital / p.deuda_total ) * p.saldo_pendiente)),2) as interes,
                p.codigo_prestamo,
                p.id_prestamo,
                (IF(CURDATE() > p.fecha_vencimiento,'VENCIDO','ACTIVO' )) as estadoPrest,
                p.total_abonado
                from colaboradores col 
                INNER JOIN clientes c ON col.id_colaborador=c.id_colaborador 
                INNER JOIN prestamos p ON c.id_cliente = p.id_cliente 
                INNER JOIN intereses i on p.id_interes = i.id_interes 
                INNER JOIN plazos pl on p.id_plazo = pl.id_plazo
                where $columna='$tipo' and (p.estado='Aceptado') and p.tipo_prestamo <> 'Nota Credito'";
                
        $data=$this->ejecutarSql($query);
        return $data;
    }
    public function getCantidadClienteDesembolsadoAndCapital($fecha){
        
        /*$query="SELECT count(p.id_prestamo) as cliente, SUM(p.capital) as capital
                from colaboradores col 
                INNER JOIN clientes c ON col.id_colaborador=c.id_colaborador 
                INNER JOIN prestamos p ON c.id_cliente = p.id_cliente 
                INNER JOIN intereses i on p.id_interes = i.id_interes 
                INNER JOIN plazos pl on p.id_plazo = pl.id_plazo
                where p.fecha_desembolso = '$fecha' and (p.estado='Aceptado') ";*/
                
                $query="SELECT count(p.id_prestamo) as cliente, SUM(p.capital) as capital
                from colaboradores col 
                INNER JOIN clientes c ON col.id_colaborador=c.id_colaborador 
                INNER JOIN prestamos p ON c.id_cliente = p.id_cliente 
                INNER JOIN intereses i on p.id_interes = i.id_interes 
                INNER JOIN plazos pl on p.id_plazo = pl.id_plazo
                INNER JOIN historial h on p.id_prestamo=h.id_prestamo
                where p.fecha_desembolso = '$fecha' and (p.estado='Aceptado') and h.fecha_validez='9999-12-31' and h.modulo='Bandeja' and h.submodulo='Aprobado'";
                
        $data=$this->ejecutarSql($query);
        return $data;

    }
    public function getIdInteresUltPrestamo($id_cliente)
    {
        // code...
        $query="SELECT p.capital as capital_max,p.id_prestamo,i.id_interes,i.porcentaje FROM clientes c 
    INNER JOIN prestamos p on c.id_cliente=p.id_cliente
    INNER JOIN intereses i on p.id_interes=i.id_interes
    
    where c.id_cliente=$id_cliente ORDER by p.id_prestamo DESC limit 1";
                
        $data=$this->ejecutarSql($query);
        return $data;
    }

    /*public function getUltimoPrestamoCliente($id_cliente){
        $query="SELECT p.codigo_prestamo,min(pg.saldo_pendiente) as saldo_pendiente,pg.fecha_pago,
                    (DATEDIFF(CURDATE(),pg.fecha_pago)) AS dias
                  FROM clientes cl 
                  INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
                  INNER JOIN pagos_new pg on p.id_prestamo=pg.id_prestamo
                  WHERE (pg.estado = 'Pagado' or pg.estado='pagado') and cl.id_cliente = $id_cliente and p. estado <> 'Rechazado' GROUP by p.id_prestamo order by p.id_prestamo desc limit 1";
                
        $data=$this->ejecutarSql($query);
        return $data;
    }*/
    public function getUltimoPrestamoCliente($id_cliente){
     
        $query="SELECT id_prestamo FROM prestamos WHERE id_cliente=$id_cliente and estado <> 'Rechazado' ORDER by id_prestamo desc limit 1";
                
        $data=$this->ejecutarSql($query);
        return $data;
    }

    public function getAllPrestamosDesembolsadoSumCount($fecha,$sucursal = ""){
        $query="SELECT
        ROUND(sum(P.capital),2) as total
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente 
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where  P.fecha_desembolso = '$fecha' and P.estado='Aceptado'";
        // print_r($query);
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }

    public function getAllPrestamosDesembolsadoSumCountByMonth($fecha,$sucursal = ""){
        $query="SELECT
        ROUND(sum(P.capital),2) as total
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where  MONTH(P.fecha_desembolso) = MONTH('$fecha') and P.estado='Aceptado' ";
        //print_r($query);
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }

    public function getAllPrestamosCount($sucursal = ""){
        $query="SELECT
        Count(*) as total
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where P.estado='Activo'";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }

    public function getMoraAllPrestamosumCountActual($fecha="",$sucursal = ""){
        $query="SELECT
        ROUND(sum(P.mora),2) as total
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where P.estado='Activo' ";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }

    public function getSaldoPendienteAllPrestamosumCountActual($fecha = "",$sucursal = ""){
        $query="SELECT
        ROUND(sum(P.saldo_pendiente),2) as total
        FROM clientes  C INNER JOIN colaboradores  CL ON C.id_colaborador=CL.id_colaborador
        INNER JOIN sucursal S on S.id_sucursal = CL.id_sucursal
        INNER JOIN prestamos as P ON C.id_cliente=P.id_cliente
        INNER JOIN intereses as I on P.id_interes=I.id_interes
        INNER JOIN plazos as PL on P.id_plazo=PL.id_plazo where P.estado='Activo' ";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }
}
?>
