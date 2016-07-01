<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Albums_View extends Mage_Core_Block_Template
{
  protected function _prepareLayout()
  {
    $album_id = $this->getRequest()->getParam('id');
    $albumInfo = Mage::getModel('gallerymedia/galleryalbums')->load($album_id);
    if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
      $breadcrumbsBlock->addCrumb('home', array(
        'label' => Mage::helper('gallerymedia')->__('Home'),
        'title' => Mage::helper('gallerymedia')->__('Go to Home Page'),
        'link'  => Mage::getBaseUrl()
      ));
      $breadcrumbsBlock->addCrumb('gallerymedia', array(
        'label' => Mage::helper('gallerymedia')->__('Gallery Media'),
        'title' => Mage::helper('gallerymedia')->__('Gallery Media'),
        'link'  => Mage::getUrl('gallerymedia')
      ));
      $breadcrumbsBlock->addCrumb('album', array(
        'label' => $albumInfo->getAlbumName(),
        'title' => $albumInfo->getAlbumName(),
        'link'  => ''
      ));
    }
    Mage::getSingleton('core/session')->setIsAlbumViewed(TRUE);
    Mage::getSingleton('core/session')->setAlbumIdViewed($album_id);
    return parent::_prepareLayout();
  }

  public function getAlbumInfo() {
    $id = $this->getRequest()->getParam('id');
    if (!$id) $id = $this->getAlbumId();
    $albumInfo = Mage::getModel('gallerymedia/galleryalbums')->load($id);
    return $albumInfo;
  }
}