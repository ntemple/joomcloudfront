<?php


class CloudfrontJoomla {

  var $plugin;
  var $S3;

  function __construct($plugin = null) {
    if ($plugin) {
      $this->plugin = $plugin;
    } else {
      $this->plugin = JPluginHelper::getPlugin('system', 'cloudfront');      
    }
    if(is_a($this->plugin->params, 'JParameter')) {
      $this->params = $plugin->params;
    } else {
      $this->params = new JParameter($this->plugin->params);
    }

  }

  function getS3() {
    if (! $this->S3) {      


      $this->S3 = new s3cloud(
      $this->params->get('API_AMAZON_ACCESS_KEY'),
      $this->params->get('API_AMAZON_SECRET')
      );
    }
    return $this->S3;
  }

  function scan() {    
    require_once('cloudfront.class.php');
    require_once('cloudput.inc.php');      

    // This can run for a vey long time
    $s3 = $this->getS3();
    $cf = new CloudFront();
    $cf->scan($s3,
    $this->params->get('bucket'),
    $this->params->get('bucketbase'),
    JPATH_ROOT);    
  }  

}
