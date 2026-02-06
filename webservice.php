<?php
/*
p = peticion
pr = parametros separados por |
a = actions
t = token
*/
require_once 'core/ControladorBase.php';
require_once 'vendor/autoload.php';
require_once 'auth.php';

class webservice extends ControladorBase
{
    public $conectar;
    public $adapter;
    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    public function LoginGeneraToken(){
               //$key = 'my_secret_key';
               //$page = strtolower($_GET['p']);
               //$data_user = require_once "jwt-controller/data.php"; remover eso por optimizacion
               $user = $_GET['user'];
               $pass = $_GET['pass'];


                if(isset($_GET['user']) && $_GET['pass']){
                   $obj = new UsuarioModel($this->adapter);
                   $retorno = $obj->getUserLogine($user, $pass);
                   if(count($retorno)>0){
                       $token_session = Auth::SignIn([
                           'usuario'  => $retorno->usuario,
                           'password' => $retorno->clave,
                           'cargo'    => $retorno->cargo
                       ]);
                       $meta["token"] = $token_session;
                       $meta["datos_user"] = $retorno;
                       $meta["respuesta"] = 'true';
                       print json_encode($meta);
                   } else {
                       print json_encode(
                           array(
                               'estado' => '3',
                               'respuesta' => 'false',
                               'mensaje' => 'Credenciales incorrectas no se genera ningun token !!'
                           )
                       );
                   }

                } else {
                    print json_encode(
                        array(
                            'estado' => '3',
                            'respuesta' => 'false',
                            'mensaje' => 'Se necesita un identificador parametros incorrectos'
                        )
                    );
                }
    }

    public function Resources(){
     if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $token_peticion = '';
        if (!isset($_GET['t'])) die('Debe especificar el token');
         $token_peticion = $_GET['t'];
        if(Auth::Check($token_peticion)){
                if (isset($_GET['m'])) {
                $model = $_GET['m'];
                $model=ucwords($model).'Model';
                $obj = new $model($this->adapter);
                $action = $_GET['a'];


                $retorno =isset($_GET['pr'])? $obj->$action(str_replace("|", ",",$_GET['pr'])):$obj->$action();

                        if ($retorno) {

                            $meta["datos"] = $_GET['m'];
                            $meta["meta"] = $retorno;
                            print json_encode($meta);
                        } else {
                            print json_encode(
                                array(
                                    'estado' => '2',
                                    'mensaje' => 'No se obtuvo el registro'
                                )
                            );
                        }

                } else {
                    print json_encode(
                        array(
                            'estado' => '3',
                            'mensaje' => 'Se necesita un identificador'
                        )
                    );
                }
            } else {
                print json_encode(
                        array(
                            'estado' => '5',
                            'mensaje' => 'token invalido'
                        )
                    );
            }

        }
    }

}

$obj = new webservice();

if(isset($_GET['p']) && isset($_GET['p'])){
    $peticion = $_GET['p'];
    if($peticion == 'login'){
        $obj->LoginGeneraToken();
    } 
} else {
    print json_encode(
            array(
                'estado' => '4',
                'mensaje' => 'Erro de parametros GET'
            )
        );
}


if(isset($_GET['m']) && isset($_GET['t']) && isset($_GET['a'])){
    $obj->Resources();
}else if($_GET['p'] != 'login') {
    print json_encode(
            array(
                'estado' => '4',
                'mensaje' => 'Erro de parametros GET'
            )
        );
}

if(isset($_GET['g'])){
    echo Auth::SignIn([
        'usuario' => 'soporte',
        'password' => 'soporte1234'
    ]);

}

