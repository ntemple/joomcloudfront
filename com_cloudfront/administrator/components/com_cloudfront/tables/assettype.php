<?php
/**
 * Cloudfront table class for Assettype
 * 
 * @package    Intellispire.Joomla
 * @subpackage Cloudfront
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Assettype Table class
 */
class TableAssettype extends JTable
{

  /** @var string */
  var $name;
  /** @var string */
  var $extension;
  /** @var string */
  var $mime;
  /** @var string */
  var $gzencode;
  /** @var string */
  var $enabled;

  /**                              

  /**
   * Constructor
   *
   * @param object Database connector object
   */
  function __construct(& $db) {
    parent::__construct('#__cloudfront_assettypes', 'cloudfront_assettype_id', $db);
  }
}
