<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryitems_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  public function __construct()
  {
    parent::__construct();
    $this->setId('galleryitems_tabs');
    $this->setDestElementId('edit_form');
    $this->setTitle(Mage::helper('gallerymedia')->__('Media Information'));
  }

  protected function _beforeToHtml()
  {
    $this->addTab('form_section', array(
      'label'   => Mage::helper('gallerymedia')->__('Media Information'),
      'title'   => Mage::helper('gallerymedia')->__('Media Information'),
      'content' => $this->getLayout()->createBlock('gallerymedia/adminhtml_galleryitems_edit_tab_main')->toHtml(),
    ));
    $this->addTab('form_gallerymedia_albums', array(
      'label' => Mage::helper('gallerymedia')->__('Media Albums'),
      'title' => Mage::helper('gallerymedia')->__('Media Albums'),
      'class' => 'ajax',
      'url'   => $this->getUrl('gallerymedia/adminhtml_galleryitems/albumslist', array('_current' => TRUE, 'id' => $this->getRequest()->getParam('id'))),
    ));
    return parent::_beforeToHtml();
  }
}