<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Reviews_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
    parent::__construct();
    $this->setId('galleryreviews_tabs');
    $this->setDestElementId('edit_form');
    $this->setTitle(Mage::helper('gallerymedia')->__('Review Information'));
  }

  protected function _beforeToHtml()
  {
    $this->addTab('form_section', array(
      'label'   => Mage::helper('gallerymedia')->__('Review Information'),
      'title'   => Mage::helper('gallerymedia')->__('Review Information'),
      'content' => $this->getLayout()->createBlock('gallerymedia/adminhtml_reviews_edit_tab_main')->toHtml(),
    ));
    return parent::_beforeToHtml();
  }
}