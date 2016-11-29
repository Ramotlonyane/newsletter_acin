<?
class templateClass
{

	function listaTemplate()
	{
		$sql = "select id,conteudo from newsletter where bDeleted='0' ";
		$res = Reg::$db->queryArray($sql);

		return $res;
	}
}
?>