<?php

session_start();
//Configuración global

require_once 'config/global.php';

//Base para los controladores

require_once 'core/ControladorBase.php';

//Funciones para el controlador frontal

require_once 'core/ControladorFrontal.func.php';

ini_set('max_execution_time', 0);


if(VERSION_APP == 'depuracion'){

    $obj = new ControladorBase();

    $obj->disableCache();

}
if (isset($_GET['action'])){
	//$controller = $_GET['controller'];

        $controllerObj = cargarControlador("Colaborador");
        lanzarAccion($controllerObj);
    }

    else{

        $controllerObj=cargarControlador(CONTROLADOR_DEFECTO);

        lanzarAccion($controllerObj);

   }


function getRealIP()
{

    if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    {
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"]))
    {
        return $_SERVER["HTTP_FORWARDED"];
    }
    else
    {
        return $_SERVER["REMOTE_ADDR"];
    }

}


?>
