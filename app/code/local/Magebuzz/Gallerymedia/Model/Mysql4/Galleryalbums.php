<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.7
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_Mysql4_Galleryalbums extends Mage_Core_Model_Mysql4_Abstract {
  protected function _construct()
  {
    $this->_init('gallerymedia/galleryalbums', 'gallery_album_id');
  }
	
	protected function _afterLoad(Mage_Core_Model_Abstract $object) {	
		$storeIds = explode(',', $object->getStoreIds());
		$object->setData('store_id', $storeIds);		
		
    return parent::_afterLoad($object);
  }
}