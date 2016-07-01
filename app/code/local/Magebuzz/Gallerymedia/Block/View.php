<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_View extends Mage_Core_Block_Template
{
  protected function _prepareLayout()
  {
    $id = $this->getRequest()->getParam('id');
    $item = Mage::getModel('gallerymedia/galleryitems')->load($id);
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
      $isView = Mage::getSingleton('core/session')->getIsAlbumViewed();
      if ($isView) {
        $album_id = Mage::getSingleton('core/session')->getAlbumIdViewed();
        $albumInfo = Mage::getModel('gallerymedia/galleryalbums')->load($album_id);
        $albumListBlock = $this->getLayout()->createBlock('gallerymedia/albums_list');
        $breadcrumbsBlock->addCrumb('mediaalbum', array(
          'label' => $albumInfo->getAlbumName(),
          'title' => $albumInfo->getAlbumName(),
          'link'  => $albumListBlock->getMediaAlbumUrl($album_id)
        ));
      }
      $breadcrumbsBlock->addCrumb('mediaitem', array(
        'label' => $item->getItemName(),
        'title' => $item->getItemName(),
        'link'  => ''
      ));
    }
    return parent::_prepareLayout();
  }

  public function getItemfo()
  {
    $id = $this->getRequest()->getParam('id');
    $item = Mage::getModel('gallerymedia/galleryitems')->load($id);
    return $item;
  }
}