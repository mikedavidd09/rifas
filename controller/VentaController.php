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
            8 => 'v.total',
            9=>'borrado'
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
    v.total,
    v.borrado

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

        
        $where_condition.= ($session->role == "sudo"  || $session->role == "admin") ?  " and fecha = '$today' ":" and fecha = '$today' and col.id_colaborador = ".$session->id_colaborador;
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

    
    public function store(){

        $consecutivo = time();
        $now  = new DateTime();
        $hora = $now->format('H:i:s');
        $sorteo = new SorteoModel($this->adapter);
        $sorteo = $sorteo->getSorteo($_POST["id_juego"],$hora);

        if($sorteo == null){
            echo json_encode(["status"=>false,"data"=>"","message"=>"Error, no se puede guardar el sorteo, fuera de horario! = ".$now->format('Y-m-d H:i:s')]);
        return;
        }

        $venta = new Venta($this->adapter);
        $venta->setIdJuego($_POST["id_juego"]);
        $venta->setNombre($_POST["nombre_cliente"]);
        $venta->setFecha(date('Y-m-d'));
        $venta->setHora(date('H:i:s'));
        $venta->setConsecutivo($consecutivo);
        $venta->setIdColaborador($_SESSION['Login_View']->id_colaborador);
        $venta->setIdSorteo($sorteo->id_sorteo);
        $venta->setTotal($_POST["total"]);
        $venta->setPagado('0');
        $venta->setBorrado('0');
        $save = $venta->put($venta);

        $id_venta = $venta->getIdEnd('id_venta');
        
        $numero = new Numero($this->adapter);

        foreach($_POST["numeros"] as $item){
        
            $numero->setIdVenta($id_venta);
            $numero->setNumero($item ['numero']);
            $numero->setMonto($item ['monto']);
            $numero->setPremio($item ['premio']);
            $save = $numero->put($numero);
        }


        if($save == 1)
            echo json_encode(["status"=>true,"consecutivo"=>$consecutivo,"sorteo"=>$sorteo->etiqueta,"data"=>"","message"=>"Exito, se guardo correctamente! id_venta=".$id_venta]);
    
    }

    public function ver_venta(){
        $id_venta = $_GET['id'];
        $venta_model = new VentaModel($this->adapter);
        $venta = $venta_model->getVenta($id_venta);
        $numeros = $venta_model->getNumeros($id_venta);
        $fecha = Utils::GetDateFormatted(($venta->fecha));
        $hora = Utils::getTimeFormatted($venta->hora);

        return $this->view('venta/ver_venta',['venta'=>$venta,'numeros'=>$numeros,'fecha'=>$fecha,'hora'=>$hora]);
    }

    public function delete(){
        $login = $_SESSION['Login_View'];
        $id_venta = $_GET['id'];
        $id_sorteo = $_GET['id_sorteo'];

        $model = new VentaModel($this->adapter);
        $sorteo = $model->getSorteo($id_sorteo);
        $sorteo_final =  date('H:i:s',strtotime($sorteo->fin));
        $now  = date('H:i:s');


        if($now > $sorteo_final){
            echo json_encode(["status"=>false,"message"=>"Sorteo finalizado, no es posible borrar este ticket"]);
            return;
        }

    
        $venta = new Venta($this->adapter);

        $venta->setAllToNone();
        $venta->setIdVenta($id_venta);
        $venta->setBorrado('1');

        $update = $venta->updateById($id_venta,'venta',$venta);

        $log = new Log_delete($this->adapter);
        $log->setFecha(date('Y-m-d H:i:s'));
        $log->setTableName('venta');
        $log->setIdRecord($id_venta);
        $log->setIdUser($login->id_user);
        $save = $log->put($log);


        if($update == 1)
            echo json_encode(["status"=>true,"message"=>"Se elimino la venta correctamente nro=".$id_venta]);
        else
            echo json_encode(["status"=>false,"message"=>"Error al eliminar la venta ".$save]);
    }

    public function pagarPremio(){

        $id_venta = $_GET['id_venta'];

        $venta = new Venta($this->adapter);
        $venta->setAllToNone();
        $venta->setIdVenta($id_venta);
        $venta->setPagado(1);
        $update = $venta->updateById($id_venta,'venta',$venta);

        if($update == 1)
            echo json_encode(["status"=>true,"message"=>"Se pago el premio correctamente id_venta=".$id_venta]);
        else
            echo json_encode(["status"=>false,"message"=>"Error al pagar el premio ".$update]);
    }
}
?>