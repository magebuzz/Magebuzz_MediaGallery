<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.7
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

$installer = $this;
$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('gallery_media_album')} ADD `store_ids` varchar(255) NOT NULL default '0';
");

$installer->endSetup(); 