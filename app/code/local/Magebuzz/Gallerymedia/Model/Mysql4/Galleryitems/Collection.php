<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_Mysql4_Galleryitems_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
  protected function _construct()
  {
    parent::_construct();
    $this->_init('gallerymedia/galleryitems');
  }

  protected function _afterLoad()
  {
    parent::_afterLoad();
    foreach ($this->_items as $item) {
      $item->setData('selected_albums_id', $item->getSelectedAlbumIds());
      $item->setData('selected_albums_text', $item->getSelectedAlbumIdsText());
    }
    return $this;
  }
}