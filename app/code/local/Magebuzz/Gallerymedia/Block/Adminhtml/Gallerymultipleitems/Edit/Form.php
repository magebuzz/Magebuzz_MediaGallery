<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryitems_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
    $form = new Varien_Data_Form(array(
        'id'      => 'edit_form',
        'action'  => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
        'method'  => 'post',
        'enctype' => 'multipart/form-data'
      )
    );
    $form->setUseContainer(TRUE);
    $this->setForm($form);
    return parent::_prepareForm();
  }
}