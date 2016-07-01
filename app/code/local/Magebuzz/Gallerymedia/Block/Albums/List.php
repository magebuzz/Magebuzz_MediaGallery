<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Albums_List extends Mage_Core_Block_Template
{
  protected function _prepareLayout()
  {
    return parent::_prepareLayout();
  }

  public function getAlbumsMedia()
  {
    $albumCollection = Mage::getModel('gallerymedia/galleryalbums')->getCollection()
      ->addFieldToFilter('status', '1')
      ->setOrder('sort_order', 'ASC');
		foreach ($albumCollection as $key => $album) {
			$storeIds = explode(',', $album->getStoreIds());
			$storeId = Mage::app()->getStore()->getId();
			if (!in_array($storeId, $storeIds) && !in_array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeIds)) {
				$albumCollection->removeItemByKey($key);
			}
		}
    return $albumCollection;
  }

  public function getFeaturedAlbums()
  {
    $collection = $this->getAlbumsMedia();
    $collection->addFieldToFilter('album_featured', 1);
    return $collection;
  }

  public function getThumbnailImageHeight()
  {
    $imageSizeConfig = Mage::getStoreConfig('gallerymedia/album_media_setting/album_thumb_image_size');
    $height = 130;
    if ($imageSizeConfig) {
      $size = explode(',', trim($imageSizeConfig));
      $height = $size[1];
    }
    return $height;
  }

  public function getThumbnailImageWidth()
  {
    $imageSizeConfig = Mage::getStoreConfig('gallerymedia/album_media_setting/album_thumb_image_size');
    $width = 130;
    if ($imageSizeConfig) {
      $size = explode(',', trim($imageSizeConfig));
      $width = $size[0];
      return $width;
    } else {
      return $width;
    }
  }

  public function getSizeImageHtml()
  {
    $config = Mage::getStoreConfig('gallerymedia/album_media_setting/album_thumb_image_size');
    if ($config) {
      $size = explode(',', trim($config));
      $width = $size[0];
      $height = $size[1];
      return 'style="width:' . $width . 'px;height:' . $height . 'px;"';
    } else {
      return null;
    }
  }

  public function getMediaAlbumUrl($id)
  {
    $album = Mage::getModel('gallerymedia/galleryalbums')->load($id);
    return $this->getUrl('gallery/album/' . $album->getAlbumUrl(), array());
  }
}