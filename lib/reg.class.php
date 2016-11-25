<?php

/**
 * Classe estática que permite aceder a componentes gerais do sistema
 */
class Reg
{
	/**
	 * Instância da conexão à base de dados
	 *
	 * @var Db
	 */
	static public $db;
    static public $db_facturacao;
    //Objecto publico do reg que contendo a traducao do Igest no respectivo idioma. Ver mais em traducao.class.php
    static public $lang;

    static public $DB_log;

    static public $DB_public;

    static public $dbInfarmed;

    static public $r;

    /**
     * Memcached
     *
     */

	static public $cache;

	/**
	 * Instância da classe de output
	 *
	 * @var Output
	 */
	static public $out;

	/**
	 * NIF da entidade emissora
	 *
	 * @var string
	 */
	static public $nif;

    /**
     * NOME UTILIZADOR
     */
	static public $user;

    /**Guardar Nome da Entidade Actual*/
    static public $entidade;

	/** array para JS
	 *
	 * @var array
	 */
	static public $js = Array();

	/**
	 * Construtor privado impede instanciação
	 */
	private function __constructor()
	{

	}

	/**
	 * addiciona ficheiro de javascript
	 *
	 * @param string $source
	 */
	public function add_script($source)
	{
		self::$js[] = $source;
	}

    /**
	 * regista callback
	 *
	 * @param string $hook
	 * @param Object $object
	 * @param string $method
	 */
	public function add_callback($hook, $object, $method)
	{
		// inicializar array para o hook pedido
		if (!is_array(self::$callback[$hook])) $callback[$hook] = array();
		// registar callback no hook
		self::$callback[$hook][] = array($object, $method);
	}


	/**
	 * chama callbacks e faz output dos mesmos
	 *
	 * @param string $hook
	 */
	public function output_callback($hook)
	{
		// se existem callbacks registados neste hook
		if (is_array(self::$callback[$hook]))
		{
			// loop aos callbacks
			foreach (self::$callback['onload'] as $c)
		    {
		    	// validar callback
			 	if (is_callable($c))
			 	{
			 		// sacar objecto / metodo
			 		$object = $c[0];
			 		$method = $c[1];
			 		// chamar
			 		echo $object->$method();
			 	}
			 	// debug
			 	else self::debug('callback invalido', serialize($c));
	 		}
		}
	}

    /**
     * Apresenta mensagens
     *
     */
    public function show_mgs($mgs, $link, $type)
    {
        self::$out->assign('content', 'mgs');
        self::$out->assign('mgs', $mgs);
        self::$out->assign('link', $link);
        self::$out->assign('type', $type);
        echo self::$out->display('layouts/intro.tpl');
        die();
    }

    /**
     *Constroi senha aleatória
     */
    public function SenhaAleatoria($charnumber)
    {
        $array = explode(",", "A,B,C,D,E,F,G,H,I,J,K,1,2,3,4,5,6,7,8,9,0,a,b,c,d,e,f,g,h,i,j,k");
        shuffle($array);
        $pass = implode($array, "");
        return substr($pass, 0, $charnumber);
    }

    /**
     *Carregar dados especidos da base de dados
     * -->table
     * -->col
     * -> colref
     * -->id
     */
    public function getData($table, $col, $colref, $id)
    {
        $sql = "SELECT " . $col . " FROM " . $table . " WHERE " . $colref . "=" . $id . "";
        $res = self::$db->query_row($sql);
        return $res[$col];
    }

	/*
    public function sendMail($toEmail, $subject,$body, $anexos=null)
    {

			$mail = new PHPMailer();
            $mail->SetFrom(FROM_EMAIL, FROM_NAME);

            $mail->AddAddress($toEmail);
            $mail->IsSMTP();                           // tell the class to use SMTP
        	$mail->SMTPAuth   = true;                  // enable SMTP authentication
        	$mail->Port       = SMTP_PORT;             // 25                   // set the SMTP server port
        	$mail->Host       = SMTP;                  // SMTP server
            //$mail->FromName = FROM_NAME;
			$mail->AddReplyTo(FROM_EMAIL, FROM_NAME);

            $mail->Username   = USERNAME_MAIL;         // SMTP server username
        	$mail->Password   = PASSWORD_MAIL;         // SMTP server password
        	$mail->From       = USERNAME_MAIL;

            $mail->Subject    = $subject;
            $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";


            $mail->MsgHTML($body);
			if($anexos!= null)
			{
				for($r=0; $r<sizeof($anexos); $r++)
				{
					$mail->AddAttachment($anexos[$r]['path'],$anexos[$r]['name']);
				}
			}
            ob_start();
            $res = $mail->Send();
            ob_end_clean();
            if($res) {
                $mail->SmtpClose();
                return true;
            }
            else {
                $mail->SmtpClose();
                return false;
            }
    }
	*/

	public function sendMail($toEmail, $subject,$body, $anexos=null,$fromEmail=FROM_EMAIL,$fromName=FROM_NAME)
    {
            ob_start();
            $mail = new PHPMailer();
            $mail->SetFrom($fromEmail, $fromName);
			$mail->AddReplyTo($fromEmail, $fromName);

            $mail->AddAddress($toEmail);                // tell the class to use SMTP
            $mail->IsSMTP();
        	$mail->SMTPAuth   = true;                  // enable SMTP authentication

			$mail->Port       = SMTP_PORT;             // 25                   // set the SMTP server port
			$mail->Host       = SMTP;                  // SMTP server

			$mail->Username   = USERNAME_MAIL;         // SMTP server username
			$mail->Password   = PASSWORD_MAIL;         // SMTP server password

            $mail->Subject    = $subject;
            $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";


            $mail->MsgHTML($body);
			if($anexos!= null)
			{
				for($r=0; $r<sizeof($anexos); $r++)
				{
					$mail->AddAttachment($anexos[$r]['path'],$anexos[$r]['name']);
				}
			}
            $res = $mail->Send();
            ob_end_clean();


            if($res) {
                $mail->SmtpClose();
                return true;
            }
            else {
                $mail->SmtpClose();
                return false;
            }
    }
	public function sendMailLog($toEmail, $subject,$body, $anexos=null,$toEmailCC=null,$fromEmail="", $nomeAmigavel="", $idLog=null)
    {
        if(defined('DB_PROD_BACKUP') && DB_PROD_BACKUP){
            return true;
        }else{
            $mail = new PHPMailer();

            if($fromEmail!="" and $nomeAmigavel==""){
                $fromName=explode("@", $fromEmail);
                $mail->SetFrom($fromEmail, $fromName[0]);
                $mail->AddReplyTo($fromEmail, $fromName[0]);
            }
            else if($fromEmail!="" and $nomeAmigavel){
                $mail->SetFrom($fromEmail, $nomeAmigavel);
                $mail->AddReplyTo($fromEmail, $nomeAmigavel);
            }
            else{
                $mail->SetFrom(FROM_EMAIL, FROM_NAME);
                $mail->AddReplyTo(FROM_EMAIL, FROM_NAME);
            }


            $mail->AddAddress($toEmail);
            $mail->IsSMTP();                           // tell the class to use SMTP
            $mail->SMTPAuth   = true;                  // enable SMTP authentication

            $mail->Port       = SMTP_PORT;             // 25 // set the SMTP server port
            if(FROM_EMAIL!=$fromEmail){
                $mail->Host       = SMTP_CRITSEND;                  // SMTP server
                $mail->Username   = USERNAME_MAIL_CRITSEND;         // SMTP server username
                $mail->Password   = PASSWORD_MAIL_CRITSEND;         // SMTP server password
            }else{
                $mail->Host       = SMTP;                  // SMTP server
                $mail->Username   = USERNAME_MAIL;         // SMTP server username
                $mail->Password   = PASSWORD_MAIL;         // SMTP server password
            }

            $mail->Subject    = $subject;
            $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";

            if($toEmailCC){
                foreach ($toEmailCC as $email_aux) {
                    $mail->AddCC($email_aux);
                }
            }

            $mail->MsgHTML($body);
            if($anexos!= null)
            {
                for($r=0; $r<sizeof($anexos); $r++)
                {
                    $mail->AddAttachment($anexos[$r]['path'],$anexos[$r]['name']);
                }
            }
            ob_start();
            //$res = $mail->Send();
            $res = $mail->Send($idLog);
            ob_end_clean();

            $error1 = explode("Error", $mail->ErrorInfo);
            if( sizeof($error1)>=2 ){
                if( $mail->idLogSend==null ){
                    $email = self::$db->mysql_real_escape_string($toEmail);
                    $subject = self::$db->mysql_real_escape_string($subject);
                    $body = self::$db->mysql_real_escape_string($body);

                    if( $idLog==null ){
                        $sql="insert into ".DBTABL_LOGS_EMAIL." (data,email,assunto,mensagem)
                            values(SYSDATE(),'$toEmail','$subject','$body') ";
                        $res=self::$DB_log->query($sql);
                        $mail->idLogSend=self::$DB_log->insert_id();
                    }
                }
                $sql = " update ".DBTABL_LOGS_EMAIL." set bSucesso='0', tentativas=tentativas+1 where id='".$mail->idLogSend."' ";
            }else{
                $sql = " update ".DBTABL_LOGS_EMAIL." set bSucesso='1' where id='".$mail->idLogSend."' ";
            }
            $res2 = self::$DB_log->query($sql);


            $info=array();
            if($res) {
                $mail->SmtpClose();
                $info['status']=true;
            }
            else {
                $mail->SmtpClose();
                $info['status']=false;
            }
            if(isset($mail->idLogSend) && $mail->idLogSend ){
                $info['idLog']=$mail->idLogSend;
            }
            return $info;
        }
    }


	public function sendMailRecomendacao($toEmail, $subject,$body, $anexos=null)
    {
		self::sendMail($toEmail, $subject,$body, $anexos);

		/*
		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = USERNAME_MAIL_GMAIL;
		$mail->Password = PASSWORD_MAIL_GMAIL;
		$mail->From     = USERNAME_MAIL_GMAIL;

		$mail->SetFrom(FROM_EMAIL, 'Prescrição Electrónica');
        $mail->AddAddress($toEmail);

		$mail->Subject    = $subject;

		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
		$mail->Body = $body;

		if(!$mail->Send())
		{
			$error = 'Mail error: '.$mail->ErrorInfo;
			return false;
		} else {
			$error = 'Message sent!';
			return true;
		}
		*/
    }


    public function calculaIdade($data_nascimento, $data)
    {
        $data_nasc = explode('-', $data_nascimento);
        $data = explode("-", $data);
        $anos = $data[0] - $data_nasc[0];

        if ($data_nasc[1] >= $data[1]){
            if ($data_nasc[2] <= $data[2]){
                return $anos; break;
            } else {
                return $anos-1;
                break;
            }
        } else {
            return $anos;
        }
    }

    public function replaceCaractEspecial($texto) {
		$trocarIsso = array('à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ü','ú','ÿ','À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','O','Ù','Ü','Ú','Ÿ',);
		$porIsso = array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','0','U','U','U','Y',);
		$titletext = str_replace($trocarIsso, $porIsso, $texto);
		return $titletext;
	}

    public function serviceSMS($servico,$res){

       switch ($servico){

            case '1':  //voipcheap
                $res=explode('<result>', $res);
                $res=explode('</result>', $res[1]);

                if($res[0]=='1'){
                    return true;
                }
                return false;

            break;
        }
    }

    public function saveLog($descricao, $query,$idUser="")
    {
        if(MYSQL_LOG==1){
            if($idUser=="")
            {
               $idUser=isset($_SESSION['session_user'])?$_SESSION['session_user']:"";
            }
            $idEnt=isset($_SESSION['entID'])?$_SESSION['entID']:"";
            $mod=isset($_REQUEST['mod'])?$_REQUEST['mod']:"";
            $op=isset($_REQUEST['op'])?$_REQUEST['op']:"";
            $id=isset($_REQUEST['id'])?$_REQUEST['id']:"";
            $ip=isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"";
            $query = str_replace("'", '"', $query);
            $sql = "INSERT INTO ".DBTABL_LOGS." (userID,idEnt, descri, query, ip,modRequest,opRequest,idRequest) " .
                   "VALUES ('".$idUser."','$idEnt', '".$descricao."',
                   '".$query."', '".$ip."', '".$mod."', '".$op."', '".$id."')";
            Reg::$DB_log->query($sql);
        }
    }
    public function saveQueryPerformance($query,$time)
    {
        if(MYSQL_LOG==1){
            $sql=" insert into mysql_performance (query,segundos) values('".addslashes($query)."','".$time."') ";
            Reg::$DB_log->exec_query($sql);
        }
    }

    public function validarBiNif($val)
    {
        $calc = 0;
        while (strlen($val) < 8) {
            $val = "0" . $val;
        }

        for ($i = 0; $i < strlen($val); $i++) {
            (int)$calc = $calc + ($val[$i] * (9-$i));
        }
        if (((int)$calc % 11) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function cutText($txt,$len=28)
    {
          $strlen = strlen($txt);
          for ($i=0;$i<strlen($txt);$i++)
          {
            if ($txt{$i} == '.' || $txt{$i} == ':') $strlen -= 0.5;
            if ($txt{$i} == 'w' || $txt{$i} == '@') $strlen++;
          }
          $strlen = round($strlen);
          if ($strlen > $len)
          {
            $txt = substr($txt,0,($len-3)).'...';
          }
          return $txt;
    }

    public function ipIsValid($id, $ip) {
        $sql = "SELECT * FROM entidade_ip " .
               "WHERE idEntidade=".$id." ";
        $res = Reg::$db->query($sql);
        if(Reg::$db->count($res) > 0) {
            while($row = Reg::$db->row($res)) {
                // obter o ip associado ao DNS
                $row['ip'] = gethostbyname($row['ip']);
                //if($ip == gethostbyname("acin.dyndns.org"))
                //    vd($row['ip']."==".$ip);
                //
                // verificar se o endereço não é um IP
                if(filter_var($row['ip'], FILTER_VALIDATE_IP)) {
                    if($row['ip'] == $ip)
                        return true;
                }

            }
            return false;
        }
        return true;

    }

    public function get_userMessages($bForDisplay=false)
    {
        if(isset($_SESSION['messages'])){
            $array= json_decode($_SESSION['messages'],1);
        }else{
            $_SESSION['messages']=json_encode(array());
            $array= array();
        }
        if($bForDisplay){
            if($array){
               for($i=0;$i<sizeof($array[0]);$i++){
                    if($array[0][$i]['keepMsgForNpages']>0){
                         $array[0][$i]['keepMsgForNpages']--;
                    }elseif($array[0][$i]['keepMsgForNpages']==0){
                        unset($array[0][$i]);
                    }
                }
                for($i=0;$i<sizeof($array[1]);$i++){
                    if($array[1][$i]['keepMsgForNpages']>0){
                         $array[1][$i]['keepMsgForNpages']--;
                    }elseif($array[1][$i]['keepMsgForNpages']==0){
                        unset($array[1][$i]);
                    }
                }
            }
            Reg::set_userMessages($array);
        }
        return $array;
    }
    public function set_userMessages($array)
    {
        $_SESSION['messages']=json_encode($array);
    }
    public function add_userMessage($msgHeader,$mensagem,$data,$type,$keepMsgForNpages=-1,$code='')//$keepMsgForNpages é decrementado 1 por pagina requisitada pelo utilizador, quando chega a 0 é removida
    {
        if($data==''){
            $data=date('Y-m-d H:i:s');
        }
        $msg['id']=str_ireplace('.','',microtime(true));
        $msg['header']=htmlPTchars($msgHeader);
        $msg['msg']=htmlPTchars($mensagem);
        $msg['data']=$data;
        $msg['tipo']=$type;
        $msg['keepMsgForNpages']=$keepMsgForNpages;
        $array=Reg::get_userMessages();
        if($code){
            $array[$type]["_".$code]=$msg;
        }else{
            $array[$type][]=$msg;
        }
        Reg::set_userMessages($array);
    }
    public function mysql_real_escape_request()
    {
        Reg::$db->connect_with_data();
        foreach($_REQUEST as $k=>$v)
        {
            if(is_array($v)){
                $aux=null;
                foreach($v as $k1=>$v1){
                  $aux[$k1]=Reg::$db->mysql_real_escape_string($v1);
                }
                $_REQUEST[$k]=$aux;
            }else{
               $_REQUEST[$k]=Reg::$db->mysql_real_escape_string($v);
            }
        }
    }
    public function utf8_decode_request()
    {
       foreach($_REQUEST as $k=>$v)
        {
            if(is_array($v)){
                $aux=null;
                foreach($v as $k1=>$v1){
                  $aux[$k1]=utf8_decode($v1);
                }
                $_REQUEST[$k]=$aux;
            }else{
               $_REQUEST[$k]=utf8_decode($v);
            }
        }
    }

    public function utf8_encode_request()
    {
       foreach($_REQUEST as $k=>$v)
        {
            if(is_array($v)){
                $aux=null;
                foreach($v as $k1=>$v1){
                  $aux[$k1]=utf8_encode($v1);
                }
                $_REQUEST[$k]=$aux;
            }else{
               $_REQUEST[$k]=utf8_encode($v);
            }
        }
    }
    public function escape_html_params_html($r)
    {
        $ar=array('<','>','/');
        return str_ireplace($ar,'',$r);
    }
    public function escape_request_html_params_html()
    {
        foreach($_REQUEST as $k=>$v)
        {
            if(is_array($v)){
                $aux=null;
                foreach($v as $k1=>$v1){
                  $aux[$k1]=Reg::escape_html_params_html($v1);
                }
                $_REQUEST[$k]=$aux;
            }else{
               $_REQUEST[$k]=Reg::escape_html_params_html($v);
            }
        }
    }
    public function setCache($key,$value,$exp="600") // 600 timeout
    {
        if(!SAVE_MEMCACHE){return;}
        $key=PLATAFORMA."_".SERVIDOR_TESTE."_".DBNAME."_".$key;
        Reg::$cache->set($key,$value,$exp);
    }
    public function getCache($key)
    {
        if(!SAVE_MEMCACHE){return null;}
        $key=PLATAFORMA."_".SERVIDOR_TESTE."_".DBNAME."_".$key;
        $res= Reg::$cache->get($key);
        return $res;
    }
    public function deleteCache($key)
    {
        if(!SAVE_MEMCACHE){return;}
        $key=PLATAFORMA."_".SERVIDOR_TESTE."_".DBNAME."_".$key;
        Reg::$cache->delete($key,0);
    }
    public function is_ajax_request(){
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }
    public function mysql_real_escape_var($var)
    {
        if(is_array($var)){
            $var=Reg::mysql_real_escape_array($var);
        }elseif(is_string($var) && !isReference($var)){
           $var=Reg::$db->mysql_real_escape_string($var);
        }
        return $var;
    }
    public function mysql_real_escape_string($var)
    {
       return  Reg::$db->mysql_real_escape_string($var);
    }
    public function mysql_real_escape_array($var)
    {
        foreach($var as $k=>$v)
        {
            if(is_array($v)){
                $var[$k]=Reg::mysql_real_escape_array($v); ;
            }elseif(is_string($v)){
               $var[$k]=Reg::$db->mysql_real_escape_string($v);
            }
        }
        return $var;
    }
}

?>
