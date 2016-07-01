<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Helper_Albums extends Mage_Core_Helper_Abstract
{
  protected $_album = null;

  public function init($album)
  {
    $this->_album = $album;

    return $this;
  }

  public function resize($imageFile, $width, $height)
  {
    if (!$imageFile) {
      $imageFile = "image-default.png";
    }
    $imagePath = Mage::getBaseDir('media') . DS . "gallerymedia/albums" . DS . $imageFile;
    $imageResized = Mage::getBaseDir('media') . DS . "gallerymedia/albums" . DS . "resized" . DS . $width . "x" . $height . "/" . $imageFile;
    try {
      if (file_exists($imageResized)) {
        $imageUrl = Mage::getBaseUrl('media') . "gallerymedia/albums/resized/" . $width . "x" . $height . "/" . $imageFile;
      } else {
        try {
          if (!file_exists($imagePath)) {
            $imageUrl = Mage::getBaseUrl('media') . "gallerymedia/albums/image-default.png";
          } else {
            $fileImg = new Varien_Image($imagePath);
            $fileImg->keepAspectRatio(TRUE);
            $fileImg->keepFrame(TRUE);
            $fileImg->keepTransparency(TRUE);
            $fileImg->constrainOnly(FALSE);
            $fileImg->backgroundColor(array(255, 255, 255));
            $fileImg->resize($width, $height);
            $fileImg->save($imageResized, null);
            $imageUrl = Mage::getBaseUrl('media') . "gallerymedia/albums/resized/" . $width . "x" . $height . "/" . $imageFile;
          }
        } catch (Exception $e) {

        }
      }
    } catch (Exception $e) {

    }
    return $imageUrl;
  }

  public function isShowTitle()
  {
    return (int)Mage::getStoreConfig('gallerymedia/album_media_setting/show_title');
  }

  public function isShowDescription()
  {
    return (int)Mage::getStoreConfig('gallerymedia/album_media_setting/show_description');
  }

  public function showUpdateDate()
  {
    return (int)Mage::getStoreConfig('gallerymedia/album_media_setting/show_updated_date');
  }
}