<?php
require_once 'config/config.php';
session_start();
require_once 'config/init.php';
$teste='fwfwefwe \ / " ';

$teste=Reg::mysql_real_escape_array($teste);
vd(__FILE__);

?>
