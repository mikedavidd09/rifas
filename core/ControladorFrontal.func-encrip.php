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
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
vWL+nPTWIbykiUC5/zhZ