<?php
class JuegosController extends ControladorBase
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

    public function home(){
        return $this->view('home',array());
    }

    public function juegaDiaria(){
        $this->view('juegos/juegaDiaria',['juego'=>'Juega Diaria','id_juego'=>1,'min'=>0,'max'=>99]);
    }
    public function juega3(){
        $this->view('juegos/juegaDiaria',['juego'=>'Juega 3','id_juego'=>2,'min'=>0,'max'=>999]);
    }
    public function juegaFecha(){
        $this->view('juegos/juegaFecha',['juego'=>'Juega Fecha','id_juego'=>3,'min'=>0,'max'=>999]);
    }
    public function juegaCombo(){
        $this->view('juegos/juegaDiaria',['juego'=>'Juega Combo','id_juego'=>4,'min'=>0,'max'=>9999]);
    }
    public function juegaTica(){
        $this->view('juegos/juegaDiaria',['juego'=>'Juega Tica','id_juego'=>5,'min'=>0,'max'=>99]);
    }
    public function juegaMonazos(){
        $this->view('juegos/juegaDiaria',['juego'=>'Monazos','id_juego'=>6,'min'=>0,'max'=>999]);
    }
    public function juegaHondurena(){
        $this->view('juegos/juegaDiaria',['juego'=>'Juega Hondurena','id_juego'=>7,'min'=>0,'max'=>99]);
    }
    public function juegaJ3Honduras(){
        $this->view('juegos/juegaDiaria',['juego'=>'J3 Hondurena','id_juego'=>8,'min'=>0,'max'=>999]);
    }
    public function juegaTerminacion(){
        $this->view('juegos/juegaDiaria',['juego'=>'Juega Termination','id_juego'=>9,'min'=>0,'max'=>99]);
    }
    public function juegaPrimera(){
        $this->view('juegos/juegaDiaria',['juego'=>'Juega Primera','id_juego'=>10,'min'=>0,'max'=>99]);
    }
    public function juegaSalvadorena(){
        $this->view('juegos/juegaDiaria',['juego'=>'Juega Salvadorena','id_juego'=>11,'min'=>0,'max'=>99]);
    }
    public function juegaMultisorteos(){
        $this->view('juegos/juegaMultisorteos',array());
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
        $venta->setNombre($_POST["nombre"]);
        $venta->setFecha(date('Y-m-d'));
        $venta->setHora(date('H:i:s'));
        $venta->setConsecutivo($consecutivo);
        $venta->setIdColaborador($_SESSION['Login_View']->id_colaborador);
        $venta->setIdSorteo($sorteo->id_sorteo);
        $venta->setTotal($_POST["total"]);
        $venta->setEstado(1);
        $save = $venta->put($venta);

        $id_venta = $venta->getIdEnd('id_venta');
        
        $numero = new Numero($this->adapter);

        foreach($_POST["numeros"] as $item){
           
            $numero->setIdVenta($id_venta);
            $numero->setNumero($item ['numero']);
            $numero->setMonto($item ['monto']);
            $save = $numero->put($numero);
        }

        if($save == 1)
            echo json_encode(["status"=>true,"consecutivo"=>$consecutivo,"sorteo"=>$sorteo->etiqueta,"data"=>"","message"=>"Exito, se guardo correctamente! id_venta=".$id_venta]);
    
    }


    public function verVentas(){

        return $this->view('ver_ventas',array());
    }

    public function getVentas(){

        $session = $_SESSION['Login_View'];
        $params = $columns = $totalRecords = $data = array();

        $params = $_REQUEST;

       $columns = array(
            0 => 'col.id_colaborador',
            1 => 'col.nombre',
            2 => 'col.apellido',
            3 => 'j.juego',
            4 => 's.etiqueta',
            5 => 'v.total'
        );

        $where_condition = $sqlTot = $sqlRec = "";

        if(!empty($params['search']['value']) ) {
            $where_condition .= " WHERE ";
            $where_condition .= " ( col.nombre LIKE '%".$params['search']['value']."%' OR col.apellido LIKE '%".$params['search']['value']."%' OR j.nombre LIKE '%".$params['search']['value']."%' OR s.etiqueta LIKE '%".$params['search']['value']."%' OR v.total LIKE '%".$params['search']['value']."%' )";
          
        }

        $sql_query = "SELECT 
        col.id_colaborador,
        col.nombre,
        col.apellido,
	    j.nombre, 
        s.etiqueta as sorteo,
        v.total
        FROM colaboradores col
        INNER JOIN ventas v ON col.id_colaborador = v.id_colaborador 
        INNER JOIN sorteos s ON v.id_sorteo = v.id_sorteo
        INNER JOIN juegos j ON v.id_juego = j.id_juego
        INNER JOIN usuarios u on col.id_colaborador = u.id_colaborador
        INNER JOIN roles r on u.id_role = r.id_role
        ORDER BY col.id_colaborador, s.id_sorteo, j.id_juego";
        $sqlTot .= $sql_query;
        $sqlRec .= $sql_query;

        $sql ="";
        if(isset($where_condition) && $where_condition != '') {
            $sqlTot .= $where_condition;
            $sqlRec .= $where_condition;
            $sql ="AND";
        } else {
            $sql ="WHERE";
        }
        $scur = ($session->role == "sudo") ?  " $sql col.estado=1":" $sql r.id_role <> 1 and col.estado=1";
        $sqlTot .= $scur;
        $sqlRec .= $scur;

        $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
        $prestamos     = new PrestamoModel($this->adapter);
        $all_prestamos = $prestamos->getListadoIndex($sqlRec);
        $totalRecords = $prestamos->TotalRecord($sqlTot);
        //print_r($sqlRec);
        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $all_prestamos
        );
        echo json_encode($json_data);








         $now  = new DateTime();
         $today = $now->format('Y:m:d');

        $ventas  = new VentaModel($this->adapter);
        $ventas  = $ventas->getVentas($today);








    }
}
?>
