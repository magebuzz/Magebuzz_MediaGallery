<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_MediaController extends Mage_Core_Controller_Front_Action
{
  public function indexAction()
  {
    $this->loadLayout();
    $this->renderLayout();
  }

  public function viewAction()
  {
    $id = $this->getRequest()->getParam('id', FALSE);
    if ($id) {
      $item = Mage::getModel('gallerymedia/galleryitems')->load($id);
      $this->loadLayout();
      $this->_initLayoutMessages('customer/session');
      $this->getLayout()->getBlock('head')->setTitle($item->getItemName());
      $this->renderLayout();
      return;
    }
  }
}