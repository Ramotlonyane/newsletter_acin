<?
// DFENIR A URL
//$address="http://".str_replace("/".end(explode('/',$_SERVER['PHP_SELF'])),"",$_SERVER['HTTP_HOST'].$_SERVER["SCRIPT_NAME"]);
$http_type=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on")?"https":"http";
$address="$http_type://".str_replace("/".end(explode('/',$_SERVER['PHP_SELF'])),"",$_SERVER['HTTP_HOST'].$_SERVER["SCRIPT_NAME"]);
define('URL', $address);

// DADOS DA BASE DE DADOS
define('DBUSER', 'imed');
define('DBPASS', 'imed');
define('DBHOST', '10.11.1.30');
define('DBNAME', 'newsletter');


define('PATH', '');
define('DIR', '');
define('LIB', 'framework/lib/');
define('BASE', './');

// lista de modulos que o sistema pode usar
$modulos['conf'] = "configuracao.php";
$modulos['news'] = "newsletter.php";
$modulos['cont'] = "contactos.php";
$modulos['plat'] = "pltaformas.php";
$modulos['temp'] = "template.php";
$modulos['cale'] = "calendar.php";

// HTML
define('NL', "\n");
define('BR', '<br/>');
define('SP', '&nbsp;');
define('TB', '&nbsp;&nbsp;&nbsp;&nbsp;');
define('DATA', date('Y-m-d'));
define('ANO', date('Y'));
define('HORA', date('H:i:s'));
define('PLATAFORMA', "Newsletter");
define('VERSION', "0.0.1");
define('SERVIDOR_DEV',1);

define('SMTP_PORT', '25');
/*
define('SMTP', 'smtp.critsend.com');
define('FROM_NAME', 'ACIN iCloud Solutions');
define('FROM_EMAIL', 'info@acin.pt');
define('USERNAME_MAIL', 'rgarces@acin.pt');
define('PASSWORD_MAIL', 'MCkR5Uyv7eRd7K');
*/

define('SMTP', 'in.mailjet.com');
define('FROM_NAME', 'iMED');
define('FROM_EMAIL', 'nao_responder@imed.com.pt');
define('USERNAME_MAIL', '78bb8111a5ce2580aa1b3ae963abad9c');
define('PASSWORD_MAIL', 'd3882d6274e9782ec224162ff33257e7');


define('SMTP_CRITSEND', 'smtp.critsend.com');
define('USERNAME_MAIL_CRITSEND', 'rgarces@acin.pt');
define('PASSWORD_MAIL_CRITSEND', 'MCkR5Uyv7eRd7K');

define('USERNAME_MAIL_GMAIL', 'imed.com.pt@gmail.com');
define('PASSWORD_MAIL_GMAIL', '01acin2013');
define('USERNAME_MAIL_ALT', 'imed.com.pt@gmail.com');
define('PASSWORD_MAIL_ALT', '01acin2013');


define('EMAIL_ADMIN', 'infoacademia@acin.pt');
define('NAME_ADMIN',  'Academia de Informática');


define('NFORPAGE',25);


ini_set('error_reporting', E_ERROR | E_PARSE | E_USER_ERROR );
ini_set('display_errors',1); //reportar erros
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ini_set("upload_max_filesize", "2M");

ini_set("memory_limit", -1 );

define('SESSION_DIR', '/var/www/dados/session');
ini_set('session.name','newsletter');
ini_set('session.save_handler','memcached');
ini_set('memcache.max_failover_attempts',100);
ini_set('session.save_path','127.0.0.1:11211?persistent=1&weight=1&timeout=1&retry_interval=15');
ini_set('session.gc_probability', 1);
define('SERVER_MEMCACHE','127.0.0.1');
define('SAVE_MEMCACHE',1);
define('UPLOAD_DIR', '/mnt/sdb1/websites/html/ramotlonyane_modise/upload');

?>
