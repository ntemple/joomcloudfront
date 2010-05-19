<?php
/**
 * Assettypes for Cloudfront Component
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
class CloudfrontViewAssettypes extends JView
{
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Assettype Manager' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();

		// Get data from the model
		$items		= & $this->get( 'Data');
    $total    = & $this->get( 'Total');

    $this->assignRef('items',  $items);   
    
    $pagination = & $this->get( 'Pagination' );
    $this->assignRef('pagination',  $pagination);
		
    parent::display($tpl);
	}
}