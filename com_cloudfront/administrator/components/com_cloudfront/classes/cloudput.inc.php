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

require_once('s3.php');

class s3cloud extends s3 {

  var $files = array();
  var $ignore = array( 'tmp', 'cgi-bin', '.', '..' );
  var $debug = false;

  function processDirectory($filebase, $bucket, $bucketbase, $expires = 'Sun, 12 Jan 2020 20:00:00 GMT') {   

    $this->files = array();
    $this->getDirectory($filebase);

    foreach ($this->files as $file) {
      $this->putfile($file, $bucket, $bucketbase, $filebase, $expires);
    }    
  }


  function getDirectory( $path = '.', $level = 0 ){
    $dh = @opendir( $path );
    if (!$dh) return $this->files; // permission denied
    
    while( false !== ( $file = readdir( $dh ) ) ){
      if( !in_array( $file, $this->ignore ) ){         
        if( is_dir( "$path/$file" ) ){
          $this->getDirectory( "$path/$file", ($level+1) );
        } else {
          $this->files[] = "$path/$file"; 
        }        
      }    
    }    
    closedir( $dh );
    return $this->files;
  } 
  
  
  

  function statfile($file, $bucket, $bucketbase, $filebase, $expires = 'Sun, 12 Jan 2020 20:00:00 GMT') { 

    $ext = strtolower(substr($file, strrpos($file, ".")+1));

    $mime = '';
    $compress = false;
    switch($ext) {
      case 'txt' : $compress = true; $mime = 'text/plain'; break;
      case 'css' : $compress = true; $mime = 'text/css'; break;
      case 'js'  : $compress = true; $mime = 'application/x-javascript'; break;
      case 'html': $compress = true; $mime = 'text/html'; break;
      case 'xml' : $compress = true; $mime = 'text/xml'; break;
      case 'jpg' : $mime = 'image/jpeg'; break;
      case 'gif' : $mime = 'image/gif'; break;
      case 'png' : $mime = 'image/png'; break;
      case 'tgz' : $mime = 'application/x-tgz'; break;
      case 'wmv' : $mime  = 'video/x-ms-wmv'; break;
      case 'flv' : $mime  = 'video/x-flv'; break;
      case 'zip' : $mime  = 'application/zip'; break;
      case 'swf' : $mime  = 'application/x-shockwave-flash'; break;
      case 'pdf' : $mime  = 'application/pdf'; break;
      case 'ico' : $mime  = 'image/ico'; break;
      default: return false; // do nothing, we don't want this file
    }
    
    $headers = array();
    $data = file_get_contents($file);
    
    if ($compress) {
      $data = gzencode($data);
      $headers['Content-Encoding'] = 'gzip';
    }  
        
    if (isset($expires) && $expires) {
      $headers['Expires'] = $expires;
    }    
    
    $s3file = $file; 
    $s3file = str_replace($filebase, '', $file);
    $s3file = preg_replace('#^\.#','', $s3file);
   
    $metadata = array();
//    $ok = $this->putData($data, $bucket, "$bucketbase$s3file", $mime, 'public-read', $headers, $metadata, true);

    $entity = array(
      'data'       => $data,
      'bucket'     => $bucket,      
      'bucketbase' => $bucketbase,
      'mime'      => $mime,       
      'acl'       => 'public-read',
      'md5'       => $this->hex2b64(md5($data)),
      'path'      => $s3file,
      'assettype' => $ext,
      'version'   => 1,
      'gencode'   => $compress,
      'metadata'  =>  array(),
      'headers'   => $headers,      
    );
        
    return $entity;
  }
  
  function finalizeEntity(&$entity) {
    $key = $entity['bucketbase'] .'/' . $entity['version'] . '/' . $entity['path'];
    $key = str_replace('//', '/', $key);
    $entity['key'] = $key;
    $entity['resource'] = $entity['bucket'] . ':'  . $entity['key'];
    return $entity;    
  }
  
  function putEntity(&$entity) {
    
    $this->finalizeEntity($entity);
       
    
    return $this->putData($entity['data'],
                          $entity['bucket'],
                          $entity['key'],
                          $entity['mime'],
                          $entity['acl'],
                          $entity['headers'],
                          $entity['metadata'],
                          $entity['md5']);
  }

  function putfile($file, $bucket, $bucketbase, $filebase, $expires = 'Sun, 12 Jan 2020 20:00:00 GMT') { 
    $entity = $this->statfile($file, $bucket, $bucketbase, $filebase, $expires);
    if (! $entity) return false;
    
    $this->putEntity($entity);    
    return $entity['resource'];    
  }
    
}


