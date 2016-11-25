<?
require_once "model/configuracao.class.php";
$configuracao = new configuracaoClass();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : 'login';

switch($op)
{
	case 'login':
		if( !empty($_REQUEST['user']) && !empty($_REQUEST['pass']) ){
			$res=$configuracao->login($_REQUEST['user'],$_REQUEST['pass']);
			if($res){
				$_SESSION['idUtilizador']=$res['id'];
				$_SESSION['email']=$res['email'];
				$_SESSION['bAprovarNewsletter']=$res['bAprovarNewsletter'];
			}
			$resposta['sucesso']= $res ? 1 : 0;
			Reg::$out->assign('resposta', $resposta);
        	echo Reg::$out->display('layouts/json.tpl');
		}else{
        	echo Reg::$out->display('layouts/loginForm.tpl');
		}
	break;
	case 'logout':
		session_destroy();
		redirect('');
	break;
}
