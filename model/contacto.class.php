<?
class contactoClass
{
	function listas()
	{
		$sql="select l.*,count(p.id) as nContactos from contacto_lista l
				left join contacto_lista_email p on (p.idLista = l.id)
				where l.bDeleted='0'
			group by l.id
			order by l.id asc  ";
		$res=Reg::$db->queryArray($sql);

		return $res;
	}

	function list_subfolders($idContactList)
	{
		if (!empty($idContactList)){

		$sql="select * from email_folder_table
				where idcontact_list = '".$idContactList."' and bDeleted = '0' ";

		$res=Reg::$db->queryArray($sql);

		return $res;
		}
		
	}
	
	function pesquisa($r)
	{

		$r=Reg::mysql_real_escape_array($r);
		if(empty($r['page']))
            $r['page']=1;

		$sqlWhere=" true ";
		if($r['email']){
			$sqlWhere.=" and e.email like '{$r['email']}' ";
		}
		if($r['idLista']){
			$sqlWhere.=" and cle.idLista = '{$r['idLista']}' ";
		}
		if($r['bBlacklist']){
			$sqlWhere.=" and e.bBlacklist = '1' ";
		}
		if($r['bErroEnvio']){
			$sqlWhere.=" and e.bErroEnvio = '1' ";
		}
		if($r['deleteLista']){
			$this->lista_remover($r['idLista']);
		}


		$sql="select e.*,group_concat(l.descricao) as listas
		  		from contacto_email e
				left join contacto_lista_email cle on cle.idEmail=e.id
		  		left join contacto_lista l on l.id=cle.idLista
		  	where $sqlWhere and e.bDeleted='0' 
		  	group by e.id
		  	order by e.id desc ";

		if( empty($r['export']) ){
			$res['n_pages']=(Reg::$db->count(Reg::$db->query($sql))/NFORPAGE);
			if( $res['n_pages'] != (int)$res['n_pages'] ){
				$res['n_pages']=$res['n_pages']+1;
			}
			$sql.=" LIMIT ".(($r['page']-1)*NFORPAGE).", ".NFORPAGE." ";
		}

		$res['dados']=Reg::$db->queryArray($sql);

		return $res;
	}
	function load_dados($id)
	{
		$id=Reg::mysql_real_escape_array($id);

		$sql="select e.*
		  		from contacto_email e
		  	where e.id='$id'";
		$res=Reg::$db->query_row($sql);
		if($res){
			$sql="select l.* from contacto_lista_email cle
		  		left join contacto_lista l on l.id=cle.idLista
		  		where cle.idEmail='$id' ";
		  	$res['listas']=Reg::$db->queryArray($sql);
		}
		return $res;
	}
	function edit_contacto_save($r)
	{

		$r=Reg::mysql_real_escape_array($r);

		$id 			= $r['id'];
		$email          = $r['email_lista'];
        $idContactos    = $r['idContactos'];
        $idSubfolders    = $r['idSubfolder'];

        if(empty($id)){

			$sql="insert into contacto_email (email,bBlacklist)
						values ('$email','0') ";
			$res=Reg::$db->query($sql);
				if($res){
						$id=Reg::$db->insert_id();
					}	
		}else{
			$sql="update contacto_email set email='$email',bBlacklist='0'
				where id='$id' ";
			$res=Reg::$db->query($sql);
		}

		if($id){
			$sql="delete from contacto_lista_email where idEmail='$id' ";
			Reg::$db->query($sql);
			$sqlfolder="delete from tblcontacto_email_tblemail_folder where idcontact_email='$id' ";
			Reg::$db->query($sqlfolder);

			if($idContactos){
				foreach ($idContactos as $idContacto) {
					$sql="insert into contacto_lista_email (idLista,idEmail)
							values ('$idContacto','$id') ";
					Reg::$db->query($sql);

						if($idSubfolders && $idContactos){

						foreach ($idSubfolders as $idSubfolder) {
							$sqlfolder="insert into tblcontacto_email_tblemail_folder (idcontact_email,idEmail_folder,bDeleted)
									values ('$id', '$idSubfolder','0') ";
							Reg::$db->query($sqlfolder);
						}
					}
				}
			}
			
		}
		return $res;
/*################################################################################# OLD TECHNIQUE ##########################################################################################################*/
		/*$id=$r['id'];
		$email=$r['email'];
		$bBlacklist=$r['bBlacklist'];
		$subfolder = $r['DialogSubfolder'];

		if(empty($id)){
			if ($subfolder) {
				var_dump($subfolder);die();
			}else{
				$sql="insert into contacto_email (email,bBlacklist)
						values ('$email','$bBlacklist') ";
				$res=Reg::$db->query($sql);
				if($res){
					$id=Reg::$db->insert_id();
				}
			}
			
		}else{
			$sql="update contacto_email set email='$email',bBlacklist='$bBlacklist'
				where id='$id' ";
			$res=Reg::$db->query($sql);
		}

		if($id){
			$sql="delete from contacto_lista_email where idEmail='$id' ";
			Reg::$db->query($sql);
			if($r['idListas']){
				foreach ($r['idListas'] as $idLista) {
					$sql="insert into contacto_lista_email (idLista,idEmail)
							values ('$idLista','$id') ";
					Reg::$db->query($sql);
				}
			}
		}*/
/*################################################################################# OLD TECHNIQUE ##########################################################################################################*/		
	}
	function load_lista($id)
	{
		$id=Reg::mysql_real_escape_array($id);

		$sql="select l.* from contacto_lista l
				where l.id='$id' ";
		$res=Reg::$db->query_row($sql);

		return $res;
	}
	function edit_lista_save($r)
	{
		$r=Reg::mysql_real_escape_array($r);

		$id=$r['id'];
		$descricao=$r['descricao'];

		if(empty($id)){
			$sql="insert into contacto_lista (descricao)
					values ('$descricao') ";
			$res=Reg::$db->query($sql);
			if($res){
				$id=Reg::$db->insert_id();
			}
		}else{
			$sql="update contacto_lista set descricao='$descricao'
				where id='$id' ";
			$res=Reg::$db->query($sql);
		}
		return $res;
	}
	function lista_remover($id)
	{
		$sql = "UPDATE contacto_lista 
				SET bDeleted = 1
				WHERE id = $id;";

		if (Reg::$db->query($sql)) {
			echo "Lista Delete Successfully";
		}
	}
	function email_remover($id)
	{
		$sql = "UPDATE contacto_email 
				SET bDeleted = 1
				WHERE id = $id;";

			Reg::$db->query($sql);
	}
	function getEmail($email)
	{
		$email=Reg::mysql_real_escape_array($email);

		$sql="select id from contacto_email where email='$email' ";
		$id=Reg::$db->query_value($sql,'id');le($sql);

		if($id)
			return $id;

		$sql="insert into contacto_email (email) values ('$email') ";
		$res=Reg::$db->query($sql);le($sql);
		if($res){
			$id=Reg::$db->insert_id();
			return $id;
		}
	}
	function associar_email_lista($idEmail,$idLista)
	{
		$idEmail=Reg::mysql_real_escape_array($idEmail);
		$idLista=Reg::mysql_real_escape_array($idLista);

		$sql="insert into contacto_lista_email (idLista,idEmail)
							values ('$idLista','$idEmail') ";
		$res= Reg::$db->query($sql);

		return $res;
	}
	function limpar_lista($idLista)
	{
		$idLista=Reg::mysql_real_escape_array($idLista);

		$sql="delete from contacto_lista_email where idLista='$idLista' ";
		$res= Reg::$db->query($sql);

		return $res;
	}

	function contacto_adicionar_blacklist($idEmail)
	{
		$idEmail=Reg::mysql_real_escape_array($idEmail);

		$sql="update contacto_email set bBlacklist='1' where id='$idEmail' ";
		$res= Reg::$db->query($sql);

		return $res;
	}
}

?>
