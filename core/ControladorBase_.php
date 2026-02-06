<?php
  class ControladorBase{
    public function __construct(){
      require_once 'Conectar.php';
      require_once 'EntidadBase.php';
      require_once 'ModeloBase.php';

      //Incluir Todos Los ModeloBase
      foreach (glob("model/*.php") as $file) {
            require_once($file);
        }
    }
    //Plugins y funcionalidades
    /*
    public function validarCharEspecial($array){
      foreach ($array as $key => $value) {
        if!(preg_match('[^ A-Za-z0-9_-ñÑ]', $value))return $key; else return true;
      }
      }
    */
  /*
  * Este método lo que hace es recibir los datos del controlador en forma de array
  * los recorre y crea una variable dinámica con el indice asociativo y le da el
  * valor que contiene dicha posición del array, luego carga los helpers para las
  * vistas y carga la vista que le llega como parámetro. En resumen un método para
  * renderizar vistas.
  */
      public function view($vista,$datos){
          if(!count($datos)==0) {
              foreach ($datos as $id_assoc => $valor) {
                  ${$id_assoc}=$valor;
          }
        }
        require_once 'core/AyudaVistas.php';
              $helper=new AyudaVistas();
              require_once 'view/'.$vista.'View.php';
      }
      public function redirect($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
             header("Location:index.php?controller=".$controlador."&action=".$accion);
      }
      public function  setNone($obj){
        $arr_obj = (array)$obj;
        foreach ($arr_obj as $attrib=>$value){
            if($attrib!='EntidadBase table'){
                $obj->$attrib = 'none';
            }else{
                break;
            }
        }
    }

      public function SubirImagen($imagen){
      ini_set('gd.jpeg_ignore_warning', false);
        $allowedExts = array("jpg", "jpeg", "gif", "png", "JPG", "GIF", "PNG");
        if($imagen["name"]!=''){
          $extension = end(explode(".", $imagen["name"]));
          if ((($imagen["type"] == "image/gif")
                  || ($imagen["type"] == "image/jpeg")
                  || ($imagen["type"] == "image/png")
                  || ($imagen["type"] == "image/pjpeg"))
              && in_array($extension, $allowedExts)) {
              $imagen_name = substr(md5(uniqid(rand())),0,10).".".$extension;
              $imagen_min  = $imagen_name;
              $cd=$imagen['tmp_name'];
              $ruta_path= "assets/img/";
              $ruta = "assets/img/" . $imagen_name;
              $destino = $imagen_name;
              $resultado = @move_uploaded_file($cd, $ruta);

              if(!empty($resultado)){
                  $this->resizeImagen($ruta_path,$imagen_name,500,500,$imagen_min,$extension);
                  return $destino;
              }else{
                  return false;
              }
          }else {
              return false;
          }
        } else {
          return false;
        }
    }
     public function SubirImagenRuta($imagen,$carpeta){
      ini_set('gd.jpeg_ignore_warning', false);
        $allowedExts = array("jpg", "jpeg", "gif", "png", "JPG", "JPEG" ,"GIF", "PNG");
        if($imagen["name"]!=''){
          $extension = end(explode(".", $imagen["name"]));
          if ((($imagen["type"] == "image/gif") || ($imagen["type"] == "image/jpeg") || ($imagen["type"] == "image/png") || ($imagen["type"] == "image/jpg"))
              && in_array($extension, $allowedExts)) {
              $imagen_name = substr(md5(uniqid(rand())),0,10).".".$extension;
              $imagen_min  = $imagen_name;
              $cd=$imagen['tmp_name'];
              $ruta_path= "assets/img/".$carpeta."/";
              $ruta = "assets/img/".$carpeta."/" . $imagen_name;
              $destino = $imagen_name;
              $resultado = @move_uploaded_file($cd, $ruta);

              if(!empty($resultado)){
                  $this->resizeImagen($ruta_path,$imagen_name,500,500,$imagen_min,$extension);
                  return $destino;
              }else{
                  return false;
              }
          }else {
              return false;
          }
        } else {
          return false;
        }
    }
  	public function SubirImagenMultiples($imagen){
      ini_set('gd.jpeg_ignore_warning', true);
  	$cantidad= count($imagen);
  	$cadenafotos="|";
        $allowedExts = array("jpg", "jpeg", "gif", "png", "JPG", "GIF", "PNG");
  	  for($i = 0;$i < $cantidad ; $i++)
  	  {
  		  if($imagen["name"][$i]!=''){
  			$extension = end(explode(".", $imagen["name"][$i]));
  			if ((($imagen["type"][$i] == "image/gif")
  					|| ($imagen["type"][$i] == "image/jpeg")
  					|| ($imagen["type"][$i] == "image/png")
  					|| ($imagen["type"][$i] == "image/pjpeg"))
  				&& in_array($extension, $allowedExts)) {
  				$imagen_name = substr(md5(uniqid(rand())),0,10).".".$extension;
  				$imagen_min  = $imagen_name;
  				$cd=$imagen['tmp_name'][$i];
  				$ruta_path= "assets/img/foto_garantia/";
  				$ruta = "assets/img/foto_garantia/" . $imagen_name;
  				$destino = $imagen_name;
  				$resultado = @move_uploaded_file($cd, $ruta);

  				if(!empty($resultado)){
  					$this->resizeImagen($ruta_path,$imagen_name,500,500,$imagen_min,$extension);
  					$cadenafotos .= $destino."|";
  				}else{
  					return false;
  				}
  			}else {
  				return false;
  			}
  		  } else {
  			return false;
  		  }
  	  }
  	  return $cadenafotos;
    }
      function resizeImagen($ruta, $nombre, $alto, $ancho,$nombreN,$extension)
      {
          ini_set('gd.jpeg_ignore_warning', true);
          $rutaImagenOriginal = $ruta . $nombre;
          if ($extension == 'GIF' || $extension == 'gif') {
              $img_original = imagecreatefromgif($rutaImagenOriginal);
          }
          if ($extension == 'jpg' || $extension == 'JPG') {
              $img_original = imagecreatefromjpeg($rutaImagenOriginal);
          }
          if ($extension == 'png' || $extension == 'PNG') {
              $img_original = imagecreatefrompng($rutaImagenOriginal);
          }

           if ($extension == 'png' || $extension == 'PNG') {
              $img_original = imagecreatefrompng($rutaImagenOriginal);
          }

            if ($extension == 'jpeg' || $extension == 'JPEG') {
              $img_original = imagecreatefromjpeg($rutaImagenOriginal);
          }

          $max_ancho = $ancho;
          $max_alto = $alto;
          list($ancho, $alto) = getimagesize($rutaImagenOriginal);
          $x_ratio = $max_ancho / $ancho;
          $y_ratio = $max_alto / $alto;
          if (($ancho <= $max_ancho) && ($alto <= $max_alto)) {//Si ancho
              $ancho_final = $ancho;
              $alto_final = $alto;
          } elseif (($x_ratio * $alto) < $max_alto) {
              $alto_final = ceil($x_ratio * $alto);
              $ancho_final = $max_ancho;
          } else {
              $ancho_final = ceil($y_ratio * $ancho);
              $alto_final = $max_alto;
          }
          $tmp = imagecreatetruecolor($ancho_final, $alto_final);
          imagecopyresampled($tmp, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);
          imagedestroy($img_original);
          $calidad = 90;
          imagejpeg($tmp, $ruta . $nombreN, $calidad);
      }

      public function eliminarImagen($ruta){
          if(file_exists("assets/img/" .$ruta)){
              unlink("assets/img/" .$ruta);
          }
          return  true;
      }

      public function generateFormToken($form) {
          // generar token de forma aleatoria
          $token = md5(uniqid(microtime(), true));

          // generar fecha de generación del token
          $token_time = time();

          // escribir la información del token en sesión para poder
          // comprobar su validez cuando se reciba un token desde un formulario
          $_SESSION['csrf'][$form.'_token'] = array('token'=>$token, 'time'=>$token_time);
          //print_r($_SESSION['csrf'][$form.'_token'] );
          return $token;
      }
      public function verifyFormToken($form, $token, $delta_time=0) {

          // comprueba si hay un token registrado en sesión para el formulario
          if(!isset($_SES