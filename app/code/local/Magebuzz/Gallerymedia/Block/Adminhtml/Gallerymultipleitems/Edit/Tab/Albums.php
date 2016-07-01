<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryitems_Edit_Tab_Albums extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
    return parent::__construct();
    $this->setId('albumsGrid');
    $this->setDefaultSort('gallery_album_id');
    $this->setUseAjax(TRUE);
  }

  protected function _prepareCollection()
  {
    $collection = Mage::getResourceModel('gallerymedia/galleryalbums_collection');
    $collection->addFieldToFilter('status', 1);
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
    $this->addColumn('in_albums', array(
      'header_css_class' => 'a-center',
      'type'             => 'checkbox',
      'name'             => 'in_albums',
      'align'            => 'center',
      'index'            => 'gallery_album_id',
      'values'           => $this->_getSelectedAlbums(),
    ));

    $this->addColumn('gallery_album_id', array(
      'header' => Mage::helper('gallerymedia')->__('ID'),
      'width'  => '50px',
      'index'  => 'gallery_album_id',
      'type'   => 'number',
    ));

    $this->addColumn('album_name', array(
      'header' => Mage::helper('gallerymedia')->__('Album Name'),
      'index'  => 'album_name'
    ));

    $this->addColumn('position', array(
      'header'   => Mage::helper('gallerymedia')->__(''),
      'name'     => 'position',
      'index'    => 'position',
      'width'    => 0,
      'editable' => TRUE,
      'filter'   => FALSE,
    ));
    return parent::_prepareColumns();
  }

  public function getGridUrl()
  {
    return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/albumslistGrid', array('_current' => TRUE));
  }

  public function getRowUrl($row)
  {
    return $this->getUrl('gallerymedia/adminhtml_galleryalbums/edit', array('id' => $row->getId()));
  }

  protected function _getSelectedAlbums()
  {
    $albums = $this->getAlbums();
    if (!is_array($albums)) {
      $albums = array_keys($this->getSelectedAlbums());
    }
    return $albums;
  }

  public function getSelectedAlbums()
  {
    $albums = array();
    $items = Mage::getModel('gallerymedia/galleryitems')->load($this->getRequest()->getParam('id'));
    $albumIds = $items->getSelectedAlbumIds();
    foreach ($albumIds as $albumId) {
      $albums[$albumId] = array('position' => 0);
    }
    return $albums;
  }
}