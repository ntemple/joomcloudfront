<?php
/**
 * cloudfront Default Controller 
 * 
 * @package    Intellispire.Joomla
 * @subpackage cloudfront
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
                                                                   
/**
 * cloudfront Base Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */

class cloudfrontController extends JController
{
   
  function __construct() {
    
    $v = JRequest::getWord('view', 'cloudfront');
    
    JSubMenuHelper::addEntry(JText::_( 'Dashboard'),  'index.php?option=com_cloudfront&view=cloudfront', $v  == 'cloudfront');
    JSubMenuHelper::addEntry(JText::_( 'Distributions'),  'index.php?option=com_cloudfront&view=distributions', $v == 'distributions');
    
    $db = JFactory::getDBO();
    $db->setQuery("select id from #__plugins where folder='system' and element='cloudfront'");
    $id = $db->loadResult();
    JSubMenuHelper::addEntry(JText::_( 'Manage Plugin'),  'index.php?option=com_plugins&view=plugin&client=site&task=edit&cid[]=' . $id, false);

    JToolBarHelper::help('help', true);
    parent::__construct(); 
  }
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{       
    parent::display();
	}
    
}
