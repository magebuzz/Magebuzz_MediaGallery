<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Gallerymultipleitems_Edit_Tab_Image extends Mage_Adminhtml_Block_Widget
{
  protected function _prepareForm()
  {
    $data = $this->getRequest()->getPost();
    $form = new Varien_Data_Form();
    $form->setValues($data);
    $this->setForm($form);
    return parent::_prepareForm();
  }

  public function __construct()
  {
    parent::__construct();
    $this->setTemplate('gallerymedia/edit/tab/image.phtml');
    $this->setId('media_gallery_content');
    $this->setHtmlId('media_gallery_content');
  }

  protected function _prepareLayout()
  {
    $this->setChild("uploader", $this->getLayout()->createBlock("adminhtml/media_uploader"));
    $this->getUploader()->getConfig()
      ->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/*/upload'))
      ->setFileField('image')
      ->setFilters(array(
        'images' => array(
          'label' => Mage::helper('gallerymedia')->__('Images (.gif, .jpg, .png)'),
          'files' => array('*.gif', '*.jpg', '*.jpeg', '*.png')
        )
      ));
    $this->setChild(
      'upload_button',
      $this->getLayout()->createBlock('adminhtml/widget_button')
        ->addData(array(
          'id'      => $this->_getButtonId('upload'),
          'label'   => Mage::helper('adminhtml')->__('Upload Files'),
          'type'    => 'button',
          'onclick' => $this->getJsObjectName() . '.upload()'
        ))
    );
    $this->setChild(
      'delete_button',
      $this->getLayout()->createBlock('adminhtml/widget_button')
        ->addData(array(
          'id'      => '{{id}}-delete',
          'class'   => 'delete',
          'type'    => 'button',
          'label'   => Mage::helper('adminhtml')->__('Remove'),
          'onclick' => $this->getJsObjectName() . '.removeFile(\'{{fileId}}\')'
        ))
    );
    return parent::_prepareLayout();
  }

  protected function _getButtonId($buttonName)
  {
    return $this->getHtmlId() . '-' . $buttonName;
  }

  /**
   * Retrive uploader block
   *
   * @return Mage_Adminhtml_Block_Media_Uploader
   */
  public function getUploader()
  {
    return $this->getChild('uploader');
  }

  /**
   * Retrive uploader block html
   *
   * @return string
   */
  public function getUploaderHtml()
  {
    return $this->getChildHtml('uploader');
  }

  public function getJsObjectName()
  {
    return $this->getHtmlId() . 'JsObject';
  }

  public function getAddImagesButton()
  {
    return $this->getButtonHtml(
      Mage::helper('gallerymedia')->__('Add New Images'),
      $this->getJsObjectName() . '.showUploader()',
      'add',
      $this->getHtmlId() . '_add_images_button'
    );
  }

  public function getUploadButtonHtml()
  {
    return $this->getChildHtml('upload_button');
  }

  public function getImagesJson()
  {
    $_result = array();
    if (count($_result) > 0) {
      return Zend_Json::encode($_result);
    }
    return '[]';
  }

  public function getImagesValuesJson()
  {
    $values = array();
    return Zend_Json::encode($values);
  }

  /**
   * Enter description here...
   *
   * @return array
   */

  public function getImageTypes()
  {
    $type = array();
    $type['gallery']['label'] = "media";
    $type['gallery']['field'] = "media";
    $imageTypes = array();
    return $type;
  }

  public function getImageTypesJson()
  {
    return Zend_Json::encode($this->getImageTypes());
  }

  public function getCustomRemove()
  {
    return $this->setChild(
      'delete_button',
      $this->getLayout()->createBlock('adminhtml/widget_button')
        ->addData(array(
          'id'      => '{{id}}-delete',
          'class'   => 'delete',
          'type'    => 'button',
          'label'   => Mage::helper('adminhtml')->__('Remove'),
          'onclick' => $this->getJsObjectName() . '.removeFile(\'{{fileId}}\')'
        ))
    );
  }

  public function getDeleteButtonHtml()
  {
    return $this->getChildHtml('delete_button');
  }

  public function getCustomValueId()
  {
    return $this->setChild(
      'value_id',
      $this->getLayout()->createBlock('adminhtml/widget_button')
        ->addData(array(
          'id'    => '{{id}}-value',
          'class' => 'value_id',
          'type'  => 'text',
          'label' => Mage::helper('adminhtml')->__('ValueId'),
        ))
    );
  }

  public function getValueIdHtml()
  {
    return $this->getChildHtml('value_id');
  }
}