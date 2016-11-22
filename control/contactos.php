<?
header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once "model/contacto.class.php";
$contacto = new contactoClass();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : 'home';

switch($op)
{
	case 'home':
		$listas=$contacto->listas();
		Reg::$out->assign('listas', $listas);

		$lista = $contacto->pesquisa($_REQUEST);
		Reg::$out->assign('lista', $lista);

		Reg::$out->assign('content', "contacto/home");
        echo Reg::$out->display('layouts/login.tpl');
	break;
    case 'subfolder':
        if (!empty($_REQUEST['idContactList'])) {

            $subfolders=$contacto->list_subfolders($_REQUEST['idContactList']);
            header("Content-Type: application/json", true);
            echo json_encode(array('response' => $subfolders, 'success' => true));
        }
        //die(print_r($_REQUEST));
        // var_dump($subfolders);
        //echo 'aaaaaaaa';
        ///* Send as JSON */
        //echo Reg::$out->display('layouts/ajax.tpl');
        ///Reg::$out->assign('listas', $subfolders);
        //$subfolder = $contacto->pesquisa($_REQUEST);
        //Reg::$out->assign('lista', $subfolder);
        //Reg::$out->assign('content', "contacto/home");
        //echo Reg::$out->display('layouts/login.tpl');
    break;
	case 'list_contatos':
		$lista = $contacto->pesquisa($_REQUEST);
		Reg::$out->assign('lista', $lista);
		Reg::$out->assign('content', "contacto/home_lista");
        echo Reg::$out->display('layouts/ajax.tpl');
	break;
	case 'edit_contacto':
		if($_REQUEST['id']){
			$dados=$contacto->load_dados($_REQUEST['id']);
			Reg::$out->assign('contacto', $dados);
		}
		$listas=$contacto->listas();
		Reg::$out->assign('listas', $listas);
		Reg::$out->assign('content', "contacto/ajax/edit_contacto");
        $resposta['html'] = Reg::$out->display('layouts/ajax.tpl');
		$resposta['sucesso'] = 1;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
	case 'edit_contacto_save':
		$res=$contacto->edit_contacto_save($_REQUEST);
		$resposta['sucesso'] = ($res) ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
	break;
    case 'edit_lista':
        if($_REQUEST['id']){
            $dados=$contacto->load_lista($_REQUEST['id']);
            Reg::$out->assign('lista', $dados);
        }
        Reg::$out->assign('content', "contacto/ajax/edit_lista");
        $resposta['html'] = Reg::$out->display('layouts/ajax.tpl');
        $resposta['sucesso'] = 1;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
    break;
    case 'edit_lista_save':
        $res=$contacto->edit_lista_save($_REQUEST);
        $resposta['sucesso'] = ($res) ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
    break;
    case 'importar_csv':
        $listas=$contacto->listas();
        Reg::$out->assign('listas', $listas);
        Reg::$out->assign('content', "contacto/ajax/importar_csv");
        $resposta['html'] = Reg::$out->display('layouts/ajax.tpl');
        $resposta['sucesso'] = $listas ? 1 : 0;
        Reg::$out->assign('resposta', $resposta);
        echo Reg::$out->display('layouts/json.tpl');
    break;
    case 'importar_csv_save':
        set_time_limit(0);
        if($_FILES['csv']){
            $idLista=$_REQUEST['idLista'];
            if(empty($_REQUEST['bAddLista'])){
                $contacto->limpar_lista($idLista);
            }

            $lines=file($_FILES["csv"]["tmp_name"]);
            $nLinhas=sizeof($lines);
            exec_script_iframe("set_import_nLines($nLinhas)");
            if ($lines) {
                foreach ($lines as $nLinha=>$email) {
                    $email=trim($email);
                    $email=str_ireplace(" ","", $email);
                    $email=str_ireplace(";","", $email);
                    $idEmail=null;
                    if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ){
                        $idEmail=$contacto->getEmail($email);
                        le($idEmail);
                        if($idEmail){
                            if($idLista){
                                $contacto->associar_email_lista($idEmail,$idLista);
                            }
                            exec_script_iframe("import_sucesso()");
                        }
                    }
                    if(empty($idEmail)){
                        exec_script_iframe("import_erro('[$nLinha] =>$email')");
                    }
                }
            } else {

            }
        }
    break;
    case 'exportarCSV':
        set_time_limit(0);
        $_REQUEST['export']=1;
        $lista=$contacto->pesquisa($_REQUEST);
        header("Content-disposition: attachment; filename=emails.csv");
        if($lista){
            foreach ($lista['dados'] as $l) {
                echo $l['email'].";".PHP_EOL;
            }
        }
    break;
}
