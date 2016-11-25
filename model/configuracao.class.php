<?

class configuracaoClass
{
	function login($user,$pass)
	{
		$user=Reg::mysql_real_escape_string($user);
		$pass=Reg::mysql_real_escape_string($pass);
		$sql="select * from utilizador where user='$user' and pass=sha1('$pass') and bDeleted='0' ";
		$res=Reg::$db->query_row($sql);
		return $res;
	}
}

