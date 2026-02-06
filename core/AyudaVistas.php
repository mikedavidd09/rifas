<?php
class AyudaVistas{

    public function url($view,$controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
        $urlString=$view.".php?controller=".$controlador."&action=".$accion;
        return $urlString;
    }

    //Helpers para las vistas
}
?>
