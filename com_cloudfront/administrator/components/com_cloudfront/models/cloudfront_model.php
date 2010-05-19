<?php
/**
 * Model for Cloudfront Component
 * 
 * @package    Intellispire.Joomla
 * @subpackage Cloudfront
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class CloudfrontBaseModel extends JModel {
  
}
                                    

class CloudfrontBaseModels extends JModel {
  
  var $_pagination = null;
  
  function __construct()
  {
    parent::__construct();

    global $mainframe, $option;

    // Get the pagination request variables
    $limit    = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
    $limitstart  = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

    // In case limit has been changed, adjust limitstart accordingly
    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

    $this->setState('limit', $limit);
    $this->setState('limitstart', $limitstart);
  }

  /**
   * Method to get the total number of weblink items
   *
   * @access public
   * @return integer
   */
  function getTotal()
  {
    // Lets load the content if it doesn't already exist
    if (empty($this->_total))
    {
      $query = $this->_buildQuery();
      $this->_total = $this->_getListCount($query);
    }

    return $this->_total;
  }

  /**
   * Method to get a pagination object for the weblinks
   *
   * @access public
   * @return integer
   */
  function getPagination()
  {
    // Lets load the content if it doesn't already exist
    if (empty($this->_pagination))
    {
      jimport('joomla.html.pagination');
      $this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
    }

    return $this->_pagination;
  }

  
}


