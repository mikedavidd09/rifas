<?php
class VentaController extends ControladorBase
{

    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        date_default_timezone_set('America/Managua');
    }

     public function index(){

        return $this->view('venta/index',array());
    }

        public function getVentas(){

        $session = $_SESSION['Login_View'];
        $params = $columns = $totalRecords = $data = array();

        $params = $_REQUEST;

       $columns = array(
            0 => 'v.id_venta',
            1 => 'v.consecutivo',
            2 => 'concat(col.nombre," ",col.apellido)',
            3 => 'v.nombre',
            4 => 'j.nombre',
            5 => 's.etiqueta',
            6 => 'v.fecha',
            7 => 'v.hora',
            8 => 'v.total'
        );

        $where_condition = $sqlTot = $sqlRec = "";

 
        $sql_query = "SELECT 
        v.id_venta,
        v.consecutivo,
        concat(col.nombre,' ',col.apellido) as vendedor,
        v.nombre,
	    j.nombre, 
        s.etiqueta,
        v.fecha,
        v.hora,
        v.total

        FROM ventas v
        INNER JOIN colaboradores col ON v.id_colaborador = col.id_colaborador
        INNER JOIN sorteos s ON v.id_sorteo = s.id_sorteo
        INNER JOIN juegos j ON v.id_juego = j.id_juego
        INNER JOIN usuarios u on col.id_colaborador = u.id_colaborador
        INNER JOIN roles r on u.id_role = r.id_role";
        $sqlTot .= $sql_query;
        $sqlRec .= $sql_query;

            $now  = new DateTime();
           $today = $now->format('Y:m:d');

                  if(!empty($params['search']['value']) ) 
            $where_condition .= " WHERE (concat(col.nombre,' ',col.apellido) LIKE '%".$params['search']['value']."%'
                                 OR v.nombre LIKE '%".$params['search']['value']."%' 
                                 OR v.consecutivo LIKE '%".$params['search']['value']."%'
                                 OR j.nombre LIKE '%".$params['search']['value']."%'
                                 OR s.etiqueta LIKE '%".$params['search']['value']."%'
                                 OR v.fecha LIKE '%".$params['search']['value']."%'
                                 OR v.hora LIKE '%".$params['search']['value']."%'
                                 OR v.total LIKE '%".$params['search']['value']."%' 
                                 OR v.id_venta LIKE '%".$params['search']['value']."%' )";

        
        $where_condition.= ($session->role == "sudo"  || $session->role == "admin") ?  " and fecha = '$today' ":" and fecha = '$today' and  col.id_colaborador = ".$session->id_colaborador;
        $sqlTot .= $where_condition;
        $sqlRec .= $where_condition;

        $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
     
       // echo $sqlRec;
        $ventas  = new VentaModel($this->adapter);
        $all_ventas = $ventas->getListadoIndex($sqlRec);
        $totalRecords = $ventas->TotalRecord($sqlTot);
        //print_r($sqlRec);
        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $all_ventas
        );
        echo json_encode($json_data);

    }

    public function ver_venta(){
        $id_venta = $_GET['id'];
        $ventaMoldel = new VentaModel($this->adapter);
        $venta = $ventaMoldel->getVenta($id_venta);
        $numeros = $ventaMoldel->getNumeros($id_venta);
        
        return $this->view('venta/ver_venta',['venta'=>$venta,'numeros'=>$numeros]);
    }

}
?>