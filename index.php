<?php
//echo "<H2>Actualizando geolocalizacion 5 min se restablecera borre cache en su dispositivo en este momento</H2>";
//exit;
session_start();

//Configuración global
require_once 'config/global.php';
//Base para los controladores
require_once 'core/ControladorBase.php';
//Funciones para el controlador frontal
require_once 'core/ControladorFrontal.func.php';
ini_set('max_execution_time', 0);


//--------------------------logica para el consultas del api del web service-------------------------
require_once "core/Auth.php";
require_once "controller/ApiPrestamoController.php";
require_once "core/Security.php";

$uri = explode("/", trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/"));
$method = $_SERVER["REQUEST_METHOD"];
//var_dump($uri);
if ($uri[0] === "api") {
    Auth::check(); // <- fuerza validación en todas las rutas

    switch ($uri[1]) {
        case "loanBalanceByCode":
             $api = new ApiPrestamoController();
            if ($method === "GET") {
                if (isset($uri[2])) {
                    $id = Security::validateLoanCode($uri[2]);
                    if ($id === false) {
                        http_response_code(400);
                        echo json_encode(["error" => "Codigo Prestamo inválido"]);
                        exit;
                    }
                    $api->getPrestamoBycode($uri[2]);
                } else {
                    echo json_encode(["error" => "Endpoint No encontrado"]);
                }
            } elseif ($method === "POST") {
                echo json_encode(["error" => "no tenemos opcion habilitado para post aun"]);
            }
            break;

        case "loanDetailsByCode":
            $api = new ApiPrestamoController();
            if ($method === "GET") {
                if (isset($uri[2])) {
                    $id = Security::validateLoanCode($uri[2]);
                    if ($id === false) {
                        http_response_code(400);
                        echo json_encode(["error" => "Codigo Prestamo inválido"]);
                        exit;
                    }
                    $api->getPlanDePagoBycode($uri[2]);
                } else {
                    echo json_encode(["error" => "Endpoint No encontrado"]);
                }
            } elseif ($method === "POST") {
                json_encode(["error" => "no tenemos opcion habilitado para post aun"]);
            }
            break;
        case "loanBalanceByCedula":
            $api = new ApiPrestamoController();
            if ($method === "GET") {
                if (isset($uri[2])) {
                    $id = Security::validateCedula($uri[2]);
                    if ($id === false) {
                        http_response_code(400);
                        echo json_encode(["error" => "Cedula de Identidad inválido"]);
                        exit;
                    }
                    $api->getPrestamoByCedula($uri[2]);
                } else {
                    echo json_encode(["error" => "Endpoint No encontrado"]);
                }
            } elseif ($method === "POST") {
                json_encode(["error" => "no tenemos opcion habilitado para post aun"]);
            }
            break;

        case "loanDetailsByCedula":
            $api = new ApiPrestamoController();
            if ($method === "GET") {
                if (isset($uri[2])) {
                    $id = Security::validateCedula($uri[2]);
                    if ($id === false) {
                        http_response_code(400);
                        echo json_encode(["error" => "Cedula de Identidad inválido"]);
                        exit;
                    }
                    $api->getPlanDePagoByCedula($uri[2]);
                } else {
                    echo json_encode(["error" => "Endpoint No encontrado"]);
                }
            } elseif ($method === "POST") {
                json_encode(["error" => "no tenemos opcion habilitado para post aun"]);
            }
            break;

        case "availabilityForLoan":
            $api = new ApiPrestamoController();
            if ($method === "GET") {
                if (isset($uri[2])) {
                    $id = Security::validateCedula($uri[2]);
                    if ($id === false) {
                        http_response_code(400);
                        echo json_encode(["error" => "Cedula de Identidad inválido"]);
                        exit;
                    }
                    $api->consultarDisponibilidadReprestamo($uri[2]);
                } else {
                    echo json_encode(["error" => "Endpoint No encontrado"]);
                }
            } elseif ($method === "POST") {
                json_encode(["error" => "no tenemos opcion habilitado para post aun"]);
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(["error" => "Endpoint no encontrado"]);
    }
}

//--------------------------logica para el consultas del api del web service-------------------------
if($uri[0] != "api") {
    if (VERSION_APP == 'depuracion') {
        $obj = new ControladorBase();
        $obj->disableCache();
    }


    if (isset($_SESSION["Login_View"])) {
        if (isset($_GET["controller"])) {
            $controllerObj = cargarControlador($_GET["controller"]);
            lanzarAccion($controllerObj);
        } else {
            $controllerObj = cargarControlador(CONTROLADOR_DEFECTO_USER);
            lanzarAccion($controllerObj);
        }
    } else {

        if (isset($_COOKIE["session"])) {
            $login = $_COOKIE["session"];
            $conectar = new Conectar();
            $adapter = $conectar->conexion();
            $obj = new ModeloBase('session', $adapter);
            $obj->borrarRegistroSession($login);

            setcookie(
                'session',
                '',
                time() - 3600,
                '/',
                $_SERVER['HTTP_HOST']
            );
        }
        $controllerObj = cargarControlador(CONTROLADOR_DEFECTO);
        lanzarAccion($controllerObj);

    }
}
?>
