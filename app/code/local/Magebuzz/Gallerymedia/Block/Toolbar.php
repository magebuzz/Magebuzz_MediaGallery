<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
  public function getPagerHtml()
  {
    $pagerBlock = $this->getLayout()->createBlock('page/html_pager');
    if ($pagerBlock instanceof Varien_Object) {
      $pagerBlock->setAvailableLimit($this->getAvailableLimit());
      $pagerBlock->setUseContainer(FALSE)
        ->setShowPerPage(FALSE)
        ->setShowAmounts(FALSE)
        ->setLimitVarName($this->getLimitVarName())
        ->setPageVarName($this->getPageVarName())
        ->setLimit($this->getLimit())
        ->setCollection($this->getCollection());
      return $pagerBlock->toHtml();
    }
    return '';
  }

  public function getAvailableLimit()
  {
    $currentMode = $this->getCurrentMode();
    if (in_array($currentMode, array('list', 'grid'))) {
      return $this->_getAvailableLimit($currentMode);
    } else {
      return $this->_defaultAvailableLimit;
    }
  }

  protected function _getAvailableLimit($mode)
  {
    if (isset($this->_availableLimit[$mode])) {
      return $this->_availableLimit[$mode];
    }
    $perPageConfigKey = 'gallerymedia/general/media_per_page_values';
    $perPageValues = (string)Mage::getStoreConfig($perPageConfigKey);
    $perPageValues = explode(',', $perPageValues);
    $perPageValues = array_combine($perPageValues, $perPageValues);
    if (Mage::getStoreConfigFlag('gallerymedia/general/media_list_allow_all')) {
      return ($perPageValues + array('all' => $this->__('All')));
    } else {
      return $perPageValues;
    }
  }
}