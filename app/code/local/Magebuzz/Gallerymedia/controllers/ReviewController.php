<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_ReviewController extends Mage_Core_Controller_Front_Action
{
  public function indexAction()
  {
    $this->loadLayout();
    $this->renderLayout();
  }

  public function formAction()
  {
    $this->loadLayout();
    $this->renderLayout();
  }

  public function postAction()
  {
    $review = $this->getRequest()->getPost();
    if ($review) {
      $mediaId = $review['media_id'];
      if ($mediaId) {
        try {
          $collection = Mage::getModel('gallerymedia/reviews');
          $collection->setData($review)
            ->setCreatedDate(now())
            ->setStatus(1)
            ->setGalleryItemId($mediaId)
            ->setNickName($review['nick_name'])
            ->setEmail($review['email'])
            ->save();

          $item = Mage::getModel('gallerymedia/galleryitems')->load($mediaId);
          $mediaUrl = Mage::getUrl('gallery/item/' . $item->getItemUrl(), array());
          Mage::getSingleton('customer/session')->addSuccess(Mage::helper('gallerymedia')->__('Thanks for your review.'));
          $this->_redirectSuccess($mediaUrl);
          return;
        } catch (Exception $e) {
          Mage::getSingleton('customer/session')->addError($e->getMessage());
          $this->_redirect('*/media/view', array('id' => $mediaId));
          return;
        }
      }
    }
  }

  public function loginAction()
  {
    $_mediaId = $this->getRequest()->getParam('id');
    $item = Mage::getModel('gallerymedia/galleryitems')->load($_mediaId);
    $mediaUrl = Mage::getUrl('gallery/item/' . $item->getItemUrl(), array());
    Mage::getSingleton('customer/session')->setBeforeAuthUrl($mediaUrl);
    $this->_redirect('customer/account/login', array('_secure' => TRUE));
    return;
  }
}