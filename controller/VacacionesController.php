<?php
require_once 'core/Filtro.php';
require_once 'core/Utils.php';

class VacacionesController extends ControladorBase
{
    public $conectar;
    public    $adapter;
    protected $permisos;
    protected $id_user;
    protected $cargo;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $obj_permisos = new UsuarioModel($this->adapter);
        $session =$_SESSION['Login_View'];
        $this->id_user=$session->id_user;
        $this->cargo=$session->cargo;
        $obj_permisos = $obj_permisos->getPermisosByModulo('Clientes',$this->id_user);
        $this->permisos = $obj_permisos;
    }

        public function index(){
        $this->view("vacaciones/ver_vacaciones", array());
    }

    public function vacacionesListado(){
        $session =$_SESSION["Login_View"];

        $params = $columns = $totalRecords = $data = array();

        $params = $_REQUEST;

       $columns = array(
            0 => 'col.id_colaborador',
            1 => 'col.nombre',
            2 => 'col.apellido',
            3 => 'suc.id_sucursal',
            4 => 'col.cargo',
            5 => 'estado',
            6 => 'fecha_ingreso',
            7 => 'fecha_egreso',
            8 => 'diaslaborados',
            9 => 'totalvacaciones',
            10 => 'diasdescansados',
            11 => 'diasdisponibles',
            12 => 'id_colaborador',
            13=>'id_colaborador'
           
        );

          $where_condition = $sqlTot = $sqlRec = "";

            if(!empty($params['search']['value']) ) {
            $where_condition .= " WHERE ";
            $where_condition .= " ( col.nombre LIKE '%".$params['search']['value']."%' ";
            $where_condition .= " OR col.apellido LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR col.cargo LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR col.estado LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR col.fecha_ingreso LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR col.fecha_egreso LIKE '%".$params['search']['value']."%' ";
            $where_condition .= " OR col.estado LIKE '%".$params['search']['value']."%')";       
        }

         $sql_query = "SELECT 
         col.id_colaborador,
         col.nombre,
         col.apellido,
         suc.id_sucursal,
         col.cargo,
         col.estado,
        concat(DAY(col.fecha_ingreso),'-',  CASE
        WHEN  MONTH(col.fecha_ingreso) = 1 THEN 'Enero'
        when  MONTH(col.fecha_ingreso) = 2  THEN 'Febrero'
        WHEN  MONTH(col.fecha_ingreso) = 3 THEN 'Marzo'
        when  MONTH(col.fecha_ingreso) = 4  THEN 'Abril'
        WHEN  MONTH(col.fecha_ingreso) = 5 THEN 'Mayo'
        when  MONTH(col.fecha_ingreso) = 6  THEN 'Junio'
        WHEN  MONTH(col.fecha_ingreso) = 7 THEN 'Julio'
        when  MONTH(col.fecha_ingreso) = 8  THEN 'Agosto'
        WHEN  MONTH(col.fecha_ingreso) = 9 THEN 'Septiembre'
        when  MONTH(col.fecha_ingreso) = 10  THEN 'Octubre'
        WHEN  MONTH(col.fecha_ingreso) = 11 THEN 'Noviembre'
        when  MONTH(col.fecha_ingreso) = 12  THEN 'Diciembre'
        
        END,'-', year(col.fecha_ingreso)) as fecha_ingreso,

        concat(DAY(col.fecha_egreso),'-',  CASE
        WHEN  MONTH(col.fecha_egreso) = 1 THEN 'Enero'
        when  MONTH(col.fecha_egreso) = 2  THEN 'Febrero'
        WHEN  MONTH(col.fecha_egreso) = 3 THEN 'Marzo'
        when  MONTH(col.fecha_egreso) = 4  THEN 'Abril'
        WHEN  MONTH(col.fecha_egreso) = 5 THEN 'Mayo'
        when  MONTH(col.fecha_egreso) = 6  THEN 'Junio'
        WHEN  MONTH(col.fecha_egreso) = 7 THEN 'Julio'
        when  MONTH(col.fecha_egreso) = 8  THEN 'Agosto'
        WHEN  MONTH(col.fecha_egreso) = 9 THEN 'Septiembre'
        when  MONTH(col.fecha_egreso) = 10  THEN 'Octubre'
        WHEN  MONTH(col.fecha_egreso) = 11 THEN 'Noviembre'
        when  MONTH(col.fecha_egreso) = 12  THEN 'Diciembre'
        END,'-', year(col.fecha_egreso)) as fecha_egreso,
       
        if(col.fecha_egreso IS NULL ,DATEDIFF(CURDATE(), col.fecha_ingreso),DATEDIFF(col.fecha_egreso, col.fecha_ingreso)) AS diaslaborados,
        if(col.fecha_egreso IS NULL ,DATEDIFF(CURDATE(), col.fecha_ingreso) * 0.082,DATEDIFF(col.fecha_egreso, col.fecha_ingreso) * 0.082) AS totalvacaciones, 
        if(vacation.descansadas IS NULL,0, vacation.descansadas) AS diasdescansados,  
        
        case when vacation.descansadas IS NULL and col.fecha_egreso IS NULL then 
        ( DATEDIFF(CURDATE(), col.fecha_ingreso) * 0.082)
          when vacation.descansadas IS NOT NULL and col.fecha_egreso IS NULL then
        ( DATEDIFF(CURDATE(), col.fecha_ingreso) * 0.082) -  vacation.descansadas
        when vacation.descansadas IS NULL and col.fecha_egreso IS NOT NULL then
        ( DATEDIFF(col.fecha_egreso, col.fecha_ingreso) * 0.082) 
        when vacation.descansadas IS NOT NULL and col.fecha_egreso IS NOT NULL then
        ( DATEDIFF(col.fecha_egreso, col.fecha_ingreso) * 0.082) -  vacation.descansadas
       end as diasdisponibles,

        col.id_colaborador,
        col.id_colaborador
        FROM
            colaboradores col 
            LEFT JOIN sucursal suc ON col.id_sucursal = suc.id_sucursal
        LEFT JOIN(
            SELECT
                id_colaborador,
                SUM(total_dias) AS descansadas
            FROM
                vacaciones  where estado = 'descansadas' or estado = 'pagadas'
            GROUP BY
                id_colaborador
        ) AS vacation
    ON
        vacation.id_colaborador = col.id_colaborador
    
";
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

        $scur = ($session->cargo == "Administrador") ?  " $sql col.estado=1":" $sql col.cargo <> 'Administrador' and col.estado=1";
        $sqlTot .= $scur;
        $sqlRec .= $scur;

          $sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";
      
          $vacaciones = new VacacionesModel($this->adapter);
          $all_vacaciones = $vacaciones->getListadoVacaciones($sqlRec);
        
          $totalRecords = $vacaciones->TotalRecord($sqlTot);

               $json_data = array(
            "draw" => intval($params['draw']),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data" => $all_vacaciones
        );
        echo json_encode($json_data);

    }

    public function ver(){
        $this->view("ver_vacaciones", array());
    }

    public function crearVacacion(){

        $id_colaborador = $_POST['id_colaborador'];
        $dias = $_POST['dias'];
        $fecha_inicio = $_POST['fecha_inicio_submit'];
        $fecha_final = $_POST['fecha_final_submit'];
        $comentario = $_POST['comentario'];
        $estado = $_POST['radio'];

        $vacaciones = new Vacaciones($this->adapter);
       
        $vacaciones->setEstado($estado);
        $vacaciones->setFechaInicio($fecha_inicio);
        $vacaciones->setFechaFinal($fecha_final);
        $vacaciones->setTotalDias($dias);
        $vacaciones->setComentario($comentario);
        $vacaciones->setIdColaborador($id_colaborador);
        $resp = $vacaciones->put($vacaciones);
       
        if($resp)
        echo json_encode(1);
        else
        echo json_encode(0);

           
    }

    public function vacacionesByIdColaborador(){
        $id_colaborador = $_POST['id'];
        $vacaciones = new VacacionesModel($this->adapter);
        $all_vacaciones = $vacaciones->vacacionesByIdColaborador($id_colaborador);
        echo json_encode($all_vacaciones);
    }

    public function eliminar(){
       
        if($this->cargo != "Administrador" && $this->cargo != "Gerente"){
            echo json_encode(array(
                'respuesta' => false,
                'data' => '',
                'mensaje' => 'No tiene permisos para eliminar, contacta al administrador'
            ));
            return;
        }

        $id = $_POST['id_delete'];  
        $vacaciones = new VacacionesModel($this->adapter);
        $resp = $vacaciones->delete($id);
        if($resp)
         echo json_encode(array(
                'respuesta' => true,
                'data' => '',
                'mensaje' => 'Regsitro eliminado con exito'
         ));
        else
        {
            echo json_encode(array(
                'respuesta' => false,
                'data' => '',
                'mensaje' => 'Error al eliminar'.$resp));
        }     
    }

public function getNumDiasFeriados(){
    $id_sucursal = $_POST['id_sucursal'];
    $fecha_inicio = date("Y-m-d", strtotime($_POST['fecha_inicio']));
    $fecha_final = date("Y-m-d", strtotime($_POST['fecha_final']));
    $vacaciones = new VacacionesModel($this->adapter);
    $dias = $vacaciones->getNumDiasFeriados($fecha_inicio,$fecha_final,$id_sucursal);
   echo json_encode($dias);
}
   
}
?>
