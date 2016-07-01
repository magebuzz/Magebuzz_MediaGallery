<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Galleryalbums_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
    parent::__construct();
    $this->setId('albumsGrid');
    $this->setUseAjax(TRUE);
    $this->setDefaultSort('gallery_album_id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(TRUE);
  }

  protected function _prepareCollection()
  {
    $collection = Mage::getModel('gallerymedia/galleryalbums')->getCollection();
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
    $this->addColumn('gallery_album_id', array(
      'header' => Mage::helper('gallerymedia')->__('Album ID'),
      'align'  => 'right',
      'width'  => '50px',
      'index'  => 'gallery_album_id',
    ));
    $this->addColumn('album_name', array(
      'header' => Mage::helper('gallerymedia')->__('Album Name'),
      'align'  => 'left',
      'index'  => 'album_name',
    ));

    $this->addColumn('album_image', array(
      'header'   => Mage::helper('gallerymedia')->__('Image'),
      'align'    => 'left',
      'width'    => '80px',
      'index'    => 'album_image',
      'type'     => 'image',
      'escape'   => TRUE,
      'sortable' => FALSE,
      'filter'   => FALSE,
      'renderer' => new Magebuzz_Gallerymedia_Block_Adminhtml_Galleryalbums_Grid_Renderer_Image,
    ));
    $this->addColumn('album_description', array(
      'header' => Mage::helper('gallerymedia')->__('Album Description'),
      'align'  => 'left',
      'index'  => 'album_description',
    ));
    $this->addColumn('album_url', array(
      'header' => Mage::helper('gallerymedia')->__('URL'),
      'align'  => 'left',
      'width'  => '180px',
      'index'  => 'album_url',
    ));
    $this->addColumn('album_featured', array(
      'header'  => Mage::helper('gallerymedia')->__('Featured'),
      'align'   => 'left',
      'width'   => '80px',
      'index'   => 'album_featured',
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
    $this->setMassactionIdField('gallery_album_id');
    $this->getMassactionBlock()->setFormFieldName('albums');
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