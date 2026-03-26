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

    public function dashBoard(){
        $login = $_SESSION['Login_View'];
        $fechaLunes = date('Y-m-d', strtotime('monday this week'));
        $today = date('Y-m-d');
        $ventaModel = new VentaModel($this->adapter);
        $facturado_dia = $ventaModel->getFacturadoRangoFecha($today,$today);
        $pagado_dia = $ventaModel->getPagadoDiaRangoFecha($today,$today);
        $facturado_semanal = $ventaModel->getFacturadoRangoFecha($fechaLunes,$today);
        $pagado_semanal = $ventaModel->getPagadoDiaRangoFecha($fechaLunes,$today);
        $total_usuarios = $ventaModel->getTotalUsuarios();

        $facturado_dia_By_juego = $ventaModel->getFacturadoRangoFechaByJuego($today,$today);
        $pagado_dia_By_juego = $ventaModel->getPagadoDiaRangoFechaByJuego($today,$today);
    
        $this->view('homeAdmin',array('facturado_dia'=>number_format($facturado_dia),
        'pagado_dia'=>number_format($pagado_dia),
        'facturado_semanal'=>number_format($facturado_semanal),
        'pagado_semanal'=>number_format($pagado_semanal),
        'facturado_dia_By_juego'=>$facturado_dia_By_juego,
        'pagado_dia_By_juego'=>$pagado_dia_By_juego,
        'total_usuarios'=>$total_usuarios));
    }

        public function dashBoardVendedor(){
        $login = $_SESSION['Login_View'];
        $fechaLunes = date('Y-m-d', strtotime('monday this week'));
        $today = date('Y-m-d');
        $ventaModel = new VentaModel($this->adapter);
        $facturado_dia = $ventaModel->getFacturadoByColaborador($today,$today,$login->id_colaborador);
        $pagado_dia = $ventaModel->getPagadoByColaborador($today,$today,$login->id_colaborador);
        $facturado_semanal = $ventaModel->getFacturadoByColaborador($fechaLunes,$today,$login->id_colaborador);
        $pagado_semanal = $ventaModel->getPagadoByColaborador($fechaLunes,$today,$login->id_colaborador);
    

        $facturado_dia_By_juego = $ventaModel->getFacturadoByJuegoByColaborador($today,$today,$login->id_colaborador);
        $pagado_dia_By_juego = $ventaModel->getPagadoByJuegoByColaborador($today,$today,$login->id_colaborador);
    
        $this->view('homeAdmin',array('facturado_dia'=>number_format($facturado_dia),
        'pagado_dia'=>number_format($pagado_dia),
        'facturado_semanal'=>number_format($facturado_semanal),
        'pagado_semanal'=>number_format($pagado_semanal),
        'facturado_dia_By_juego'=>$facturado_dia_By_juego,
        'pagado_dia_By_juego'=>$pagado_dia_By_juego,
        'total_usuarios'=>1));
    }

    public function juegaMultisorteos(){
        $login = $_SESSION['Login_View'];
        $juego = new JuegoModel($this->adapter);
        $vendedor = $juego->getVendedor($login->id_colaborador);
        $juegos = $juego->getJuegos();
        $this->view('juegos/multiSorteos',array('juegos'=>$juegos,'vendedor'=>$vendedor));
    }

    public function jugar(){
        $id_juego = $_GET['juego'];
        $login = $_SESSION['Login_View'];
        $juego  = new JuegoModel($this->adapter);
        $vendedor = $juego->getVendedor($login->id_colaborador);
        $juego = $juego->getJuegoById($id_juego);
        $maxdigits = strlen(abs($juego->max)); 

        if($id_juego == 3)
            $this->view('juegos/juegaFecha',['juego'=>$juego,'vendedor'=>$vendedor,'maxdigits'=>$maxdigits]);
        else
            $this->view('juegos/jugar',['juego'=>$juego,'vendedor'=>$vendedor,'maxdigits'=>$maxdigits]);
    }

    public function getJuegoById(){
        $id_juego = $_POST['id_juego'];
        $modelJuego  = new JuegoModel($this->adapter);
        $juego = $modelJuego->getJuegoById($id_juego);
        $maxdigits = strlen(abs($juego->max));
        $now = date('H:i:s');
        $sorteos = $modelJuego->getSorteosDisponibles($id_juego,$now);
        $arraySorteos = [];
        if($sorteos)
        foreach($sorteos as $item)
            $arraySorteos[] = (int)$item->id_sorteo;
        echo json_encode(['juego'=>$juego,'maxdigits'=>$maxdigits,'sorteos'=>$sorteos,'arraySorteos'=>$arraySorteos]);
    }

    public function sorteos(){

        $juego = new JuegoModel($this->adapter);
        $juegos = $juego->getJuegos();
    
        foreach($juegos as $item){
            $sorteos = $juego->getSorteos($item->id_juego);
            $item->sorteos = $sorteos;
        }

        $this->view('juegos/sorteos',['juegos'=>$juegos]);
    }

    public function add_numero_ganador(){
        $today = date('Y-m-d');
        $juego = new JuegoModel($this->adapter);
        $juegos = $juego->getJuegos();
        foreach($juegos as $item){
            $item->sorteos = $juego->getNumerosGanadores($item->id_juego,$today);
        
        }
        return $this->view('ganadores/agregar',['juegos'=>$juegos]);
    }

    public function borrar_numeros_ganadores(){
        $today = date('Y-m-d');
        $juego = new JuegoModel($this->adapter);
        $juegos = $juego->getAllNumerosGanadores($today);
        $this->view('ganadores/borrar',['juegos'=>$juegos]);
    }

    public function ver_numeros_ganadores(){
        $today = date('Y-m-d');
        $juego = new JuegoModel($this->adapter);
        $juegos = $juego->getJuegos();
        foreach($juegos as $item)
            $item->sorteos = $juego->getNumerosGanadores($item->id_juego,$today);
        return $this->view('ganadores/ver',['juegos'=>$juegos]);
    }


    public function guardarNumero(){

    $numero = $_POST['numero'];
    $id_juego = $_POST['id_juego'];
    $id_sorteo = $_POST['id_sorteo'];
    $today = date('Y-m-d');

    $numeroGanador = new numeroGanador($this->adapter);
    $numeroGanador->setNumero($numero);
    $numeroGanador->setIdJuego($id_juego);
    $numeroGanador->setIdSorteo($id_sorteo);
    $numeroGanador->setFecha(date('Y-m-d'));

    $model = new JuegoModel($this->adapter);

    $id_numero_ganador = $model->existeNumeroGanador($id_juego,$id_sorteo,$today);
    if(!$id_numero_ganador){
        $save  = $numeroGanador->put($numeroGanador);
    }else{
        $numeroGanador->setIdNumeroGanador($id_numero_ganador);
        $save = $numeroGanador->updateById($id_numero_ganador,'numero_ganador',$numeroGanador);
    }

    if($save){
        echo json_encode(['status'=>true,'message'=>'El numero '.$numero.' fue guardado correctamente id_juego: '.$id_juego.' id_sorteo: '.$id_sorteo]);
    }
    else{
        echo json_encode(['status'=>false,'message'=>'Error al guardar el numero '.$numero.' id_juego: '.$id_juego.' id_sorteo: '.$id_sorteo." error=".$save]);
    }  
    }

    public function ver_ganadores(){

        return $this->view('ganadores/index',array());
    }

    public function getGanadores(){

    $session = $_SESSION['Login_View'];
    $params = $columns = $totalRecords = $data = array();

    $params = $_REQUEST;

    $columns = array(
            0 => 'v.id_venta',
            1 => 'CONCAT(col.nombre, " ", col.apellido)',
            2 => 'v.nombre',
            3 => 'v.consecutivo',
            4 => 'j.nombre',
            5 => 's.etiqueta',
            6 => 'v.fecha',
            7 => 'v.hora',
            8 => 'v.total',            
            9 => 'ng.numero',
            10 => 'n.premio',
            11 => 'v.pagado',
        );

    $where_condition = $sqlTot = $sqlRec = "";

    $sql_query = "SELECT
    v.id_venta,
    CONCAT(col.nombre, ' ', col.apellido),
    v.nombre,
    v.consecutivo,
    j.nombre,
    s.etiqueta,
    v.fecha,
    v.hora,
    CONCAT('C$',' ',v.total),
    ng.numero,
    CONCAT('C$',' ',n.premio),
    v.pagado
    FROM ventas v
    INNER JOIN colaboradores col 
        ON v.id_colaborador = col.id_colaborador
    INNER JOIN sorteos s 
        ON v.id_sorteo = s.id_sorteo
    INNER JOIN juegos j 
        ON v.id_juego = j.id_juego
    INNER JOIN numeros n 
        ON v.id_venta = n.id_venta
    INNER JOIN numeros_ganadores ng 
        ON ng.numero = n.numero 
        AND ng.id_sorteo = s.id_sorteo 
        AND ng.fecha = v.fecha";
    $sqlTot .= $sql_query;
    $sqlRec .= $sql_query;
    $now  = new DateTime();
    $today = $now->format('Y-m-d');

    if(!empty($params['search']['value']) ) 
        $where_condition .= " WHERE (concat(col.nombre,' ',col.apellido) LIKE '%".$params['search']['value']."%'
                                OR v.nombre LIKE '%".$params['search']['value']."%' 
                                OR v.consecutivo LIKE '%".$params['search']['value']."%'
                                OR j.nombre LIKE '%".$params['search']['value']."%'
                                OR s.etiqueta LIKE '%".$params['search']['value']."%'
                                OR v.fecha LIKE '%".$params['search']['value']."%'
                                OR v.hora LIKE '%".$params['search']['value']."%'
                                OR v.total LIKE '%".$params['search']['value']."%' 
                                OR ng.numero LIKE '%".$params['search']['value']."%'
                                OR n.monto * 80 LIKE '%".$params['search']['value']."%' )";

        
        $where_condition.= ($session->role == "sudo"  || $session->role == "admin") ?  " and v.fecha = '$today' and v.borrado = 0 ":" and v.fecha = '$today' and v.borrado = 0 and  col.id_colaborador = ".$session->id_colaborador;
        $sqlTot .= $where_condition;
        $sqlRec .= $where_condition;

        $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

       // echo $sqlRec;
        $juegos  = new JuegoModel($this->adapter);
        $ganadores = $juegos->getListadoIndex($sqlRec);
        $totalRecords = $juegos->TotalRecord($sqlTot);
        //print_r($sqlRec);
        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $ganadores
        );
        echo json_encode($json_data);
    }

    public function ViewMovimientos(){

        return $this->view('movimientos/ver',array());
    }

    public function ViewCalculos(){
        return $this->view('movimientos/calculos',array());
    }

   public function ViewmontoLimite(){
    $juegoMdl = new JuegoModel($this->adapter);
    $juegos = $juegoMdl->getJuegos();
    $this->view('juegos/montoLimite',['juegos'=>$juegos]);
   }
   public function guardarMontoLimite(){
    if(isset($_POST['id_juego']) && isset($_POST['monto_limite'])){
        $id_juego = $_POST['id_juego'];
        $monto_limite = $_POST['monto_limite'];
        $juego = new Juego($this->adapter);
        $juego->setAllNone();
        $juego->setIdJuego($id_juego);
        $juego->setMontoLimite($monto_limite);
       
        $save = $juego->updateById($id_juego,'juego',$juego);
    
        if($save){
            echo json_encode(['status'=>true,'message'=>'Se actualizo el monto limite correctamente']);
        }else{
            echo json_encode(['status'=>false,'message'=>'Error al actualizar el monto limite']);
        }
    }
    else{
        echo json_encode(['status'=>false,'message'=>'Error, variables no definidas']);
    }

   }

}
?>
