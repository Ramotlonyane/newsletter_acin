<?php
/**
 * @package    OLIVE
 * @subpackage output
 * @version    v.1.0
 * @author     André Torgal <info@andretorgal.com>
 * @license    copyright 2007 André Torgal
 */


/**
 * Enter description here...
 *
 */
abstract class OliveTemplateEngine
{
	
	/**
	 * @var array      stores output variables
	 */
  protected $_vars = array();
  /**
   * @var string     path to template files
   */
  protected $_path = array();

  
  /**
   * constructor
   */
  public function __construct()
  {

  }
  
  
  // ====== SETUP =======
  
  
  /**
   * setup environment for templating engine
   */
  abstract public function setup();
  

  /**
   * Set the path to the template files.
   *
   * @param string $path path to template files
   *
   * @return void
   */
  public function set_path($path) 
  {
    // store path	
    $this->_path = $path;
  }

  
  /**
   * assigns a named variable.
   *
   * @param string $name name of the variable to set
   * @param mixed $value the value of the variable
   */
  public function assign($name, $value) 
  {
    $this->_vars[$name] = $value;
  }

  function get_vars(){
    return $this->_vars;
  }
  
  /**
   * assign variables as contained in an associative array.
   *
   * @param array $vars array of vars to set
   *
   * @return void
   */
  public function assign_vars($vars) 
  {
    //merge with existing variables
  	$this->_vars = array_merge($this->_vars, $vars);
  }
  
  // ====== DISPLAY =======

  /**
   * Open, parse, and return the template file.
   *
   * @param string string the template file name
   *
   * @return string
   */
  abstract public function display($file); 

}


?>
