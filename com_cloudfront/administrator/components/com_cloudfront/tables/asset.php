<?php
/**
 * Cloudfront table class for Asset
 * 
 * @package    Intellispire.Joomla
 * @subpackage Cloudfront
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Asset Table class
 */
class TableAsset extends JTable
{

  /** @var string */
  var $path;
  /** @var string */
  var $resource;
  /** @var string */
  var $md5;
  /** @var string */
  var $assettype;
  /** @var string */
  var $version;
  /** @var string */
  var $distribution_id;
  /** @var string */
  var $enabled;

  /**                              

  /**
   * Constructor
   *
   * @param object Database connector object
   */
  function __construct(& $db) {
    parent::__construct('#__cloudfront_assets', 'cloudfront_asset_id', $db);
  }
}
