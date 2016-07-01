<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_Config extends Mage_Catalog_Model_Product_Media_Config
{
  public function getBaseMediaPath()
  {
    return Mage::getBaseDir('media') . DS . 'gallerymedia' . DS . 'mediafile';
  }

  public function getBaseMediaUrl()
  {
    return Mage::getBaseUrl('media') . 'gallerymedia/mediafile';
  }

  public function getBaseTmpMediaPath()
  {
    return Mage::getBaseDir('media') . DS . 'tmp' . DS . 'gallerymedia' . DS . 'mediafile';
  }

  public function getBaseTmpMediaUrl()
  {
    return Mage::getBaseUrl('media') . 'tmp/gallerymedia/mediafile';
  }
}