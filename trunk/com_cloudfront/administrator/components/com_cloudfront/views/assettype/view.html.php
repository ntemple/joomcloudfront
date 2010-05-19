<?php
/**
 * Assettype for Cloudfront Component
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
class CloudfrontViewAssettype extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the assettype
		$assettype		=& $this->get('Data');
		$isNew		= ($assettype->cloudfront_assettype_id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Assettype' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('assettype',		$assettype);

		parent::display($tpl);
	}
}