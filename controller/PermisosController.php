<?php
class PermisosController extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $url ;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    public function getObjetPermisos($type = 'add')
    {
        $exito= '';
        $count= $_POST['count'];
        $obj = new Permisos($this->adapter);
        for($x = 0; $x<$count;$x++){
            $c=0;
            $r=0;
            $u=0;
            $d=0;
            $id_modules = $_POST['id_modules_'.$x];
            if(isset($_POST['c_'.$x])) $c=1;
            if(isset($_POST['r_'.$x])) $r=1;
            if(isset($_POST['u_'.$x])) $u=1;
            if(isset($_POST['d_'.$x])) $d=1;
            $obj->permisos = $c.','.$r.','.$u.','.$d;
            $obj->id_modules = $id_modules;
            $obj->id_usuarios = $_POST['id_user'];
            if($type == 'add'){
                $exito = $obj->put($obj);
            }
            else {
                $id_permisos = $_POST['id_permisos_'.$x];
                $obj->id_permisos = 'none';
                $exito = $obj->updateById($id_permisos,'permisos',$obj);
            }

        }
        return $exito;
    }

    public function getObjetSubModulesPermisos()
    {
        $exito = '';
        $count = $_POST['count'];
        $obj = new Modules($this->adapter);
        for ($x = 0; $x < $count; $x++) {
            $id_modules = $_POST['id_modules_' . $x];
            if (isset($_POST['r_' . $x])) $r = 'children'; else $r = '';
            $obj->type = $r;
            $obj->id_modules = $id_modules;
            $obj->label = 'none';
            $obj->font_icon = 'none';
            $obj->modules_url = 'none';
            $obj->id_parent = 'none';
            $obj->sort = 'none';
            $exito = $obj->updateById($id_modules, 'modules', $obj);
        }
        return $exito;
    }

    public function index()
    {
        $this->view("ver_permisos", array("permisos" => ''));
    }
    public function bloqueos()
    {
        $this->view("ver_bloqueos", array("permisos" => ''));
    }

    public function perfiles()
    {
        $this->view("ver_perfiles", array("permisos" => ''));
    }
    public function listado()
    {
        $session =$_SESSION["Login_View"];

        $params = $columns = $totalRecords = $data = array();

        $params = $_REQUEST;

        $columns = array(
            0 => 'u.usuario',
            1 => 'c.nombre',
            2 => 'c.apellido',
            3 => 'c.cargo',
        );

        $where_condition = $sqlTot = $sqlRec = "";

        if(!empty($params['search']['value']) ) {
            $where_condition .= " WHERE ";
            $where_condition .= "upper(concat(c.nombre,' ',c.apellido)) LIKE upper('%".$params['search']['value']."%') ";
        }

        $sql_query = "SELECT c.id_colaborador,
                            u.usuario,
                            c.nombre,
                            c.cargo
                            FROM usuarios u inner join colaboradores c on u.id_colaborador = c.id_colaborador";
        $sqlTot .= $sql_query;
        $sqlRec .= $sql_query;

        $sql ="";
        $sqlTot .= $where_condition;
        $sqlRec .= $where_condition;


        $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

        $prestamos     = new PrestamoModel($this->adapter);
        $all_prestamos = $prestamos->getListadoIndex($sqlRec);
        $totalRecords = $prestamos->TotalRecord($sqlTot);

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $all_prestamos
        );
        echo json_encode($json_data);
    }
    public function listadoBloqueo()
    {
        $session =$_SESSION["Login_View"];

        $params = $columns = $totalRecords = $data = array();

        $params = $_REQUEST;

        $columns = array(
            0 => 'u.usuario',
            1 => 'c.nombre',
            2 => 'c.apellido',
            3 => 'ss.id_session',
        );

        $where_condition = $sqlTot = $sqlRec = "";

        if(!empty($params['search']['value']) ) {
            $where_condition .= " WHERE ";
            $where_condition .= "upper(concat(c.nombre,' ',c.apellido)) LIKE upper('%".$params['search']['value']."%') ";
        }

        $sql_query = "SELECT u.id_user, u.usuario, c.nombre, c.cargo, IF(ss.id_session is null ,'', 'checked') 
                      FROM usuarios u inner join colaboradores c on u.id_colaborador = c.id_colaborador LEFT JOIN sessiones ss on ss.id_usuario = u.id_user";
        $sqlTot .= $sql_query;
        $sqlRec .= $sql_query;

        $sql ="";
        $sqlTot .= $where_condition;
        $sqlRec .= $where_condition;


        $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

        $prestamos     = new PrestamoModel($this->adapter);
        $all_prestamos = $prestamos->getListadoIndex($sqlRec);
        $totalRecords = $prestamos->TotalRecord($sqlTot);

        $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $all_prestamos
        );
        echo json_encode($json_data);
    }

    public function viewCrear()
    {
        $obj = new Modules($this->adapter);
        $permisos = $obj->getBy('type','parent');;
        $this->view("agregar_permisos", array("modules" => $permisos));
    }

    public function viewEditSubModulo()
    {
        $id = $_GET['id'];
        $nombre = $_GET['nombre'];
        $id_colaborador = $_GET['id_colaborador'];
        $obj = new ModulesModel($this->adapter);
        $modules = $obj->getChildrenByIdParents($id);
        $this->view("datos_sub_modulos", array("modules" => $modules,"nombre" => $nombre,"id"=>$id,"id_colaborador"=>$id_colaborador));
    }

    public function viewEdit(){
        $user = new UsuarioModel($this->adapter);
        $obj = new PermisosModel($this->adapter);
        $name = $_GET['id_colaborador'];
        $user = $user->getBy('id_colaborador',$name);
        $modules = $obj->getModulesPermisos($name);
        $this->view("datos_permisos", array("modules" => $modules,"user"=>$user,"id_colaborador"=>$name));
    }

    public function viewEditPerfil(){
        $user = new UsuarioModel($this->adapter);
        $obj = new PermisosModel($this->adapter);
        $name = $_GET['id_colaborador'];
        $user = $user->getBy('id_colaborador',$name);
        $modules = $obj->getModulesPermisos($name);
        $perfil = $obj->getPerfilByIdColaborador($name);

        $this->view("datos_perfiles", array("modules" => $modules,"user"=>$user,"perfil"=>$perfil[0]->perfil_u,"id_perfil"=>$perfil[0]->id_perfil));
    }
    //plantilla para generar el dataTable
    public function buildData(){
        $table = 'modules';
        $prt = Utils::getParameterGET($_GET['url']);
        $this->url = Utils::encrypt_url("controller=permisos&action=viewEdit&permisos=".$prt['permisos']);


        $primaryKey = 'id_modules';
        $columns = array(
            array( 'db' => 'usuario', 'dt' => 0 , 'field' => 'usuario',
                'formatter' => function( $d, $row ) {
                    return "<a href='index.php?url=".$this->url."&name=".$d."' data-toggle='modal' data-target='#myModal' data-remote='false'>".$d."</a>";
                }
            ),
            array( 'db' => 'c.nombre',  'dt' => 1 ,'field' => 'nombre' ),
            array( 'db' => 'c.nombre',  'dt' => 2 ,'field' => 'nombre' ),
            array( 'db' => 'c.id_colaborador',  'dt' => 3 ,'field' => 'id_colaborador' )
        );
        $sql_details = require_once 'config/db_datatable.php';

        $joinQuery = " 
        FROM usuarios u inner join colaboradores c on u.id_colaborador = c.id_colaborador
        ";
        $extraWhere = "";

        $this->buildJsonDatatable($sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere);
    }

    public function ver_datos()
    {
        $origen=isset($_GET["origen"]) ? $_GET["origen"]:'';
        $id_sucursal=isset($_GET["sucursal"]) ? $_GET["sucursal"]:'';

        if (isset($_GET["obj"])) {
            $obj = $_GET["obj"];
            $datos_clientes = json_decode($obj);
            $obj_prestamos = new PrestamoModel($this->adapter);
            $prestamos = $obj_prestamos->getPrestamosCliente($datos_clientes->id_cliente);
            $this->view("datos_clientes", array("all_clientes" => $datos_clientes,"prestamos_cliente"=>$prestamos,"origen"=>$origen,"sucursal"=>$id_sucursal));
        }else {
            echo "error";
        }
    }

    public function crear()
    {
        $res = new PermisosModel($this->adapter);
        $res = $res->getBy('id_usuarios', $_POST['id_user']);
        if(empty($res)){
            $save = $this->getObjetPermisos();
            if ($save == 1) {	echo true;} else {echo $save;}
        } else {
            echo "Este usuario ya tiene permisos asignados si desea modificarlos cierre esta ventana modal ";
        }


    }

    public function delete()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $obj = new PermisosModel($this->adapter);
            $delete = $obj->deleteBy("id_usuarios",$id);
            if ($delete==1) {
                echo true;
                //$cliente->SetLogFile("Cliente","EliminarCliente",$micliente[0]->nombre." ".$micliente[0]->apellido);
            } else {
                echo false;
                //$cliente->SetLogFile("Error","EliminarCliente",$delete);

            }
        }
    }

    public function update()
    {
        $save = $this->getObjetPermisos('update');
        if ($save == 1) {	echo true;} else {echo $save;}
    }
    public function updateSubModules()
    {
        $save = $this->getObjetSubModulesPermisos();
        if ($save == 1) {
            $resp = array(
                'respuesta' => 'true',
                'data' => '',
                'mensaje' => 'Exito!. Se actualizo de forma correcta'
            );
            $json = json_encode($resp);
            echo($json);
        } else {
            $resp = array(
                'respuesta' => 'false',
                'data' => '',
                'mensaje' => 'Error!. El no se pudo actualizar'
            );
            $json = json_encode($resp);
            echo($json);
        }
    }
    public function updatePerfil()
    {
        $perfil = new Perfiles($this->adapter);
        $perfil->setPerfil($_POST["perfil"]);
        $perfil->setIdPerfil($_POST['id_perfil']);
        $perfil->setIdUsuario($_POST["id_user"]);
        $obj = new PerfilesModel($this->adapter);
        $obj_u = new UsuarioModel($this->adapter);

        if($obj->tienePerfil($_POST["id_user"])){
            $save = $perfil->updateById($_POST["id_user"],'usuario',$perfil);
            $obj_u->updateTableById($_POST["id_user"],'perfil','id_user',1);

        } else {
            $save = $perfil->put($perfil);
            $obj_u->updateTableById($_POST["id_user"],'perfil','id_user',1   );
        }

        if ($save == 1) {
            $resp = array(
            'respuesta' => 'true',
            'data' => '',
            'mensaje' => 'Exito, se perfil fue asignado correctamente.'
        );
            $json = json_encode($resp);

            echo ($json);

        } else { $resp = array(
            'respuesta' => 'false',
            'data' => '',
            'mensaje' => 'Error.'
        );
            $json = json_encode($resp);

            echo ($json);
        }
    }


    public function getData()
    {
        $clientes = new Clientes($this->adapter);
        $all_clientes = $clientes->getAll();
    }
    public function getClintesResumenBydate()
    {
        $fecha_bengin    = strlen($_GET['date_start']) !=10? date('Y-m-d', strtotime($_GET['date_start'])):$_GET['date_start'];
        $fecha_end       = strlen($_GET['date_end']) !=10? date('Y-m-d', strtotime($_GET['date_end'])):$_GET['date_end'];
        $obj = new ClientesModel($this->adapter);
        $result =$obj->getDesembolsosResumenBydate($fecha_bengin,$fecha_end);
        echo json_encode($result);
    }

    public function dataByComboCliente(){
        $login = $_SESSION["Login_View"];
        $cliente = '';
        if($login->cargo == "Analista"){
            $cliente = new Clientes($this->adapter);
            $all_clientes=$cliente->getBy("id_colaborador",$login->id_colaborador);
        }else{
            $cliente = new ClientesModel($this->adapter);
            $all_clientes=$cliente->getClientesBySucursal($login->id_sucursal);
        }
        $all_clientes = is_object($all_clientes) ? [$all_clientes]:$all_clientes;
        echo json_encode($all_clientes);
    }

    public function searcheCaseInsensitevi()
    {
        $searche = $_POST['b'];
        if (!empty($searche))
        {
            $this->searche($searche);
        }
    }

    function searche($b)
    {
        $login = $_SESSION["Login_View"];
        $obj = new ClientesModel($this->adapter);
        $result =$obj->getClientesByNombre($b,$login->id_sucursal,$login->cargo);
        if ($result=='') {
            //$msg = ["msg"=>"<span class='txRed'>No se han encontrado resultados para </span>'<b>" . $b . "</b>'."];
            echo json_encode($msg);
        } else {
            echo json_encode($result);
        }
    }
    public function viewEditReporte(){
		$id_colaborador = $_GET["id_colaborador"];
		$modPermiso= new ModulePermisoReporteModel($this->adapter);
		$reporte = new ModulesModel($this->adapter);
		$data = $reporte->getModuloReporte();
		$dataReporteAsigando =  $modPermiso->getDataReporteAsignado($id_colaborador);
		 $this->view("edit_permisos_reporte", array("modules" => $data,"reporteasig"=>$dataReporteAsigando));
	}
	public function viewCrearReportePermiso(){
		$reporte = new ModulesModel($this->adapter); //getModuloReporte
		$data = $reporte->getModuloReporte();
        $this->view("agregar_permisos_reporte", array("modules" => $data));
	}
	public function addPerReporte(){
		$list_mp = $_POST["modulo_prestamos"];
		$list_mg = $_POST["modulo_gestores"];
		$list_mc = $_POST["modulo_clientes"];
		$id_user = $_POST["id_user"];
		//$login = $_SESSION["Login_View"];
		$modPermiso= new ModulePermisoReporte($this->adapter);
		$existe =  $modPermiso->getBy("id_usuario", $id_user);
		$sms="";
		$save="";
		if(empty($existe)){
		if(isset($_POST["mod_Prestamos"])){
			//echo "Modulo Prestamos";
			$id_modulos_parent = $_POST["mod_Prestamos"];
			if($list_mp != ""){
				$list = explode(",",$list_mp);
				$count = count($list);
				for($i=0;$i<$count;$i++){
					if($i == 0){
						$id_module_child = $_POST[$list[$i]];
					 }else{
						$id_module_child .=",".$_POST[$list[$i]];
					 }
				}
				
				$modPermiso->setIdPermiso("0");
				$modPermiso->setIdModuloParent($id_modulos_parent);
				$modPermiso->setIdModuloChild($id_module_child);
				$modPermiso->setIdUsuario($id_user);
				$save = $modPermiso->put($modPermiso);
				if($save == 1){
					$sms= "MOdulo Prestamo Correcto";
				}else{
					$sms= $save;
				}
			}
		}
		if(isset($_POST["mod_Gestores"])){
		//echo "Modulo Gestores";
			$id_modulos_parent = $_POST["mod_Gestores"];
			if($list_mg != ""){
			//echo "Modulo Gestores lista llena";
				$list = explode(",",$list_mg);
				$count = count($list);
				for($i=0;$i<$count;$i++){
					if($i == 0){
						$id_module_child = $_POST[$list[$i]];
					 }else{
						$id_module_child .=",".$_POST[$list[$i]];
					 }
				}
				//$modPermiso= new ModulePermisoReporte($this->adapter);
				$modPermiso->setIdPermiso("0");
				$modPermiso->setIdModuloParent($id_modulos_parent);
				$modPermiso->setIdModuloChild($id_module_child);
				$modPermiso->setIdUsuario($id_user);
				$save = $modPermiso->put($modPermiso);
				if($save == 1){
					$sms= ($sms  != "") ? $sms."--MOdulo Gestores Correcto":"MOdulo Gestores Correcto";
				}else{
					$sms.= $save;
				}
			}
		}
		if(isset($_POST["mod_Clientes"])){
		//echo "Modulo Cleintes";
			$id_modulos_parent = $_POST["mod_Clientes"];
			if($list_mc != ""){
				$list = explode(",",$list_mc);
				$count = count($list);
				for($i=0;$i<$count;$i++){
					if($i == 0){
						$id_module_child = $_POST[$list[$i]];
					 }else{
						$id_module_child .=",".$_POST[$list[$i]];
					 }
				}
				//$modPermiso= new ModulePermisoReporte($this->adapter);
				$modPermiso->setIdPermiso("0");
				$modPermiso->setIdModuloParent($id_modulos_parent);
				$modPermiso->setIdModuloChild($id_module_child);
				$modPermiso->setIdUsuario($id_user);
				$save = $modPermiso->put($modPermiso);
				if($save == 1){
					$sms= ($sms  != "") ? $sms."--MOdulo Cleintes Correcto":"MOdulo Cleintes Correcto";
				}else{
					$sms.= $save;
				}
			}
		}
			
		 if ($save == 1) {
            $resp = array(
            'respuesta' => 'true',
            'data' => '',
            'mensaje' => $sms
        );
            $json = json_encode($resp);

            echo ($json);

        } else { $resp = array(
            'respuesta' => 'false',
            'data' => '',
            'mensaje' => 'Error.'
        );
            $json = json_encode($resp);

            echo ($json);
        }
	}else{
			$resp = array(
            'respuesta' => 'false',
            'data' => '',
            'mensaje' => '<br />Error.Ya existe asigando reportes a este usuario'
        );
            $json = json_encode($resp);

            echo ($json);
		}
	}
	public function updatePerReporte(){
		$list_mp = $_POST["modulo_prestamos"];
		$list_mg = $_POST["modulo_gestores"];
		$list_mc = $_POST["modulo_clientes"];
		$id_user = $_POST["id_user"];
		//capturar los id permiso para la actualizacion o ingreso
		$id_permiso_prestamos =$_POST["id_permiso_prestamos"];
		$id_permiso_gestores =$_POST["id_permiso_gestores"];
		$id_permiso_clientes =$_POST["id_permiso_clientes"];
		
		$modPermiso= new ModulePermisoReporte($this->adapter);
		$existe =  $modPermiso->getBy("id_usuario", $id_user);
		$sms="";
		$save="";
		//if(empty($existe)){
		if(isset($_POST["mod_Prestamos"])){
			$id_modulos_parent = $_POST["mod_Prestamos"];
			if($list_mp != ""){
				$list = explode(",",$list_mp);
				$count = count($list);
				for($i=0;$i<$count;$i++){
					if($i == 0){
						$id_module_child = $_POST[$list[$i]];
					 }else{
						$id_module_child .=",".$_POST[$list[$i]];
					 }
				}
				
				if($id_permiso_prestamos != ""){ 
				//id permiso prestamo es diferente de null se actualiza
					$modPermiso->setIdPermiso("none");
					$modPermiso->setIdModuloParent("none");
					$modPermiso->setIdModuloChild($id_module_child);
					$modPermiso->setIdUsuario("none");
					//$save = $modPermiso->put($modPermiso);
					$save = $modPermiso->updateById($id_permiso_prestamos,"permiso", $modPermiso);
					if($save == 1){
						$sms= "MOdulo Prestamo se actualizo correctamente";
					}else{
						$sms= $save;
					}
				}else{
					//si no se ingresa como nuevo
					$modPermiso->setIdPermiso("0");
					$modPermiso->setIdModuloParent($id_modulos_parent);
					$modPermiso->setIdModuloChild($id_module_child);
					$modPermiso->setIdUsuario($id_user);
					$save = $modPermiso->put($modPermiso);
					//$save = $modPermiso->updateById($id_permiso_prestamos,"permiso", $modPermiso);
					if($save == 1){
						$sms= "MOdulo Prestamo se actualizo correctamente";
					}else{
						$sms= $save;
					}
				}
			}
		}
		if(isset($_POST["mod_Gestores"])){
		//echo "Modulo Gestores";
			$id_modulos_parent = $_POST["mod_Gestores"];
			if($list_mg != ""){
			//echo "Modulo Gestores lista llena";
				$list = explode(",",$list_mg);
				$count = count($list);
				for($i=0;$i<$count;$i++){
					if($i == 0){
						$id_module_child = $_POST[$list[$i]];
					 }else{
						$id_module_child .=",".$_POST[$list[$i]];
					 }
				}
				if($id_permiso_gestores != ""){
					$modPermiso->setIdPermiso("none");
					$modPermiso->setIdModuloParent("none");
					$modPermiso->setIdModuloChild($id_module_child);
					$modPermiso->setIdUsuario("none");
					//$save = $modPermiso->put($modPermiso);
					$save = $modPermiso->updateById($id_permiso_gestores,"permiso", $modPermiso);
					if($save == 1){
						$sms= ($sms  != "") ? $sms."--MOdulo Gestores se actualizo correctamente":"MOdulo Gestores se actualizo correctamente";
					}else{
						$sms.= $save;
					}
				}else{
					$modPermiso->setIdPermiso("0");
					$modPermiso->setIdModuloParent($id_modulos_paren);
					$modPermiso->setIdModuloChild($id_module_child);
					$modPermiso->setIdUsuario($id_user);
					$save = $modPermiso->put($modPermiso);
					//$save = $modPermiso->updateById($id_modulos_parent,"modulos_parent", $modPermiso);
					if($save == 1){
						$sms= ($sms  != "") ? $sms."--MOdulo Gestores se actualizo correctamente":"MOdulo Gestores se actualizo correctamente";
					}else{
						$sms.= $save;
					}
				}
			}
		}
		if(isset($_POST["mod_Clientes"])){
		//echo "Modulo Cleintes";
			$id_modulos_parent = $_POST["mod_Clientes"];
			if($list_mc != ""){
				$list = explode(",",$list_mc);
				$count = count($list);
				for($i=0;$i<$count;$i++){
					if($i == 0){
						$id_module_child = $_POST[$list[$i]];
					 }else{
						$id_module_child .=",".$_POST[$list[$i]];
					 }
				}
				if($id_permiso_clientes != ""){
						$modPermiso->setIdPermiso("none");
						$modPermiso->setIdModuloParent("none");
						$modPermiso->setIdModuloChild($id_module_child);
						$modPermiso->setIdUsuario("none");
						//$save = $modPermiso->put($modPermiso);
						$save = $modPermiso->updateById($id_permiso_clientes,"permiso", $modPermiso);
						if($save == 1){
							$sms= ($sms  != "") ? $sms."--MOdulo Cleintes se actualizo correctamente":"MOdulo Cleintes se actualizo correctamente";
						}else{
							$sms.= $save;
						}
				}else{
				
						$modPermiso->setIdPermiso("0");
						$modPermiso->setIdModuloParent($id_modulos_parent);
						$modPermiso->setIdModuloChild($id_module_child);
						$modPermiso->setIdUsuario($id_user);
						$save = $modPermiso->put($modPermiso);
						//$save = $modPermiso->updateById($id_permiso_clientes,"permiso", $modPermiso);
						if($save == 1){
							$sms= ($sms  != "") ? $sms."--MOdulo Cleintes se actualizo correctamente":"MOdulo Cleintes se actualizo correctamente";
						}else{
							$sms.= $save;
						}
				
				}
			}
		}
			
		 if ($save == 1) {
            $resp = array(
            'respuesta' => 'true',
            'data' => '',
            'mensaje' => $sms
        );
            $json = json_encode($resp);

            echo ($json);

        } else { $resp = array(
            'respuesta' => 'false',
            'data' => '',
            'mensaje' => 'Error.'
        );
            $json = json_encode($resp);

            echo ($json);
        }
	}
	public function deletePerReporte(){
		$id = $_GET["id"];
		$modPermiso= new ModulePermisoReporte($this->adapter);
		
		$save=$modPermiso->deleteById($id, "usuario");
		
		if($save == 1){
			$resp = array(
            'respuesta' => 'true',
            'data' => '',
            'mensaje' => 'Permisos Borrados Correctamente.'
        );
            $json = json_encode($resp);

            echo ($json);
		}else{
			$resp = array(
            'respuesta' => 'false',
            'data' => '',
            'mensaje' => 'Erro al querer borrar los permisos.'.$save
        );
            $json = json_encode($resp);

            echo ($json);
		}
	}
}
?>
