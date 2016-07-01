<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Helper_Image extends Mage_Core_Helper_Abstract
{
  protected $_item = null;

  public function init($_item)
  {
    $this->_item = $_item;

    return $this;
  }

  public function getImageUrl($imageFile) {
    if (!$imageFile) {
      $imageFile = "image-default.png";
    }
    $imagePath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'gallerymedia/mediafile/'.$imageFile;
    return $imagePath;
  }

  public function resize($imageFile, $width, $height, $ds = '')
  {
    if (!$imageFile) {
      $imageFile = "image-default.png";
    }
    $imagePath = Mage::getBaseDir('media') . DS . "gallerymedia/mediafile" . DS . $ds . DS . $imageFile;
    $imageResized = Mage::getBaseDir('media') . DS . "gallerymedia/mediafile" . DS . "resized" . DS . $width . "x" . $height . "/" . $imageFile;
    try {
      if (file_exists($imageResized)) {
        $imageUrl = Mage::getBaseUrl('media') . "gallerymedia/mediafile/resized/" . $width . "x" . $height . "/" . $imageFile;
      } else {
        try {
          if (!file_exists($imagePath)) {
            $imageUrl = Mage::getBaseUrl('media') . "gallerymedia/image-default.png";
          } else {
            $fileImg = new Varien_Image($imagePath);
            $fileImg->keepAspectRatio(TRUE);
            $fileImg->keepFrame(TRUE);
            $fileImg->keepTransparency(TRUE);
            $fileImg->constrainOnly(FALSE);
            $fileImg->backgroundColor(array(255, 255, 255));
            $fileImg->resize($width, $height);
            $fileImg->save($imageResized, null);


            $imageUrl = Mage::getBaseUrl('media') . "gallerymedia/mediafile/resized/" . $width . "x" . $height . "/" . $imageFile;
          }
        } catch (Exception $e) {

        }
      }
    } catch (Exception $e) {

    }
    return $imageUrl;
  }

  public function saveThumbnail($imageFile, $width, $height, $ds = '')
  {
    if (!$imageFile) {
      $imageFile = "image-default.png";
    }
    $imagePath = Mage::getBaseDir('media') . DS . "gallerymedia/mediafile" . DS . $ds . DS . $imageFile;
    $imageResized = Mage::getBaseDir('media') . DS . "gallerymedia/mediafile/thumbnail" . "/" . $imageFile;
    try {
      if (file_exists($imageResized)) {
        $imageUrl = Mage::getBaseDir('media') . DS . "gallerymedia/mediafile/thumbnail" . "/" . $imageFile;
      } else {
        try {
          if (!file_exists($imagePath)) {
            $imageUrl = Mage::getBaseUrl('media') . "gallerymedia/image-default.png";
          } else {
            $fileImg = new Varien_Image($imagePath);
            $fileImg->keepAspectRatio(TRUE);
            $fileImg->keepFrame(TRUE);
            $fileImg->keepTransparency(TRUE);
            $fileImg->constrainOnly(FALSE);
            $fileImg->backgroundColor(array(255, 255, 255));
            $fileImg->resize($width, $height);
            $fileImg->save($imageResized, null);
          }
        } catch (Exception $e) {

        }
      }
    } catch (Exception $e) {

    }
    return $imageFile;
  }
}