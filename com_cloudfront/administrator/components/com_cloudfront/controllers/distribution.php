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

/**
 * distribution cloudfront Controller
 *
 * @package    Intellispire.Joomla
 * @subpackage cloudfront
 */
class CloudfrontControllerDistribution extends CloudfrontController
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
		JRequest::setVar( 'view', 'distribution' );
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
		$model = $this->getModel('distribution');

		if ($model->store($post)) {
			$msg = JText::_( 'Distribution Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Distribution' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_cloudfront&view=distributions';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('distribution');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Distributions Could not be Deleted' );
		} else {
			$msg = JText::_( 'Distribution Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_cloudfront&view=distributions', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_cloudfront&view=distributions', $msg );
	}
}