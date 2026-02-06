<?php
require_once 'core/Utils.php';
error_reporting(E_ALL & ~E_DEPRECATED);
class UsuariosController extends ControladorBase
{
    public $conectar;
    public $adapter;
   // protected $permisos;
    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $obj_permisos = new UsuarioModel($this->adapter);
        if(isset($_SESSION['Login_View'])) {
            $session = $_SESSION['Login_View'];
            $id_user = $session->id_user;
            //$obj_permisos = $obj_permisos->getPermisosByModulo('Colaboradores', 17);
            //$this->permisos = $obj_permisos;
        }
    }

    public function index()
    {
        //creamos el Objeto EmpleadosModel
        //conseguimos todos los usuarios;
        $token = $this->generateFormToken("login_form");
        $this->view("login", array("token" => $token));
        //$this->redirectLogin();
    }
    public function dataByComboUsuarios(){
        $obj = new UsuarioModel($this->adapter);
        $all_colaborador=$obj->getAll();
        if(is_object($all_colaborador)) $all_colaborador = [$all_colaborador];
        echo json_encode($all_colaborador);
    }

    public function getPermisosByModulo($modulo){
        $obj = new UsuarioModel($this->adapter);
        $permisos=$obj->getPermisosByModulo($modulo);
        return $permisos;
    }

    public function getArqueoByIdColaborador($id_colaborador,$fecha){
        $obj = new UsuarioModel($this->adapter);
        $tiene_arqueo = $obj->getArqueoByIdColaborador($id_colaborador,$fecha);
        return $tiene_arqueo;
    }
    public function tieneSessionAbierta($id_colaborador){
        $obj = new UsuarioModel($this->adapter);
        $tiene_arqueo = $obj->tieneSessionAbierta($id_colaborador);
        return $tiene_arqueo;
    }
    public function borrarRegistroSession_($id_colaborador=""){
        if(isset($_POST['id_usuario'])){
            $id_colaborador = $_POST['id_usuario'];
        }

        $obj =  new ModeloBase('sessions',$this->adapter);
        $obj->borrarRegistroSession($id_colaborador);
        $resp = array(
            'respuesta' => 'true',
            'data'      =>  '',
            'mensaje'   =>  'Usuario Desbloquedo'
        );
        $json = json_encode($resp);
        echo($json);
    }
    public function registrarRegistroSession_(){
        $id_colaborador = $_POST['id_usuario'];
        $obj =  new ModeloBase('sessions',$this->adapter);
        $obj->registrarSession(date("Y-m-d"),date ("h:i:s"),"Bloqueado Por Soporte",$id_colaborador);
        $resp = array(
            'respuesta' => 'true',
            'data'      =>  '',
            'mensaje'   =>  'Usuario Bloqueado'
        );
        $json = json_encode($resp);
        echo($json);
    }
    public function arqueoCerrarSession(){
        $session = $_SESSION['Login_View'];
        $id_colaborador= $_GET['id_colaborador'];
        $hoy = date('Y-m-d');
        $obj = new UsuarioModel($this->adapter);
        $tiene_arqueo = $obj->getArqueoByIdColaborador($id_colaborador,$hoy);

        if($tiene_arqueo && ($id_colaborador == $session->id_colaborador)){
            $login=$_SESSION["Login_View"];

            $obj =  new ModeloBase('sessions',$this->adapter);

            $obj->borrarRegistroSession($login->id_user);

            date_default_timezone_set("america/managua");
            $user =$login->usuario;
            $accion="Cerrar Session";
            $hora= date ("h:i:s");
            $fecha= date ("d/m/Y");
            $string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Estado: Session Finalizada | \n";
            $fichero = fopen('logfile.txt', 'a+');

            fwrite($fichero, $string);
            fclose($fichero);

            $this->DestruirSession();
            $this->redirect();

            $resp = array(
                'respuesta' => 'true',
                'data'      =>  '',
                'mensaje'   =>  'Usted acaba de entregar el arqueo su jornada laboral a finalizado'
            );
            $json = json_encode($resp);
            echo($json);
        }
    }
    public function  exitaUserName(){
        $userName= $_GET['user_name'];
        $obj = new UsuarioModel($this->adapter);
        $existUsername = $obj->getUsername($userName);
        $existUser = $existUsername['userExits'];

        if($existUser){
            $resp = array(
                'respuesta' => 'true',
                'data'      =>  '',
                'mensaje'   =>  ''
            );
            $json = json_encode($resp);
            echo($json);
        } else {
            $resp = array(
                'respuesta' => 'false',
                'data'      =>  '',
                'mensaje'   =>  ''
            );
            $json = json_encode($resp);
            echo($json);
        }
    }
    public function crear()
    {
        /*$session = $_SESSION['Login_View'];
        if ($this->permisos['0'] == '0' && !$session->perfil) {
            Utils::getMensaje('Crear');
        }*/
        date_default_timezone_set('America/Managua');
        require_once 'core/Utils.php';
        $from = '01:00';
        $to = '19:30';
        $flat = true;
        $dateTest = new DateTime();
        $input = $dateTest->format('H:i');
        $detalles = $_POST["detalles"];
        $hoy = date('Y-m-d');

        //print_r($_POST["auth_token"]."/");
        if (isset($_POST["auth_token"])) {
            //print_r($_POST["auth_token"]);
            //echo"My token recibido es ".$_POST["auth_token"];
            //verificamos si el token recibido es correcto
            $token = $this->verifyFormToken("login_form", $_POST["auth_token"]);
            // print_r($_SESSION['csrf']['login_form_token']);
            if ($token == true) {
                //verificamos si el usuario existe.
                if (isset($_POST["user"])) {
                    //$datos= new ModeloBase("usuarios",$this->adapter);
                    $datos = new Usuario($this->adapter);
                    $login = new UsuarioModel($this->adapter);
                    $datos->setUsuario($_POST["user"]);
                    $datos->setclave($_POST["pass"]);
                    //estraer el usuario correspondiente de la solictud
                    $userLogin = $login->getUserLogine($datos->getUsuario(), $datos->getClave());
                    if(empty($userLogin)){
                        $resp = array(
                            'respuesta' => 'false',
                            'data' => '',
                            'mensaje' => 'Credenciales Incorrectas'
                        );
                        $json = json_encode($resp);
                        echo($json);
                        exit;
                    }
                    //print_r($userLogin);
                    $id_colaborador = $userLogin->id_colaborador;
                 
                    $data = $this->tieneSessionAbierta($userLogin->id_user);
                    $data_session = $data['data_session'];
                    $tiene_session = $data['tiene_session']&&($userLogin->role != 'sudo' && $userLogin->role != 'admin');
                    if($data_session != ""){
                        $misma_session = $data_session->detalles == $detalles? true : false;
                    } else {
                        $misma_session = false;
                    }
                    if (!empty($userLogin) && (($userLogin->role == 'admin' || $userLogin->role == 'super') || Utils::hourIsBetween($from, $to, $input)) && (!$tiene_session || $misma_session)) {


                        $login->AsignarSessionStar("Login_View", $userLogin);
                        //$this->redirect("menu", "index");
                        $resp = array(
                            'respuesta' => 'true',
                            'data' => '',
                            'mensaje' => 'Acceso correcto'
                        );
                        $json = json_encode($resp);
                        echo($json);
                        $login = $_SESSION["Login_View"];
                        date_default_timezone_set("america/managua");
                        $accion = "Iniciar de Session";
                        $user = $login->usuario;
                        $hora = date("h:i:s");
                        $fecha = date("d/m/Y");
                        $string = "Accion: " . $accion . " | Usuario: " . $user . " | fecha: " . $fecha . " | Hora: " . $hora . " | Estado: Session Iniciada |\n";
                        //$fichero = fopen('logfile.txt', 'a+');

                       // fwrite($fichero, $string);
                       // fclose($fichero);

                        if(!$misma_session){
                            $obj =  new ModeloBase('sessions',$this->adapter);
                            $obj->registrarSession(date("Y-m-d"),$hora,$detalles,$userLogin->id_user);
                            setcookie(
                                'session',
                                $userLogin->id_user,
                                time() + (60 * 60 * 24 * 365),
                                '/',
                                $_SERVER['HTTP_HOST']
                            );
                        }

                    } else {
                        if (!Utils::hourIsBetween($from, $to, $input)&&($userLogin->cargo != 'Administrador' &&$userLogin->cargo != 'Supervisor'&& ($userLogin->perfil_name != 'Sudo' || $userLogin->perfil_name != 'privileged'))) {
                            $resp = array(
                                'respuesta' => 'false',
                                'data' => '',
                                'mensaje' => 'Error ! Usted tiene restringido el acceso  en este horario!  horario permitido [7:00:am - 8:00PM]'
                            );
                            $json = json_encode($resp);
                            echo($json);
                            exit;
                        }
                   
                        if ($tiene_session) {
                            $resp = array(
                                'respuesta' => 'false',
                                'data' => '',
                                'mensaje' => 'Error!. usted tiene una session abierta en otro dispositivo cierre session , y luego ingrese en este dispositivo!'.$data_session->detalles
                            );
                            $json = json_encode($resp);
                            echo($json);
                            exit;
                        }
                        if (empty($userLogin)) {
                            $resp = array(
                                'respuesta' => 'false',
                                'data' => '',
                                'mensaje' => 'Error!. Usuario o Clave incorrecta intente de nuevo'
                            );
                            $json = json_encode($resp);
                            echo($json);
                            exit;
                        }
                    }
                }
            }
            //$this->redirect("Clientes", "crear");
        }
    }
}

?>
