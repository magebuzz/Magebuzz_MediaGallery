<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Reviews_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
  public function __construct()
  {
    parent::__construct();
    $this->_objectId = 'id';
    $this->_blockGroup = 'gallerymedia';
    $this->_controller = 'adminhtml_reviews';
    $this->_updateButton('save', 'label', Mage::helper('gallerymedia')->__('Save Review'));
    $this->_updateButton('delete', 'label', Mage::helper('gallerymedia')->__('Delete Review'));
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
    if (Mage::registry('reviews_data') && Mage::registry('reviews_data')->getId()) {
      return Mage::helper('gallerymedia')->__("Edit Review '%s'", $this->htmlEscape(Mage::registry('reviews_data')->getReviewTitle()));
    } else {
      return Mage::helper('gallerymedia')->__('Add Review');
    }
  }
}