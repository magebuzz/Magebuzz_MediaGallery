<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryitems extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_galleryitems';
    $this->_blockGroup = 'gallerymedia';
    $this->_headerText = Mage::helper('gallerymedia')->__('Media Item Manager');
    $this->_addButtonLabel = Mage::helper('gallerymedia')->__('Add Item');
    parent::__construct();
  }
}