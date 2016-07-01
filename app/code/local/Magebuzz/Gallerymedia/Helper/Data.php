<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Helper_Data extends Mage_Core_Helper_Abstract
{
  public function generateUrl($string)
  {
    $urlKey = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($string));
    $urlKey = strtolower($urlKey);
    $urlKey = trim($urlKey, '-');
    return $urlKey;
  }

  public function getConfigSize($config)
  {
    if ($config) {
      $size = explode(',', trim($config));
      return $size;
    } else {
      return null;
    }
  }

  public function enableGalleryMedia()
  {
    return (int)Mage::getStoreConfig('gallerymedia/general/enabled');
  }

  public function getGalleryMediaIntro()
  {
    return Mage::getStoreConfig('gallerymedia/general/gallery_intro');
  }

  public function isPreviewVideo()
  {
    return (int)Mage::getStoreConfig('gallerymedia/media_setting/preview_video');
  }

  public function showMediaTitle()
  {
    return (int)Mage::getStoreConfig('gallerymedia/media_setting/show_title');
  }

  public function showMediaDescription()
  {
    return (int)Mage::getStoreConfig('gallerymedia/media_setting/show_description');
  }

  public function showAlbumDescription()
  {
    return (int)Mage::getStoreConfig('gallerymedia/album_media_setting/show_description');
  }

  public function showUpdateDate()
  {
    return (int)Mage::getStoreConfig('gallerymedia/media_setting/show_updated_date');
  }

  public function allowGuestReview()
  {
    return (int)Mage::getStoreConfig('gallerymedia/general/allow_guest_review');
  }

  public function assignMediaToAlbum($media, $albumIds)
  {
    $added_albums = 0;
    $model = Mage::getModel('gallerymedia/galleryalbummedia');
    if (!count($albumIds)) {
      $albumIds = array(0);
    }
    if ($albumIds == array(0)) {
      return $this;
    }
    foreach ($albumIds as $key => $albumId) {
      $albumId = (int)$albumId;
      if ($albumId) {
        $model->loadAlbumMedia($media->getId(), $albumId);
        if (!$model->getGalleryAlbumId())
          $added_albums++;
        $model->setGalleryItemId($media->getId());
        $model->setGalleryAlbumId($albumId);
        $model->save();
        $model->setGalleryEntityId(null);
      } else {
        unset($albumIds[$key]);
      }
    }
    if (!count($albumIds)) {
      $albumIds = array(0);
    }
    $collection = Mage::getResourceModel('gallerymedia/galleryalbummedia_collection')
      ->addFieldToFilter('gallery_album_id', array('nin' => $albumIds))
      ->addFieldToFilter('gallery_item_id', $media->getId());
    if (count($collection)) {
      foreach ($collection as $item) {
        $item->delete();
      }
    }
    return $this;
  }

  public function getYouTubeIdFromURL($url)
  {
    parse_str(parse_url($url,PHP_URL_QUERY),$params);
    if (isset($params['v'])) return $params['v'];
    return;
  }

  public function loginReviewUrl()
  {
    return $this->_getUrl('gallerymedia/review/login');
  }

  public function getReviewCount($id)
  {
    $rating = 0;
    $reviewCount = 0;
    $collections = Mage::getModel('gallerymedia/reviews')->getCollection()
      ->addFieldToFilter('gallery_item_id', $id)
      ->addFieldToFilter('status', 2);
    foreach ($collections as $review) {
      $rating = $rating + $review->getRating();
      $reviewCount++;
    }
    return $reviewCount;
  }

  public function getRatingSummary($id)
  {
    $rating = 0;
    $reviewCount = 0;
    $collections = Mage::getModel('gallerymedia/reviews')->getCollection()
      ->addFieldToFilter('gallery_item_id', $id)
      ->addFieldToFilter('status', 2);
    foreach ($collections as $review) {
      $rating = $rating + $review->getRating();
      $reviewCount++;
    }
    $ratingSummary = 0;
    if ($reviewCount) {
      $ratingSummary = $rating / $reviewCount;
    }
    return $ratingSummary;
  }
}