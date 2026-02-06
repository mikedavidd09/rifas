<?php
class ClientesModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="clientes";
        parent::__construct($this->table,$adapter);
    }

    //Metodo listado del index
    public function getListadoIndex($query){
        $listado_clientes=$this->ejecutarSqlRow($query);
        return $listado_clientes;
    }
    //Metodos de consulta
    public function getClienteByid($id){
        $query="SELECT UPPER(s.sucursal) as sucursal,c.id_colaborador,cl.* from sucursal s INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador WHERE cl.id_cliente=$id";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }
    //Metodos de consulta
    public function validationsForCedula($cedula){
        $query="select *from clientes where cedula = '$cedula'";
        $usuario=$this->ejecutarSql($query);
        return $usuario;
    }
    public function getClienteByIdColaboradorForSort($id_colaborador, $fecha){
        $clientes =[];

        $query ="SELECT p.primera_cuota,p.tipo_prestamo, p.id_prestamo, concat(c.nombre,'-',c.apellido,' | ', p.codigo_prestamo,'-C$',p.capital,'-',p.modalidad) as cliente, c.direccion 
                  ,(SELECT
                        IF(
                            pn.monto IS NOT NULL,
                            IF(SUM(pn.monto) < pt.cuota,
                            0,
                            1),
                            0
                        ) AS pagado 
                    FROM
                        pagos_new pn
                    INNER JOIN prestamos pt on pn.id_prestamo = pt.id_prestamo
                    WHERE
                        pn.fecha_pago = '$fecha' AND pt.id_prestamo = p.id_prestamo and pn.estado = 'Pagado') as pagado,
                    (SELECT
                      if(pn.monto is not null,concat(sum(pn.monto),'/',pt.cuota),'nada')
                      AS abonado
                    FROM
                        pagos_new pn
                    INNER JOIN prestamos pt on pn.id_prestamo = pt.id_prestamo
                    WHERE
                        pn.fecha_pago = '$fecha' AND pt.id_prestamo = p.id_prestamo and pn.estado = 'Pagado') as abonado,
                        p.cuota,
                        p.programacion,
                        p.modalidad,
                        p.fecha_vencimiento
                FROM clientes c
                    
                INNER JOIN prestamos p ON c.id_cliente = p.id_cliente
                INNER JOIN colaboradores col ON col.id_colaborador = c.id_colaborador 
                 where
                 col.id_colaborador=$id_colaborador and p.estado = 'Aceptado' 
                 order by p.route_order";
        //print_r($query);
        //Extraigo los prestamos activo correspondiente por usuario
        $clientes=$this->ejecutarSql($query);

        return $clientes;
    }

    public function getClientexUserXPrestamoActivo($id_colaborador){
        $query ="SELECT * FROM $this->table AS C
                  INNER JOIN prestamos AS P ON C.id_cliente=P.id_cliente
                  WHERE P.total_abonado < P.deuda_total AND C.id_colaborador=$id_colaborador AND P.estado='Aceptado'";
        $resultSet=$this->ejecutarSql($query);

        return $resultSet;
    }
    public function getClienteByActivoByPrestamo(){
        $query ="SELECT * FROM $this->table AS C
                  INNER JOIN prestamos AS P ON C.id_cliente=P.id_cliente
                  INNER JOIN plazos as PL ON P.id_plazo=PL.id_plazo
                  INNER JOIN intereses as I ON P.id_interes=I.id_interes WHERE P.total_abonado < P.deuda_total AND P.estado='Aceptado'";
        $resultSet=$this->ejecutarSql($query);

        return $resultSet;

    }
    public function getClienteByActivoByPrestamoBySucursal($id_sucursal){
        /*$query ="SELECT * FROM $this->table AS C
                  INNER JOIN prestamos AS P ON C.id_cliente=P.id_cliente
                  INNER JOIN plazos as PL ON P.id_plazo=PL.id_plazo
                  INNER JOIN intereses as I ON P.id_interes=I.id_interes WHERE P.total_abonado < P.deuda_total AND P.estado='Aceptado'";*/
        $query="SELECT * FROM colaboradores as C INNER JOIN clientes as CL on C.id_colaborador=CL.id_colaborador INNER JOIN prestamos as P on CL.id_cliente=P.id_cliente INNER JOIN pagos as PA INNER JOIN P.id_prestamo=PA.id_prestamo WHERE P.total_abonado < P.deuda_total AND P.estado='Aceptado' AND C.id_sucursal=$id_sucursal";
        $resultSet=$this->ejecutarSql($query);

        return $resultSet;

    }
    public function getClienteByIdColaborador($id_colaborador){
        $clientes =[];

        $query ="SELECT * FROM $this->table WHERE id_colaborador=$id_colaborador";
        //Extraigo los prestamos activo correspondiente por usuario
        $clientes=$this->ejecutarSql($query);

        return $clientes;
    }
    public function getMiCarteraClientes($id_colaborador){
        $query ="SELECT id_cliente,CONCAT(nombre,' ',apellido) as cliente FROM $this->table WHERE id_colaborador=$id_colaborador";
        //Extraigo los prestamos activo correspondiente por usuario
        $clientes=$this->ejecutarSql($query);

        return $clientes;
    }
    public function getClienteByPrestamoByUser($id_colaborador){
        $query ="SELECT DISTINCT * FROM $this->table AS C
                  INNER JOIN prestamos AS P ON C.id_cliente=P.id_cliente
				  INNER JOIN plazos as PL ON P.id_plazo=PL.id_plazo
				  INNER JOIN intereses as I ON P.id_interes=I.id_interes
                  WHERE C.id_colaborador=$id_colaborador";
        $prestamo=$this->ejecutarSql($query);

        return $prestamo;
    }
    public function getClienteByPrestamo($id_prestamo){
        /*$query ="SELECT C.*,P.*,PL.*,I.*,nota.id_prestamo as notaId_prestamo,nota.id_prestamoComb,nota.combinar,nota.asignarCombinar
         FROM $this->table AS C
                  INNER JOIN prestamos AS P ON C.id_cliente=P.id_cliente
                  INNER JOIN plazos as PL ON P.id_plazo=PL.id_plazo
                  INNER JOIN intereses as I ON P.id_interes=I.id_interes
                  LEFT JOIN nota_credito nota on nota.codigo_nota=P.codigo_notaCredito  where P.id_prestamo=$id_prestamo";
        $resultSet=$this->ejecutarSql($query);*/
        $query ="SELECT C.*,
                        P.*,
                        PL.*,
                        I.*,
                        nota.id_prestamo as notaId_prestamo,
                        nota.id_prestamoComb,
                        nota.combinar,
                        (case nota.asignarCombinar 
                        when 0 then 'Dispensa de Interes' 
                        when 1 then 'Asignado' 
                        when 2 then 'AsignadoCombinado' 
                        when 3 then 'Reestructuracion' 
                        when 4 then 'Contingencia' 
                        when 5 then 'Combinacion' end) as tipon,
                        nota.asignarCombinar,
                        tipo.tipo as tiponota
         FROM $this->table AS C
                  INNER JOIN prestamos AS P ON C.id_cliente=P.id_cliente
                  INNER JOIN plazos as PL ON P.id_plazo=PL.id_plazo
                  INNER JOIN intereses as I ON P.id_interes=I.id_interes
                  LEFT JOIN nota_credito nota on nota.codigo_nota=P.codigo_notaCredito  
                  LEFT JOIN (SELECT nota.id_prestamo,(case nota.asignarCombinar when 0 then 'Dispensa de Interes' when 1 then 'Asignado' when 2 then 'AsignadoCombinado' when 3 then 'Reestructuracion' when 4 then 'Contingencia' when 5 then 'Combinacion' end) as tipo FROM prestamos p INNER join nota_credito nota on p.id_prestamo=nota.id_prestamo) as tipo on P.id_prestamo=tipo.id_prestamo  where P.id_prestamo=$id_prestamo";
        $resultSet=$this->ejecutarSql($query);

        return $resultSet;

    }
        public function getClienteByCodigoPrestamo($id_prestamo){
        $query ="SELECT concat(C.nombre,'',C.apellido) as cliente,
                        P.codigo_prestamo,
						P.cuota,
						P.modalidad,
						P.dia_desembolso,
						P.saldo_pendiente,
						P.fecha_desembolso,
						P.fecha_vencimiento,
						P.capital,
						P.deuda_total,
						P.mora,
						P.monto_favor,
                        PL.equivalen_mes,
						PL.numero_cuotas,
                        I.porcentaje,
                        notac.codigo_nota as npnew,
                        (SELECT t.codigo_nota from nota_credito t INNER JOIN prestamos pr on t.id_prestamo=pr.id_prestamo where t.estado='activo' and t.id_prestamo=P.id_prestamo) as tiponota,
                        (SELECT nota.codigo_nota from prestamos pt INNER JOIN  rnotacombinada rnotacom on pt.id_prestamo=rnotacom.id_prestamo INNER JOIN nota_credito nota on rnotacom.id_nota=nota.id_nota where nota.estado='activo' and pt.id_prestamo=P.id_prestamo) as tiponota1,
                        C.direccion,
                        C.direccion_negocio,
                        CASE
                            WHEN pf.perfil = 'saneado' THEN 'Saneado'
                            WHEN P.fecha_vencimiento < CURDATE() AND P.estado != 'Cancelado' THEN 'Vencido'
                            ELSE P.estado
                        END AS estado
                        
         FROM clientes AS C
                  INNER JOIN prestamos AS P ON C.id_cliente=P.id_cliente
                  INNER JOIN plazos as PL ON P.id_plazo=PL.id_plazo
                  INNER JOIN intereses as I ON P.id_interes=I.id_interes
                  LEFT JOIN nota_credito notac on notac.id_nota=P.id_nota
                    inner join colaboradores col on C.id_colaborador = col.id_colaborador 
                    inner join sucursal s on s.id_sucursal = col.id_sucursal
                    INNER JOIN usuarios u on col.id_colaborador=u.id_colaborador
                    INNER JOIN perfiles pf on u.id_user=pf.id_usuario
                where P.codigo_prestamo='$id_prestamo'";

        //print_r($query);
            $resultSet=$this->ejecutarSql($query);

            return $resultSet;

        }
    public function getPrestamoByAbonaByCliente($id_colaborador,$fecha_actual){
        $query="SELECT DISTINCT P.codigo_prestamo,PA.fecha_cuota FROM $this->table AS C
                  INNER JOIN prestamos AS P ON C.id_cliente=P.id_cliente INNER JOIN pagos as PA on P.id_prestamo=PA.id_prestamo
                  WHERE P.total_abonado < P.deuda_total AND C.id_colaborador=$id_colaborador AND PA.fecha_cuota='$fecha_actual'";
        $prestamo_abonado=$this->ejecutarSql($query);

        return $prestamo_abonado;
    }
    public function getDesembolsosResumenBydate($fecha_bengin,$fecha_end){
        $query="SELECT p.fecha_desembolso ,COUNT(p.id_prestamo) as clientes  FROM prestamos p WHERE p.fecha_desembolso>='$fecha_bengin' and p.fecha_desembolso<='$fecha_end' GROUP BY p.fecha_desembolso";
        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;
    }

    public function  getClientesBySucursal($id_sucursal){
        $query="SELECT c.nombre, c.apellido, c.id_cliente, c.cedula, c.codigo_cliente, c.sexo, c.telefono, c.tipo_negocio,c.id_colaborador,c.direccion,c.imagen
                                            FROM clientes c
                                            INNER JOIN colaboradores cs ON c.id_colaborador = cs.id_colaborador
                                            INNER JOIN sucursal s ON s.id_sucursal = cs.id_sucursal
                                            WHERE s.id_sucursal = $id_sucursal";
        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;
    }

    public function getClientesByNombre($nombre,$id_sucursal,$cargo,$id_colaborador){


            $sql = ($cargo == 'Analista') ? "and cs.id_colaborador=".$id_colaborador:(($cargo!='Administrador' && $cargo!='Supervisor') ? 'and s.id_sucursal ='.$id_sucursal:'');


        $query ="SELECT c.id_cliente,c.codigo_cliente,concat(c.nombre,' ',c.apellido) as nombre FROM clientes c
                INNER JOIN colaboradores cs ON c.id_colaborador = cs.id_colaborador
                INNER JOIN sucursal s ON s.id_sucursal = cs.id_sucursal
               WHERE upper(concat(c.nombre,' ',c.apellido)) LIKE upper('%$nombre%') $sql LIMIT 5";
        $resultSet=$this->ejecutarSql($query);
        return is_object($resultSet) ? [$resultSet]:$resultSet;
    }

    public function getArray($prestamo,$encon){
        $aux=[];
        $aux["abonado"]=$encon;
        $aux["id_cliente"]=$prestamo->id_cliente;
        $aux["codigo_cliente"]=$prestamo->codigo_cliente;
        $aux["codigo_prestamo"]=$prestamo->codigo_prestamo;
        $aux["nombre"]=$prestamo->nombre;
        $aux["apellido"]=$prestamo->apellido;
        $aux["cedula"]=$prestamo->cedula;
        $aux["telefono"]=$prestamo->telefono;
        $aux["direccion"]=$prestamo->direccion;
        $aux["cuota"]=$prestamo->cuota;
        $aux["fecha_desembolso"]=$prestamo->fecha_desembolso;

        return $aux;
    }
    public function setUpdateCarteraColaborador($id_colaborador,$id_cliente){
        $query ="update $this->table set id_colaborador=$id_colaborador where id_cliente in($id_cliente)";
        $result = $this->ejecutarSqlUpdate($query);
        return $result;
    }
    public function getArrayByAbonado($prestamo_activo,$prestamo_abonado){
        $datos=[];

        //$prest_act []=$prestamo_activo;
        // $prest_abon []=$prestamo_abonado;
        $encontrado=false;
        if(is_array($prestamo_activo)){
            if(is_array($prestamo_abonado)){
                foreach ($prestamo_activo as $prestamo) {
                    foreach ($prestamo_abonado as $abonado) {
                        if($prestamo->codigo_prestamo == $abonado->codigo_prestamo){
                            $encontrado=true;
                        }
                    }
                    if($encontrado ==true){
                        $datos[]=$this->getArray($prestamo,1);
                        $encontrado=false;
                    }
                    else if($encontrado == false){
                        $datos[]=$this->getArray($prestamo,0);
                    }
                }

            }else if(is_object($prestamo_abonado)){
                foreach ($prestamo_activo as $prestamo) {
                    if($prestamo->codigo_prestamo == $prestamo_abonado->codigo_prestamo){
                        $encontrado=true;
                    }
                    if($encontrado ==true){
                        $datos[]=$this->getArray($prestamo,1);
                        $encontrado=false;
                    }
                    else if($encontrado == false){
                        $datos[]=$this->getArray($prestamo,0);
                    }
                }
            }else{
                foreach ($prestamo_activo as $prestamo) {
                    $datos[]=$this->getArray($prestamo,0);
                }
            }
        }else if(is_object($prestamo_activo)){
            if(empty($prestamo_abonado)){
                $datos[]=$this->getArray($prestamo_activo,0);
            }else{
                if($prestamo_activo->codigo_prestamo == $prestamo_abonado->codigo_prestamo){
                    $encontrado=true;
                }
                if($encontrado ==true){
                    $datos[]=$this->getArray($prestamo_activo,1);
                    $encontrado=false;
                }
                else if($encontrado == false){
                    $datos[]=$this->getArray($prestamo_activo,0);
                }
            }
        }else{

        }

        return $datos;
    }
    public function getClienteExistenteFiador($cedula){
        $query="select id_fiador from fiador where cedula='$cedula'";
    
         $resultSet=$this->ejecutarSql($query);
        
        return is_object($resultSet) ? [$resultSet]:$resultSet;
    }
    public function getANalizarClienteParaCapital($id_cliente){
		$query="SELECT s.id_sucursal,
	   concat(c.nombre,' ',c.apellido) as gestor,
       concat (cl.nombre,' ',cl.apellido) as cliente,
       cl.cedula,
       cl.telefono,
	    cl.tipo_negocio,
       cl.direccion,
       p.id_prestamo,
       p.codigo_prestamo,
       p.estado,
       p.fecha_desembolso,
       p.fecha_vencimiento,
       p.capital,
       p.deuda_total,
       p.cuota,
       pl.numero_cuotas,
       pl.equivalen_mes,
       i.porcentaje,
       pa1.camax,
       pa1.camin,
       pa1.promCa,
        if(pa2.fecha_pago is null,0,if(p.fecha_vencimiento >= pa2.fecha_pago ,0,DATEDIFF(pa2.fecha_pago,p.fecha_vencimiento))) dias_vencido,
       if(pa2.fecha_pago is null,'No',if(p.fecha_vencimiento >= pa2.fecha_pago, 'No','Si')) as tipo_cancelado,
        if(nota1.tipo_nota is null,if(nota2.tipo_nota is null,if(nota3.tipo_nota is null,'Ninguno',if(nota2.tipo_nota =1,'Dispensa de Interes','Reestructuracion')),if(nota2.tipo_nota =1,'Dispensa de Interes','Reestructuracion')),if(nota2.tipo_nota =1,'Dispensa de Interes','Reestructuracion')) as tipo_nota,
        if(nota1.tipo_nota is null,if(nota2.tipo_nota is null,if(nota3.tipo_nota is null,'Ninguno',nota3.codigo_nota),nota2.codigo_nota),nota1.codigo_nota) as codigo_nota,
      pa1.numeros_atrasos
       from sucursal s 
		INNER JOIN colaboradores c on s.id_sucursal=c.id_sucursal
        INNER JOIN clientes cl on c.id_colaborador=cl.id_colaborador
        INNER JOIN prestamos p on cl.id_cliente=p.id_cliente
        INNER JOIN plazos pl on p.id_plazo=pl.id_plazo
        INNER JOIN intereses i on p.id_interes=i.id_interes
        LEFT JOIN (
    SELECT pa.id_prestamo,max(pa.cuotas_atrasadas)as camax, min(pa.cuotas_atrasadas) as camin,round((sum(pa.cuotas_atrasadas)/count(pa.cuotas_atrasadas)),2) as promCa,SUM(if(pa.cuotas_atrasadas > 0 , 1,0)) as numeros_atrasos from pagos_new pa where pa.estado='Pagado'  GROUP by pa.id_prestamo
        ) pa1 on p.id_prestamo=pa1.id_prestamo
    left JOIN (
        SELECT pa.id_prestamo,pa.fecha_pago from pagos_new pa where pa.estado='Pagado' and pa.saldo_pendiente=0 			 GROUP by pa.id_prestamo
        
     ) pa2 on p.id_prestamo=pa2.id_prestamo
     LEFT JOIN(
     	SELECT pr.id_prestamo,n.id_nota,n.codigo_nota,n.tipo_nota from prestamos pr INNER JOIN nota_credito n where n.estado='Activo'  GROUP by n.id_nota     
         
      ) nota1 on p.id_nota=nota1.id_nota
     LEFT JOIN(
    SELECT t.codigo_nota,t.id_nota,t.tipo_nota,pr.id_prestamo from nota_credito t INNER JOIN prestamos pr on t.id_prestamo=pr.id_prestamo where t.estado='activo' GROUP by t.id_nota   
 ) nota2 on p.id_nota=nota2.id_nota
  LEFT JOIN(
 SELECT nota.codigo_nota,nota.id_nota,nota.tipo_nota,pt.id_prestamo from prestamos pt INNER JOIN  rnotacombinada rnotacom on pt.id_prestamo=rnotacom.id_prestamo INNER JOIN nota_credito nota on rnotacom.id_nota=nota.id_nota where nota.estado='activo' GROUP by nota.id_nota   

 ) nota3 on p.id_nota=nota3.id_nota
where cl.id_cliente=$id_cliente and p.estado in('Cancelado','Aceptado') order by p.id_prestamo desc";
//print_r($query);
        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;
	
	}
    public function getClienteMoraVencidaPorColaborador($id_colaborador,$fecha){
        $query="SELECT DISTINCT COUNT(cl.id_cliente) as nclientemoravencida FROM sucursal s 
INNER JOIN colaboradores cs on s.id_sucursal=cs.id_sucursal
INNER JOIN clientes cl on cs.id_colaborador=cl.id_colaborador
INNER JOIN prestamos p on cl.id_cliente=p.id_cliente

where p.mora > 0 and p.fecha_vencimiento >'$fecha' and cs.id_colaborador=$id_colaborador";
        $result = $this->ejecutarSql($query);
        //print_r($query);die();
        return is_object($result)?[$result]:$result;
    }

    public function  getClienteNotFirtsPayment($fecha_primera_cuota,$id_colaborador){
        $query = "SELECT * 
                    FROM `prestamos` ps
                    left join pagos pg
                    on ps.id_prestamo = pg.id_prestamo
                    inner join clientes cl 
                    on cl.id_cliente = ps.id_cliente
                    inner join colaboradores cs
                    on cs.id_colaborador = cl.id_colaborador
                    
                    where ps.primera_cuota = '$fecha_primera_cuota' and pg.id_prestamo is null and cs.id_colaborador = '$id_colaborador'";

        $result = $this->ejecutarSql($query);
        return is_object($result)?[$result]:$result;
    }
}
?>
