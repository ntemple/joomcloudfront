<?php
/**
 * assettype Controller for cloudfront Component
 * 
 * @package    Intellispire.Joomla
 * @subpackage cloudfront
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * assettype cloudfront Controller
 *
 * @package    Intellispire.Joomla
 * @subpackage cloudfront
 */
class CloudfrontControllerAssettype extends CloudfrontController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'assettype' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('assettype');

		if ($model->store($post)) {
			$msg = JText::_( 'Assettype Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Assettype' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_cloudfront&view=assettypes';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('assettype');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Assettypes Could not be Deleted' );
		} else {
			$msg = JText::_( 'Assettype Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_cloudfront&view=assettypes', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_cloudfront&view=assettypes', $msg );
	}
}