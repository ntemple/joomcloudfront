<?php
/**
 * @version  $Id$
 *
 * Cloudfront  Intellispire cloudfront Client for Joomla! 1.5
 * Copyright (c)2008-2009 Nick Temple, Intellispire
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; version 2
 * of the License, and no other version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @category     Cloudfront
 * @package      Cloudfront
 * @author       Nick Temple <nickt@nicktemple.com>
 * @license      GNU/GPL 2.0 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @copyright    2008-2009 Intellispire/Nick Temple
 *
 */

 /*
 TASKS:
 - replce DataStore with Joomla! compatible version.
 - Create VERY basic component
 -- edit distributions
 -- scan (remove from plugin)
 -- clear cache
Bundle and test.
DOCUMENTATION
- Write offer and marketing [include setup?]
- GHC?

 
 Long Term:
 - CSS/JS minification
 - optimize link
 - allow for JS cacheing to CNAME records.
 - allow editing of allowed content types
 - upload 
 - refactor Entity to a class
 - Allow SSL bucket direct to https://[bucket].s3.amazonaws.com (no JS)
 - fix icons
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');
require_once('cloudfront/cloudfront.class.php');


/**
 * Intellispire Plugin
 */
class plgSystemCloudfront extends JPlugin {

  var $clearcache = false;
  var $dirty = false; 

  /**
   * Constructor
   *
   * @param object $subject The object to observe
   * @param       array  $config  An array that holds the plugin configuration
   * @since 1.5
   */
  function __construct(& $subject, $config)
  {
    parent::__construct($subject, $config);
  }

  /**
   * Method run after all rendering is complete
   *
   */
  function onAfterRender()
  {        
    // Cloudfront does not currently handle HTTPS
    // we could bounce all non-javascript files off
    // of S3, or we could setup XSS ... but for now, we just suffer.  
      
    // @todo: if this is the backend, then return
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") return;

    
    $discovery = $this->params->get('discoverymode') + 0;
    $runmode = $this->params->get('runmode') + 0;
    $trace = $this->params->get('trace') + 0;

    $this->page = JResponse::GetBody();
    
    ob_start();     
    switch ($discovery) {
      case 1: $this->collect();
              break;
      case 4: // This can break things, so reset it.
              // It will run a very, very long time
              $this->params->set('discoverymode', 0);              
              $this->saveParams();
              $this->scan();
              break;
      case 5: $this->cacheClear();
              break;
    }        
    if ($runmode == 1) {     
      $this->replace();
    }

    if ($this->dirty) $this->saveParams();      
    
    $out = ob_get_clean();
    if ($trace) {
      $this->page .= "<pre>\n$out\n</pre>\n";
    }
    JResponse::SetBody($this->page);
  }

  /**
  * Simple search and replace, cache.
  */
  function replace() {
    // No assets? Don't do anything
    $assets = $this->getAssets();
    if (!$assets) return;

    $assets_search  = array_keys($assets);
    $assets_replace = array_values($assets);

    $this->page = str_replace($assets_search, $assets_replace, $this->page);
  }
  
  /**
  * Get the assets, first from the cache, then from the database.
  */
  function getAssets() {
    if (! $this->assets) {
      $this->assets = $this->cacheLoad();      
      if (!$this->assets) {        
        require_once('cloudfront/cloudfront.class.php');
        $cf = new CloudFront();
        $this->assets = $cf->buildcache();
        $this->dirty = true;
      }
    }    
    return $this->assets;
  }

  
  /**
  * Find all the possible URLs and store them in a cache
  *
  * This is a potentially VERY expensive function
  *
  * @param mixed $store
  */
  function collect($store = false) {

    require_once('cloudfront/cloudfront.class.php');
    $dirty = false;

    /* Determine the base paths for the buckets */
    $bucketbase = $this->params->get('bucketbase');

    $hostname = $this->getHostname();
    $basedir  = $this->getBasedir();

    $cf = new CloudFront();
    $new_assets = $cf->getMap($this->page, $hostname, $basedir);
    $this->cacheClear();
    
    $assets = $this->getAssets();
  }
  
  function getBasedir() {
    $basedir  = $this->params->get('basedir');
    if (!$basedir) {
      // Try to find the basedir. We have hardcoded knowledge about Joomla! here, based
      // upon the fact that this file is located in plugins/cloudfrount.php.

      $basedir = dirname($_SERVER['SCRIPT_NAME']);
      $location = basename($basedir);
      if ($location == 'administrator') {
        $basedir = dirname($basedir);
      }
      $this->params->set('basedir', $basedir);
      $this->dirty = true;
    }
    return $basedir;    
  }
  
  function getHostname() {
    $hostname = $this->params->get('hostname');
    if (! $hostname) {
      $hostname  = $_SERVER['HTTP_HOST'];
      $this->params->set('hostname', $hostname);
      $this->dirty = true;
    }
    return $hostname;    
  }

  /** 
  * Create a single S3 object for this instance
  * 
  */
  function getS3() {
    if (! $this->S3) {      
      require_once('cloudfront/cloudput.inc.php');      
      $this->S3 = new s3cloud(
        $this->params->get('API_AMAZON_ACCESS_KEY'),
        $this->params->get('API_AMAZON_SECRET')
      );
    }
    return $this->S3;
  }

  /**
  * Scan the disk for assets and new versions
  * @todo move to component
  *   
  */
  function scan() {
    
    // This can run for a vey long time

    $s3 = $this->getS3();
    $cf = new CloudFront();
    $cf->scan($s3,
              $this->params->get('bucket'),
              $this->params->get('bucketbase'),
              JPATH_ROOT);
  }

  function saveParams() {

    $assets = $this->getAssets();
    $this->cacheSave($assets);

    // Save our assets
    $db = JFactory::getDBO();
    $db->setQuery("update #__plugins set params=" . $db->Quote($this->params->toString()) . " where element='cloudfront'");
    $db->query();
  }

  
  // Cache functions
  function cacheSave($data) {
    $fname = JPATH_CACHE . '/cloudfront_data.php';
    $data = serialize($data);
    file_put_contents($fname, $data);
    print("file_put_contents($fname, $data);\n");
  }

  function cacheLoad() {
    $fname = JPATH_CACHE . '/cloudfront_data.php';

    $data = null;
    if (file_exists($fname)) {
      $data = file_get_contents($fname);
      $data = unserialize($data);
    }  

    if (!$data) {
     $data = array();
    }
    return $data;
  }
  
  function cacheClear() {    
    @unlink(JPATH_CACHE . '/cloudfront_data.php');
  }

}









