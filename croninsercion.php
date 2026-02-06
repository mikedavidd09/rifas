<?php
require_once 'config/global.php';

//Base para los controladores

require_once 'core/ControladorBase.php';

//Funciones para el controlador frontal

require_once 'core/ControladorFrontal.func.php';

ini_set('max_execution_time', 0);

if (isset($_GET['action'])){

    $controllerObj = cargarControlador("pagos");
    lanzarAccion($controllerObj);
}

else{

     $controllerObj=cargarControlador(CONTROLADOR_DEFECTO);

	    lanzarAccion($controllerObj);

}


?>
