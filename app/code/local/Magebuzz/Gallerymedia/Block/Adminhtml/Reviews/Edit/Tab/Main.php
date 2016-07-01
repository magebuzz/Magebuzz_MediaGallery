<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Reviews_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{

  protected function _prepareForm()
  {
    $form = new Varien_Data_Form();
    $form->setHtmlIdPrefix('gallerymedia_');
    $this->setForm($form);
    $fieldset = $form->addFieldset('gallerymedia_form', array('legend' => Mage::helper('gallerymedia')->__('Review Information')));

    $fieldset->addField('review_title', 'text', array(
      'label'    => Mage::helper('gallerymedia')->__('Review Title'),
      'class'    => 'required-entry',
      'required' => TRUE,
      'name'     => 'review_title',
    ));
    $fieldset->addField('rating', 'text', array(
      'label'    => Mage::helper('gallerymedia')->__('Rating'),
      'class'    => 'required-entry',
      'required' => TRUE,
      'name'     => 'rating',
    ));
    $fieldset->addField('review_content', 'editor', array(
      'name'     => 'review_content',
      'label'    => Mage::helper('gallerymedia')->__('Review'),
      'title'    => Mage::helper('gallerymedia')->__('Review'),
      'style'    => 'width:700px; height:200px;',
      'config'   => Mage::getSingleton('gallerymedia/wysiwyg_config')->getConfig(),
      'required' => TRUE,
    ));
    $fieldset->addField('status', 'select', array(
      'label'  => Mage::helper('gallerymedia')->__('Status'),
      'name'   => 'status',
      'values' => array(
        array(
          'value' => 1,
          'label' => Mage::helper('gallerymedia')->__('Pending'),
        ),

        array(
          'value' => 2,
          'label' => Mage::helper('gallerymedia')->__('Approved'),
        ),
        array(
          'value' => 3,
          'label' => Mage::helper('gallerymedia')->__('Not Approved'),
        ),
      ),
    ));

    if (Mage::getSingleton('adminhtml/session')->getReviewsData()) {
      $form->setValues(Mage::getSingleton('adminhtml/session')->getReviewsData());
      Mage::getSingleton('adminhtml/session')->setReviewsData(null);
    } elseif (Mage::registry('reviews_data')) {
      $form->setValues(Mage::registry('reviews_data')->getData());
    }
    return parent::_prepareForm();
  }
}