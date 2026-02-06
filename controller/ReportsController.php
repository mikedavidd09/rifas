<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
class ReportsController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    public function GetDsteRange(){
        $fecha_bengin    =isset($_POST['fecha_bengin'])? date('Y-m-d', strtotime($_POST['fecha_bengin'])) : "";
        $fecha_end       =isset($_POST['fecha_end'])? date('Y-m-d', strtotime($_POST['fecha_end'])): "";

        $date_range = ["bengin"=>$fecha_bengin,"end"=>$fecha_end];

        return $date_range;
    }

    public function viewReports(){
        $login = $_SESSION["Login_View"];
		$obj = new ReportsModel($this->adapter);
		$module = $obj->getModule($login->id_user);
		if(empty($module)){
			$this->view("ver_reportes",array("data"=>''));
		}else{
			//$child = $obj->getModuleChild($module->child);
			//print_r($module);
			$this->view("ver_reportes_permisos",array("module"=>$module));
		}
    }
    public function viewReportsSupervisor(){
        $login = $_SESSION["Login_View"];
        $obj = new ReportsModel($this->adapter);
        $module = $obj->getModule($login->id_user);
        if(empty($module)){
            $this->view("ver_reportes_supervisor",array("data"=>''));
        }else{
            //$child = $obj->getModuleChild($module->child);
            print_r($module);
            $this->view("ver_reportes_permisos",array("module"=>$module));
        }
    }
    public function getChild(){
		$child = $_GET["child"];
		$obj = new ReportsModel($this->adapter);
		$child = $obj->getModuleChild($child);
			echo json_encode($child);
	}

    public function generarReports(){
       // echo "LLego aqui";exit;
          $groud_by     = $_POST["groud_by"];
          $filter_by    = $_POST["filter_by"];
          $id_sucursal  = $_POST["id_sucursal"];
        $id_sucursal  = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = '$id_sucursal'";
          $gestor    = "";
          $date_range = $this->GetDsteRange();
          $fields_ = [];

          $obj = new ReportsModel($this->adapter);
           //echo "paso la asignacion de merio de reportes al objeto";exit;
          if($filter_by == "Moroso"){
              $title="Clientes Morosos Activo Mayor a 25%";
            $result = $obj->generarReportsByClientesMorasMayor25($groud_by,$filter_by,$date_range,$id_sucursal);
              $totales      = ["Capital","Deuda_Total","Saldo_Pendiente","Mora","","Porcent_Mora"];
             $total_montos =["Capital","Deuda_Total","Saldo_Pendiente","Mora","","Porcent_Mora"];
          }

          if($filter_by == "Dia"){
              $title="Clientes Al Dia";
            $result = $obj->generarReportsByClienteDia($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Mora_C$","Porcen_Capital_C$","Porcen_Interes_C$"];
             $total_montos = ["Mora_C$","Porcen_Capital_C$","Porcen_Interes_C$"];
          }
          if($filter_by == "citas_agendada"){
              $title="Listado de Citas";
            $result = $obj->generarReportsByClienteCitasAgendada($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = [];
             $total_montos = [];
          }
            if($filter_by == "Aumento de Capital"){
                $result = $obj->generarReportsAumentoDeCapitalForMonht($groud_by,$filter_by,$date_range,$id_sucursal);
                $totales      = ["monto_aumentado"];
                $total_montos = ["monto_aumentado"];
            }
          if($filter_by == "Moroso_p"){
              $title="Clientes Morosos";
            $result = $obj->generarReportsByPrestamosMoras($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Capital","Deuda_Total","Saldo_Pendiente","Mora","","","Porcent_Mora"];
             $total_montos = ["Capital","Deuda_Total","Saldo_Pendiente","Mora","","","Porcent_Mora"];
          }
			if($filter_by == "Ultimo_abono"){
			    $title="Prestamos con su ultimo Abono";
            $result = $obj->generarReportsByPrestamosUtimaCuota($groud_by,$filter_by,$date_range);
             $totales      = ["capital","saldo_pendiente","Ultimo_abono",""];
             $total_montos = ["capital","saldo_pendiente","Ultimo_abono",""];
          }
		   if($filter_by == "Cartera_Total"){
		        $title="Cartera Total";
            $result = $obj->generarReportsByPrestamosCarteraTotal($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["CarteraTotal","InteresTotal","DeudaTotal","SanidadMonto","PorcentajeSanidad","MoraTotal","PorcentajeMora","PromedioMoraPorcentaje","PromedioSnaidadPorcentaje","CantidadCliente","ClientesSinMora","ClientesConMora","Total_Recaudo","Cartera_Real","Interes_Real","Deuda_Real"];
             $total_montos = ["CarteraTotal","InteresTotal","DeudaTotal","SanidadMonto","PorcentajeSanidad","MoraTotal","PorcentajeMora","PromedioMoraPorcentaje","PromedioSnaidadPorcentaje","CantidadCliente","ClientesSinMora","ClientesConMora","Total_Recaudo","Cartera_Real","Interes_Real","Deuda_Real"];
          }
          if($filter_by == "Metricas_Rete"){
                $title="Cartera Total";
            $result = $obj->generarReportsByMetricasRetencion($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["CarteraTotal","InteresTotal","DeudaTotal","SanidadMonto","PorcentajeSanidad","MoraTotal","PorcentajeMora","PromedioMoraPorcentaje","PromedioSnaidadPorcentaje","CantidadCliente","ClientesSinMora","ClientesConMora","Total_Recaudo","Cartera_Real","Interes_Real","Deuda_Real"];
             $total_montos = ["CarteraTotal","InteresTotal","DeudaTotal","SanidadMonto","PorcentajeSanidad","MoraTotal","PorcentajeMora","PromedioMoraPorcentaje","PromedioSnaidadPorcentaje","CantidadCliente","ClientesSinMora","ClientesConMora","Total_Recaudo","Cartera_Real","Interes_Real","Deuda_Real"];
          }
          if($filter_by == "Metricas_general"){
                $title="Cartera Total";
            $result = $obj->generarReportsByMetricasGeneral($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["CarteraTotal","InteresTotal","DeudaTotal","SanidadMonto","PorcentajeSanidad","MoraTotal","PorcentajeMora","PromedioMoraPorcentaje","PromedioSnaidadPorcentaje","CantidadCliente","ClientesSinMora","ClientesConMora","Total_Recaudo","Cartera_Real","Interes_Real","Deuda_Real"];
             $total_montos = ["CarteraTotal","InteresTotal","DeudaTotal","SanidadMonto","PorcentajeSanidad","MoraTotal","PorcentajeMora","PromedioMoraPorcentaje","PromedioSnaidadPorcentaje","CantidadCliente","ClientesSinMora","ClientesConMora","Total_Recaudo","Cartera_Real","Interes_Real","Deuda_Real"];
          }
          if($filter_by == "Saldos_Cartera"){
                $title="Saldo de Cartera por Analista";
            $result = $obj->generarReportsByPrestamosSaldoCartera($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Nº_Cliente","Nº_Cliente_Aldia","Porc_Sanidad_Saldo_Cartera","Nº_Cliente_Enmora","Porc_Cliente_Enmora","Monto_Saldo_cartera_Mora","Porc_Saldo_Cartera_Enmora","Nº_Cliente_Vencido","Monto_Vencido","Porc_Monto_vencido","Saldo_Cartera"];
             $total_montos = ["Nº_Cliente","Nº_Cliente_Aldia","Porc_Sanidad_Saldo_Cartera","Nº_Cliente_Enmora","Porc_Cliente_Enmora","Monto_Saldo_cartera_Mora","Porc_Saldo_Cartera_Enmora","Nº_Cliente_Vencido","Monto_Vencido","Porc_Monto_vencido","Saldo_Cartera"];
         }
            if($filter_by == "Cartera_General"){
		        $title="Cartera General";
            $result = $obj->generarReportsByPrestamosCarteraGeneral($groud_by,$filter_by,$date_range,$id_sucursal);
                $totales      = ["Nº_Cliente","Nº_Cliente_Aldia","Porc_Sanidad_Saldo_Cartera","Nº_Cliente_Enmora","Porc_Cliente_Enmora","Monto_Saldo_cartera_Mora","Porc_Saldo_Cartera_Enmora","Nº_Cliente_Vencido","Porc_Cliente_Vencido","Monto_Vencido","Porc_Monto_vencido","Saldo_Cartera"];
                $total_montos = ["Nº_Cliente","Nº_Cliente_Aldia","Porc_Sanidad_Saldo_Cartera","N_Cliente_Enmora","Porc_Cliente_Enmora","Monto_Saldo_cartera_Mora","Porc_Saldo_Cartera_Enmora","N_Cliente_Vencido","Porc_Cliente_Vencido","Monto_Vencido","Porc_Monto_vencido","Saldo_Cartera"];
         }
          if($filter_by == "Dia_p"){
              $title="Clientes AL Dia";
            $result = $obj->generarReportsByPrestamosDia($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Mora_C$","por_capital_C$","por_interes_C$"];
             $total_montos = ["Mora_C$","por_capital_C$","por_interes_C$"];
          }
          if($filter_by == "Cliente_menor_10"){
              $title="Clientes AL Dia";
            $result = $obj->generarReportsByClienesMenor10Porcent($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Mora_C$","por_capital_C$","por_interes_C$"];
             $total_montos = ["Mora_C$","por_capital_C$","por_interes_C$"];
          }
          if($filter_by == "Cliente_menorigual_20"){
              $title="Clientes AL Dia";
            $result = $obj->generarReportsByClienesMenorIgual20Porcent($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["capital","cuota","deuda_total","saldo_pendiente","","",""];
             $total_montos = ["capital","cuota","deuda_total","saldo_pendiente","","",""];
          }
          if($filter_by == "Reporte_Contabilidad"){
              $title="Clientes AL Dia";
            $result = $obj->generarReportsByClienesContabilidad($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Mora_C$","por_capital_C$","por_interes_C$"];
             $total_montos = ["Mora_C$","por_capital_C$","por_interes_C$"];
          }
           if($filter_by == "Prestamo_PorDia"){
               $title="Prestamos Por dias";
                $id_sucursal  = ($_POST['id_sucursal']=="All") ? "" :" s.id_sucursal =". $_POST['id_sucursal'];
            $result = $obj->generarReportsByPrestamosPorDiaSemana($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Diario_1","Semanal_1","Total_1","Diario_2","Semanal_2","Total_2","Diario_3","Semanal_3","Total_3","Diario_4","Semanal_4","Total_4","Diario_5","Semanal_5","Total_5"];
             $total_montos = ["Diario_1","Semanal_1","Total_1","Diario_2","Semanal_2","Total_2","Diario_3","Semanal_3","Total_3","Diario_4","Semanal_4","Total_4","Diario_5","Semanal_5","Total_5"];
          }
        
          if($filter_by == "Recaudado"){
            date_default_timezone_set('America/Managua');
               $title="Recaudo";
               $fecha = $date_range["bengin"];
               	$weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
         
            $result = $obj->generarReportsByEmpleadoRecaudado($groud_by,$filter_by,$date_range,$id_sucursal,$weekday[date("w")]);
             $totales      = ["Meta","Recaudo","cumplimiento","por_capital","por_interes"];
             $total_montos = ["Meta","Recaudo","cumplimiento","por_capital","por_interes"];
          }
          if($filter_by == "Recaudado Saneados"){
            date_default_timezone_set('America/Managua');
               $title="Recaudo";
               $fecha = $date_range["bengin"];
                $weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
         
            $result = $obj->generarReportsByEmpleadoRecaudadoSaneados($groud_by,$filter_by,$date_range,$id_sucursal,$weekday[date("w")]);
             $totales      = ["Meta","Recaudo","cumplimiento","por_capital","por_interes"];
             $total_montos = ["Meta","Recaudo","cumplimiento","por_capital","por_interes"];
          }
          if($filter_by == "Recaudado_sup"){
            date_default_timezone_set('America/Managua');
               $title="Recaudo Por Colaborador";
               $fecha = $date_range["bengin"];
                $weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
         
            $result = $obj->generarReportsByEmpleadoRecaudadoSup($groud_by,$filter_by,$date_range,$id_sucursal,$weekday[date("w")]);
             $totales      = ["Meta","Recaudo","cumplimiento"];
             $total_montos = ["Meta","Recaudo","cumplimiento"];
          }
          //Primera_cuota Recaudado_sup
          if($filter_by == "Primera_cuota"){
            date_default_timezone_set('America/Managua');
               $title="Primera_Cuota";
         
            $result = $obj->generarReportsByPrimeraCuota($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = [""];
             $total_montos = [""];
          }
        if($filter_by == "Recaudado Por Semanas"){
               $title="Recaudo";
               $fecha = $date_range["bengin"];
               $primersabado;
                 $sabados = array();
			for($i=1;$i<=20;$i++){
				$dia = date ("w",strtotime($fecha));
				
				if($dia == 6){
					$primersabado = $fecha;
					$sabados[]= $primersabado;
					break;
				}
				$fecha = strtotime ('+1 day',strtotime( $fecha));
				$fecha = date ( 'Y-m-d' , $fecha );
			}
			$sabado=$primersabado;
			for($i=1;$i<=6;$i++){
			    $sabado = strtotime ('+7 day',strtotime( $sabado));
			    	$sabado = date ( 'Y-m-d' , $sabado );
			    $mesUnSado = date ("m",strtotime($primersabado));
			    if($mesUnSado == date ("m",strtotime($sabado))){
			        	$sabados[]= $sabado;
			        
			    }
			}
			$sql="SELECT c.id_colaborador,c.nombre,c.apellido, SUM(pn.monto) as monto FROM  sucursal s 
                inner join colaboradores c on s.id_sucursal=c.id_sucursal
                inner join clientes cl on c.id_colaborador=cl.id_colaborador
                inner join prestamos p on cl.id_cliente=p.id_cliente
                inner join pagos_new pn on p.id_prestamo=pn.id_prestamo";
			$rangoFechas=array();
			$count = count($sabados);
			for($i=0;$i<$count;$i++){
			    $lunes= date ( 'Y-m-d' , strtotime ('-5 day',strtotime( $sabados[$i])));
			    $j=$i+1;
			    $join = ($j == 1) ? "s$j inner JOIN":"s$j on s$i.id_colaborador=s$j.id_colaborador inner join";
			    $rangoFechas["semana".$j]= "($sql between '".$lunes."' and '".$sabados[$i]."'  GROUP by c.id_colaborador)  $join";
			}
			    /*echo"<pre>";
				print_r($rangoFechas);
				echo"</pre>";*/
				
			$result = $obj->generarReportsByEmpleadoRecaudadoPorSemanas($groud_by,$filter_by,$date_range,$id_sucursal,$rangoFechas);
             $totales      = [];
             $total_montos = [];
          }
          if($filter_by == "Recaudado Clientes"){
               $title="Recaudo Por Clientes";
            $gestor = isset($_POST["id_colaborador"])? " and cs.id_colaborador =".$_POST["id_colaborador"]:" ";
            $result = $obj->generarReportsByClientesRecaudado($groud_by,$filter_by,$date_range,$id_sucursal,$gestor);
             $totales      = ["Recaudado_C$","Porcen_Capital_C$","Porcen_Interes_C$"];
             $total_montos = ["Recaudado_C$","Porcen_Capital_C$","Porcen_Interes_C$"];
          }

          if($filter_by == "Prestamos Activos"){
               $title="Prestamos Activo";
            $gestor = isset($_POST["id_colaborador"])? " and cs.id_colaborador =".$_POST["id_colaborador"]:" ";
            $result = $obj->generarReportsByClientesActivos($groud_by,$filter_by,$date_range,$id_sucursal,$gestor);
             $totales      = ["Capital","Deuda","Saldo_C$","Cuota","Mora","","","","","","M_ultima_cuota","","","","","","",""];
             $total_montos = ["Capital","Deuda","Saldo_C$","Cuota","Mora","","","","","","M_ultima_cuota","","","","","","",""];
          }

          if($filter_by == "Moras acumulada"){
              $title="Moras Acumuldas ";
            $result = $obj->generarReportsByEmpleadoMoras($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Mora_C$","Porcen_Capital_C$","Porcen_Interes_C$"];
             $total_montos = ["Mora_C$","por_capital_C$","por_interes_C$"];
          } //
         if($filter_by == "cartera_saneada"){
              $title="Cartera Saneadas y en riesgo de perdida ";
            $result = $obj->generarReportsByCarteraSaneada($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Capital_origen","Interes_origen","Capital_a_la_fecha","Interes_a_la_fecha","Total_abonado_saneado","Capital_abonado_saneado","Interes_abonado_saneado",""];
             $total_montos = ["Capital_origen","Interes_origen","Capital_a_la_fecha","Interes_a_la_fecha","Total_abonado_saneado","Capital_abonado_saneado","Interes_abonado_saneado",""];
          }//
           if($filter_by == "detalle_saneada"){
              $title="Cartera Saneadas y en riesgo de perdida ";
            $result = $obj->generarReportsByDetallePrestamoSaneado($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Capital_origen","Interes_origen","Capital_a_la_fecha","Interes_a_la_fecha","Total_abonado_saneado","Capital_abonado_saneado","Interes_abonado_saneado",""];
             $total_montos = ["Capital_origen","Interes_origen","Capital_a_la_fecha","Interes_a_la_fecha","Total_abonado_saneado","Capital_abonado_saneado","Interes_abonado_saneado",""];
          }
          if($filter_by == "detalle_saneada_fecha"){
              $title="Detalles de Prestamos saneado ";
            $result = $obj->generarReportsByDetallePrestamoSaneadoFecha($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Capital","Saldo Pendiente","","","cuota"];
             $total_montos = ["capital","saldo_pendiente","","","cuota"];
          }
     	 //echo "LLego a la  funcion Prestamos NoAbonado";exit;
          if($filter_by == "Prestamos NoAbonado"){
              //echo "LLego a la  funcion Prestamos NoAbonado";exit;
               $title="Prestamos Que No fueron Abonados";
             date_default_timezone_set('America/Managua');
            $weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

            $result = $obj->generarClientesNoAbonosCartera($groud_by,$filter_by,$date_range,$id_sucursal,$weekday[date("w",strtotime($date_range["bengin"]))],$weekday[date("w",strtotime($date_range["end"]))]);
             $totales      = [];
             $total_montos = [];
          }
          if($filter_by == "Metas del Dia"){
               $title="Metas del Dia";
            $weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
           // print_r($id_sucursal);
            $result = $obj->Rpts_cumplimiento_metas($groud_by,$filter_by,$date_range,$id_sucursal,$weekday[date("w")]);
             $totales      = [];
             $total_montos = [];
          }
          if($filter_by == "Datos Prestamos"){
               $title="Cartera del Dia";
             date_default_timezone_set('America/Managua');
            $weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

            $result = $obj->generarReportsPrestamosFullField($groud_by,$filter_by,$date_range,$id_sucursal,$weekday[date("w",strtotime($date_range["bengin"]))]);
           // print_r($result);
             $totales      = ["Capital","Deuda","Total_abonado","Saldo_pendiente","Cuotas_faltantes","Cuota","Mora_actual","Cuotas_Atrasadas","","","","","",""];
             $total_montos = ["Capital","Deuda","Total_abonado","Saldo_pendiente","Cuotas_faltantes","Cuota","Mora_actual","Cuotas_Atrasadas","","","","","",""];
          }
          if($filter_by == "Cartera"){
               $title="Cartera del Dia";
               date_default_timezone_set('America/Managua');
            $weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
            $result = $obj->generarReportsCarterarresumida($groud_by,$filter_by,$date_range,$id_sucursal,$weekday[date("w",strtotime($date_range["bengin"]))]);
             $totales      = ["Saldo","Cuota","Mora","","","","CAtrasadas","Cfaltantes","","",""];
             $total_montos = ["Saldo","Cuota","Mora","","","","CAtrasadas","Cfaltantes","","",""];
          }

          if($filter_by == "Fecha Desembolso"){
               $title="Prestamos Desembolsados";
            $result = $obj->generarReportsPrestamosDesembolsos($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Capital","Deuda","Total_abonado","Saldo_pendiente","Cuota","","Mora_actual","","Numero_cuotas","","","Monto_Favor","",""];
             $total_montos = ["Capital","Deuda","Total_abonado","Saldo_pendiente","Cuota","","Mora_actual","","Numero_cuotas","","","Monto_Favor","",""];
          }
          if($filter_by == "Fecha Desembolso Agrupado"){
               $title="Prestamos Por Fecha Desembolso Agrupado";
            $result = $obj->generarReportsPrestamosDesembolsosAgrupado($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = ["Monto_Desembolsado_$","por_capital_C$","por_interes_C$"];
             $total_montos = ["Monto_Desembolsado_$","por_capital_C$","por_interes_C$"];
          }

          if($filter_by == "Fecha Vencimiento"){
              $title="Prestamos Por Fecha Vencimiento";
            $result = $obj->generarReportsPrestamosVencidos($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      =["Capital","Deuda","Total_abonado","Saldo_pendiente","","Cuota","Mora_actual","","",""]; //["Capital","Deuda","Total_abonado","Saldo_pendiente","","Cuota","Mora_actual","","","","","","",""];
             $total_montos = ["Capital","Deuda","Total_abonado","Saldo_pendiente","","Cuota","Mora_actual","","",""];//["Capital","Deuda","Total_abonado","Saldo_pendiente","","Cuota","Mora_actual","","","","","","",""];
          }
          if($filter_by == "Inactivo"){
              $title="Clientes Inactivo";
            $result = $obj->generarReportsClientesInactivo($groud_by,$filter_by,$date_range,$id_sucursal);
             $totales      = [];
             $total_montos = [];
          }

          if($filter_by == "Pagos"){
           $title="Prestamos Por Pagos";
            $result = $obj->generarReportsByClientesRecaudadoByPagos($groud_by,$filter_by,$date_range,$id_sucursal);
            $totales      = ["Recaudado_C$","saldo_pendiente","",""];
            $total_montos = ["Recaudado_C$","saldo_pendiente","",""];
          }

          if($filter_by == "Historial Cliente"){
              $title="Historial Cliente";
            $cliente = isset($_POST["codigo_cliente"])? $_POST["codigo_cliente"]:" ";
            $result = $obj->generarReportsByHistorialCliente($groud_by,$filter_by,$date_range ,$id_sucursal ,$cliente);
            $totales      = ["Recaudado_C$","Saldo_C$","Porcen_Capital_C$","Porcen_Interes_C$"];
            $total_montos = ["Recaudado_C$","Saldo_C$","por_capital_C$","por_interes_C$"];
          }

          if($filter_by == "Reporte_histAfter7pm"){
              $title="Reporte de pagos Aplicados despues de las  7 PM";
            $cliente = isset($_POST["codigo_cliente"])? $_POST["codigo_cliente"]:" ";
            $result = $obj->generarReportsByHistorialPagosAfter7pm($groud_by,$filter_by,$date_range ,$id_sucursal ,$cliente);
            $totales      = [];
            $total_montos = [];
          }


          if($filter_by == "Prestamos por Promotor"){
              $title="Prestamos por Promotor";
            $result = $obj->generarReportsPrestamosPromotor($groud_by,$filter_by,$date_range,$id_sucursal);
            $totales      = [];
            $total_montos = [];
          }

          if($filter_by == "Prestamo mas Alto"){
              $title="Prestamo mas Alto";
            $result = $obj->generarReportsCapitalPromotor($groud_by,$filter_by,$date_range,$id_sucursal);
            $totales      = [];
            $total_montos = [];
          }

          if($filter_by == "Activos"){
             $title="Prestamo Activos";
            $result = $obj->generarReportsByPrestamosActivos($groud_by,$filter_by,$date_range,$id_sucursal);
            $totales      = ["Capital","Deuda_Total","Total_abonado","por_capital_C$","por_interes_C$","Saldo_Pendiente","Cuota","Mora","","",""];
            $total_montos = ["Capital","Deuda_Total","Total_abonado","por_capital_C$","por_interes_C$","Saldo_Pendiente","Cuota","Mora","","",""];
          }

          if($filter_by == "Vencidos"){
              $title="Prestamo Vencidos";
            $result = $obj->generarReportsByPrestamosVencidos($groud_by,$filter_by,$date_range,$id_sucursal);
            $totales      = ["Capital","Deuda_Total","Total_abonado","Saldo_Pendiente","Cuota","","","",""];
            $total_montos = ["Capital","Deuda_Total","Total_abonado","Saldo_Pendiente","Cuota","","","",""];
          }

          if($filter_by == "Cancelados"){
                $title="Prestamo Cancelados";
            $result = $obj->generarReportsByPrestamosCancelados($groud_by,$filter_by,$date_range,$id_sucursal);
            $totales      = ["Capital","Deuda_Total","Total_abonado","por_capital_C$","por_interes_C$","Saldo_Pendiente","Cuota","Mora","","",""];
            $total_montos = ["Capital","Deuda_Total","Total_abonado","por_capital_C$","por_interes_C$","Saldo_Pendiente","Cuota","Mora","","",""];
          }
          if($filter_by == "CanceladosPorFecha"){
            //echo "LLego aqui CanceladosPorFecha";exit;
             $title="Prestamo Cancelados Por Fecha";  
            $result = $obj->cancelacion($groud_by,$filter_by,$date_range,$id_sucursal);
            $totales      = [];
            $total_montos = [];
          }

          if($filter_by == "Recaudado Fechas"){
               $title="Recaudado Fechas";
              date_default_timezone_set('America/Managua');
              $fecha_bengin = $date_range['bengin'];
              $fecha_end = $date_range['end'];
              $fechas= '';
              $totales = [];
              $total_montos = [];
              $counter = 0;
              for($i=$fecha_bengin;$i<=$fecha_end;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
                  $fechas .= ",'$i'";
                  $counter++;
              }
              if($counter>31){
                  echo "<div class=\"alert alert-danger  alert-dismissible fade in\">
                            <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                          <strong>Invalido!</strong> Error no puede seleccionar un rango mayor a los 31 dias.
                        </div>";
                  error_reporting(0);
              } else {
                  $result = $obj->generarReportsByRecaudadoFechas($groud_by,$filter_by,$date_range,$id_sucursal);

              }

          }

        $title = '';
          $fields = $result['field'];
          foreach ($fields as $field) {
              $fields_[] = $field->name;
          }
          //$title        = $filter_by.' por '.$groud_by.' '.$date_range['bengin'].' al '.$date_range['end'];
          $title       .=' '.$date_range['bengin'].' al '.$date_range['end'];
          $header       =  $fields_;
          $fields       =  $fields_;
          //print_r($fields);
          //  $totales      = ["Mora","Interes"];
          // $total_montos = ["monto_mora","interes_mora"];

          $this->view("reports_",array("data"=>$result['result'],"header"=>$header,
                      "fields"=>$fields,"title"=>$title,"totales"=>$totales,"total_montos"=>$total_montos));
    }
}
?>
