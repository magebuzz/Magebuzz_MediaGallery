<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_Mysql4_Galleryitems extends Mage_Core_Model_Mysql4_Abstract
{
  protected function _construct()
  {
    // Note that the gallerymedia_id refers to the key field in your database table.
    $this->_init('gallerymedia/galleryitems', 'gallery_item_id');
  }
}