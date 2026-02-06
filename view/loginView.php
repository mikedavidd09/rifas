<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="logoSistemaWolf.ico">
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <title>WOLF</title>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
    <!--if lt IE 9
    script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
    script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
    -->
</head>
<body>
<section class="material-half-bg " style="background-image: url(resources/imagen/logoFondo.jpeg);    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;" >
</section>
<section class="login-content">
   <!-- <div class="logo"> <div style="background-image: url(resources/imagen/logoFondo.jpg)" class="img-thumbnail img-logo img-responsive"></div>-->

    </div>
    <div class="login-box">

        <form class="login-form" id="login_form" method="post">

            <h5>INICIO DE SESSION</h5>
            <div class="form-group">
                <input type="text" placeholder="Usuario" id="user" name="user" class="form-control">         </div>
            <div class="form-group">
                <input type="password" id="pwd" class="form-control" placeholder="Contraseña" name="pass">
                <input type="hidden" name="auth_token" value="<?php print_r($token); ?>" />
                <input type="hidden" name="detalles" id="detalles" value="" />
            </div>
            <div class="form-group btn-container">
                <input href="javascript:;" value="Iniciar Sesion"  type="button" class="btn btn-success btn-block" onclick="loging('login_form','usuarios');return false;"/>
            </div>

        </form>
    </div>
</section>
</body>
<script src="assets/js/jquery-3.0.0.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/pace.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/validation-send-form.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>
<SCRIPT>
    var es_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
    if(!es_chrome){
        alert("El navegador que se está utilizando No es Chrome, Por favor para una compatibilidad total con el sistema use Google Chrome : ("+ es_chrome +")");
    }
    var txt = "";
    txt += "Navegador Nombre Codigo: " + navigator.appCodeName;
    txt += "Browser Name: " + navigator.appName;
    txt += "Navegador Versiopm: " + navigator.appVersion;
    txt += "Cookies Enabled: " + navigator.cookieEnabled;
    txt += "Lenguaje del navegador: " + navigator.language;
    txt += "Navegador en Linea: " + navigator.onLine;
    txt += "Plataforma: " + navigator.platform;
    txt += "Usuario agente: " + navigator.userAgent;

    $("#detalles").val(txt);
</SCRIPT>
</html>
