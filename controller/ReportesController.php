<?php

class ReportesController extends ControladorBase

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

        $fecha_bengin    =date('Y-m-d', strtotime($_POST['fecha_bengin']));

        $fecha_end       =date('Y-m-d', strtotime($_POST['fecha_end']));



        $date_range = ["bengin"=>$fecha_bengin,"end"=>$fecha_end];



        return $date_range;

    }



    public function GetClientesMora(){

       $id_sucursal= ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

        $date_range = $this->GetDsteRange();

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByClientesMora($date_range['bengin'],$date_range['end'],$id_sucursal);


        $title        = 'Clientes en mora en el periodo '.$date_range['bengin'].' al '.$date_range['end'];

        $header       = ["Cliente","Telefono","Colaborador","Prestamo","Estado","Fecha","Cuota","Mora C$","Interes C$"];

        $fields       = ["cliente","telefono","colaborador","codigo_prestamo","estado","fecha_mora","monto_cuota","monto_mora","interes_mora"];

        $totales      = ["Cuota","Mora","Interes"];

        $total_montos = ["monto_cuota","monto_mora","interes_mora"];


        $this->view("reportes_",array("data"=>$result,"header"=>$header,

                    "fields"=>$fields,"title"=>$title,"totales"=>$totales,

                    "total_montos"=>$total_montos));

    }



    public function GetClientesDesembolsoFecha(){

        $id_sucursal =  ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

        $date_range = $this->GetDsteRange();

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByClientesFechaDesem($date_range['bengin'],$date_range['end'],$id_sucursal );

        $title        = 'Clientes con fecha desembolso desde '.$date_range['bengin'].' al '.$date_range['end'];

        $header       = ["Cliente","Colaborador","Codigo Prestamo","Fecha desembolso","Vencimiento","Modalidad ","Estado","Tipo","Capital","Deuda Total"];

        $fields       = ["cliente","colaborador","codigo_prestamo","fecha_desembolso","fecha_vencimiento","modalidad","estado","tipo_prestamo","capital","deuda_total"];

        $totales      = ["Capital","Deuda Total"];

        $total_montos = ["capital","deuda_total"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }



    public function GetClientesMoraActual(){



        $id_col     = isset($_GET['colaborador'])?$_GET['colaborador']:'' ;

        $nombre_col = isset($_GET['nombre_col'])?' del colaborador: '. $_GET['nombre_col']:'';

if(isset($_POST['id_sucursal']))
    $concat_sql  = ($_POST['id_sucursal']=="All") ? "" : " and s.id_sucursal = ".$_POST['id_sucursal'];
else
        $concat_sql = " and css.id_colaborador=".$id_col;



        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByClientesMoraActual($concat_sql);



        $title        = 'Clientes actualmente al dia'.$nombre_col;;

        $header       = ["Cliente","Telefono","Colaborador","Prestamo","Estado","Tipo","Modalidad","Fecha Desembolso","Fecha Vencimiento","Tipo Mora","Cuota","MoraC$","Cuotas Atrasadas","Capital C$","Deuda Total C$","Saldo Pendiente C$"];

        $fields       = ["cliente","telefono","colaborador","codigo_prestamo","estado","tipo_prestamo","modalidad","fecha_desembolso","fecha_vencimiento","tipomora","cuota","mora","nc_atrasadas","capital","deuda_total", "saldo_pendiente"];

        $totales      = ["CuotaC$","MoraC$","Cuotas Atrasadas","Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $total_montos = ["cuota","mora" ,"nc_atrasadas","capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }

        public function GetAnalistaMoraXrango(){

        $id_col     = isset($_GET['colaborador'])?$_GET['colaborador']:'' ;

        $nombre_col = isset($_GET['nombre_col'])?' del colaborador: '. $_GET['nombre_col']:'';

    if(isset($_POST['id_sucursal']))
        $concat_sql  = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];
    else
        $concat_sql = "and c.id_colaborador=".$id_col;

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByAnalistaMoraXrango($concat_sql);

        $title        = 'Rango de mora Por Analista activos'.$nombre_col;;

        $header       = ["Analista","Temprana","Monto C$","Avanzada","Monto C$","Riesgo","Monto C$","Riesgo Perdido","Monto C$"];

        $fields       = ["analista","temprana","mora1","avanzada","mora2","riesgo","mora3","perdido","mora4"];

        $totales      = ["Temprana","Monto C$","Avanzada","Monto C$","Riesgo","Monto C$","Riesgo Perdido","Monto C$"];

        $total_montos = ["temprana","mora1","avanzada","mora2","riesgo","mora3","perdido","mora4"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

        "fields"=>$fields,"title"=>$title,"totales"=>$totales,

        "total_montos"=>$total_montos));

    }








    public function GetClientesAldiaActual(){

        $id_col     = isset($_GET['colaborador'])?$_GET['colaborador']:'' ;

        $nombre_col = isset($_GET['nombre_col'])?' del colaborador: '. $_GET['nombre_col']:'';


        if(isset($_POST['id_sucursal']))
            $concat_sql  = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];
        else
            $concat_sql = "and c.id_colaborador=".$id_col;


        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByClientesAlDiaActual($concat_sql);



        $title        = 'Clientes actualmente al dia'.$nombre_col;

        $header       = ["Codigo","Nombre","Apellido","Telefono","Colaborador","Prestamo","Estado","DispensaInteres","MoraC$","Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $fields       = ["codigo_cliente","nombre","apellido","telefono","colaborador","codigo_prestamo","estado","descuento_interes","mora" ,"capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $totales      = ["MoraC$","Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $total_montos = ["mora" ,"capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }



    public function GetClientesMontoFavorActual(){



        $id_col     = isset($_GET['colaborador'])?$_GET['colaborador']:'' ;

        $nombre_col = isset($_GET['nombre_col'])?'del colaborador: '. $_GET['nombre_col']:'';

        if(isset($_POST['id_sucursal']))
            $concat_sql  = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];
        else
            $concat_sql = "and c.id_colaborador=".$id_col;

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByClientesMontoFavor($concat_sql);



        $title        = 'Clientes actualmente con monto a favor'.$nombre_col;;

        $header       = ["Codigo","Nombre","Apellido","Telefono","Colaborador","Prestamo","DispensaInteres","Monto Favor C$","MoraC$","Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $fields       = ["codigo_cliente","nombre","apellido","telefono","colaborador","codigo_prestamo","descuento_interes","monto_favor","mora" ,"capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $totales      = ["Monto Favor C$","MoraC$","Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $total_montos = ["monto_favor","mora" ,"capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }



    public function GetClientesTasaInterez(){



        $id_col     = isset($_GET['colaborador'])?$_GET['colaborador']:'' ;

        $nombre_col = isset($_GET['nombre_col'])?'del colaborador: '. $_GET['nombre_col']:'';

        if(isset($_POST['id_sucursal']))
            $concat_sql  = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];
        else
            $concat_sql = "and c.id_colaborador=".$id_col;

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByClientesTasaInteres($concat_sql);



        $title        = 'Clientes ordenados por interez:'.$nombre_col;

        $header       = ["Codigo","Nombre","Apellido","Colaborador","Telefono","Prestamo","Modalidad","Porcentaje","Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $fields       = ["codigo_cliente","nombre","apellido","colaborador","telefono","codigo_prestamo","modalidad","porcentaje" ,"capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $totales      = ["Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $total_montos = ["capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }



	    public function GetClientesTasaInterezBySucursal(){



        $id_sucursal= $_POST['id_sucursal'];



        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByClientesTasaInteresBySucursal($id_sucursal);



        $title        = 'Clientes ordenados por interez:';

        $header       = ["Codigo","Nombre","Apellido","Telefono","Colaborador","Prestamo","Modalidad","Porcentaje","Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $fields       = ["codigo_cliente","nombre","apellido","telefono","colaborador","codigo_prestamo","modalidad","porcentaje" ,"capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $totales      = ["Capital C$","Deuda Total C$","Total Abonado C$","Saldo Pendiente C$"];

        $total_montos = ["capital","deuda_total", "total_abonado", "saldo_pendiente"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }



    public function GetClientesFechaVencimiento(){

		$id_sucursal = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

        $date_range = $this->GetDsteRange();

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByClientesFechavencim($date_range['bengin'],$date_range['end'],$id_sucursal);

        //print_r($result);

        $title        = 'Clientes con fecha de vencimiento desde '.$date_range['bengin'].' al '.$date_range['end'];

        $header       = ["Cliente","Telefono","Colaborador","Fecha Vencimiento","Prestamo","Estado","Descuento_interes","Deuda","Abonado","Pendiente","Mora"];

        $fields       = ["cliente","telefono","colaborador","fecha_vencimiento","codigo_prestamo","estado","descuento_interes","deuda_total","Tabonado","saldo_pendiente","mora"];

        $totales      = ["Deuda","Abonado","Pendiente","Mora"];

        $total_montos = ["deuda_total","Tabonado","saldo_pendiente","mora"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }



    public function GetClientesAlDia(){

        $id_sucursal = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

        $date_range = $this->GetDsteRange();

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByAldia($date_range['bengin'],$date_range['end'],$id_sucursal);



        $title        = 'Clientes al dia desde'.$date_range['bengin'].' al '.$date_range['end'];

        $header       = ["Cliente","Telefono","Colaborador","Fecha","Cuota","mora"];

        $fields       = ["cliente","telefono","colaborador","fecha_cuota","monto_cuota","monto_mora"];

        $totales      = ["Cuota","mora"];

        $total_montos = ["monto_cuota","monto_mora"];



        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }



    public function GetPagos(){

        $id_sucursal = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

        $date_range = $this->GetDsteRange();

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->ReportsByPagos($date_range['bengin'],$date_range['end'],$id_sucursal);



        $title        = 'Clientes en mora en el periodo '.$date_range['bengin'].' al '.$date_range['end'];

        $header       = ["Codigo","Cliente","Colaborador","Prestamo","Fecha","Hora","Monto Cuota","Saldo Exedente C$"];

        $fields       = ["codigo_cliente","cliente","colaborador","codigo_prestamo","fecha_cuota","hora_cuota","monto_cuota","saldo_excedente"];

        $totales      = ["Monto cuota","Saldo Excedente"];

        $total_montos = ["monto_cuota","saldo_excedente"];



        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }

  public function Get_CanceladosByFecha(){

        $id_sucursal = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

        $date_range = $this->GetDsteRange();

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->cancelacion($date_range['bengin'],$date_range['end'],$id_sucursal,$id_sucursal);



        $title        = 'Clientes Cancelados en el periodo del '.$date_range['bengin'].' al '.$date_range['end'];

        $header       = ["Cliente","Analista","Prestamo","Estado","Desembolso","Vencimiento","Cancelacion","Dias Vencidos","Hora Cancelacion"];

        $fields       = ["cliente","analista","codigo_prestamo","estado","fecha_desembolso","fecha_vencimiento","fecha_cancelacion","diasvencidos","hora_cuota"];

        $totales      = [""];

        $total_montos = [""];



        $this->view("reportes_",array("data"=>$result,"header"=>$header,

            "fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

    }

		public function GetColaboradoresMorosos(){

		$id_sucursal = $_POST["id_sucursal"];

        $obj = new ReportesModel("Colaboradores",$this->adapter);

        $result = $obj->ReportsByColaboradoresMoraAcum($id_sucursal);

		$title  = 'Moras Acumuladas por colaboradores';

		$header = ["Codigo","Nombre","Apellido","cedula","mora"];

		$fields = ["codigo_colaborador","nombre","apellido","cedula","moras"];

		$totales   	  = ["Total Mora"];

		$total_montos = ["moras"];

		$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));


	 }



    public function GetColaboradorespagos(){

	$id_sucursal = $_POST["id_sucursal"];

	 $date_range = $this->GetDsteRange();

        $obj = new ReportesModel("Colaboradores",$this->adapter);

        $result = $obj->ReportsByColaboradorespagos($date_range['bengin'],$date_range['end'],$id_sucursal);

		$title  = 'Pagos por Colaboradores'.$date_range['bengin'].' al '.$date_range['end'];

		$header = ["Nombre","Apellido","Cedula","Codigo Prestamo","Estado","Nombre Cliente","Apellido Cliente","Fecha Cuota","Hora Cuota","Saldo Pendiente","Monto Cuota","% al Capital","% al Interes"];

		$fields = ["nombre","apellido","cedula","codigo_prestamo","estado","nombrecliente","apellidocliente","fecha_cuota","hora_cuota","saldo_pendiente","monto_cuota","porcentaje_capital","porcentaje_interes"];

		$totales   	  = ["Totales Cuota","Total al Capital","Total al Interes"];

        $total_montos = ["monto_cuota","porcentaje_capital","porcentaje_interes"];



		$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }

   public function GetColaboradoresArqueo(){

  $id_sucursal = $_POST["id_sucursal"];

  $id_sucursal = $_POST["id_sucursal"]=="All"?'':"and s.id_sucursal = '$id_sucursal'";

  $fecha_time_begin = date('Y-m-d H:i:s', strtotime($_POST["fecha_bengin"].' 00:00:00'));

  $fecha_time_end = date('Y-m-d H:i:s', strtotime($_POST["fecha_end"].' 23:59:59'));


   $obj = new ReportesModel("Colaboradores",$this->adapter);

   $result = $obj->ReportsByColaboradoresArqueo($fecha_time_begin,$fecha_time_end,$id_sucursal);

   $title  = 'Pagos por Colaboradores'.$_POST["fecha_bengin"].' al '.$_POST["fecha_end"];

   $header = ["Colaborador","Minuta/Cheque","Monto","C$1000","C$500","C$200","C$100","C$50","C$20","C$10","C$5","C$1","$100","$50","$20","$10","$5","$1","Efectivo","Desembolso","Recaudado","Diferencia"];

   $fields = ["colaborador","minuta_cheque","monto_cheque","mil_c","quinientos_c","doscientos_c","cien_c","cincuenta_c","veinte_c","diez_c","cinco_c","uno_c","cien_usd","cincuenta_usd","veinte_usd","diez_usd","cinco_usd","uno_usd","total_efectivo","desembolso","total_recaudo","diferencia"];
   $totales   	  = ["Monto","C$1000","C$500","C$200","C$100","C$50","C$20","C$10","C$5","C$1","$100","$50","$20","$10","$5","$1","Efectivo","desembloso","Recaudado","Diferencia"];

   $total_montos = ["monto_cheque","mil_c","quinientos_c","doscientos_c","cien_c","cincuenta_c","veinte_c","diez_c","cinco_c","uno_c","cien_usd","cincuenta_usd","veinte_usd","diez_usd","cinco_usd","uno_usd","total_efectivo","desembolso","total_recaudo","diferencia"];

   $this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,"total_montos"=>$total_montos));

  }

  public function GetColaboradoresArqueoGroup(){

 $id_sucursal = $_POST["id_sucursal"];

 $id_sucursal = $_POST["id_sucursal"]=="All"?'':"and s.id_sucursal = '$id_sucursal'";

 $fecha_time_begin = date('Y-m-d H:i:s', strtotime($_POST["fecha_bengin"].' 00:00:00'));

 $fecha_time_end = date('Y-m-d H:i:s', strtotime($_POST["fecha_end"].' 23:59:59'));


  $obj = new ReportesModel("Colaboradores",$this->adapter);

  $result = $obj->ReportsByColaboradoresArqueoGroup($fecha_time_begin,$fecha_time_end,$id_sucursal);

  $title  = 'Arqueo por Colaboradores '.$_POST["fecha_bengin"].' al '.$_POST["fecha_end"];

  $header = ["Colaborador","C$1000","C$500","C$200","C$100","C$50","C$20","C$10","C$5","C$1","$100","$50","$20","$10","$5","$1","Efectivo","Recaudado","Diferencia"];

  $fields = ["colaborador","mil_c","quinientos_c","doscientos_c","cien_c","cincuenta_c","veinte_c","diez_c","cinco_c","uno_c","cien_usd","cincuenta_usd","veinte_usd","diez_usd","cinco_usd","uno_usd","total_efectivo","total_recaudo","diferencia"];
  $totales   	  = ["C$1000","C$500","C$200","C$100","C$50","C$20","C$10","C$5","C$1","$100","$50","$20","$10","$5","$1","Efectivo","Recaudado","Diferencia"];

  $total_montos = ["mil_c","quinientos_c","doscientos_c","cien_c","cincuenta_c","veinte_c","diez_c","cinco_c","uno_c","cien_usd","cincuenta_usd","veinte_usd","diez_usd","cinco_usd","uno_usd","total_efectivo","total_recaudo","diferencia"];

  $this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

          "total_montos"=>$total_montos));

 }



	public function GetPagosxColaborador(){

			$id_sucursal = $_POST["id_sucursal"];



	 $date_range = $this->GetDsteRange();

        $obj = new ReportesModel("Colaboradores",$this->adapter);

        $result = $obj->ReportsByPagosxColaborador($date_range['bengin'],$date_range['end'],$id_sucursal);

		$title  = 'Recaudado por Colaborador'.$date_range['bengin'].' al '.$date_range['end'];

		$header = ["Nombre","Apellido","Recaudado","% al Capital","% al Interes"];

		$fields = ["nombre","apellido","recaudado","pc","pi"];

		$totales   	  = ["Total Recaudado","Total al Capital","Total al Interes"];

        $total_montos = ["recaudado","pc","pi"];

		$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }

    public function GetClientesDesembolsoFechaAgrupado(){

        $date_range = $this->GetDsteRange();

        $id_sucursal = $_POST["id_sucursal"];

        $id_sucursal = $_POST["id_sucursal"]=="All"?'':"and s.id_sucursal = '$id_sucursal'";

        $obj = new ReportesModel("Colaboradores",$this->adapter);

        $result = $obj->ReportsbyTipoPrestamoColaborador($date_range['bengin'],$date_range['end'],$id_sucursal);

        $title  = 'Desembolso por Colaborador'.$date_range['bengin'].' al '.$date_range['end']; 

        $header = ["Sucursal","Analista","Nuevos","Capital Nuevo","Represtamos","Capital Represtamo","Total Prestamos","Capital Total"];

        $fields = ["sucursal","analista","nuevo","cnuevo","represtamo","creprestamo","total2","ctotal"];

        $totales = ["Nuevo","Capital Nuevo","Represtamo","Capital Represtamo","Total Operaciones","Capital Total"];

        $total_montos = ["nuevo","cnuevo","represtamo","creprestamo","total2","ctotal"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));
     }



		 public function GetPrestamos(){

             $id_sucursal = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

        $obj = new ReportesModel("prestamos",$this->adapter);

        $result = $obj->ReportsPrestamos($id_sucursal);

		$title  = 'Reportes Prestamos';

             $header = ["Cliente","Colaborador","Estado","Codigo Prestamo","Capital","Deuda Total","Total Abonado","Saldo Pendiente","%Capital_TA","%Interes_TA","%Capital_SP","%Interes_SP"];

             $fields = ["cliente","colaborador","estado","codigo_prestamo","capital","deuda_total","total_abonado","saldo_pendiente","pc_ta","pi_ta","pc_sp","pi_sp"];

             $totales = ["Capital","Deuda Total","Total Abonado","Saldo Pendiente","%Capital_TA","%Interes_TA","%Capital_SP","%Interes_SP"];

             $total_montos = ["capital","deuda_total","total_abonado","saldo_pendiente","pc_ta","pi_ta","pc_sp","pi_sp"];



		$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }

	 	 public function GetPrestamosFecha(){

		$date_range = $this->GetDsteRange();

		$id_sucursal = $_POST["id_sucursal"];

	    $obj = new ReportesModel("prestamos",$this->adapter);

        $result = $obj->ReportsPrestamosfecha($id_sucursal,$date_range['bengin'],$date_range['end'] );



		$title  = 'Reportes Prestamos';

		$header = ["Cliente","Colaborador","Codigo","localidad","Direccion","Estado","Tipo","dia desembolso","Modalidad","Cuota","Tasa %","Fecha desembloso","Fecha vencimiento","Capital","Deuda Total","Total Abonado","Saldo Pendiente"];

		$fields = ["cliente","colaborador","codigo_prestamo","localidad","direccion","estado","tipo_prestamo","dia_desembolso","modalidad","cuota","porcentaje","fecha_desembolso","fecha_vencimiento","capital","deuda_total","total_abonado","saldo_pendiente"];

    $totales      = ["Capital","Deuda Total","Total Abonado","Saldo Pendiente"];

    $total_montos = ["capital","deuda_total","total_abonado","saldo_pendiente"];

		$this->view("reportesConsolidado_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }


	  public function GetPrestamosByCliente(){

          $id_sucursal = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

        $obj = new ReportesModel("prestamos",$this->adapter);

        $result = $obj->ReportsPrestamosByCliente($id_sucursal);

		$title  = 'Reportes Prestamos';

		$header = ["Cliente","Colaborador","Porcentaje","Nro Prestamos","Capital","Deuda Total","Total Abonado","Saldo Pendiente","%Capital_TA","%Interes_TA","%Capital_SP","%Interes_SP"];

		$fields = ["cliente","colaborador","porcentaje","nprestamos","capital","deuda_total","total_abonado","saldo_pendiente","pc_ta","pi_ta","pc_sp","pi_sp"];

          $totales = ["Nro Prestamos","Capital","Deuda Total","Total Abonado","Saldo Pendiente","%Capital_TA","%Interes_TA","%Capital_SP","%Interes_SP"];

          $total_montos = ["nprestamos","capital","deuda_total","total_abonado","saldo_pendiente","pc_ta","pi_ta","pc_sp","pi_sp"];

		$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }

	 	 	 public function GetPrestamosByClienteFecha(){

		$date_range = $this->GetDsteRange();

		$id_sucursal = $_POST["id_sucursal"];

	    $obj = new ReportesModel("prestamos",$this->adapter);

        $result = $obj->ReportsClienteFecha($id_sucursal,$date_range['bengin'],$date_range['end'] );

		$title  = 'Reportes Prestamos';

		$header = ["Nombre","Apellido","Colaborador","Codigo","Estado","Capital","Tasa %","Deuda Total","Total Abonado","Saldo Pendiente"];

		$fields = ["nombre","apellido","colaborador","codigo_prestamo","estado","capital","porcentaje","deuda_total","total_abonado","saldo_pendiente"];

		$totales      = ["Capital","Deuda Total","Total Abonado","Saldo Pendiente"];

        $total_montos = ["capital","deuda_total","total_abonado","saldo_pendiente"];



		$this->view("reportesConsolidado_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }



	 public function GetPrestamosByColaborador(){

         $id_sucursal = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];

		 $obj = new ReportesModel("prestamos",$this->adapter);

        $result = $obj->ReportsPrestamosByColaborador($id_sucursal);

		$title  = 'Reportes Prestamos';

		$header = ["Colaborador","Codigo Colaborador","porcentaje","Nro Prestamos","Capital","Deuda Total","Total Abonado","Saldo Pendiente","%Capital_TA","%Interes_TA","%Capital_SP","%Interes_SP"];

		$fields = ["colaborador","codigo_colaborador","porcentaje","nprestamos","capital","deuda_total","total_abonado","saldo_pendiente","pc_ta","pi_ta","pc_sp","pi_sp"];

		  $totales = ["Nro Prestamos","Capital","Deuda Total","Total Abonado","Saldo Pendiente","%Capital_TA","%Interes_TA","%Capital_SP","%Interes_SP"];

        $total_montos = ["nprestamos","capital","deuda_total","total_abonado","saldo_pendiente","pc_ta","pi_ta","pc_sp","pi_sp"];


		$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }


	 	 public function GetPrestamosByColaboradorFecha(){

		 $date_range = $this->GetDsteRange();

		 $id_sucursal = $_POST["id_sucursal"];



		  $obj = new ReportesModel("prestamos",$this->adapter);

        $result = $obj->ReportsPrestamosByColaboradorFecha($id_sucursal,$date_range['bengin'],$date_range['end']);

		$title  = 'Reportes Prestamos';

		$header = ["Nombre","Apellido","Codigo Colaborador","porcentaje","Capital","Deuda Total","Total Abonado","Saldo Pendiente"];

		$fields = ["nombre","apellido","codigo_colaborador","porcentaje","capital","deuda_total","total_abonado","saldo_pendiente"];

		  $totales      = ["Capital","Deuda Total","Total Abonado","Saldo Pendiente"];

        $total_montos = ["capital","deuda_total","total_abonado","saldo_pendiente"];

		$this->view("reportesConsolidado_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }


	 public function GetPrestamosByIdColaborador(){

		 $id_colaborador= $_POST["id_colaborador"];

		$obj = new ReportesModel("prestamo",$this->adapter);

		$result = $obj->ReportsPrestamosByIdColaborador($id_colaborador);

		$title  = 'Reportes Prestamos';

		$header = ["Nombre","Apellido","Colaborador","Codigo Prestamo","Estado","porcentaje","Capital","Deuda Total","Total Abonado","Saldo Pendiente"];

		$fields = ["nombre","apellido","colaborador","codigo_prestamo","estado","porcentaje","capital","deuda_total","total_abonado","saldo_pendiente"];

		$totales      = ["Capital","Deuda Total","Total Abonado","Saldo Pendiente"];

        $total_montos = ["capital","deuda_total","total_abonado","saldo_pendiente"];


		$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));

	 }


	 public function GetColaboradoresBySucursal(){

		 $id_sucursal= $_POST["id_sucursal"];

		$obj = new ReportesModel("Colaboradores",$this->adapter);

		$result = $obj->ReportsAllColaboradores($id_sucursal);

		$title  = 'Reportes Prestamos';

		$header = ["Nombre","Apellido","Codigo ","Cargo","Cedula","INNS","Telefono","Fecha ingreso","Direccion"];

		$fields = ["nombre","apellido","codigo_colaborador","cargo","cedula","inss","telefono","fecha_ingreso","direccion"];

		$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title));


	 }


public function GetDatosClientes(){

$id_sucursal = ($_POST["id_sucursal"]=="All") ? " " : "and s.id_sucursal=".$_POST["id_sucursal"];
$obj = new ReportesModel("clientes",$this->adapter);
$result = $obj->RptsDatosClientes($id_sucursal);

$title = 'Reporte Datos Cliente';

$header = ["Clientes","Estado","sexo","Cedula","Telefono","Localidad","Direccion","Negocio","Analista","Telefono"];

$fields= ["cliente","estado","sex1","ced1","tel1","localidad","dir1","tipo_negocio","analista","tel2"];

$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title));

}


public function GetClientesPrimeraCuota(){

$date_range = $this->GetDsteRange();

$id_sucursal = ($_POST["id_sucursal"]=="All") ? " " : "and s.id_sucursal=".$_POST["id_sucursal"];

$obj = new ReportesModel("clientes",$this->adapter);

$result = $obj->RptsPrimerasCuotas($id_sucursal,$date_range['bengin']);

$title = 'Reporte Clientes Primera Cuota'; 

$header = ["Analista","Cliente","Sucursal","Codigo","Fecha Desembolso","Tipo","Modalidad","Pagado"];

$fields= ["analista","cliente","sucursal","codigo_prestamo","fecha_desembolso","tipo_prestamo","modalidad","pagado"];

$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title));

}

public function GetClientesSaldoPendiente(){

$date_range = $this->GetDsteRange();

$id_sucursal = ($_POST["id_sucursal"]=="All") ? " " : "and s.id_sucursal=".$_POST["id_sucursal"];

$obj = new ReportesModel("clientes",$this->adapter);

$result = $obj->RptsSaldoPendiente($id_sucursal);

$title = 'Reporte Clientes Saldo Pendiente < 20%'; 

$header = ["Analista","Cliente","Sucursal","Codigo","Fecha Desembolso","Vence","Tipo","Modalidad","Estado","Dias Vencidos","Capital","Deuda","Pendiente"];

$fields= ["analista","cliente","sucursal","codigo_prestamo","fecha_desembolso","fecha_vencimiento","tipo_prestamo","modalidad","estado","ndiasv","capital","deuda_total","saldo_pendiente"];

$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title));

}

public function GetClientesSaldoPendienteMenores(){

$date_range = $this->GetDsteRange();

$id_sucursal = ($_POST["id_sucursal"]=="All") ? " " : "and s.id_sucursal=".$_POST["id_sucursal"];

$obj = new ReportesModel("clientes",$this->adapter);

$result = $obj->RptsSaldoPendienteMenores($id_sucursal);

$title = 'Reporte Clientes Saldo Pendiente < 1500'; 

$header = ["Analista","Cliente","Sucursal","Codigo","Fecha Desembolso","Vence","Tipo","Modalidad","Estado","Dias Vencidos","Capital","Deuda","Pendiente"];

$fields= ["analista","cliente","sucursal","codigo_prestamo","fecha_desembolso","fecha_vencimiento","tipo_prestamo","modalidad","estado","ndiasv","capital","deuda_total","saldo_pendiente"];

$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title));

}


public function GetCumplimientoMetas(){ 
    

$weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

$date_range = $this->GetDsteRange();

$id_sucursal = ($_POST["id_sucursal"]=="All") ? " " : "and s.id_sucursal=".$_POST["id_sucursal"];

$obj = new ReportesModel("clientes",$this->adapter);

$result = $obj->Rpts_cumplimiento_metas( $id_sucursal, $weekday[date("w")] , $date_range['bengin']);

$title = 'Reporte Cumpliento de Metas Por Analistas'; 

$header = ["Analista","Sucursal","Meta Vigente","Recupera Vigente","Cumplimiento","Meta Vencida","Recupera Vencida","Cumplimiento","Meta Total","Recupera Total","Cumplimiento Total"];

$fields = ["analista","sucursal","metactivos","Recaudo_Pvigente","cumplimiento_vigente","metavencidos","Recaudo_vencidos","cumplimiento_vencido","metaglobal","Recaudo_Global","cumplimiento_total"];

$totales = ["metactivos","Recaudo_Pvigente","cumplimiento_vigente","metavencidos","Recaudo_vencidos","cumplimiento_vencido","metaglobal","Recaudo_Global","cumplimiento_total"];

$total_montos = ["metactivos","Recaudo_Pvigente","cumplimiento_vigente","metavencidos","Recaudo_vencidos","cumplimiento_vencido","metaglobal","Recaudo_Global","cumplimiento_total"];

$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title,"totales"=>$totales,

            "total_montos"=>$total_montos));


}

public function Get_bosquesdemora_x_analistas(){

        $id_col     = isset($_GET['colaborador'])?$_GET['colaborador']:'' ;

        $nombre_col = isset($_GET['nombre_col'])?' del colaborador: '. $_GET['nombre_col']:'';

    if(isset($_POST['id_sucursal']))
        $concat_sql  = ($_POST['id_sucursal']=="All") ? "" : "and s.id_sucursal = ".$_POST['id_sucursal'];
    else
        $concat_sql = "and c.id_colaborador=".$id_col;

        $obj = new ReportesModel("clientes",$this->adapter);

        $result = $obj->Rpts_bosquesdemora_x_analistas($concat_sql);

        $title        = 'Rango de mora Por Analista activos'.$nombre_col;;

        $header       = ["Analista","Temprana","Monto C$","Avanzada","Monto C$","Riesgo","Monto C$","Riesgo Perdido","Monto C$","Vencido","Monto"];

        $fields       = ["analista","temprana","mora1","avanzada","mora2","riesgo","mora3","perdido","mora4","vencido","mora5"];

        $totales      = ["Temprana","Monto C$","Avanzada","Monto C$","Riesgo","Monto C$","Riesgo Perdido","Monto C$","Vencido","Monto"];

        $total_montos = ["temprana","mora1","avanzada","mora2","riesgo","mora3","perdido","mora4","vencido","mora5"];

        $this->view("reportes_",array("data"=>$result,"header"=>$header,

        "fields"=>$fields,"title"=>$title,"totales"=>$totales,

        "total_montos"=>$total_montos));

    }


    public function GetCuotasdeldia(){

        $weekday = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

$date_range = $this->GetDsteRange();

$id_sucursal = ($_POST["id_sucursal"]=="All") ? " " : "and s.id_sucursal=".$_POST["id_sucursal"];

$obj = new ReportesModel("clientes",$this->adapter);

$result = $obj->Rpts_cuotas_del_dia($id_sucursal, $weekday[date("w",strtotime( $date_range['bengin']))] , $date_range['bengin']);

$title = 'Reporte aplicacion de cuotas del dia';

$header = ["Sucursal","Analista","Cliente","Codigo","Estado","Dia Desembolso","Pagado","Fecha Cuota ","Cuota Base","Cuota","Saldo"];

$fields= ["sucursal","analista","cliente","codigo_prestamo","estado","dia_desembolso","pagado","fecha_cuota","cuota","monto_cuota","saldo_pendiente"];

$this->view("reportes_",array("data"=>$result,"header"=>$header,"fields"=>$fields,"title"=>$title));

}



    public function reportsClientes(){

        $this->view("reports_clientes",array());

    }



    public function reportsClientesE(){

        $this->view("reports_clientesE",array());

    }

    public function reportsColaboradores(){

        $this->view("reports_colaboradores",array());

    }



    public function reportsColaboradoresE(){

        $this->view("reports_colaboradoresE",array());

    }



	    public function reportsColaboradoresG(){

        $this->view("reports_colaboradoresGroup",array());



		}



		   public function reportsPrestamos(){

        $this->view("reports_Prestamos",array());

    }



		   public function reportsPrestamosf(){

        $this->view("reports_PrestamosF",array());

    }



}

?>
