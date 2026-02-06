<?php
if(!isset($_GET['t'])) die('Debe especificar el token');
$token = $_GET['t'];
if(Auth::Check($token))
	return Auth::GetData($token);
else 
	return false;

