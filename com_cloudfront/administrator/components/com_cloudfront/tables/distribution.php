<?php
/**
 * Cloudfront table class for Distribution
 * 
 * @package    Intellispire.Joomla
 * @subpackage Cloudfront
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Distribution Table class
 */
class TableDistribution extends JTable
{

  /** @var string */
  var $host;
  /** @var string */
  var $enabled;

  /**                              

  /**
   * Constructor
   *
   * @param object Database connector object
   */
  function __construct(& $db) {
    parent::__construct('#__cloudfront_distributions', 'cloudfront_distribution_id', $db);
  }
}
