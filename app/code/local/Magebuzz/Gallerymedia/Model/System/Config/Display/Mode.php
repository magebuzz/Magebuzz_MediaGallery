<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Model_System_Config_Display_Mode
{
  protected function toOptionArray()
  {
    return array(
      array('value' => 'gallery-list', 'label' => Mage::helper('adminhtml')->__('Gallery List')),
      array('value' => 'gallery-tab', 'label' => Mage::helper('adminhtml')->__('Gallery Tab')),
    );
  }
}