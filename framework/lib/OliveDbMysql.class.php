<?php
/**
 * @package    OLIVE
 * @subpackage data
 * @version    v.1.3
 * @author     Andr� Torgal <info@andretorgal.com>
 * @license    copyright 2007 Andr� Torgal
 */

/**
 * Class Parent : Db
 */
require_once('OliveDb.class.php');

/**
 * db abstraction layer for mysqlmysql_fetch_array()
 */
class OliveDbMysql extends OliveDb
{
	/**
	 * connect to server and select database
	 *
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $db
	 *
	 * @return bool
	 */
    public function data_connect($host, $user, $pass, $db)
    {
        $this->_host=$host;
        $this->_user=$user;
        $this->_pass=$pass;
        $this->_db=$db;
    }
    public function str_con()
    {
       // echo "{$this->_user}@{$this->_host} {$this->_pass} {$this->_db}";
    }
	public function connect($host, $user, $pass, $db, $conexaoAdicional="")
	{
		// DB : connect ?
        if(!OliveDbMysql::$connection)
        {
             OliveDbMysql::$connection = mysql_connect($host, $user, $pass, false);
    		// check error
    		if (OliveDbMysql::$connection)
    		{
    			// try
    			if (mysql_select_db($db))
    			{
    			    OliveDbMysql::$db=$db;
    				return TRUE;
    			}
                die("error: 142");
    			return false;
    		}
            //tentar novamente
            sleep(5);//esperar 5 segundos
            OliveDbMysql::$connection = mysql_connect($host, $user, $pass, false);
            if (OliveDbMysql::$connection)
    		{
    			// try
    			if (mysql_select_db($db))
    			{
    			    OliveDbMysql::$db=$db;
    				return TRUE;
    			}
                die("error: 142");
    			return false;
    		}
            die("error: 141");
    		return false;   
        }else{
            return true;
        }	    
	}
    
    public function connect_with_data()
    {
        $this->connect($this->_host,$this->_user,$this->_pass,$this->_db);
    }
    
    //verifica se a conecção a base de dados está correcta
    // 
	public function checkDB()
    {
       if(OliveDbMysql::$connection)
	   {
	       if(OliveDbMysql::$db==$this->_db){
	           
	           return true;
	       }else{  //tentar mudar a base de dados para a pretendida na mesma conecção
	           if(mysql_select_db($this->_db))
			   {
			     OliveDbMysql::$db=$this->_db;
				 return TRUE;
			   }else{
			     mysql_close(OliveDbMysql::$connection);
                 OliveDbMysql::$connection=NULL;
			     $this->connect($this->_host,$this->_user,$this->_pass,$this->_db);
                 return true;
			   }
	       }
       }else{
            $this->connect($this->_host,$this->_user,$this->_pass,$this->_db);
            return true;
       }
    }
	
	/**
	 * send sql to server and return result source
	 *
	 * @param string $sql statement
	 *
	 * @return resource $result MySQL Resource Identifier
	 */
	public function query($sql,$tentativas=1)
	{	
	    $this->checkDB();
		$result = mysql_query($sql, OliveDbMysql::$connection);
        
		// increment counter
		$this->_query_count++;
		
		// return success
		if($result)
		{
			return $result;
		}
		else
		{
		    if($tentativas>1)
            {
                $this->query($sql,$tentativas--);
            }else{
    			// increment errors
    			$this->_error_count++;
    			// return false
    			return false;
            }
		}
	}
    
	public function query_row($sql,$tentativas=1)
	{
		// fetch result
		if ($result = $this->query($sql,$tentativas))
		{
			// fetch row
			return $this->row($result);
		}
	}

	public function query_value($sql, $field)
	{
		// fetch row
		$row = $this->query_row($sql);
		// return error if query failed
		if (!$row) return false;		
		// check $row for errors
		if (array_key_exists($field, $row))
		{
			// fetch value
			return  $row[$field];
		}
		else return false;
	}
    public function exec_query($sql)
	{	
	    $this->checkDB();
		$res =  mysql_query($sql, OliveDbMysql::$connection);
        return $res;
	}
		
		
	/**
	 * send sql to server and return result Array
	 *
	 * @param string $sql statement
	 * @return resource $result Array
	 */
	public function queryArray($sql,$tentativas=1)
	{
		$retArr = array();
		
		$result = $this->exec_query($sql);
		while($row = $this->row($result)){
			array_push($retArr, $row);	
		}
		// increment counter
		$this->_query_count++;        
        
		// return success
		if($retArr)
		{
			return $retArr;
		}
		else
		{
			// increment errors
			$this->_error_count++;
			// return false
			return false;
		}
	}
	/**
	 * returns numbers of records in result source
	 *
	 * @param resource $result MySQL Resource Identifier
	 *
	 * @return integer
	 */
	public function count($result)
	{
	    $this->checkDB();
		// DB : count records in result
		return mysql_num_rows($result);
	}
	
	/**
	 * returns numbers of records affected by last operation
	 *
	 * @return integer
	 */
	public function affected()
	{
	   $this->checkDB();
		// DB : count records in result
		return mysql_affected_rows();
	}


	/**
	 * fecth next row (assoc_array) from result source
	 *
	 * @param resource $result MySQL Resource Identifier
	 *
	 * @return array with row (FALSE array if no row is returned)
	 */
	public function row($result)
	{
	    $this->checkDB();
		// DB : fetch row as array
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		// failure
		return $row;
	}


/**
	 * Retorna array 1Dimensão com uma coluna como key outra como value.
	 * @param  [type]  $sql        [description]
	 * @param  [type]  $name       [description]
	 * @param  integer $tentativas [description]
	 * @return [type]              [description]
	 */
	public function querySetKeyCollum($sql,$key,$name, $tentativas=1)
	{
		$retArr = array();
		$result = $this->exec_query($sql);
		while($row = $this->row($result))
		{
			//array_push($retArr, $row[$name]);
			$keyValue = $row[$key];
			$retArr[$keyValue] = $row[$name];
		}
		// increment counter
		$this->_query_count++;        
        
		// return success
		if($retArr)
		{
			return $retArr;
		}
		else
		{
			// increment errors
			$this->_error_count++;
			
			// return false
			return false;
		}
	}

	/**
	 * fecth last insert id
	 *
	 * @return integer
	 */
	public function insert_id()
	{
		$id = mysql_insert_id(OliveDbMysql::$connection);

		return $id;
	}
    public function close_all_conections()
	{
		 mysql_close(OliveDbMysql::$connection);
	}
    
    public function mysql_real_escape_string($var)
    {
	   $this->checkDB();
       return mysql_real_escape_string($var); 
    }    
    public function startTransaction()
    {
        $this->query("START TRANSACTION");
    }

    public function commit()
    {
        $this->query("COMMIT");
    }

    public function rollback()
    {
        $this->query("ROLLBACK");
    }
}
?>
