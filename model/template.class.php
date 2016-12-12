<?
class templateClass
{

	function listaTemplate()
	{
		$sql = "select id,conteudo,hash from newsletter where bDeleted='0' ";
		$res = Reg::$db->queryArray($sql);

		return $res;
	}
}
?>