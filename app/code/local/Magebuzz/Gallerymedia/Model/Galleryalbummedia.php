<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_Galleryalbummedia extends Mage_Core_Model_Abstract
{
  protected function _construct()
  {
    parent::_construct();
    $this->_init('gallerymedia/galleryalbummedia');
  }

  public function loadAlbumMedia($media_id, $album_id)
  {
    $collection = $this->getCollection()
      ->addFieldToFilter('gallery_item_id', $media_id)
      ->addFieldToFilter('gallery_album_id', $album_id);

    $item = $collection->getFirstItem();
    $this->setData($item->getData());
    return $this;
  }
}