<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_AlbumController extends Mage_Core_Controller_Front_Action
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
      $album = Mage::getModel('gallerymedia/galleryalbums')->load($id);
      $this->loadLayout();
      $this->getLayout()->getBlock('head')->setTitle($album->getAlbumName());
      $this->renderLayout();
      return;
    } else {
      return $this->_redirect('*/*/index');
    }
  }
}