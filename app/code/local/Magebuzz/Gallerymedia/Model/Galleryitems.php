<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_Galleryitems extends Mage_Core_Model_Abstract
{
  protected function _construct()
  {
    parent::_construct();
    $this->_init('gallerymedia/galleryitems');
  }

  public function getSelectedAlbumIds()
  {
    $albumIds = array();
    $collection = Mage::getModel('gallerymedia/galleryalbummedia')->getCollection()
      ->addFieldToFilter('gallery_item_id', $this->getId());
    if (count($collection)) {
      foreach ($collection as $item) {
        $albumIds[] = $item->getGalleryAlbumId();
      }
    }
    return $albumIds;
  }

  public function getSelectedAlbumIdsText()
  {
    $albumIds = "";
    $collection = Mage::getModel('gallerymedia/galleryalbummedia')->getCollection()
      ->addFieldToFilter('gallery_item_id', $this->getId());
    if (count($collection)) {
      foreach ($collection as $item) {
        $albumIds = Mage::getModel('gallerymedia/galleryalbums')->load($item->getData('gallery_album_id'))->getData('album_name') . ', ' . $albumIds;
      }
    }
    return substr($albumIds, 0, -2);
  }
}