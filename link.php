<?php
require_once 'config/config.php';
session_start();
require_once 'config/init.php';


require_once "model/newsletter.class.php";
$newsletter = new newsletterClass();

if(!empty($_REQUEST['id']) && !empty($_REQUEST['hash'])){

    $link = $newsletter->obter_link($_REQUEST['id'],$_REQUEST['hash']);

    if ($link) {
        if ($_REQUEST['idEnvio'] && $_REQUEST['hashEnvio']) {
            $email=$newsletter->get_email_newsletter_hash($_REQUEST['idEnvio'],$_REQUEST['hashEnvio']);
            le($email);

            if($email){
                $newsletter->registar_newsletter_acesso_link($_REQUEST['idEnvio'],$_REQUEST['id']);
            }
        }

        header("Location: ".$link["url"]);
    }
}
?>
