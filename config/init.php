<?
// incluir Functions
require_once LIB . 'functions.php';

// incluir Registry
require_once PATH . 'lib/reg.class.php';
// inscluir file das Permissões
//require_once PATH . 'lib/permissions.php';
//inscluir mailer
require_once PATH . 'lib/mailer/class.phpmailer.php';
// incluir Output
require_once LIB  . 'OliveTemplateEnginePHP.class.php';
// incluir DB
require_once LIB  . 'OliveDbMysql.class.php';

// instanciar output
Reg::$out = new OliveTemplateEnginePHP();

// inicializar output
Reg::$out->set_path(PATH . 'view');

if(defined('SERVER_MEMCACHE')){
    Reg::$cache=new Memcached();
    Reg::$cache->addServer(SERVER_MEMCACHE,11211,1);
}

// instanciar db
Reg::$db = new OliveDbMysql();
// ligar à Base de Dados iMED
$ok = Reg::$db->data_connect(DBHOST, DBUSER, DBPASS, DBNAME);

?>
