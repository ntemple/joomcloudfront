<?php
/**
 * distribution Controller for cloudfront Component
 * 
 * @package    Intellispire.Joomla
 * @subpackage cloudfront
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(JPATH_ADMINISTRATOR . '/components/com_cloudfront/classes/cloudfrontjoomla.php');

/**
 * distribution cloudfront Controller
 *
 * @package    Intellispire.Joomla
 * @subpackage cloudfront
 */
class CloudfrontControllerCloudfront extends CloudfrontController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}
  
  function scan() {
    $start = time();
    print "<pre>";
    print "Scanning....\n";
        
    $bridge = new CloudfrontJoomla();
    $bridge->scan();
    $end = time();
    
    $t = $end - $start;
    print "Done in $t seconds\n" ;
    print "</pre>";
  }
 
 
}
