<?php
require_once 'config/config.php';
session_start();
require_once 'config/init.php';

require_once "model/newsletter.class.php";
require_once "model/contacto.class.php";
$newsletter = new newsletterClass();
$contacto = new contactoClass();

$mod = (isset($_REQUEST['mod'])) ? $_REQUEST['mod'] : 'view';
switch($mod)
{
    case 'blacklist':
        $email=$newsletter->get_email_newsletter_hash($_REQUEST['idEnvio'],$_REQUEST['hashEnvio']);
        if($email){
            if($_REQUEST['remover']){
                $res = $contacto->contacto_adicionar_blacklist($email['id']);
                $resposta['sucesso'] = ($res) ? 1 : 0;
                Reg::$out->assign('resposta', $resposta);
                echo Reg::$out->display('layouts/json.tpl');
            }else{
                Reg::$out->assign('email', $email);
                echo Reg::$out->display('layouts/blacklistForm.tpl');
            }
        }
    break;
    case 'view':
        if($_REQUEST['id'] && !empty($_REQUEST['hash'])){
            $news=$newsletter->load_newsletter_hash($_REQUEST['id'],$_REQUEST['hash']);
            if($news){

                if($_REQUEST['idEnvio'] && $_REQUEST['hashEnvio']){
                    $email=$newsletter->get_email_newsletter_hash($_REQUEST['idEnvio'],$_REQUEST['hashEnvio']);
                    if($email){
                        $newsletter->registar_newsletter_acesso($_REQUEST['idEnvio']);
                    }
                }

                if($_REQUEST['code']){
                    echo htmlentities($news['conteudo']);
                }else{
                    ?>
                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//PT" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">
                    <head>
                        <title><?=$news['assunto']?></title>
                    </head>
                    <?
                    echo $news['conteudo'];
                    echo "<style>.viewBrowser{display:none}</style>";
                }
            }
        }
    break;
}

?>
