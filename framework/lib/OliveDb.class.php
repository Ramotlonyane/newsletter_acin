<?PHP
/**
 * @package    OLIVE
 * @subpackage data
 * @version    v.1.2
 * @author     André Torgal <info@andretorgal.com>
 * @license    copyright 2007 André Torgal
 */


/**
 * ABSTRACT db abstraction layer
 */
abstract class OliveDb
{
	/**
	 * @var mysql connection
	 */
    static 	$connection = NULL;
    static 	$db = NULL;
    
	/**
	 * @var integer
	 */
	protected $_query_count = 0;
	/**
	 * @var integer
	 */
	protected $_error_count = 0;

    protected $_host = NULL;
    protected $_user = NULL;
    protected $_pass = NULL;
    protected $_db = NULL;
    protected $_connection = NULL;
	/**
	 * contructor
	 */
	public function __construct() { }


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
	abstract public function connect($host, $user, $pass, $db);


	/**
	 * send sql to server and return result source
	 *
	 * @param string $sql statement
	 *
	 * @return resource $result MySQL Resource Identifier
	 */
	abstract public function query($sql);


	/**
	 * returns numbers of records in result source
	 *
	 * @param resource $result MySQL Resource Identifier
	 *
	 * @return integer
	 */
	abstract public function count($result);


	/**
	 * returns numbers of records affected by last operation
	 *
	 * @return integer
	 */
	abstract public function affected();


	/**
	 * fecth next row (assoc_array) from result source
	 *
	 * @param resource $result MySQL Resource Identifier
	 *
	 * @return array with row (FALSE array if no row is returned)
	 */
	abstract public function row($result);


	/**
	 * fecth last insert id
	 *
	 * @return integer
	 */
	abstract public function insert_id();



	/**
	 * return query count
	 *
	 * @return integer
	 */
	public function queries()
	{
		return $this->_query_count;
	}


	/**
	 * return query count
	 *
	 * @return integer
	 */
	public function errors()
	{
		return $this->_error_count;
	}

}

?>