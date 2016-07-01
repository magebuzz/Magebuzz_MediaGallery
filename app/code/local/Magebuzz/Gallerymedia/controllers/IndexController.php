<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_IndexController extends Mage_Core_Controller_Front_Action
{
  public function indexAction()
  {
    $this->loadLayout();
    $this->getLayout()->getBlock('head')->setTitle('Gallery Media');
    $this->renderLayout();
  }

  public function fixAction()
  {
    $rewriteModel = Mage::getModel('core/url_rewrite');
    $collection = $rewriteModel->getCollection();
    foreach ($collection as $item) {
      if (substr($item->getRequestPath(), 0, 8) == 'gallery/')
        $item->delete();
    }
    $collection = Mage::getModel('gallerymedia/galleryitems')->getCollection();
    foreach ($collection as $item) {
      $item->setItemUrl(Mage::helper('gallerymedia')->generateUrl($item->getItemName()) . '-' . $item->getId());
      if (!$item->getData('item_file'))
        $item->setData('item_file', $item->getData('media_thumbnail'));
      $item->save();
      $request_path = 'gallery/item/' . $item->getItemUrl();
      $rewriteModel->loadByRequestPath($request_path);
      if (!$rewriteModel->getId()) {
        $rewriteModel->setData('id_path', 'gallerymedia/item/' . $item->getId());
        $rewriteModel->setData('request_path', 'gallery/item/' . $item->getItemUrl());
        $rewriteModel->setData('target_path', 'gallerymedia/media/view/id/' . $item->getId());
        $rewriteModel->save();
      }
    }

    $collection = Mage::getModel('gallerymedia/galleryalbums')->getCollection();
    foreach ($collection as $item) {
      $item->setAlbumUrl(Mage::helper('gallerymedia')->generateUrl($item->getData('album_name')) . '-' . $item->getId());
      // if(!$item->getData('item_file'))
      // $item->setData('item_file', $item->getData('media_thumbnail'));
      $item->save();
      $request_path = 'gallery/album/' . $item->getAlbumUrl();
      $rewriteModel->loadByRequestPath($request_path);
      if (!$rewriteModel->getId()) {
        $rewriteModel->setData('id_path', 'gallerymedia/album/' . $item->getId());
        $rewriteModel->setData('request_path', 'gallery/album/' . $item->getData('album_url'));
        $rewriteModel->setData('target_path', 'gallerymedia/album/view/id/' . $item->getId());
        $rewriteModel->save();
      }
    }
  }
}