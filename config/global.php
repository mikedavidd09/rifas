<?php
	//se ocupa por si no hay session activa
  define("CONTROLADOR_DEFECTO","usuarios");
  define("ACCION_DEFECTO","index");
  //se ocupa para validar si hay session visualizar el menu por defecto
   define("CONTROLADOR_DEFECTO_USER","menu");
  define("ACCION_DEFECTO_USER","index");

   define("CONTROLLADOR_ANALISTA","menu");
  define("ACCION_ANALISTA","indexAnalista");

   define("CONTROLLADOR_OPERATIVO","menu");
  define("ACCION_OPERATIVO","indexOperativo");

   define("CONTROLLADOR_SOPORTE","menu");
  define("ACCION_SOPORTE","indexSoporte");

   define("CONTROLLADOR_DEFECTO_INDEX","menu");
  define("ACCION_DEFECTO_INDEX","indexAnalista");
  define("ACCION_PERMISOS","ModulosPermisos");
  define("ACCION_SANEADO","indexSaneado");
    define("VERSION_APP","depuracion");
  //aca se pueden agregar mas constantes de configuracion



  
  define("CONTROLADOR_SUDO","menu");
  define("ACCION_SUDO","indexSudo");
  define("CONTROLADOR_ADMIN","menu");
  define("ACCION_ADMIN","indexAdmin");
  define("CONTROLADOR_ACCION_SUPER","menu");
  define("ACCION_SUPER","indexSuper");
  define("CONTROLADOR_ACCION_VENDEDOR","menu");
  define("ACCION_VENDEDOR","indexVendedor");
 ?>
