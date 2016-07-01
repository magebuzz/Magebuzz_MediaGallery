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
    ALTER TABLE {$this->getTable('gallery_media_album')} ADD `sort_order` int(11) NOT NULL default '0';
    ALTER TABLE {$this->getTable('gallery_media_item')} ADD `sort_order` int(11) NOT NULL default '0';
  ");
} catch (Exception $e) {
  Mage::logException($e);
}
$installer->endSetup(); 