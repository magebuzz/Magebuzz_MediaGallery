<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Gallerymultipleitems_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
  public function __construct()
  {
    parent::__construct();
    $this->_objectId = 'id';
    $this->_blockGroup = 'gallerymedia';
    $this->_removeButton('back');
    $this->_updateButton('save', 'onclick', "checkContent();");
    $this->_controller = 'adminhtml_galleryitems';
    $this->_updateButton('save', 'label', Mage::helper('gallerymedia')->__('Save Item'));
    $this->_formScripts[] = "
			function toggleEditor() {
			if (tinyMCE.getInstanceById('gallerymedia_content') == null) {
			tinyMCE.execCommand('mceAddControl', false, 'gallerymedia_content');
			} else {
			tinyMCE.execCommand('mceRemoveControl', false, 'gallerymedia_content');
				}
			}
			function checkContent(){
			var rowCount = document.getElementById('media_gallery_content_save').value;
			if(rowCount == '[]'){
				alert('choise image upload');
			}else{
				editForm.submit();
			}
		}
    ";
  }

  public function getHeaderText()
  {
    if (Mage::registry('galleryitems_data') && Mage::registry('galleryitems_data')->getId()) {
      return Mage::helper('gallerymedia')->__("Edit Media '%s'", $this->htmlEscape(Mage::registry('galleryitems_data')->getItemName()));
    } else {
      return Mage::helper('gallerymedia')->__('Add Media');
    }
  }
}