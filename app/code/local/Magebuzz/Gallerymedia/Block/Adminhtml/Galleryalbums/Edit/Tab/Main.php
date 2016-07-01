<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.7
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryalbums_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form {
  protected function _prepareForm() {
    $form = new Varien_Data_Form();
    $form->setHtmlIdPrefix('gallerymedia_');
    $this->setForm($form);
    $fieldset = $form->addFieldset('gallerymedia_form', array('legend' => Mage::helper('gallerymedia')->__('Album Information')));
    $albums_data = array();
    $model = Mage::registry('galleryalbums_data');
    if (Mage::getSingleton('adminhtml/session')->getGalleryalbumsData()) {
      $albums_data = Mage::getSingleton('adminhtml/session')->getGalleryalbumsData();
      Mage::getSingleton('adminhtml/session')->setGalleryalbumsData(null);
    } elseif (Mage::registry('galleryalbums_data')) {
      $albums_data = Mage::registry('galleryalbums_data')->getData();
    }
    if (isset($albums_data['album_image']) && $albums_data['album_image'] != '') {
      $albums_data['album_image'] = 'gallerymedia/albums/' . $albums_data['album_image'];
    }
    $fieldset->addField('album_name', 'text', array(
      'label'    => Mage::helper('gallerymedia')->__('Album Name'),
      'class'    => 'required-entry',
      'required' => TRUE,
      'name'     => 'album_name',
    ));
    $fieldset->addField('album_image', 'image', array(
      'label'    => Mage::helper('gallerymedia')->__('Album Image'),
      'required' => FALSE,
      'name'     => 'album_image',
    ));
    $fieldset->addField('album_description', 'editor', array(
      'name'     => 'album_description',
      'label'    => Mage::helper('gallerymedia')->__('Album Description'),
      'title'    => Mage::helper('gallerymedia')->__('Album Description'),
      'style'    => 'width:500px; height:300px;',
      'config'   => Mage::getSingleton('gallerymedia/wysiwyg_config')->getConfig(),
      'required' => TRUE,
    ));

    $fieldset->addField('album_featured', 'select', array(
      'label'  => Mage::helper('gallerymedia')->__('Featured'),
      'name'   => 'album_featured',
      'values' => array(
        array(
          'value' => 1,
          'label' => Mage::helper('gallerymedia')->__('Yes'),
        ),
        array(
          'value' => 0,
          'label' => Mage::helper('gallerymedia')->__('No'),
        ),
      ),
    ));
		
		if (!Mage::app()->isSingleStoreMode()) {
      $fieldset->addField('store_id', 'multiselect', array(
				'name'      => 'store_id[]',
				'label'     => Mage::helper('gallerymedia')->__('Store View'),
				'title'     => Mage::helper('gallerymedia')->__('Store View'),
				'required'  => true,
				'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
      ));
    }
    else {
      $fieldset->addField('store_id', 'hidden', array(
      'name'      => 'store_id[]',
      'value'     => Mage::app()->getStore(true)->getId()
      ));      
      Mage::registry('galleryalbums_data')->setStoreId(Mage::app()->getStore(true)->getId());
    }

    $fieldset->addField('sort_order', 'text', array(
      'label'    => Mage::helper('gallerymedia')->__('Sort Order'),
      'required' => FALSE,
      'name'     => 'sort_order',
    ));

    $fieldset->addField('status', 'select', array(
      'label'  => Mage::helper('gallerymedia')->__('Status'),
      'name'   => 'status',
      'values' => array(
        array(
          'value' => 1,
          'label' => Mage::helper('gallerymedia')->__('Enabled'),
        ),
        array(
          'value' => 2,
          'label' => Mage::helper('gallerymedia')->__('Disabled'),
        ),
      ),
    ));
    $form->setValues($albums_data);
    return parent::_prepareForm();
  }
}