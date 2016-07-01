<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryitems_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
    parent::__construct();
    $this->setId('galleryitemsGrid');
    $this->setUseAjax(TRUE);
    $this->setDefaultSort('gallery_item_id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(TRUE);
  }

  protected function _prepareCollection()
  {
    $collection = Mage::getModel('gallerymedia/galleryitems')->getCollection();
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
    $this->addColumn('gallery_item_id', array(
      'header' => Mage::helper('gallerymedia')->__('Item ID'),
      'align'  => 'right',
      'width'  => '50px',
      'index'  => 'gallery_item_id',
    ));
    $this->addColumn('item_name', array(
      'header' => Mage::helper('gallerymedia')->__('Name'),
      'align'  => 'left',
      'width'  => '250px',
      'index'  => 'item_name',
    ));

    $this->addColumn('media_thumbnail', array(
      'header'   => Mage::helper('gallerymedia')->__('Thumbnail'),
      'align'    => 'left',
      'width'    => '80px',
      'index'    => 'media_thumbnail',
      'type'     => 'image',
      'escape'   => TRUE,
      'sortable' => FALSE,
      'filter'   => FALSE,
      'renderer' => new Magebuzz_Gallerymedia_Block_Adminhtml_Galleryitems_Grid_Renderer_Image,
    ));

    $this->addColumn('item_description', array(
      'header' => Mage::helper('gallerymedia')->__('Media Description'),
      'align'  => 'left',
      'index'  => 'item_description',
    ));
    $this->addColumn('albums', array(
      'header'  => Mage::helper('gallerymedia')->__('Albums'),
      'index'   => 'gallery_album_id',
      'type'    => 'options',
      'options' => Mage::getModel('gallerymedia/galleryalbums')->getAlbumCollection()
    ));
    $this->addColumn('media_type', array(
      'header'  => Mage::helper('gallerymedia')->__('Media Type'),
      'align'   => 'left',
      'width'   => '80px',
      'index'   => 'media_type',
      'type'    => 'options',
      'options' => array(
        1 => 'Photo',
        2 => 'Video',
      ),
    ));

    $this->addColumn('item_featured', array(
      'header'  => Mage::helper('gallerymedia')->__('Featured'),
      'align'   => 'left',
      'width'   => '80px',
      'index'   => 'item_featured',
      'type'    => 'options',
      'options' => array(
        1 => 'Yes',
        0 => 'No',
      ),
    ));

    $this->addColumn('status', array(
      'header'  => Mage::helper('gallerymedia')->__('Status'),
      'align'   => 'left',
      'width'   => '80px',
      'index'   => 'status',
      'type'    => 'options',
      'options' => array(
        1 => 'Enabled',
        2 => 'Disabled',
      ),
    ));

    $this->addColumn('sort_order', array(
      'header' => Mage::helper('gallerymedia')->__('Sort Order'),
      'align'  => 'left',
      'index'  => 'sort_order',
    ));
    $this->addColumn('action',
      array(
        'header'    => Mage::helper('gallerymedia')->__('Action'),
        'width'     => '100',
        'type'      => 'action',
        'getter'    => 'getId',
        'actions'   => array(
          array(
            'caption' => Mage::helper('gallerymedia')->__('Edit'),
            'url'     => array('base' => '*/*/edit'),
            'field'   => 'id'
          )
        ),
        'filter'    => FALSE,
        'sortable'  => FALSE,
        'index'     => 'stores',
        'is_system' => TRUE,
      ));
    return parent::_prepareColumns();
  }

  protected function _prepareMassaction()
  {
    $this->setMassactionIdField('gallery_item_id');
    $this->getMassactionBlock()->setFormFieldName('mediaitems');
    $this->getMassactionBlock()->addItem('delete', array(
      'label'   => Mage::helper('gallerymedia')->__('Delete'),
      'url'     => $this->getUrl('*/*/massDelete'),
      'confirm' => Mage::helper('gallerymedia')->__('Are you sure?')
    ));

    $statuses = Mage::getSingleton('gallerymedia/status')->getOptionArray();
    array_unshift($statuses, array('label' => '', 'value' => ''));
    $this->getMassactionBlock()->addItem('status', array(
      'label'      => Mage::helper('gallerymedia')->__('Change status'),
      'url'        => $this->getUrl('*/*/massStatus', array('_current' => TRUE)),
      'additional' => array(
        'visibility' => array(
          'name'   => 'status',
          'type'   => 'select',
          'class'  => 'required-entry',
          'label'  => Mage::helper('gallerymedia')->__('Status'),
          'values' => $statuses
        )
      )
    ));
    return $this;
  }

  public function getRowUrl($row)
  {
    return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

  public function getGridUrl()
  {
    return $this->getUrl('*/*/grid', array('_current' => TRUE));
  }
}