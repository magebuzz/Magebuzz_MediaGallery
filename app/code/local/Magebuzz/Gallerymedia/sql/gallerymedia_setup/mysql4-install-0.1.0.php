<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

$installer = $this;
$installer->startSetup();
try {
  $installer->run("
  -- DROP TABLE IF EXISTS {$this->getTable('gallery_media_album')};
  CREATE TABLE {$this->getTable('gallery_media_album')} (
    `gallery_album_id` int(11) unsigned NOT NULL auto_increment,
    `album_image` varchar(255) NOT NULL default '',
    `album_name` varchar(255) NOT NULL default '',
    `album_description` text NOT NULL default '',
    `album_featured` tinyint(1) NOT NULL default '0',
    `album_url` varchar(255) default '',
    `status` smallint(6) NOT NULL default '0',
    `created_time` datetime NULL,
    `update_time` datetime NULL,
    PRIMARY KEY (`gallery_album_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  -- DROP TABLE IF EXISTS {$this->getTable('gallery_media_item')};
  CREATE TABLE {$this->getTable('gallery_media_item')} (
    `gallery_item_id` int(11) unsigned NOT NULL auto_increment,
    `item_name` varchar(255) NOT NULL default '',
    `media_type` smallint(6) NOT NULL default '0',
    `item_file` varchar(255) NOT NULL default '',
  	`media_thumbnail` varchar(255) NOT NULL default '',
    `item_description` text NOT NULL default '',
    `item_featured` tinyint(1) NOT NULL default '0',
    `item_url` varchar(255) default '',
    `status` smallint(6) NOT NULL default '0',
    `created_time` datetime NULL,
    `update_time` datetime NULL,
    PRIMARY KEY (`gallery_item_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  -- DROP TABLE IF EXISTS {$this->getTable('gallery_album_entity_item')};
  CREATE TABLE {$this->getTable('gallery_album_entity_item')} (
    `gallery_entity_id` int(11) unsigned NOT NULL auto_increment,
    `gallery_album_id` int(11) unsigned NOT NULL,
    `gallery_item_id` int(11) unsigned NOT NULL,
    PRIMARY KEY (`gallery_entity_id`),
    CONSTRAINT `FK_gallery_album_entity_items` FOREIGN KEY (`gallery_item_id`) REFERENCES `{$this->getTable('gallery_media_item')}` (`gallery_item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_gallery_album_entity_albums` FOREIGN KEY (`gallery_album_id`) REFERENCES `{$this->getTable('gallery_media_album')}` (`gallery_album_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(`gallery_item_id`,`gallery_album_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  -- DROP TABLE IF EXISTS {$this->getTable('gallery_item_reviews')};
  CREATE TABLE {$this->getTable('gallery_item_reviews')} (
    `review_id` int(11) unsigned NOT NULL auto_increment,
  	`nick_name` varchar(255) NOT NULL default '',
  	`email` varchar(255) NOT NULL default '',
    `review_title` varchar(255) NOT NULL default '',
    `review_content` text NOT NULL default '',
    `rating` int NOT NULL,
    `status` smallint(6) NOT NULL default '0',
    `created_date` datetime NULL,
    `gallery_item_id` int(11) unsigned NOT NULL,
    PRIMARY KEY (`review_id`),
    CONSTRAINT `FK_gallery_item_reviews` FOREIGN KEY (`gallery_item_id`) REFERENCES `{$this->getTable('gallery_media_item')}` (`gallery_item_id`) ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ");
} catch (Exception $e) {
  Mage::logException($e);
}
$installer->endSetup(); 