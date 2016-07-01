<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryitems_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
    $form = new Varien_Data_Form();
    $form->setHtmlIdPrefix('gallerymedia_');
    $this->setForm($form);
    $fieldset = $form->addFieldset('gallerymedia_form', array('legend' => Mage::helper('gallerymedia')->__('Media information')));
    $media_data = array();
    $model = Mage::registry('galleryitems_data');
    if (Mage::getSingleton('adminhtml/session')->getGalleryitemsData()) {
      $media_data = Mage::getSingleton('adminhtml/session')->getGalleryitemsData();
      Mage::getSingleton('adminhtml/session')->setGalleryitemsData(null);
    } elseif (Mage::registry('galleryitems_data')) {
      $media_data = Mage::registry('galleryitems_data')->getData();
    }
    if (isset($media_data['item_file']) && $media_data['item_file'] != '') {
      $media_data['item_file'] = 'gallerymedia/mediafile/' . $media_data['item_file'];
    }
    if (isset($media_data['media_thumbnail']) && $media_data['media_thumbnail'] != '') {
      $media_data['media_thumbnail'] = 'gallerymedia/mediafile/thumbnail/' . $media_data['media_thumbnail'];
    }
    $fieldset->addField('item_name', 'text', array(
      'label'    => Mage::helper('gallerymedia')->__('Item Name'),
      'class'    => 'required-entry',
      'required' => TRUE,
      'name'     => 'item_name',
    ));
    $fieldset->addField('media_type', 'select', array(
      'label'    => Mage::helper('gallerymedia')->__('Media Type'),
      'name'     => 'media_type',
      'onchange' => "changeSelect()",
      'values'   => array(
        array(
          'value' => 1,
          'label' => Mage::helper('gallerymedia')->__('Photo'),
        ),
        array(
          'value' => 2,
          'label' => Mage::helper('gallerymedia')->__('Video'),
        ),
      ),
    ));
    $fieldset->addField('item_file', 'file', array(
      'label'              => Mage::helper('gallerymedia')->__('Media File'),
      'required'           => FALSE,
      'name'               => 'item_file',
      'after_element_html' => '<br/><div id="upload_comment"><label style="font-style:italic; color:#8a8a8a; font-size:11px;">* Upload video file(support .mp4, .flv)</label><br/><input id="use_youtube" type="checkbox" class="use_youtube" value="0" onclick="useYoutube()"/><label style="font-style:italic; color:#8a8a8a; font-size:11px; margin-left:5px">Use Youtube Url</label></div>',
    ));
    $fieldset->addField('video_url', 'text', array(
      'label'    => Mage::helper('gallerymedia')->__('Youtube URL'),
      'required' => FALSE,
      'name'     => 'video_url',
    ));

    $fieldset->addField('media_thumbnail', 'image', array(
      'label'    => Mage::helper('gallerymedia')->__('Thumbnail'),
      'required' => FALSE,
      'name'     => 'media_thumbnail',
    ));

    $fieldset->addField('item_description', 'editor', array(
      'name'     => 'item_description',
      'label'    => Mage::helper('gallerymedia')->__('Description'),
      'title'    => Mage::helper('gallerymedia')->__('Description'),
      'style'    => 'width:500px; height:300px;',
      'config'   => Mage::getSingleton('gallerymedia/wysiwyg_config')->getConfig(),
      'required' => TRUE,
    ));

    $fieldset->addField('item_featured', 'select', array(
      'label'  => Mage::helper('gallerymedia')->__('Featured'),
      'name'   => 'item_featured',
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
    $form->setValues($media_data);
    return parent::_prepareForm();
  }
}