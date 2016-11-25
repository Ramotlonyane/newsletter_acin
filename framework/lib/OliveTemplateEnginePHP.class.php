<?php
/**
 * @package    OLIVE
 * @subpackage output
 * @version    v.1.0
 * @author     André Torgal <info@andretorgal.com>
 * @license    copyright 2007 André Torgal
 */

// class parent
require_once('OliveTemplateEngine.class.php');

/**
 * Enter description here...
 *
 */
class OliveTemplateEnginePHP extends OliveTemplateEngine 
{


  /**
   * constructor
   */
  public function __construct()
  {
  }
  
  
  /**
   * sets parent reference
   *
   * @param Olive $parent (optional)
   */
  public function set_parent(OliveParent $parent)
  {
    $this->_parent = $parent;
  }  

  
  /**
   * setup implementation
   */
  public function setup()
  {
  	// nothing to see here, move along
  }
  
  
  // ====== DISPLAY =======  
  
  
  /**
   * Open, parse, and return the template file.
   *
   * @param string string the template file name
   *
   * @return string
   */
  public function display($filename) 
  {
    // extract the vars to local namespace
    extract($this->_vars);    
    // set filename
    //caminho completo?
    if($filename[0]!="/"){//não
        
        if( !defined("IS_MOBILE") ) { // não é mobile
          $filename = $this->_path . '/' . $filename;
        }
        else { // é mobile
            $filename = DIR_MOB."views/" . $filename;
        }
        
    }
    
    // check file exists
    if (file_exists($filename))
    {
      // start output buffering
      ob_start();     
      // include the file
      include($filename);
	  // get the contents of the buffer
      $contents = ob_get_contents(); 
	  // end buffering and discard
	  ob_end_clean();                	      
    }
    else die('Invalid template file «' . $filename . '»');
    // return the contents
    return $contents;              
  }
  
}


?>
