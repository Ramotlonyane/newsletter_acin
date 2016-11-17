<?
header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once "model/newsletter.class.php";
require_once "model/contacto.class.php";
$newsletter = new newsletterClass();
$contacto = new contactoClass();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : 'home';

switch($op)
{
	case 'home':
		$plataformas=$newsletter->listaPlataformas();
		Reg::$out->assign('plataformas', $plataformas);

		$lista = $newsletter->pesquisa($_REQUEST);
		Reg::$out->assign('lista', $lista);

		Reg::$out->assign('content', "newsletter/home");
        echo Reg::$out->display('layouts/login.tpl');
	break;
	case 'list_news':
		$lista = $newsletter->pesquisa($_REQUEST);
		Reg::$out->assign('lista', $lista);
		Reg::$out->assign('content', "newsletter/home_lista");
        echo Reg::$out->display('layouts/ajax.tpl');
	break;
	case 'new1':
		$plataformas=$newsletter->listaPlataformas();
		Reg::$out->assign('plataformas', $plataformas);
		$contactos=$newsletter->lista_contactos();
		Reg::$out->assign('contactos', $contactos);
		if(empty($_REQUEST['id'])){
		Reg::$out->assign('content', "newsletter/new1");
        echo Reg::$out->display('layouts/ajax.tpl');
        return false;
			//$_REQUEST['id'] = $newsletter->novaNewsletter($_SESSION['idUtilizador']);
		}
		$dados=$newsletter->load_dados($_SESSION['idUtilizador'],$_REQUEST['id']);
		Reg::$out->assign('newsletter', $dados);
		Reg::$out->assign('content', "newsletter/new1");
        echo Reg::$out->display('layouts/ajax.tpl');
	break;
	case 'save_new1':
		if($_REQUEST['id']) {
			$bEdit=$newsletter->editavel_newsletter($_REQUEST['id']);
		}else{
			$bEdit=true;
		}
		
		if($bEdit){
			$res=$newsletter->save_new1($_SESSION['idUtilizador'],$_REQUEST['id'],$_REQUEST);
		}
		$resposta['sucesso'] = ($res) ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'importFicheiro':
		if($_REQUEST['idNewsletter']){
			$resposta=$newsletter->importFicheiro($_SESSION['idUtilizador'],$_REQUEST['idNewsletter'],$_FILES,$_REQUEST);
            Reg::$out->assign('resposta', $resposta);
            echo Reg::$out->display('layouts/json.tpl');
		}
	break;
	case 'removerFicheiro':
		$res=$newsletter->removerFicheiro($_SESSION['idUtilizador'],$_REQUEST['idNewsletter'],$_REQUEST['id']);
		$resposta['sucesso'] = ($res) ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'new2':
		$dados=$newsletter->load_dados($_SESSION['idUtilizador'],$_REQUEST['id']);
		Reg::$out->assign('newsletter', $dados);
		Reg::$out->assign('content', "newsletter/new2");
        echo Reg::$out->display('layouts/ajax.tpl');
	break;
	case 'testarEmail':
	case 'save_new2':
		$res=$newsletter->editavel_newsletter($_REQUEST['id']);
		if($res){
			$res=$newsletter->save_new2($_SESSION['idUtilizador'],$_REQUEST['id'],$_REQUEST);
		}
		$resposta['sucesso'] = ($res) ? 1 : 0;
		if($resposta['sucesso'] && $op=="testarEmail"){
			Reg::$out->assign('content', "newsletter/ajax/testarEmail");
	        $resposta['html'] = Reg::$out->display('layouts/ajax.tpl');
		}
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'showLinks':
		$dados=$newsletter->load_dados($_SESSION['idUtilizador'],$_REQUEST['idNewsletter']);
		$resposta['sucesso'] = ($dados) ? 1 : 0;
		if($resposta['sucesso']){
			$dados["links"] = $newsletter->obter_links($_REQUEST['idNewsletter']);

			foreach ($dados["links"] as $key => $link) {
				$dados["links"][$key]["url"] = $newsletter->gerar_link_url($link["url"], $link["id"]);
			}

			Reg::$out->assign('newsletter', $dados);
			Reg::$out->assign('content', "newsletter/ajax/showLinks");
	        $resposta['html'] = Reg::$out->display('layouts/ajax.tpl');
		}
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'aprovarNewsletterForm':
		$resposta['sucesso'] = 0;
		$res=$newsletter->editavel_newsletter($_REQUEST['id']);
		if($res){
			$resposta['sucesso'] = 1;
			$res=$newsletter->save_new2($_SESSION['idUtilizador'],$_REQUEST['id'],$_REQUEST);
			$dados=$newsletter->load_dados($_SESSION['idUtilizador'],$_REQUEST['id']);
			Reg::$out->assign('newsletter', $dados);
			Reg::$out->assign('content', "newsletter/ajax/aprovarNewsletterForm");
	        $resposta['html'] = Reg::$out->display('layouts/ajax.tpl');
		}
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'aprovarNewsletter':
		if($_SESSION['bAprovarNewsletter']) {
			$res= $newsletter->aprovar_newsletter($_SESSION['idUtilizador'],$_REQUEST['id'],$_REQUEST);
		}
		$resposta['sucesso'] = ($res) ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'testarEmailForm':
		Reg::$out->assign('content', "newsletter/ajax/testarEmail");
        $resposta['html'] = Reg::$out->display('layouts/ajax.tpl');
		$resposta['sucesso'] = 1;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'view':
		$dados=$newsletter->load_dados($_SESSION['idUtilizador'],$_REQUEST['id']);
		if(empty($dados)){
			redirect('mod=news&op=home');
		}

		$relatorioDados = $newsletter->obter_relatorio($_REQUEST['id']);

		Reg::$out->assign('relatorio', $relatorioDados);
		Reg::$out->assign('newsletter', $dados);
		Reg::$out->assign('content', "newsletter/view");
        echo Reg::$out->display('layouts/login.tpl');
	break;
	case 'enviar_email_teste':
		$idNewsletter=$_REQUEST['id'];
		$email=$_REQUEST['email'];
		$dados_envio=$newsletter->obter_dados_envio_newsletter($idNewsletter);
		$idEmail=$contacto->getEmail($email);
		if($idEmail && $dados_envio){
			//registar novo envio de testes
			$idEnvio=$newsletter->novo_contacto_newsletter($idNewsletter,$idEmail);
			if($idEnvio){
				$hashEnvio=sha1("{$idEnvio}.{$idNewsletter}.{$idEmail}");
				$conteudo=str_ireplace("@hash_envio_newsletter@","&idEnvio={$idEnvio}&hashEnvio={$hashEnvio}", $dados_envio['conteudo']);
				$res= Reg::sendMail($email,$dados_envio['assunto'],$conteudo,$dados_envio['anexos'],$dados_envio['emailEnvio'],$dados_envio['nomeEnvio']);
			}
		}
		$resposta['sucesso'] = ($res) ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'envio_newsletter':
		$idNewsletter=$_REQUEST['id'];
		$dados=$newsletter->load_newsletter_linha($idNewsletter);
		if($dados['idEstado']=="2"){
			if(empty($_REQUEST['nEmails']))
				$_REQUEST['nEmails'] =  10 ;

			$emails=$newsletter->get_email_processar($idNewsletter,$_REQUEST['nEmails']);
			$dados_envio=$newsletter->obter_dados_envio_newsletter($idNewsletter);

			if($emails && $dados_envio){
				foreach ($emails as $e) {
					$idEmail=$e['idEmail'];
					$idEnvio=$e['id'];
					$hashEnvio=sha1("{$idEnvio}.{$idNewsletter}.{$idEmail}");
					$conteudo=str_ireplace("@hash_envio_newsletter@","&idEnvio={$idEnvio}&hashEnvio={$hashEnvio}", $dados_envio['conteudo']);
					$res= Reg::sendMail($e['email'],$dados_envio['assunto'],$conteudo,$dados_envio['anexos'],$dados_envio['emailEnvio'],$dados_envio['nomeEnvio']);
					$newsletter->registar_envio($dados['id'],$e['idEmail'],$res);
					Reg::$out->assign('res', $res);
					Reg::$out->assign('email', $e);
					echo Reg::$out->display('elements/newsletter/envio_newsletter_result.tpl');
					ob_flush();
					flush();
				}
			}else if(empty($emails)){
				$newsletter->newsletter_processada($dados['id']);
			}

			$dados=$newsletter->analise_envio($idNewsletter);
	        Reg::$out->assign('dados', $dados);
			echo Reg::$out->display('elements/newsletter/envio_newsletter_result.tpl');
			ob_flush();
			flush();
		}
	break;
	case 'cancelarNewsletter':
		$dados=$newsletter->load_newsletter_linha($_REQUEST['id']);
		if($dados['idEstado']=="2"){
			$res=$newsletter->cancelar_newsletter($_REQUEST['id']);
		}
		$resposta['sucesso'] = ($res) ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'adicionar_link':
		$idLink = false;

		if(isset($_REQUEST["id"]) && isset($_REQUEST["nome"]) && isset($_REQUEST["url"])) {
			$idLink = $newsletter->adicionar_link($_REQUEST["id"],
												  $_SESSION["idUtilizador"],
												  $_REQUEST["nome"],
												  $_REQUEST["url"]);

			$resposta["url"] = $link["url"] = $newsletter->gerar_link_url($_REQUEST["url"], $idLink);
			$resposta["id"] = $idLink;
		}

		Reg::$out->assign('resposta', $resposta);
    	echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'remover_link':

		if(isset($_REQUEST["id"])) {
			$newsletter->remover_link($_REQUEST["id"]);
		}

		Reg::$out->assign('resposta', 'ok');
    	echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'remove':

		if(isset($_REQUEST["id"])) {
			$newsletter->remover($_REQUEST["id"]);
		}

		Reg::$out->assign('resposta', 'ok');
    	echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'copy':

		if(isset($_REQUEST["id"])) {
			$newsletter->copy($_REQUEST["id"]);
		}

		Reg::$out->assign('resposta', 'ok');
    	echo Reg::$out->display('layouts/json.tpl');
	break;
}
