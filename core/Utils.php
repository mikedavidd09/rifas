<?php
class Utils {

    static function hourIsBetween($from, $to, $input) {
        $dateFrom = DateTime::createFromFormat('!H:i', $from);
        $dateTo = DateTime::createFromFormat('!H:i', $to);
        $dateInput = DateTime::createFromFormat('!H:i', $input);
        if ($dateFrom > $dateTo) $dateTo->modify('+1 day');
        return ($dateFrom <= $dateInput && $dateInput <= $dateTo) || ($dateFrom <= $dateInput->modify('+1 day') && $dateInput <= $dateTo);
    }
    function getButtonAdd($controller,$action,$permisos){
        $array_permisos = explode(',', $permisos);
        $url = Utils::encrypt_url("controller=$controller&action=viewCrear&permisos=$permisos");
        if($array_permisos[0]){
            echo "<div id='inferior'>
	        		<a href='index.php?url=$url' data-toggle='modal' data-target='#myModal' data-remote='false' class='btn btn-default'>Nuevo</a>
	      		 </div>";
        }
    }
    static function  getMensaje($msj = ''){
        $resp = array(
            'respuesta' => 'false',
            'data'      =>  '',
            'mensaje'   =>  'Usted no tiene permisos para esta accion ('. $msj.'). contactece con el adminisrador del sistema'
        );
        $json = json_encode($resp);
        echo($json);
        exit;
    }
    function getButtonOptions($controller ,$permisos,$type = 'add'){
        $button = '';
        $array_permisos = explode(',', $permisos);

        $url_add 	=  "controller=$controller&action=crear";
        $url_edit 	=  "controller=$controller&action=update";
        $url_delete =  "controller=$controller&action=delete";
        //crud
        switch ($type){
            case "edit":
                if($array_permisos[2]){
                    $button= "<a href='index.php?controller=$controller&action=index'  type='button' class=' link btn btn-default'>Cerrar</a><input id='add' href='$url_edit' type='submit' value='Actualizar' class='btn btn-success' onclick='validarEnviar_();return false;'/>";
                }
                if($array_permisos[3]){
                    $button= $button."<input id='delete' href='$url_delete' type='submit' value='Borrar' class='btn btn-danger' onclick='realizarBorrado_();return false;'/>";
                }
                break;
            case "add":
                if($array_permisos[0]){
                    $button= "<a href='index.php?controller=$controller&action=index'  type='button' class=' link btn btn-default'>Cerrar</a><input id='add' href='$url_add' type='submit' value='Guardar' class='btn btn-success' onclick='validarEnviar_();return false;'/>";
                }
                break;
        }
        echo "<script>$('.botones').html(\"$button\");</script>";
    }
    static function GetDatesInLetters($fecha){
        $dia= Utils::GetDay($fecha);
        $num = date("j", strtotime($fecha));
        $anno = date("Y", strtotime($fecha));
        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        $mes = $mes[(date('m', strtotime($fecha))*1)-1];
        return $dia.', '.$num.' de '.$mes.' del '.$anno;
    }

    function GetDay($fecha) {
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
        $dia = $dias[date('w', strtotime($fecha))];
        return $dia;
    }
}

?>
