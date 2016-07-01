<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Gallerymultipleitems extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_gallerymultipleitems';
    $this->_blockGroup = 'gallerymultipleitems';
    $this->_headerText = Mage::helper('gallerymedia')->__('Add Multiple Items');
    $this->_addButtonLabel = Mage::helper('gallerymedia')->__('Add Multiple Items');
    parent::__construct();
  }
}