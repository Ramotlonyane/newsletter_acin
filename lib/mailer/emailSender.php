<?php

include("class.phpmailer.php");

function init()
{
	$mail = new PHPMailer();
	$mail->IsSMTP();

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 25;                    // set the SMTP server port
	$mail->Host       = MAILER_HOST;// SMTP server
	$mail->Username   = MAILER_USERNAME;// SMTP server username
	$mail->Password   = MAILER_PASSWORD; // SMTP server password
	return $mail;
}

function send( $mail )
{
	global $MAILER_FROM_NAME;
	
	$mail->From = MAILER_FROM;
	$mail->FromName = $MAILER_FROM_NAME;
	$mail->IsHTML(true); // send as HTML
	$mail->AltBody    = "Para visualizar este email utilize um gestor de email que suporte HTML!"; // optional, comment out and test
	$mail->WordWrap   = 50; // set word wrap
	if(!$mail->Send()) {
		//echo "Mailer Error: " . $mail->ErrorInfo;
		return false;
	} else {
		//echo "Message sent!";
		return true;
	}
}

function bodyContent($mail, $content)
{
	$body             = $mail->getFile('lib/mailer/contents.html');
	$body             = eregi_replace("[\]",'',$body);	
	$body			= str_replace("replace_here", $content, $body);
	$mail->MsgHTML($body);
}

function emailAttachments($mail, $files)
{
	$mail = init();
	for($r=0; $r < sizeof($files);$r++)
	{
		if($files[$r][1] != "" && strlen($files[$r][1]) > 5) {
			$mail->AddAttachment($files[$r][0], $files[$r][1]);  
		}
		else {
			$mail->AddAttachment($files[$r]); 
		}
	}
}

function submitEmail($to, $subject, $content, $files)
{
	
	if($to != "")
	{
		$mail = init();
		$mail->Subject    = $subject;
		//$mail->ConfirmReadingTo  = $MAILER_CONFIRM_READING_TO;
		//$mail->Sign($MAILER_CERT, $MAILER_KEY, $MAILER_KEY_PASSWORD);
		bodyContent($mail, $content);
		//emailAttachments($mail, $files);
		$mail->AddAddress($to);
		//$mail->AddBCC($MAILER_CC);
		$result = send($mail);
		///$result = true;
		return $result;
	}
	else
	{
		return true;
	}
}

function submitEmailAttach($to, $subject, $content, $file_path )
{


	if($to != "")
	{
		$mail = init();
		$mail->Subject    = $subject;
		//$mail->ConfirmReadingTo  = $MAILER_CONFIRM_READING_TO;
		//$mail->Sign($MAILER_CERT, $MAILER_KEY, $MAILER_KEY_PASSWORD);
		bodyContent($mail, $content);
		
		foreach ( $file_path as $attach ){
			$mail->AddAttachment( $attach );
		}
		
		$mail->AddAddress($to);
		//$mail->AddBCC($MAILER_CC);
		$result = send($mail);
		///$result = true;
		return $result;
	}
	else
	{
		return true;
	}
}
?>
