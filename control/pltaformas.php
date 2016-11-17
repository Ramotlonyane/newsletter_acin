<?
header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once "model/plataforma.class.php";
$plataforma = new plataformaClass();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : 'home';

switch($op)
{
	case 'home':
		$lista = $plataforma->pesquisa($_REQUEST);
		Reg::$out->assign('lista', $lista);

		Reg::$out->assign('content', "plataforma/home");
        echo Reg::$out->display('layouts/login.tpl');
	break;
	case 'list':
		$lista = $plataforma->pesquisa($_REQUEST);
		Reg::$out->assign('lista', $lista);
		Reg::$out->assign('content', "plataforma/home_lista");
        echo Reg::$out->display('layouts/ajax.tpl');
	break;
	case 'edit_plataforma':
		if($_REQUEST['id']){
			$dados=$plataforma->load_dados($_REQUEST['id']);
			Reg::$out->assign('plataforma', $dados);
		}
		Reg::$out->assign('content', "plataforma/ajax/edit_plataforma");
        $resposta['html'] = Reg::$out->display('layouts/ajax.tpl');
		$resposta['sucesso'] = 1;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'edit_plataforma_save':
		$res=$plataforma->edit_plataforma_save($_REQUEST);
		$resposta['sucesso'] = ($res) ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
}
