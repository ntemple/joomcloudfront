<?php
/**
 * Distributions for Cloudfront Component
 * 
 * @package    Intellispire.Joomla
 * @subpackage cloudfront
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Hellos View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CloudfrontViewCloudfront extends JView
{
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
    JToolBarHelper::title(   JText::_( 'Cloudfront Dashboard' ), 'generic.png' );
		parent::display($tpl);
	}
}