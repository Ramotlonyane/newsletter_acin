<?php
require_once 'config/config.php';
session_start();
require_once 'config/init.php';


require_once "model/newsletter.class.php";
$newsletter = new newsletterClass();

if($_REQUEST['id'] && !empty($_REQUEST['hash'])){
    $file=$newsletter->get_ficheiro($_REQUEST['id'],$_REQUEST['hash']);
    if($file){

        if($_REQUEST['idEnvio'] && $_REQUEST['hashEnvio']){
            $email=$newsletter->get_email_newsletter_hash($_REQUEST['idEnvio'],$_REQUEST['hashEnvio']);
            if($email){
                $newsletter->registar_newsletter_acesso($_REQUEST['idEnvio'],$_REQUEST['id']);
            }
        }

        $fullPath=UPLOAD_DIR.$file['caminho'];
        $nomFile=$file['nome'];
        $fsize = filesize($fullPath);
        $ext= pathinfo($nomFile, PATHINFO_EXTENSION);
        switch ($ext) {
            case "gif":
                $ctype="image/gif";
            break;
            case "png":
                $ctype="image/png";
            break;
            case "jpeg":
            case "jpg":
                $ctype="image/jpg";
            break;
            case "pdf":
                $ctype="application/pdf";
            break;
            default:
                $ctype="octet-stream";
        }
        header("Content-Type: $ctype");
        ob_clean();
        flush();
        readfile( UPLOAD_DIR.$file['caminho'] );
    }
}

?>
