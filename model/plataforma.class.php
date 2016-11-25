<?
class plataformaClass
{
	function pesquisa($r)
	{
		$r=Reg::mysql_real_escape_array($r);

		if(empty($r['page']))
            $r['page']=1;

		$sqlWhere=" p.bDeleted='0' ";
		if($r['plataforma']){
			$sqlWhere.=" and p.plataforma like '%{$r['plataforma']}%' ";
		}

		$sql="select p.* from plataforma p
		  	where $sqlWhere
		  	order by p.id desc ";

		$res['n_pages']=(Reg::$db->count(Reg::$db->query($sql))/NFORPAGE);
		if( $res['n_pages'] != (int)$res['n_pages'] ){
			$res['n_pages']=$res['n_pages']+1;
		}
		$sql.=" LIMIT ".(($r['page']-1)*NFORPAGE).", ".NFORPAGE." ";

		$res['dados']=Reg::$db->queryArray($sql);

		return $res;
	}
	function load_dados($id)
	{
		$id=Reg::mysql_real_escape_array($id);

		$sql="select p.*
		  		from plataforma p
		  	where p.id='$id'";
		$res=Reg::$db->query_row($sql);
		if($res){
		}
		return $res;
	}
	function edit_plataforma_save($r)
	{
		$r=Reg::mysql_real_escape_array($r);

		$id=$r['id'];
		$plataforma=$r['plataforma'];
		$link=$r['link'];
		$emailEnvio=$r['emailEnvio'];
		$nomeEnvio=$r['nomeEnvio'];
		$templateConteudo=$r['templateConteudo'];

		if(empty($id)){
			$sql="insert into plataforma (plataforma,link,emailEnvio,nomeEnvio,templateConteudo)
					values ('$plataforma','$link','$emailEnvio','$nomeEnvio','$templateConteudo') ";
			$res=Reg::$db->query($sql);
			if($res){
				$id=Reg::$db->insert_id();
			}
		}else{
			$sql="update plataforma set plataforma='$plataforma'
						,link='$link'
						,emailEnvio='$emailEnvio'
						,nomeEnvio='$nomeEnvio'
						,templateConteudo='$templateConteudo'
				where id='$id' ";
			$res=Reg::$db->query($sql);
		}
		return $res;
	}
}

?>
