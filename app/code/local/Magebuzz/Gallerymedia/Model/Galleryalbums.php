<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_Galleryalbums extends Mage_Core_Model_Abstract
{
  protected function _construct()
  {
    parent::_construct();
    $this->_init('gallerymedia/galleryalbums');
  }

  public function getAlbumCollection()
  {
    $collection = $this->getCollection();
    $options = array();
    foreach ($collection as $item) {
      $options[$item->getId()] = $item->getData('album_name');
    }
    return $options;
  }
}