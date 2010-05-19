<?php
/**
 * Distribution for Cloudfront Component
 * 
 * @package    Intellispire.Joomla
 * @subpackage cloudfront
 * @license    GNU/GPL
 */


// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Hello View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CloudfrontViewDistribution extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the distribution
		$distribution		=& $this->get('Data');
		$isNew		= ($distribution->cloudfront_distribution_id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Distribution' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('distribution',		$distribution);

		parent::display($tpl);
	}
}