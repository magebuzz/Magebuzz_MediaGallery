<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryitems_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
  public function render(Varien_Object $row)
  {
    if ($row->getData($this->getColumn()->getIndex()) == "") {
      return "(no image)";
    } else {
      $html = '<img ';
      $html .= 'id="' . $this->getColumn()->getId() . '" ';
      $html .= 'width="80" ';
      $html .= 'src="' . Mage::getBaseUrl("media") . 'gallerymedia/' . 'mediafile/' . 'thumbnail/' . $row->getData($this->getColumn()->getIndex()) . '"';
      $html .= 'class="sc-media ' . $this->getColumn()->getInlineCss() . '"/>';
      return $html;
    }
  }
}