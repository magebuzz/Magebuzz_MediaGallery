<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_List extends Mage_Core_Block_Template
{
  public function __construct()
  {
    parent::__construct();
    $collection = $this->getItems();
    $this->setCollection($collection);
  }

  protected function _prepareLayout()
  {
    parent::_prepareLayout();
    $toolbar = $this->getToolbarBlock();
    $collection = $this->getCollection();
    if ($orders = $this->getAvailableOrders()) {
      $toolbar->setAvailableOrders($orders);
    }
    if ($sort = $this->getSortBy()) {
      $toolbar->setDefaultOrder($sort);
    }
    if ($dir = $this->getDefaultDirection()) {
      $toolbar->setDefaultDirection($dir);
    }
    $toolbar->setCollection($collection);
    $this->setChild('toolbar', $toolbar);
    $this->getCollection()->load();
    return $this;
  }

  public function getDefaultDirection()
  {
    return 'asc';
  }

  public function getAvailableOrders()
  {
    return array('sort_order' => 'Sort Order', 'created_time' => 'Created Time', 'update_time' => 'Updated Time', 'item_name' => 'Name');
  }

  public function getSortBy()
  {
    return 'sort_order';
  }

  public function getToolbarBlock()
  {
    $block = $this->getLayout()->createBlock('gallerymedia/toolbar', microtime());
    return $block;
  }

  public function getMode()
  {
    return $this->getChild('toolbar')->getCurrentMode();
  }

  public function getToolbarHtml()
  {
    return $this->getChildHtml('toolbar');
  }


  public function getItems()
  {
    $album_id = $this->getRequest()->getParam('id');
    $collection = Mage::getModel('gallerymedia/galleryitems')->getCollection()
      ->addFieldToFilter('status', '1');
    $collection->getSelect()
      ->distinct()
      ->join(array('media_album' => Mage::getSingleton('core/resource')->getTableName('gallery_album_entity_item')), 'media_album.gallery_item_id=main_table.gallery_item_id');
    $collection->addFieldToFilter('gallery_album_id', $album_id);
    return $collection;
  }

  public function getFeaturedItems()
  {
    $collection = Mage::getModel('gallerymedia/galleryitems')->getCollection()
      ->addFieldToFilter('status', '1')
      ->addFieldToFilter('item_featured', '1');
    return $collection;
  }

  public function getItemUrl($id)
  {
    $item = Mage::getModel('gallerymedia/galleryitems')->load($id);
    return $this->getUrl('gallery/item/' . $item->getItemUrl(), array());
  }

  public function getSizeHtml()
  {
    $config = Mage::getStoreConfig('gallerymedia/media_setting/media_box_size');
    if ($config) {
      $size = explode(',', trim($config));
      $width = $size[0];
      $height = $size[1];
      return 'style="width:' . $width . 'px;height:' . $height . 'px;"';
    } else {
      return null;
    }
  }

}