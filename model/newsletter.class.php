<?
class newsletterClass
{
	function listaPlataformas()
	{
		$sql = "select * from plataforma where bDeleted='0' ";
		$res = Reg::$db->queryArray($sql);

		return $res;
	}
	function pesquisa($r)
	{
		$r=Reg::mysql_real_escape_array($r);

		if(empty($r['page']))
            $r['page']=1;

		$sqlWhere=" n.bDeleted='0' ";
		if($r['idPlataforma']){
			$sqlWhere.=" and n.idPlataforma='{$r['idPlataforma']}' ";
		}
		$sql="select n.*,ne.estado,p.plataforma
		  		from newsletter n
				left join plataforma p on n.idPlataforma=p.id
		  		left join newsletter_estado ne on ne.id=n.idEstado
		  	where $sqlWhere
		  	order by n.data desc ";

		$res['n_pages']=(Reg::$db->count(Reg::$db->query($sql))/NFORPAGE);
		if( $res['n_pages'] != (int)$res['n_pages'] ){
			$res['n_pages']=$res['n_pages']+1;
		}
		$sql.=" LIMIT ".(($r['page']-1)*NFORPAGE).", ".NFORPAGE." ";

		$res['dados']=Reg::$db->queryArray($sql);

		return $res;
	}
	function novaNewsletter($idUtilizador)
	{
		$idUtilizador=Reg::mysql_real_escape_array($idUtilizador);
		$hash=sha1(time().rand());

		$sql="insert into newsletter (idUtilizador,hash,bDeleted) values ('$idUtilizador','$hash',1) ";
		$res=Reg::$db->query($sql);
		if($res){
			$res=Reg::$db->insert_id();
		}
		return $res;
	}

	function load_dados($idUtilizador,$idNewsletter)
	{
		$idUtilizador=Reg::mysql_real_escape_array($idUtilizador);
		$idNewsletter=Reg::mysql_real_escape_array($idNewsletter);

		$sql="select n.*,p.plataforma,p.templateConteudo,e.estado from newsletter n
				left join plataforma p on n.idPlataforma=p.id
				left join newsletter_estado e on e.id=n.idEstado
			where n.id='$idNewsletter' ";
		$res=Reg::$db->query_row($sql);
		if($res){
			if(empty($res['conteudo'])){
				$res['conteudo']=$res['templateConteudo'];//carregar template
			}

			$sql="select * from newsletter_ficheiro
						where idNewsletter='$idNewsletter' and bDeleted='0' ";
			$res['ficheiros']=Reg::$db->queryArray($sql);

			$sql="select l.*,count(p.id) as nContactos from newsletter_lista_contacto nc
					left join contacto_lista l on l.id=nc.idContactoLista
					left join contacto_lista_email p on (p.idLista = l.id)
				where nc.idNewsletter='$idNewsletter'
			group by l.id ";
			$res['contactos']=Reg::$db->queryArray($sql);

			//analisar o envio
			if($res['bDeleted']=="0" && $res['idEstado']>1 ) {
				$res['envio']=newsletterClass::analise_envio($idNewsletter);
				if($res['idEstado']=="2" && $res['envio']['nProcessados']==$res['envio']['nContactos']){
					newsletterClass::newsletter_processada($idNewsletter);
					$res['idEstado']=="3";
					$res['estado']="Enviado";
				}
			}
		}

		return $res;
	}
	function lista_contactos()
	{
		$sql="select l.*,count(p.id) as nContactos from contacto_lista l
				left join contacto_lista_email p on (p.idLista = l.id)
				where l.bDeleted='0'
			group by l.id
			order by l.id asc  ";
		$res=Reg::$db->queryArray($sql);

		return $res;
	}
	function importFicheiro($idUtilizador,$idNewsletter,$files,$request)
	{
		$idUtilizador=Reg::mysql_real_escape_array($idUtilizador);
		$idNewsletter=Reg::mysql_real_escape_array($idNewsletter);
		$request=Reg::mysql_real_escape_array($request);

		// Get a file name
        if (isset($request["name"])) {
            $fileName = $request["name"];
        } elseif (!empty($files)) {
            $fileName = $files["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $fileName=utf8_decode($fileName);
        $fileName=str_ireplace(" ","",$fileName);

        $dir = UPLOAD_DIR;
        $caminho=createBackupFile($dir,".file");
        $caminhoAux=str_ireplace(UPLOAD_DIR,'',$caminho);

        // Open temp file
        if (!$out = @fopen($caminho, $chunks ? "ab" : "wb")) {
            return json_decode('{"jsonrpc" : "2.0", "error" :
            	{"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($files)) {
            if ($files["file"]["error"] || !is_uploaded_file($files["file"]["tmp_name"])) {
                return json_decode('{"jsonrpc" : "2.0", "error" :
                	{"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($files["file"]["tmp_name"], "rb")) {
                return json_decode('{"jsonrpc" : "2.0", "error" :
                	{"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                return json_decode('{"jsonrpc" : "2.0", "error" :
                	{"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        $tamanho =filesize($caminho);

        $ext = strtolower( pathinfo($fileName, PATHINFO_EXTENSION) );
        if( in_array($ext, array('png','jpg','jpeg','gif'))){
            $bImagem="1";
        }else{
            $bImagem="0";
        }


        $sql="insert into newsletter_ficheiro (idNewsletter,idUtilizador,caminho,nome,tamanho,bImagem)
        					values ('$idNewsletter','$idUtilizador','$caminhoAux','$fileName','$tamanho','$bImagem') ";
        $res=Reg::$db->query($sql);

        if($res){
        	$id=Reg::$db->insert_id();

        	return json_decode('{"jsonrpc" : "2.0", "result" : null,"sucesso":true, "id" : "'.$id.'"
        		,"nome":"'.utf8_encode($fileName).'"
        		,"hash":"'.sha1($caminhoAux).'"}');
        }

        return json_decode('{"jsonrpc" : "2.0", "error" :
        	{"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	}

	function removerFicheiro($idUtilizador,$idNewsletter,$id)
	{
		$idUtilizador=Reg::mysql_real_escape_array($idUtilizador);
		$idNewsletter=Reg::mysql_real_escape_array($idNewsletter);
		$id=Reg::mysql_real_escape_array($id);

		//newsletter existe e está no estado em elaboração
		$sql = "select * from newsletter where id='$idNewsletter' and bDeleted='0' and idEstado='1' ";
		$res = Reg::$db->query_row($sql);

		if($res){
			$sql="update newsletter_ficheiro set bDeleted='1' where id='$id' and idNewsletter='$idNewsletter'  ";
			$res=Reg::$db->query($sql);

			return $res;
		}
	}
	function editavel_newsletter($id)
	{
		$id=Reg::mysql_real_escape_array($id);

		//newsletter existe e está no estado em elaboração
		$sql = "select * from newsletter where id='$id' and bDeleted='0' and idEstado='1' ";
		$res = Reg::$db->query_row($sql);

		return $res;
	}
	function save_new1($idUtilizador,$idNewsletter,$r)
	{
		if (empty($idNewsletter)) {
			$sql="insert into newsletter (idUtilizador,hash) values ('$idUtilizador','$hash') ";
			$res=Reg::$db->query($sql);
			if($res){
				$idNewsletter=Reg::$db->insert_id();
			}
		}
		$idUtilizador=Reg::mysql_real_escape_array($idUtilizador);
		$idNewsletter=Reg::mysql_real_escape_array($idNewsletter);
		$r=Reg::mysql_real_escape_array($r);

		$idPlataforma=$r['idPlataforma'];
		$descricao= $r['descricao'];
		$assunto  = $r['assunto'];

		$sql = "update newsletter set idPlataforma='$idPlataforma'
									,descricao='$descricao'
									,assunto='$assunto'
									,bDeleted='0'
						where id='$idNewsletter' and bDeleted='0' ";
		$res=Reg::$db->query($sql);

		if($res){

			$sql="delete from newsletter_lista_contacto where idNewsletter='$idNewsletter' ";
			$res=Reg::$db->query($sql);
			if($r['idContactos']){
				foreach ($r['idContactos'] as $idLista) {
					$sql="insert into newsletter_lista_contacto (idNewsletter,idContactoLista)
														values ($idNewsletter,$idLista) ";
					$res=Reg::$db->query($sql);
				}
			}

			$sql="update newsletter_ficheiro set bAnexo='0' where idNewsletter='$idNewsletter' ";
			$res=Reg::$db->query($sql);
			if($r['anexos']){
				foreach ($r['anexos'] as $idAnexo) {
					$sql="update newsletter_ficheiro set bAnexo='1'
							 where idNewsletter='$idNewsletter' and id='$idAnexo' ";
					$res=Reg::$db->query($sql);
				}
			}
		}
		return $res;
	}
	function save_new2($idUtilizador,$idNewsletter,$r)
	{
		$idUtilizador=Reg::mysql_real_escape_array($idUtilizador);
		$idNewsletter=Reg::mysql_real_escape_array($idNewsletter);
		$r=Reg::mysql_real_escape_array($r);
		$conteudo=$r['conteudo'];

		$sql="update newsletter set conteudo='$conteudo'
				where id='$idNewsletter' and bDeleted='0' ";
		$res=Reg::$db->query($sql);

		return $res;
	}

	function get_ficheiro($id,$hash)
	{
		$id=Reg::mysql_real_escape_array($id);
		$hash=Reg::mysql_real_escape_array($hash);

		$sql="select * from newsletter_ficheiro
				where id='$id' and sha1(caminho)='$hash' ";
		$res=Reg::$db->query_row($sql);

		return $res;
	}
	function load_newsletter_hash($id,$hash)
	{
		$id=Reg::mysql_real_escape_array($id);
		$hash=Reg::mysql_real_escape_array($hash);

		$sql="select n.*,p.plataforma from newsletter n
				left join plataforma p on n.idPlataforma=p.id
				where n.id='$id' and n.hash='$hash' ";
		$res=Reg::$db->query_row($sql);

		return $res;
	}
	function get_email_newsletter_hash($idEnvio,$hashEnvio)
	{
		$idEnvio=Reg::mysql_real_escape_array($idEnvio);
		$hashEnvio=Reg::mysql_real_escape_array($hashEnvio);

		$sql="select e.* from newsletter_envio en
					left join contacto_email e  on e.id=en.idEmail
			where en.id='$idEnvio' and sha1(concat(en.id,'.',en.idNewsletter,'.',en.idEmail))='$hashEnvio' ";
		$res=Reg::$db->query_row($sql);

		return $res;
	}
	/* descontinuado 2014-01-12
	function enviar_email_teste($id,$email)
	{
		$id=Reg::mysql_real_escape_array($id);
		$email=Reg::mysql_real_escape_array($email);

		$sql="select n.*,p.plataforma,p.emailEnvio,p.nomeEnvio from newsletter n
				left join plataforma p on n.idPlataforma=p.id
				where n.id='$id' ";
		$res=Reg::$db->query_row($sql);
		if($res)
		{
			if( empty($res['nomeEnvio']) )
				$res['nomeEnvio']=FROM_NAME;

			if( empty($res['emailEnvio']) )
				$res['emailEnvio']=FROM_EMAIL;


			$anexos=null;
			$sql="select * from newsletter_ficheiro
						where idNewsletter='$id' and bDeleted='0' and bAnexo='1' ";
			$res['ficheiros']=Reg::$db->queryArray($sql);
			if($res['ficheiros']){
				foreach ($res['ficheiros'] as $a) {
					$anexo['path']=UPLOAD_DIR.$a['caminho'];
					$anexo['name']=$a['nome'];
					$anexos[]=$anexo;
				}
			}
			$res= Reg::sendMail($email,$res['assunto'],$res['conteudo'],$anexos,$res['emailEnvio'],$res['nomeEnvio']);
		}
		return $res;
	}*/
	function obter_dados_envio_newsletter($id)
	{
		$id=Reg::mysql_real_escape_array($id);

		$sql="select n.*,p.plataforma,p.emailEnvio,p.nomeEnvio from newsletter n
				left join plataforma p on n.idPlataforma=p.id
				where n.id='$id' ";
		$res=Reg::$db->query_row($sql);
		if($res)
		{
			if( empty($res['nomeEnvio']) )
				$res['nomeEnvio']=FROM_NAME;

			if( empty($res['emailEnvio']) )
				$res['emailEnvio']=FROM_EMAIL;

			$res['anexos']=null;
			$sql="select * from newsletter_ficheiro
						where idNewsletter='$id' and bDeleted='0' and bAnexo='1' ";
			$res['ficheiros']=Reg::$db->queryArray($sql);
			if($res['ficheiros']){
				foreach ($res['ficheiros'] as $a) {
					$anexo['path']=UPLOAD_DIR.$a['caminho'];
					$anexo['name']=$a['nome'];
					$res['anexos'][]=$anexo;
				}
			}
		}
		return $res;
	}
	function aprovar_newsletter($idUtilizador,$id,$r)
	{
		$idUtilizador=Reg::mysql_real_escape_array($idUtilizador);
		$id=Reg::mysql_real_escape_array($id);
		$r=Reg::mysql_real_escape_array($r);
		$observacoes=$r['observacoes'];

		$sql="update newsletter set idEstado='2',
					idUtilizadorAprovacao='$idUtilizador',
					dataAprovacao=sysdate(),
					observacoes='$observacoes'
				where id='$id' and bDeleted='0' and idEstado='1' ";
		$res=Reg::$db->query($sql);

		if($res){
			$sql="delete from newsletter_envio where idNewsletter='$id' ";
			Reg::$db->query($sql);//remover registos de envio de testes

			$sql="insert into newsletter_envio (idNewsletter,idEmail,data)
					select n.id,cle.idEmail,sysdate() from newsletter n
					       left join newsletter_lista_contacto nlc on nlc.idNewsletter=n.id
					       left join contacto_lista nl on nl.id=nlc.idContactoLista
					       left join contacto_lista_email cle on cle.idLista=nl.id
					       left join contacto_email ce on ce.id=cle.idEmail
					where n.id='$id' and ce.bBlacklist='0'
					group by ce.id ";
			$res=Reg::$db->query($sql);
		}

		return $res;
	}
	function load_newsletter_linha($id)
	{
		$id=Reg::mysql_real_escape_array($id);

		$sql="select n.* from newsletter n
				where n.id='$id'  ";
		$res=Reg::$db->query_row($sql);

		return $res;
	}
	function analise_envio($id){
		$id=Reg::mysql_real_escape_array($id);
		$sql="select count(e.id) as nContactos
					 ,sum(e.bProcessado) as nProcessados
					 ,sum(e.bSucesso) as nEnvioSucesso
					 ,sum(e.bErro) as nEnvioErro
			 from newsletter_envio e
		where idNewsletter='$id' ";
		$res=Reg::$db->query_row($sql);
		if($res){
			if($res['nProcessados']=="")
				$res['nProcessados']=0;

			if($res['nEnvioSucesso']=="")
				$res['nEnvioSucesso']=0;

			if($res['nEnvioErro']=="")
				$res['nEnvioErro']=0;
		}
		return $res;
	}
	function newsletter_processada($id)
	{
		$id=Reg::mysql_real_escape_array($id);
		//confirmar que foi tudo processado

		$res['envio']=newsletterClass::analise_envio($id);
		if($res['envio'] && $res['envio']['nProcessados']==$res['envio']['nContactos']){
			$sql="update newsletter set idEstado='3' where id='$id' ";
			$res=Reg::$db->query_row($sql);
			return $res;
		}
	}
	function get_email_processar($id,$nEmails=10)
	{
		$id=Reg::mysql_real_escape_array($id);
		$nEmails=Reg::mysql_real_escape_array($nEmails);

		$sql="select n.*,ce.email from newsletter_envio n
				left join contacto_email ce on ce.id=n.idEmail
			where n.idNewsletter='$id' and n.bProcessado='0' and ce.bBlacklist='0'
			limit $nEmails  ";
		$res=Reg::$db->queryArray($sql);

		return $res;
	}
	function registar_envio($id,$idEmail,$bSucesso)
	{
		$id=Reg::mysql_real_escape_array($id);
		$idEmail=Reg::mysql_real_escape_array($idEmail);
		$bSucesso=Reg::mysql_real_escape_array($bSucesso);

		$bSucesso=$bSucesso?"1":"0";
		$bErro=$bSucesso?"0":"1";
		$sql="update newsletter_envio set bProcessado='1'
								,bSucesso='$bSucesso'
								,bErro='$bErro'
								,data=sysdate()
						where idNewsletter='$id' and idEmail='$idEmail' ";
		$res=Reg::$db->query($sql);

		if($bErro){
			$sql=" update contacto_email set bErroEnvio='1' where id='$idEmail' ";
			$res=Reg::$db->query($sql);
		}
		return $res;
	}
	function cancelar_newsletter($id)
	{
		$id=Reg::mysql_real_escape_array($id);

		$sql="update newsletter set idEstado=4 where id='$id' and idEstado=2 ";
		$res=Reg::$db->query($sql);

		return $res;
	}
	function novo_contacto_newsletter($id,$idEmail)
	{
		$id=Reg::mysql_real_escape_array($id);
		$idEmail=Reg::mysql_real_escape_array($idEmail);

		$sql="insert into newsletter_envio (idNewsletter,idEmail,data)
						values ('$id','$idEmail',sysdate()) ";
		$res=Reg::$db->query($sql);
		if($res){
			$id=Reg::$db->insert_id();
		}

		return $id;
	}
	function registar_newsletter_acesso($idEnvio,$idFicheiro='0')
	{
		$id=Reg::mysql_real_escape_array($idEnvio);
		$idFicheiro=Reg::mysql_real_escape_array($idFicheiro);

		$sql="insert into newsletter_envio_acesso (idEnvio,idFicheiro)
						values ('$id','$idFicheiro') ";
		$res=Reg::$db->query($sql);
		if($res){
			$id=Reg::$db->insert_id();
		}

		return $id;
	}

	/**
	 * regista uma visualização de um link, associado a um email
	 * @param  integer $idEnvio id do envio para um determinado email
	 * @param  integer $idLink  id do link visualizado
	 * @return integer/null     retorna o id se for corretamente registado, caso contrário NULL
	 */
	function registar_newsletter_acesso_link($idEnvio, $idLink)
	{
		$sql = "INSERT INTO newsletter_link_acesso (data, idEnvio, idLink)
				VALUES (sysdate(),
				        $idEnvio,
				        $idLink);";

		$res = Reg::$db->query($sql);

		if ($res) {
			$id = Reg::$db->insert_id();
		}

		return $id;
	}

	/**
	 * adiciona percentagens de visualizacoes no array final de recursos
	 * @param  array   $resultado_query          resultado final
	 * @param  array   $resultado_query_contagem resultados com percentagens de visualizacoes
	 * @param  integer $totalEnvios              total de envios para calculo de percentagens
	 * @return array                             resultado final
	 */
	function adiciona_visualizacoes_array($resultado_query, $resultado_query_contagem, $totalEnvios)
	{
		foreach ($resultado_query as $key => $value) {
			foreach ($resultado_query_contagem as $key1 => $value1) {
				if ($value["id"] == $value1["id"]) {
					$vis = $value1["visualizacoes"];
					if (isset($vis)) {
						$resultado_query[$key]["visualizacoes"] = $vis." (".(($vis/$totalEnvios)*100)."%)";
					}
					break;
				}
			}

			if (!isset($resultado_query[$key]["visualizacoes"])) {

				$resultado_query[$key]["visualizacoes"] = "0 (0%)";
			}
		}

		return $resultado_query;
	}

	/**
	 * faz fetch de todos os tipos de resources e devolve um array contendo todos links e ficheiros associados ao newsletter requisitado
	 * este array contém as informações necessárias a renderizar no relatório
	 * @param  int 	 $idNewsletter newsletter pretendido
	 * @return array               array contendo todas as informações pertinentes para o relatório
	 */
	function obter_relatorio($idNewsletter)
	{
		$resultado = Array();

		$sql = "SELECT count(n.id) AS total
				FROM newsletter_envio AS n
				WHERE n.idNewsletter = $idNewsletter;";

		$totalEnvios = Reg::$db->query_row($sql)["total"];

		$sql = "SELECT nf.id,
					   'Ficheiro' AS tipo,
				       nf.nome
				FROM newsletter_ficheiro AS nf
				WHERE nf.idNewsletter = $idNewsletter;";

		$resultado_query = Reg::$db->queryArray($sql);

		if (!empty($resultado_query)) {

			$sql = "SELECT nf.id,
					       count(DISTINCT nea.idEnvio) AS visualizacoes
					FROM newsletter_ficheiro AS nf
					LEFT JOIN newsletter_envio_acesso AS nea ON nea.idFicheiro = nf.id
					LEFT JOIN newsletter_envio AS ne ON ne.id = nea.idEnvio
					WHERE ne.idNewsletter = $idNewsletter
					  AND ne.bSucesso = 1
					GROUP BY nf.id;";

			$resultado_query_contagem = Reg::$db->queryArray($sql);

			$resultado_query = $this->adiciona_visualizacoes_array($resultado_query, $resultado_query_contagem, $totalEnvios);

			$resultado = array_merge($resultado,$resultado_query);
		}

		$sql = "SELECT nl.id,
					   'Link' AS tipo,
				       nl.nome
				FROM newsletter_link AS nl
				WHERE nl.idNewsletter = $idNewsletter;";

		$resultado_query = Reg::$db->queryArray($sql);

		if (!empty($resultado_query)) {

			$sql = "SELECT nl.id,
					       count(DISTINCT nla.idEnvio) AS visualizacoes
					FROM newsletter_link AS nl
					LEFT JOIN newsletter_link_acesso AS nla ON nla.idLink = nl.id
					LEFT JOIN newsletter_envio AS ne ON ne.id = nla.idEnvio
					WHERE ne.idNewsletter = $idNewsletter
					  AND ne.bSucesso = 1
					GROUP BY nl.id;";

			$resultado_query_contagem = Reg::$db->queryArray($sql);

			$resultado_query = $this->adiciona_visualizacoes_array($resultado_query, $resultado_query_contagem, $totalEnvios);

			$resultado = array_merge($resultado,$resultado_query);
		}

		return $resultado;
	}

	/**
	 * adiciona novo link
	 * @param  integer $idNewsletter id da newsletter a associar
	 * @param  integer $idUtilizador id do utilizador a associar
	 * @param  string $nome          nome do link
	 * @param  string $url           url do link
	 * @return integer/array         id do novo link, caso contrário o resultado da query de insert
	 */
	function adicionar_link($idNewsletter, $idUtilizador, $nome, $url)
	{
		$sql = "INSERT INTO newsletter_link (nome, url, idNewsletter, idUtilizador)
				VALUES ('$nome',
				        '$url',
				        $idNewsletter,
				        $idUtilizador);";

		$res = Reg::$db->query($sql);

		if($res){
			$res=Reg::$db->insert_id();
		}

		return $res;
	}

	/**
	 * obtem os links associados a uma newsletter
	 * @param  integer $idNewsletter id da newsletter
	 * @return array                 um array contendo uns links
	 */
	function obter_links($idNewsletter)
	{
		$sql = "SELECT l.id,
				       l.nome,
				       l.url
				FROM newsletter_link AS l
				WHERE l.idNewsletter = $idNewsletter
				AND l.bDeleted = 0;";

		return Reg::$db->queryArray($sql);
	}

	/**
	 * remove o link com o id fornecido
	 * @param  integer $id id do link a remover
	 * @return array   	   resultado da query
	 */
	function remover_link($id)
	{
		$sql = "UPDATE newsletter_link AS l
				SET l.bDeleted = 1
				WHERE l.id = $id;";

		Reg::$db->query($sql);
	}

	/**
	 * gera um link para ser registado posteriormente ao click como visualizado, e redireccionado para o url dado
	 * @param  string $url url a redireccionar
	 * @param  sttring $id id do link
	 * @return string      url final
	 */
	function gerar_link_url($url, $id)
	{
		return URL."/link.php?id=".$id."&hash=".sha1($url)."&destino=".urlencode($url)."&@hash_envio_newsletter@&";
	}

	/**
	 * obtem um link pela hash do url e o seu id
	 * @param  integer $id      id do link a encontrar
	 * @param  string $hash     hash do url a encontrar
	 * @return NULL/array       retorna o link em caso de sucesso, casso contrário null
	 */
	function obter_link($id, $hash) {

		$sql = "SELECT l.id,
				       l.nome,
				       l.url
				FROM newsletter_link AS l
				WHERE l.id = $id
				  AND sha1(l.url) = '$hash';";

		return Reg::$db->query_row($sql);
	}

	/**
	 * remove o link com o id fornecido
	 * @param  integer $id id do link a remover
	 * @return array   	   resultado da query
	 */
	function remover($id)
	{
		$sql = "UPDATE newsletter 
				SET bDeleted = 1
				WHERE id = $id;";

		Reg::$db->query($sql);
	}

		/**
	 * Revert o link com o id fornecido
	 * @param  integer $id id do link a remover
	 * @return array   	   resultado da query
	 */
	function revertNews($id)
	{
		$sql = "UPDATE newsletter 
				SET idEstado = 1,idUtilizadorAprovacao =1
				WHERE id = $id;";

		Reg::$db->query($sql);
	}

	/**
	 * copy newsletter
	 * @param  integer $id id do link 
	 * @return integer    	   
	 */
	function copy($id)
	{
		$sql="insert into newsletter (data,idPlataforma,idEstado,descricao,assunto,conteudo,hash) 
		select NOW(),idPlataforma,idEstado,concat('(copy) ',descricao),assunto,conteudo,hash from newsletter where id='$id' ";
		$res= Reg::$db->query($sql);

		if($res){
			$res=Reg::$db->insert_id();
		}

		return $res;
	}
}
?>
