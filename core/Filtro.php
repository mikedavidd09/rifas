<?php
/**
 * Created by PhpStorm.
 * User: Eddy Ruiz
 * Date: 17/8/2018
 * Time: 19:47
 */
class Filtro
{

    private $resultado;

    public function __construct(){
    }

    // VALIDANDO CAMPOS VACIOS
    public static  function validar_Vacios($campo){
        if(!empty($campo) || $campo != "" or $campo != NULL){
            $resultado = TRUE;
        }else{
            $resultado = FALSE;
        }
        return $resultado;
    }

    // VALIDANDO CORREOS
    public static function validar_Correo($correo){
        filter_var($correo, FILTER_VALIDATE_EMAIL) ? $resultado = TRUE : $resultado = FALSE;
        return $resultado;
    }

    // VALIDANDO ENTEROS
    // CERO Y MENOS CERO NO SON ENTEROS VALIDOS
    public static function validar_Enteros($entero, $min=0, $max=0){

        if($min>0 && $max>0)
        {
            $rango = array (
                "options" => array
                ( "min_range" => $min, "max_range" => $max)
            );

            filter_var($entero, FILTER_VALIDATE_INT, $rango) ? $resultado = TRUE : $resultado = FALSE;
        }
        else{
            filter_var($entero, FILTER_VALIDATE_INT) ? $resultado = TRUE : $resultado = FALSE;
        }

        return $resultado;
    }

    // VALIDANDO BOLEANOS
    // RETORNA TRUE PARA 1, TRUE, ON, YES
    // EN OTROS CASOS RETORNARA FALSE
    public static function validar_Boleanos($boleano){
        filter_var($boleano, FILTER_VALIDATE_BOOLEAN) ? $resultado = TRUE : $resultado = FALSE;
        return $resultado;
    }

    // VALIDANDO NUMEROS DE PUNTO FLOTANTE (DECIMALES)
    public static  function validar_Flotante($flotante){

        $separador = array('options' => array('decimal' => '.'));

        // si se quiere usar un numero con miles y punto flotante (1.238,32) se usa esta condicion
        // (!filter_var($flotante, FILTER_VALIDATE_FLOAT,  array('options' => array('decimal' => ','), 'flags' => FILTER_FLAG_ALLOW_THOUSAND)))
        filter_var($flotante, FILTER_VALIDATE_FLOAT, $separador) ? $resultado = TRUE : $resultado = FALSE;
        return $resultado;
    }

    // VALIDANDO UNA IP
    // en version se le debe pasar como parametro el tipo de ip FILTER_FLAG_IPV4 - FILTER_FLAG_IPV6
    public static function validar_IP($ip, $version="FILTER_FLAG_IPV4"){
        filter_var($ip, FILTER_VALIDATE_IP, $version) ? $resultado = TRUE : $resultado = FALSE;
        return $resultado;
    }

    // VALIDANDO CON EXPRESIONES REGULARES
    // VERIFICA QUE EL CONTENIDO CONCUERDE CON LA EXPRESION REGULAR
    /*
        $expresion = '/^M(.*)/';
        si se le pasa una cadena de texto que comience con M el resultado sera positivo
    */
    public static function Validar_ExpRegular($contenido, $expresion){

        // para pasarle las opciones al filtro
        $opcion = array("options" => array("regexp" => $expresion));

        filter_var($contenido, FILTER_VALIDATE_REGEXP, $opcion) ? $resultado = TRUE : $resultado = FALSE;
        return $resultado;
    }


    // VALIDANDO UNA URL
    public static function validar_URL($url){
        filter_var($url, FILTER_VALIDATE_URL) ? $resultado = TRUE : $resultado = FALSE;
        return $resultado;
    }


    public static  function Limpiar_Etiqueta($cad)
    {
        //Remueve las etiquetas HTML
        $cad_limpia = filter_var($cad, FILTER_SANITIZE_STRING);
        return $cad_limpia;
    }



    //Funcion utilizada para limpiar cadena
    public static  function Limpiar_Cadena($valor)
    {
        $valor = (isset($valor) && !empty($valor)) ? $valor : "";
        $valor=strtoupper($valor);

        $valor = str_ireplace("'1'='1'","",$valor);
       
       
        $valor = str_ireplace(" LIMIT 1","",$valor);
        $valor = str_ireplace(" ORDER BY ","",$valor);

        $valor = str_ireplace(";--","",$valor);
        $valor = str_ireplace("'","",$valor);
        $valor = str_ireplace(" ' ","",$valor);
        $valor = str_ireplace("' ","",$valor);
        $valor = str_ireplace(" '","",$valor);
        $valor = str_ireplace(" -- ","",$valor);
        $valor = str_ireplace(" ^ ","",$valor);
        $valor = str_ireplace(" [","",$valor);
        $valor = str_ireplace("] ","",$valor);
        $valor = str_ireplace("1=1","",$valor);
        $valor = str_ireplace(" 1=1 ","",$valor);
        $valor = str_ireplace("1 =1","",$valor);
        $valor = str_ireplace("1= 1","",$valor);
        $valor = str_ireplace("1 = 1","",$valor);

        $valor = str_ireplace(" SELECT ","",$valor);
        $valor = str_ireplace(" COPY ","",$valor);
        $valor = str_ireplace(" DELETE ","",$valor);
        $valor = str_ireplace(" UPDATE ","",$valor);
        $valor = str_ireplace(" ALTER ","",$valor);
        $valor = str_ireplace(" DROP ","",$valor);
        $valor = str_ireplace(" DUMP ","",$valor);
        $valor = str_ireplace(" OR ","",$valor);
        $valor = str_ireplace(" AND ","",$valor);
        $valor = str_ireplace(" LIKE ","",$valor);
        $valor = str_ireplace(" TRUNCATE ","",$valor);

        $valor = str_ireplace("SELECT ","",$valor);
        $valor = str_ireplace("COPY ","",$valor);
        $valor = str_ireplace("DELETE ","",$valor);
        $valor = str_ireplace("UPDATE ","",$valor);
        $valor = str_ireplace("DROP ","",$valor);
        $valor = str_ireplace("DUMP ","",$valor);
        $valor = str_ireplace("TRUNCATE ","",$valor);

        return addslashes($valor);
    }



    public static function Requeridos($arreglo=null){
        $tam=0;
        $ban=false;
        if($arreglo!=null){
            foreach($arreglo as $key){
                if (array_key_exists($key,$_POST)){$tam++;}
            }
        }

        if(count($arreglo)==$tam){$ban=true;}

        return $ban;

    }


    public static function bool_string($booleano)
    {
        switch($booleano)
        {
            case 1:
            case true:
                   return 'true';
                break;
            case 0:
            case false:
                  return 'false';
                break;

        }
    }


    public static function archivo_vacio($nombre_key){
        $ban=false;
        if(array_key_exists($nombre_key,$_POST)){
                if($_POST[$nombre_key]=="")
                {
                   $ban=true;
                }
        }

        return $ban;
    }


}

?>
