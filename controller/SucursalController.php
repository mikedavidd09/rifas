<?php
require_once 'core/Utils.php';

class SucursalController extends ControladorBase{
    public $conectar;
    public $adapter;
    protected $permisos;
    public function __construct(){
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $obj_permisos = new UsuarioModel($this->adapter);
        $session =$_SESSION['Login_View'];
        $id_user=$session->id_user;
        $obj_permisos = $obj_permisos->getPermisosByModulo('Sucursales',$id_user);
        $this->permisos = $obj_permisos;
    }
    public function index(){
        //creamos el Objeto EmpleadosModel
        $Sucursal = new SucursalModel($this->adapter);
        //conseguimos todos los usuarios;
        $all_Sucursales=$Sucursal->getAllSucursales();
        $this->view("ver_sucursales",array("all_sucursales"=>$all_Sucursales));
    }
	public function listado()
    {
        $session =$_SESSION["Login_View"];
        $id_sucursal =$_GET["id_sucursal"];
        $consulta =$_GET["consulta"];

        $params = $columns = $totalRecords = $data = array();

        $params = $_REQUEST;
        $where_condition = $sqlTot = $sqlRec = $sql_query = "";
        $p = $estado = '';

        if($consulta == "Cliente"){
             $columns = array(
                0 => 'c.nombre',
                1 => 'c.nombre',
                2 => 'c.apellido',
                3 => 'c.codigo_cliente',
                4 => 'c.sexo',
                5 => 'c.cedula',
                6 => 'c.telefono',
                7 => 'c.tipo_negocio'
            );
            if(!empty($params['search']['value']) ) {
            $where_condition .= " WHERE ";
            $where_condition .= " ( c.nombre LIKE '%".$params['search']['value']."%' ";
            $where_condition .= " OR c.apellido LIKE '%".$params['search']['value']."%' ";
            $where_condition .= " OR c.codigo_cliente LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR c.sexo LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR c.cedula LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR c.telefono LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR c.tipo_negocio LIKE '%".$params['search']['value']."%' )";
        }

            $sql_query = "SELECT c.id_cliente,c.nombre,
                             c.apellido,
                             c.codigo_cliente,
                             c.sexo,c.cedula,
                             c.telefono,
                             c.tipo_negocio
                      FROM clientes c
                      INNER JOIN colaboradores cs ON c.id_colaborador = cs.id_colaborador
                      INNER JOIN sucursal s ON s.id_sucursal = cs.id_sucursal";
        }else if($consulta == "Colaborador"){
            $columns = array(
                0 => 's.nombre',
                1 => 's.nombre',
                2 => 's.apellido',
                3 => 's.codigo_colaborador',
                4 => 's.inss',
                5 => 's.sexo',
                6 => 's.cedula',
                7 => 's.telefono',
                8 => 's.cargo'
            );
            if(!empty($params['search']['value']) ) {
                $where_condition .= " WHERE ";
                $where_condition .= " ( s.nombre LIKE '%".$params['search']['value']."%' ";
                $where_condition .= " OR s.apellido LIKE '%".$params['search']['value']."%'";
                $where_condition .= " OR s.codigo_colaborador LIKE '%".$params['search']['value']."%'";
                $where_condition .= " OR s.inss LIKE '%".$params['search']['value']."%'";
                $where_condition .= " OR s.sexo LIKE '%".$params['search']['value']."%'";
                $where_condition .= " OR s.cedula LIKE '%".$params['search']['value']."%'";
                $where_condition .= " OR s.telefono LIKE '%".$params['search']['value']."%'";
                $where_condition .= " OR s.cargo LIKE '%".$params['search']['value']."%' )";
            }
            $sql_query = "SELECT s.id_colaborador,
                             s.nombre,
                             s.apellido,
                             s.codigo_colaborador,
                             s.inss,
                             s.sexo,
                             s.cedula,
                             s.telefono,
                             s.cargo
                              FROM colaboradores s";

        }else if($consulta == "Prestamo"){
            $columns = array(
                    0 => 'p.codigo_prestamo',
                    1 => 'p.codigo_prestamo',
                    2 => 'c.nombre',
                    3 => 'p.modalidad',
                    4 => 'pl.numero_cuotas',
                    5 => 'i.porcentaje',
                    6 => 'p.capital',
                    7 => 'p.deuda_total',
                    8 => 'p.saldo_pendiente',
                    9 => 'p.total_abonado',
                    10 => 'p.cuota',
                    11 => 'p.mora',
                );
                if(!empty($params['search']['value']) ) {
                    $where_condition .= " WHERE ";
                    $where_condition .= " ( p.codigo_prestamo LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR c.nombre LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR p.modalidad LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR pl.numero_cuotas LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR i.porcentaje LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR p.capital LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR p.deuda_total LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR p.saldo_pendiente LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR p.total_abonado LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR p.cuota LIKE '%".$params['search']['value']."%'";
                    $where_condition .= " OR p.mora LIKE '%".$params['search']['value']."%' )";
                }
            $sql_query = "SELECT p.id_prestamo,
                             p.codigo_prestamo,
                             CONCAT(c.nombre,' ',c.apellido),
                             p.modalidad,
                             pl.numero_cuotas,
                             i.porcentaje,
                             p.capital,
                             p.deuda_total,
                             p.saldo_pendiente,
                             p.total_abonado,
                             p.cuota,
                             p.mora
                              FROM clientes  c INNER JOIN colaboradores  cl ON c.id_colaborador=cl.id_colaborador
                              INNER JOIN sucursal s on s.id_sucursal = cl.id_sucursal
                              INNER JOIN prestamos as p ON c.id_cliente=p.id_cliente
                              INNER JOIN intereses as i on p.id_interes=i.id_interes
                              INNER JOIN plazos as pl on p.id_plazo=pl.id_plazo";
            $estado="and p.estado='Aceptado'";
        }

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
        $scur = " $sql s.id_sucursal =$id_sucursal $estado";
        $sqlTot .= $scur;
        $sqlRec .= $scur;

        $sqlRec .=  " ORDER BY  $p". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
       // print_r($sqlRec);
        $data     = new SucursalModel($this->adapter);
        $all = $data->getListadoIndex($sqlRec);
        $totalRecords = $data->TotalRecord($sqlTot);

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $all
        );
        echo json_encode($json_data);
    }
	  public function getClientes(){
         $id_sucursal=$_GET["id_sucursal"];
         $this->view("ver_clientessucursales",array("sucursal"=>$id_sucursal));

     }

	  public function getColaboradores(){

		 $id_sucursal=$_GET["id_sucursal"];
         $this->view("ver_colaboradorsucursal",array("sucursal"=>$id_sucursal));

	 }

	 	  public function getPrestamos(){

		 $id_sucursal=$_GET["id_sucursal"];

		 $this->view("ver_prestamossucursal",array("sucursal"=>$id_sucursal));

	 }



    public function crear(){
        $session = $_SESSION['Login_View'];
        if($this->permisos['0'] == '0' && !$session->perfil){
            Utils::getMensaje('Crear');
        }
         if(isset($_POST["sucursal"])){
            //Creamos una nueva sucursal
            $sucursal = new sucursal($this->adapter);

            $sucursal->setSucursal($_POST["sucursal"]);

            $save=$sucursal->put($sucursal);
         }
         if ($save == 1) {
                      $resp = array(
                          'respuesta' => 'true',
                          'data' => '',
                          'mensaje' => 'Sucursal agregada exitosamente'
                      );
                      $json = json_encode($resp);
                      echo($json);
                  } else
                  {
                      $resp = array(
                          'respuesta' => 'false',
                          'data' => '',
                          'mensaje' => 'ERROR. NO se pudi agregar la sucursal ,ocurrio un error interno.'
                      );
                      $json = json_encode($resp);
                      echo($json);
                  }

    }


    public function borrar(){
        $session = $_SESSION['Login_View'];
        if($this->permisos['3'] == '0' && !$session->perfil){
            Utils::getMensaje('Borrar');
        }
        if(isset($_GET["id_sucursal"])){
            $id=(int)$_GET["id_sucursal"];
            $cliente=new Sucursal($this->adapter);
            $cliente->deleteById($id);
        }
        $this->redirect();
    }

    public function dataByComboSucursal2(){
        $Sucursal = new Sucursal($this->adapter);
        $all_Sucursal=$Sucursal->getAll();
        echo json_encode($all_Sucursal);
    }

    public function dataByComboSucursal(){

      $session =$_SESSION["Login_View"];      
      $sucursal = new sucursalModel($this->adapter);
      $all_Sucursales = $sucursal->ComboSucursales($session->cargo,$session->id_sucursal,$session->id_colaborador);
      echo json_encode($all_Sucursales);

    }


    public function getSucursalByID($id_sucursal){
        $sucursalObj = new Sucursal($this->adapter);
        $sucural= $sucursalObj->getById($id_sucursal);
        print_r($sucural);
        exit;
        return $sucural;
    }
    public function getNameSucursalByID($id_sucursal){
        $sucursal = $this->getSucursalByID($id_sucursal);
        print_r($sucursal);
        exit;
        return $name_sucursal;
    }

    public function statistics(){

    $sucursales = new sucursalModel($this->adapter);
    $estadisticas  = $sucursales->Get_sucursales_stats();
    $analistas = $sucursales->Get_analistas_stats();
    $this->view("ver_statistics",array("sucursales_stats"=>$estadisticas,"analistas_stats"=>$analistas));
    }

    public function metas_statics(){

      date_default_timezone_set('America/Managua');
      $fecha_cuota = date('Y/m/d');

      $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

    $sucursal = new sucursalModel($this->adapter);
    $metasXsucursal = $sucursal->get_metasXsucursal($dias[date("w")]);
    $metasXanalista = $sucursal->get_metasXanalista($dias[date("w")]);
    $recaudoXsucursal = $sucursal->get_recaudoXsucursal();
    $recaudoXanalista = $sucursal->get_recaudoXanalista();

    $this->view("ver_metas",array("metasXsucursal"=>$metasXsucursal,"metasXanalista"=>$metasXanalista,"recaudoXsucursal"=>$recaudoXsucursal,"recaudoXanalista"=>$recaudoXanalista));

    }
}
?>
