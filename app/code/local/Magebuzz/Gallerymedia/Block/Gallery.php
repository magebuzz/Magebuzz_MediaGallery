<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Gallery extends Mage_Core_Block_Template
{
  protected function _prepareLayout()
  {
    if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
      $breadcrumbsBlock->addCrumb('home', array('label' => Mage::helper('cms')->__('Home'), 'title' => Mage::helper('cms')->__('Home'), 'link' => Mage::getBaseUrl()));
      $breadcrumbsBlock->addCrumb('gallerymedia', array('label' => 'Gallery Media', 'title' => 'Gallery Media', 'link' => ""));
    }
    return parent::_prepareLayout();
  }
}