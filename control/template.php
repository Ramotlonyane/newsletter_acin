<?
header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once "model/template.class.php";
$template = new templateClass();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : 'home';

switch($op)
{
	case 'home':
	$templates=$template->listaTemplate();
	Reg::$out->assign('templates', $templates);

	/*$lista = $newsletter->pesquisa($_REQUEST);
	Reg::$out->assign('lista', $lista);*/

	Reg::$out->assign('content', "template/home");
    echo Reg::$out->display('layouts/login.tpl');
	break;

}