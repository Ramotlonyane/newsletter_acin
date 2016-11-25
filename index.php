<?php
require_once 'config/config.php';
session_start();
require_once 'config/init.php';

if(empty($_SESSION['idUtilizador']) && $_REQUEST['op']!="login"){
	$_REQUEST['mod']="conf";
	$_REQUEST['op']="login";
}

if(!in_array($_REQUEST['mod'], array_keys($modulos))){
	$_REQUEST['mod']="news";
}

if(Reg::is_ajax_request()){
    Reg::utf8_decode_request();
}

$modulo = str_replace(' ', '', $modulos[$_REQUEST['mod']]);
require_once PATH . 'control/' . $modulo;
?>
