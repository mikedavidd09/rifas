<?php
require_once 'core/Utils.php';

class NumeroGanadorController extends ControladorBase{
    public $conectar;
    public $adapter;
    
    public function __construct(){
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();

    }
    public function borrarView(){
		$this->view("ganadores/borrar",array());
    }

    public function listarBorrado(){
    $params = $columns = $totalRecords = $data = array();

    $params = $_REQUEST;
    date_default_timezone_set('America/Managua');
    $fecha = date('Y-m-d');

    $columns = array(
            0 => 'ng.id_numero_ganador',
            1 => 'j.nombre',
            2 => 's.etiqueta',
            3 => 'ng.numero',
            4 => 'ng.id_numero_ganador');

    $where_condition = $sqlTot = $sqlRec = "";

    $sql_query = "SELECT ng.id_numero_ganador,j.nombre, s.etiqueta, ng.numero,ng.id_numero_ganador
                from juegos j inner join sorteos s on j.id_juego = s.id_juego
                inner join numeros_ganadores ng on s.id_sorteo = ng.id_sorteo and ng.fecha ='$fecha'
                and j.id_juego = ng.id_juego";

    $sqlTot .= $sql_query;
    $sqlRec .= $sql_query;

     if(!empty($params['search']['value']) ) 
        $where_condition .= " WHERE (j.nombre LIKE '%".$params['search']['value']."%'
                                OR s.etiqueta LIKE '%".$params['search']['value']."%'
                                OR ng.numero LIKE '%".$params['search']['value']."%'
                                OR ng.id_numero_ganador LIKE '%".$params['search']['value']."%' )";

        
        $sqlTot .= $where_condition;
        $sqlRec .= $where_condition;

        $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

      $numeroGanadorModel = new NumeroGanadorModel($this->adapter);
      $numeroGanador = $numeroGanadorModel->getListadoIndex($sqlRec);
      $totalRecords = $numeroGanadorModel->TotalRecord($sqlTot);
       // print_r($sqlRec);
      $json_data = array(
          "draw"            => intval( $params['draw'] ),
          "recordsTotal"    => intval( $totalRecords ),
          "recordsFiltered" => intval($totalRecords),
          "data"            => $numeroGanador
      );

         echo json_encode($json_data);

    }

    public function delete(){
        $id_numero_ganador = $_GET['id_numero_ganador'];
        $numeroGanador = new numeroGanadorModel($this->adapter);
        $delete = new numeroGanador($this->adapter);
        $existe = $numeroGanador->existeNumeroGanador($id_numero_ganador);
        if(!$existe){
            echo json_encode(["status"=>false,"message"=>"No existe el registro seleccionado"]);
            exit;
        }
        
            $delete = $numeroGanador->deleteById($id_numero_ganador,'numero_ganador');
            if($delete == 1){
                echo json_encode(["status"=>true,"message"=>"Registro borrado correctamente"]);
                exit;
            }
            else{
                echo json_encode(["status"=>false,"message"=>"Ocurrio un error al borrar el registro ".$delete]);
                exit;
            }
        
    }



}