<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Media_Uploader extends Mage_Adminhtml_Block_Media_Uploader
{
  public function __construct()
  {
    parent::__construct();
    $this->setTemplate("gallerymedia/uploader.phtml");
  }
}