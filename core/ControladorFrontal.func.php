<?php
function cargarControlador($controlador){

  $controlador=ucwords($controlador).'Controller';

  $strFileController = 'controller/'.$controlador.'.php';
  if(!is_file($strFileController)){
    $strFileController = 'controller/'.ucwords(CONTROLADOR_DEFECTO).'Controller.php';
  }
  require_once $strFileController;
   $controllerObj=  new $controlador();
   return $controllerObj;
}

function cargarAccion($controllerObj,$action){
   $accion=$action;
   $controllerObj->$accion();
}

function lanzarAccion($controllerObj){
   if(isset($_GET["action"]) && method_exists($controllerObj, $_GET["action"])){
       cargarAccion($controllerObj, $_GET["action"]);
   }else if(isset($_SESSION["Login_View"])){
    print_r($_SESSION["Login_View"]);
       $session =$_SESSION["Login_View"];
     
       if($session->role){
           switch ($session->role) {
               case "sudo":
                   $accion_defalt = ACCION_SUDO;
                   break;
               case "admin":
                   $accion_defalt = ACCION_ADMIN;
                   break;
               case "super":
                   $accion_defalt = ACCION_SUPER;
                   break;
               case "vendedor":
                   $accion_defalt = ACCION_VENDEDOR;
                   break;
               default:
                   $accion_defalt = ACCION_DEFECTO;
           }
          } else {
           $accion_defalt = ACCION_DEFECTO;
       }
       cargarAccion($controllerObj, $accion_defalt);
   } else {
       cargarAccion($controllerObj, ACCION_DEFECTO);
   }
}
 ?>
