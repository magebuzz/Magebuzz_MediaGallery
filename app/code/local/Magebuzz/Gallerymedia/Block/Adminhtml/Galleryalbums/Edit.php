<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryalbums_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
  public function __construct()
  {
    parent::__construct();
    $this->_objectId = 'id';
    $this->_blockGroup = 'gallerymedia';
    $this->_controller = 'adminhtml_galleryalbums';
    $this->_updateButton('save', 'label', Mage::helper('gallerymedia')->__('Save Album'));
    $this->_updateButton('delete', 'label', Mage::helper('gallerymedia')->__('Delete Album'));
    $this->_addButton('saveandcontinue', array(
      'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
      'onclick' => 'saveAndContinueEdit()',
      'class'   => 'save',
    ), -100);
    $this->_formScripts[] = "
    function toggleEditor() {
    if (tinyMCE.getInstanceById('gallerymedia_content') == null) {
    tinyMCE.execCommand('mceAddControl', false, 'gallerymedia_content');
    } else {
    tinyMCE.execCommand('mceRemoveControl', false, 'gallerymedia_content');
    }
    }
    function saveAndContinueEdit(){
    editForm.submit($('edit_form').action+'back/edit/');
    }
    ";
  }

  public function getHeaderText()
  {
    if (Mage::registry('galleryalbums_data') && Mage::registry('galleryalbums_data')->getId()) {
      return Mage::helper('gallerymedia')->__("Edit Album '%s'", $this->htmlEscape(Mage::registry('galleryalbums_data')->getAlbumName()));
    } else {
      return Mage::helper('gallerymedia')->__('Add Album');
    }
  }
}