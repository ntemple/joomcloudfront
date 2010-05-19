CREATE TABLE IF NOT EXISTS `#__cloudfront_assets` (
  `cloudfront_asset_id` bigint(20) unsigned NOT NULL auto_increment,
  `path` varchar(255) NOT NULL,
  `resource` varchar(255) NOT NULL,
  `md5` varchar(255) NOT NULL,
  `assettype` varchar(255) NOT NULL,
  `version` int(11) NOT NULL,
  `distribution_id` bigint(20) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cloudfront_asset_id`),
  UNIQUE KEY `path` (`path`)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__cloudfront_assettypes` (
  `cloudfront_assettype_id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `gzencode` tinyint(4) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cloudfront_assettype_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cloudfront_distributions` (
  `cloudfront_distribution_id` bigint(20) unsigned NOT NULL auto_increment,
  `host` varchar(255) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  PRIMARY KEY  (`cloudfront_distribution_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cloudfront_urls` (
  `cloudfront_url_id` bigint(20) unsigned NOT NULL auto_increment,
  `strexp` varchar(255) NOT NULL,
  `asset_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`cloudfront_url_id`),
  UNIQUE KEY `strexp` (`strexp`)
) DEFAULT CHARSET=utf8;

