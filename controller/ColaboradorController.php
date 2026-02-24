<?php
require_once 'core/Utils.php';

class ColaboradorController extends ControladorBase{
    public $conectar;
    public $adapter;
    

    public function __construct(){
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();

    }
    public function index(){
		$this->view("ver_colaborador",array());
    }

    public function agregar(){
        $role = $_SESSION['Login_View']->role;
        $where = $role =='sudo' ? '' : ' where id_role <> 1';
        $roles = new RolesModel($this->adapter);
        $this->view("agregar_colaborador", ["roles" => $roles->getRoles($where)]);
    }
    

    public function listado()
    {
        $session =$_SESSION["Login_View"];

        $params = $columns = $totalRecords = $data = array();

        $params = $_REQUEST;

       $columns = array(
            0 => 'col.nombre',
            1 => 'col.nombre',
            2 => 'col.apellido',
            3 => 'col.codigo',
            4 => 'col.cedula',
            5 => 'col.telefono',
        );

        $where_condition = $sqlTot = $sqlRec = "";

        if(!empty($params['search']['value']) ) {
            $where_condition .= " WHERE ";
            $where_condition .= " ( col.nombre LIKE '%".$params['search']['value']."%' ";
            $where_condition .= " OR col.apellido LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR col.codigo LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR col.cedula LIKE '%".$params['search']['value']."%'";
            $where_condition .= " OR col.telefono LIKE '%".$params['search']['value']."%'";
          
        }

        $sql_query = "SELECT col.id_colaborador,
                             col.nombre,
                             col.apellido,
                             col.codigo,
                             col.cedula,
                             col.telefono
                              FROM colaboradores col inner join usuarios u on col.id_colaborador=u.id_usuario inner join roles r on u.id_role=r.id_role";
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
    }
    public function ver_datos()
    {
       
        if (isset($_GET["obj"])) {
            $id_col = $_GET["obj"];
            $data_col = new colaboradorModel($this->adapter);
            $colaborador = $data_col->getDataColaborador($id_col);
            $button = "<div id='inferior'>
            <a class='link'  href='index.php?controller=colaborador&action=index'><input type='button' value='Regresar' class='btn btn-primary'/></a>
            <a href='#' onclick=\"realizarBorrado('Colaborador', " . $colaborador->id_colaborador . ")\"><input type='button' value='Desactivar' class='btn btn-danger '/></a>
            <a href=\"#\" onclick=\"validarEnviar('colaborador_form','Colaborador','update'," . $colaborador->id_colaborador . ");\"><input type='button' value='Actualizar' class='btn btn-success'/></a>
            </div>";
            $role = $_SESSION['Login_View']->role;
            $roles = new RolesModel($this->adapter);
             $where = $role =='sudo' ? '' : ' where id_role <> 1';
            $roles = $roles->getRoles($where);
            
            $this->view("datos_colaborador", array("colaborador" => $colaborador,"roles" => $roles,"button" => $button));
        }else {
            echo "error";
        }
    }

    public function getObjetColaborador(){
        $fecha   =date('Y/m/d', strtotime($_POST['fecha_ingreso']));
        $fechaEgreso = isset($_POST["fecha_egreso"])? date('Ymd' , strtotime($_POST["fecha_egreso"])) : date('Ymd');

        $colaborador = new Colaborador($this->adapter);
        $colaborador->setNombre($_POST["nombre"]);
        $colaborador->setApellido($_POST["apellido"]);
        $colaborador->setFechaIngreso($fecha);
        $colaborador->setFechaEgreso($fechaEgreso);
        $colaborador->setSexo($_POST["sexo"]);
        $colaborador->setCedula($_POST["cedula"]);
        $colaborador->setDireccion($_POST["direccion"]);
        $colaborador->setTelefono($_POST["telefono"]);
        $colaborador->setIdSucursal(1);
        $id = $colaborador->getIdNow('id_colaborador');
        $codigo = isset($_POST["codigo"])?'none':$colaborador->generarCodeColaborador($colaborador->nombre, $colaborador->apellido, $id);
        $colaborador->setCodigo($codigo);
        $colaborador->setEstado("1");

        return $colaborador;
    }

    public function getObjetUser(){
        $img_old = isset($_POST["img_old"])?$_POST["img_old"]:'user.png';
        $user = new Usuario($this->adapter);
        $colaborador = new Colaborador($this->adapter);
        $pass = $_POST['password'] == ''?'none':password_hash($_POST['password'], PASSWORD_BCRYPT);
        $passHash = $pass ;
        $user->setUsuario($_POST["usuario"]);
        $user->setClave($passHash);
        $img = $_FILES['imagen']['size']!=0? $this->SubirImagenRuta($_FILES['imagen'],"fotos_colaborador"):$img_old;
        $user->setImagen($img);
        $id = (isset($_GET["id"]))?'none':$colaborador->getIdEnd('id_colaborador');
        $user->setIdColaborador($id);
        $user->setIdRole($_POST["id_role"]);

        return $user;
    }

    public function crear(){

        $save_c = false;
        $save_p = false;
        if (isset($_POST["nombre"])) {
            $obj_c = $this->getObjetColaborador();
            $save_c = $obj_c->put($obj_c);

			if($save_c==1)
			 $obj_c->SetLogFile("Colaborador","NuevoColaborador",$obj_c->getNombre()." ".$obj_c->getApellido());
		else
			 $obj_c->SetLogFile("Error","NuevoColaborador",$save_c);

        }
        if (isset($_POST["usuario"])){
            $obj_p = $this->getObjetUser();
            $save_p = $obj_p->put($obj_p);

			if($save_p==1)
			 $obj_p->SetLogFile("Colaborador","NuevoUsuario",$obj_p->getUsuario());
		else
			 $obj_p->SetLogFile("Error","NuevoUsuario",$save_p);

        }

        if (($save_c == 1 && $save_p == 1 ) || $save_c == 1 )
        {
            $resp = array(
                'respuesta' => 'true',
                'data' => '',
                'mensaje' => 'Exito, el registro se guardo exitosamente! ya puede revisarlo en ver Colaboradores.'
            );
            $json = json_encode($resp);

            echo($json);
        } else
        {
            $resp = array(
                'respuesta' => 'false',
                'data' => '',
                'mensaje' => 'ERROR. NO se puedo ingresar el '.$_POST['cargo'].' no se guardaron los cambios ocurrio un error interno.'
            );
            $json = json_encode($resp);

            echo($json);
        }

    }

        public function delete(){
        $session = $_SESSION['Login_View'];
  
        if(isset($_GET["id"])){
            $id=(int)$_GET["id"];
            $colaborador=new Colaborador($this->adapter);
            $this->setNone($colaborador);
            $colaborador->setIdColaborador($id);
            $colaborador->setNombre('none');    
            $colaborador->setApellido('none');
            $colaborador->setCodigo('none');
            $colaborador->setCedula('none');
            $colaborador->setDireccion('none');
            $colaborador->setTelefono('none');
            $colaborador->setFechaIngreso('none');
            $colaborador->setFechaEgreso('none');
            $colaborador->setIdSucursal('none');

            $colaborador->setEstado('0');
          //  $colaborador->setIdColaborador($id);

			$delete = $colaborador->updateById($id,'colaborador',$colaborador);
           

           // $delete = $colaborador->deleteById($id,'colaborador');

             if ($delete == 1 ) {
                $resp =array("respuesta"=>"true","data"=>"","mensaje"=>"Exito, el registro se guardo exitosamente! ya puede revisarlo en ver Colaboradores.");
                $json = json_encode($resp);
                echo($json);
            } else
                {
       
                $resp =array("respuesta"=>"false","data"=>"","mensaje"=>$delete);
                $json = json_encode($resp);
                echo($json);
            }
            }
    }

    public function update()
    {
		//error_reporting(0);
        $session = $_SESSION['Login_View'];

        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];
            $obj_field = $this->getObjetColaborador();
            $obj_field->setIdColaborador($id);
            $update = $obj_field->updateById($id, 'colaborador', $obj_field);
            $obj_user = $this->getObjetUser();
            $obj_user->setIdUsuario('none');
           
            $img_old = $_POST["img_old"];
            if($_FILES['imagen']['size']!=0 && !empty($img_old) && $img_old != 'Sin imagen'){
                $this->eliminarImagen($img_old,"fotos_colaborador");
            }
            $update_2 = $obj_user->updateById($id, 'colaborador', $obj_user);
            //print_r($obj_field);

          //  echo "update1=".$update." update2=".$update_2;

            if (($update == 1 && $update_2 == 1 ) || $update == 1 )
              {
                $resp = array(
                    'respuesta' => 'true',
                    'data' => '',
                    'mensaje' => 'Exito, Los cambios se guardaron exitosamente!'
                );
                $json = json_encode($resp);

                echo($json);
            } else
            {
                $resp = array(
                    'respuesta' => 'false',
                    'data' => '',
                    'mensaje' => $update_2
                );
                $json = json_encode($resp);

                echo($json);
            }

        }
    }
}
?>
