<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Reviews_List extends Mage_Core_Block_Template
{
  public function __construct()
  {
    parent::__construct();
    $id = $this->getRequest()->getParam('id');
    if ($id) {
      $collection = Mage::getModel('gallerymedia/reviews')->getCollection()
        ->addFieldToFilter('status', '2')
        ->addFieldToFilter('gallery_item_id', $id);
      $this->setCollection($collection);
    }
  }

  protected function _prepareLayout()
  {
    parent::_prepareLayout();
    $pager = $this->getLayout()->createBlock('page/html_pager', 'reviews.pager');
    $pager->setAvailableLimit(array(5 => 5, 10 => 10, 20 => 20, 'all' => 'all'));
    $pager->setCollection($this->getCollection());
    $this->setChild('pager', $pager);
    $this->getCollection()->load();
    return $this;
  }

  public function getPagerHtml()
  {
    return $this->getChildHtml('pager');
  }

  public function getReviews()
  {
    $id = $this->getRequest()->getParam('id');
    if ($id) {
      $collection = Mage::getModel('gallerymedia/reviews')->getCollection()
        ->addFieldToFilter('status', '2')
        ->addFieldToFilter('gallery_item_id', $id);
      return $collection;
    }
    return array();
  }
}