<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_Status extends Varien_Object
{
  const STATUS_ENABLED = 1;
  const STATUS_DISABLED = 2;

  static public function getOptionArray()
  {
    return array(
      self::STATUS_ENABLED  => Mage::helper('gallerymedia')->__('Enabled'),
      self::STATUS_DISABLED => Mage::helper('gallerymedia')->__('Disabled')
    );
  }

  static public function getOptionStatus()
  {
    return array(
      1 => Mage::helper('gallerymedia')->__('Pending'),
      2 => Mage::helper('gallerymedia')->__('Approved'),
      3 => Mage::helper('gallerymedia')->__('Not Approved')
    );
  }
}