<?php
/**
* @version  $Id$
*
* Cloudfront  Intellispire cloudfront Client for Joomla! 1.5
* Copyright (c) 2008 Nick Temple, Intellispire
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

class DataStore {

  var $dbj = null;

  function __construct() {
    $this->dbj = JFactory::getDBO();
  }

  /**
  * Find an asset by it's normalized path
  * 
  * @param mixed $file
  * @return mixed
  */
  function findAsset($file) {
    $query = _db_prepare('select cloudfront_asset_id from #__cloudfront_assets where path=?', $file);
    $this->dbj->setQuery($query);
    $id = $this->dbj->loadResult();
    return $id;
  }

  function loadDistributions() {
    $query = _db_prepare('select cloudfront_distribution_id as id, host from #__cloudfront_distributions'); // where enabled=1
    $this->dbj->setQuery($query);
    $distributions = $this->dbj->loadAssocList();  
    return $distributions;
  }

  function fixAssets() {
    $query = _db_prepare('select cloudfront_asset_id as id from #__cloudfront_assets where distribution_id=0');
    $this->dbj->setQuery($query);
    $assets = $this->dbj->loadResultArray();

    $distributions = $this->loadDistributions();
    if (!count($distributions)) return false;

    foreach ($assets as $asset) {
      $x = rand(0, count($distributions));      
      $query = _db_prepare('update #__cloudfront_assets set distribution_id=? where cloudfront_asset_id=?', $distributions[$x]['id'], $asset);      
      $this->dbj->setQuery($query);
      $this->dbj->query();
    }    
    unset($assets);

    $urls = array();
    foreach ($distributions as $dist) {
      $id = $dist['id'];
      $host = $dist['host'];
      $urls[$id] = 'http://' . $host . '/';      
    }
    print_r($urls);

    $query = "SELECT
    #__cloudfront_urls.strexp,
    #__cloudfront_assets.resource,
    #__cloudfront_assets.distribution_id
    FROM
    #__cloudfront_urls, #__cloudfront_assets
    WHERE 
    #__cloudfront_urls.asset_id = #__cloudfront_assets.cloudfront_asset_id 
    AND #__cloudfront_assets.enabled   
    AND #__cloudfront_assets.assettype in ('gif', 'jpg', 'png', 'css', 'ico')             
    ";
    
    $this->dbj->setQuery($query);
    $assets = $this->dbj->loadAssocList();
    $srp = array();    

    foreach ($assets as $asset) {
      list($bucket, $path) = explode(':', $asset['resource']);
      $search = $asset['strexp'];
      $replace = $urls[$asset['distribution_id']] . $path;      

      $srp[$search] = $replace;
    }
    print_r($srp);
    return $srp;
  }

  /**
  * Associate a specific string with an asset.
  * 
  * Note that strings (strexp) is unique, so we simply ignore attempts to insert duplicates.
  * 
  * @param mixed $strexp
  * @param mixed $asset_id
  */
  function storeUrl($strexp, $id) {  
    $query = _db_prepare('insert ignore into #__cloudfront_urls (strexp, asset_id) values (?,?)', $strexp, $id);
    $this->dbj->setQuery($query);
    $this->dbj->query();
  }  

  /**
  * Checks to see if the file is in the database, and if it matches the version.
  * If it IS available, returns the new version number
  * otherwise, returns "1" (as the first seen version of this file)
  * 
  * cases:
  *   - we have never seen this entity before (version 1), insert, store
  *   - we have seen this entity before but they are different (version X) update, store
  *   - we have seen this entity before, and they are the same (false) do nothing
  * 
  * @param mixed $file
  * @param mixed $md5
  * @return mixed $version
  */

  function checkVersion($file, $md5) {

    $query = _db_prepare('select md5,version from #__cloudfront_assets where path=?', $file);
    $this->dbj->setQuery($query);
    $row = $this->dbj->loadAssocList();
    $row = $row[0];

    if (!$row) return 1;

    if ($md5 && ($row['md5'] == $md5)) {
      return false; // do nothing
    } else{
      return $row['version'] + 1;
    }
  }

  function storefile($file) {                
    $query = _db_prepare("insert delayed into `#__cloudfront_assets` (`path`,`resource`,`md5`,`assettype`,`version`, `enabled`) values (?,?,?,?,?, 1)",     
    $file['path'],
    $file['resource'],
    $file['md5'],
    $file['assettype'],
    $file['version']);
    $this->dbj->setQuery($query);
    $this->dbj->query();          
  }

  function updatefile($file) {                
    $query = _db_prepare("update `#__cloudfront_assets` set `resource`=?,`md5`=?,`assettype`=?,`version`=? where `path`=?", 
    $file['resource'],
    $file['md5'],
    $file['assettype'],
    $file['version'],
    $file['path']
    );    
    $this->dbj->setQuery($query);
    $this->dbj->query();
  }
}


/**
* Prepare a query
*/
if (!function_exists('_db_prepare')) {
  function _db_prepare() {
    $args = func_get_args();

    if (count($args) == 1) {
      // only a query, no substition expected nor required
      return $args[0];
    }

    // We need to substitute
    if (is_array($args[1])) {
      // The last argument is an array of replacement values
      $template = array_shift($args);  // get the template
      $args = array_shift($args);      // get the actual replacement values
      array_unshift($args, $template); // put the template on top
    }
    $query = call_user_func_array('_db_make_qw', $args);
    return $query;
  }

  /**
  * string _db_make_qw($query, $arg1, $arg2, ...)
  *
  * @access private
  */
  function _db_make_qw() {
    $args = func_get_args();
    $tmpl =& $args[0];
    $tmpl = str_replace("%", "%%", $tmpl);
    $tmpl = str_replace("?", "%s", $tmpl);
    foreach ($args as $i=>$v) {
      if (!$i) continue;
      if (is_int($v)) continue;
      $args[$i] = "'".mysql_real_escape_string($v)."'";
    }
    for ($i=$c=count($args)-1; $i<$c+20; $i++)
      $args[$i+1] = "UNKNOWN_PLACEHOLDER_$i";
    return call_user_func_array("sprintf", $args);
  }

}

/**
* Manage a cloudfront instance by search for and replacig URL's in the page
*/

class CloudFront {

  /* methods for getting urls */

  function getMap($page, $hostname, $basedir) {
    $urls = $this->getURLs($page);
    $assets = $this->mapURLs($urls, $hostname, $basedir);

    return $assets;
  }

  /**
  * Attempt to find all the assets on a page.
  * 
  * We specifically leave in the quotes in order to appropriately search and replace later.
  * 
  * Could be improved to find (for example) background images in css files.
  * 
  * @param mixed $page
  * @return array
  */
  function getURLs($page) {
    // This function can probably cache this work
    $urls = array();

    # Images
    $image_regex_src_url = '/<img[^>]*'. 'src=(["|\'].*["|\'])/Ui';
    preg_match_all($image_regex_src_url, $page, $out, PREG_PATTERN_ORDER);
    $images_url_array = $out[1];
    $urls = array_merge($urls, $images_url_array);

    # CSS
    $image_regex_src_url = '/<link[^>]*'. 'href=(["|\'].*["|\'])/Ui';
    preg_match_all($image_regex_src_url, $page, $out, PREG_PATTERN_ORDER);
    $images_url_array = $out[1];
    $urls = array_merge($urls, $images_url_array);


    # Javascript
    $image_regex_src_url = '/<script[^>]*'. 'src=(["|\'].*["|\'])/Ui';
    preg_match_all($image_regex_src_url, $page, $out, PREG_PATTERN_ORDER);
    $images_url_array = $out[1];
    $urls = array_merge($urls, $images_url_array);

    return $urls;
  }

  /**
  * 
  * 
  * @param mixed $page
  * @return mixed
  */

  function getLinks($page) {

    preg_match_all ("/a[\s]+[^>]*?href[\s]?=[\s\"\']+".
    "(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/",
    $page, $matches);

    return $matches[1];

  }

  function mapURLs($urls, $hostname, $basedir, $cacheable = null) {

    $ds = new DataStore();       

    if (! $cacheable)
      $cacheable = array('jpg', 'gif', 'png', 'css', 'js', 'ico');

    $assets = array();

    foreach ($urls as $urlx) {

      // Strip off delimiters       
      $url = trim($urlx, '"\'');

      // strip the hostname, if it exists
      $url = str_replace('http://' . $hostname, '', $url);

      # Fix relative pathing if we can
      if ($url[0] != '/') {
        $url = dirname($_SERVER['SCRIPT_NAME']) . '/'. $url;
      }

      # TODO: Check to see that the URL is good. We should be able to access:
      # http://$hostname/$basedir$url

      if ($basedir && strpos($url, $basedir) === 0) {
        // We're in the right base directory
        // Strip the base
        $url = str_replace($basedir, '', $url);
        $ext = strtolower(substr($url, strrpos($url, ".")+1));
        $url = str_replace('//', '/', '/'. $url);

        $asset_id = $ds->findAsset($url);

        if ($asset_id && in_array($ext, $cacheable)) {
          $ds->storeUrl($urlx, $asset_id);
          print "Accepted: [$asset_id] ($urlx) $url\n";
        }
      }
    } # Foreach

    return $assets;
  }

  function buildcache() {
    $ds = new DataStore();       
    return $ds->fixAssets();    
  }


  /**
  * Scan the directory structure, and upload all found assets.
  * Store the reference in the db.
  * Be smart: files that have not changed (md5) do not get uploaded.
  * Changed files DO get uploaded, but as a different file, so as to clear
  * the (local) cache.
  * 
  * @param mixed $s3
  * @param mixed $bucket
  * @param mixed $bucketbase
  * @param mixed $filebase
  */

  function scan($s3, $bucket, $bucketbase, $filebase) {    
    ini_set('max_exection_time', 0);
    set_time_limit(0);

    $files = $s3->getDirectory($filebase);
    $ds = new DataStore();

    foreach ($files as $file) {
      $entity = $s3->statfile($file, $bucket, $bucketbase, $filebase);
      if ($entity) {
        $version = $ds->checkVersion($entity['path'], $entity['md5']);        
        if ($version) {
          $entity['version'] = $version;    
          $s3->finalizeEntity($entity);
          $s3->putEntity($entity);    
          if ($version == 1) {
            $ds->storefile($entity);
            print "Stored: $file\n";
          } else {
            $ds->updatefile($entity);
            print "Updated: $file\n";
          }
        } else{
          print "Same: $file\n";
        }        
        unset($entity); // clean up.
      }
    }
  }  
}
