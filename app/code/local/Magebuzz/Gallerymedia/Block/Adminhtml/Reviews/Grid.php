<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Block_Adminhtml_Reviews_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
    parent::__construct();
    $this->setId('reviewsGrid');
    $this->setUseAjax(TRUE);
    $this->setDefaultSort('review_id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(TRUE);
  }

  protected function _prepareCollection()
  {
    $collection = Mage::getModel('gallerymedia/reviews')->getCollection();
    $collection->getSelect()
      ->distinct()
      ->join(array('media_items' => 'gallery_media_item'), 'media_items.gallery_item_id=main_table.gallery_item_id', array('item_name'));
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
    $this->addColumn('review_id', array(
      'header' => Mage::helper('gallerymedia')->__('ID'),
      'align'  => 'right',
      'width'  => '50px',
      'index'  => 'review_id',
    ));

    $this->addColumn('created_date', array(
      'header' => Mage::helper('gallerymedia')->__('Created On'),
      'align'  => 'left',
      'index'  => 'created_date',
    ));
    $this->addColumn('review_title', array(
      'header' => Mage::helper('gallerymedia')->__('Title'),
      'align'  => 'left',
      'index'  => 'review_title',
    ));

    $this->addColumn('nick_name', array(
      'header' => Mage::helper('gallerymedia')->__('Nickname'),
      'align'  => 'left',
      'index'  => 'nick_name',
    ));

    $this->addColumn('review_content', array(
      'header' => Mage::helper('gallerymedia')->__('Review'),
      'align'  => 'left',
      'index'  => 'review_content',
    ));
    $this->addColumn('item_name', array(
      'header' => Mage::helper('gallerymedia')->__('Media'),
      'align'  => 'left',
      'index'  => 'item_name',
    ));

    $this->addColumn('status', array(
      'header'  => Mage::helper('gallerymedia')->__('Status'),
      'align'   => 'left',
      'width'   => '100px',
      'index'   => 'status',
      'type'    => 'options',
      'options' => array(
        1 => 'Pending',
        2 => 'Approved',
        3 => 'Not Approved',
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
    $this->addExportType('*/*/exportCsv', Mage::helper('gallerymedia')->__('CSV'));
    $this->addExportType('*/*/exportXml', Mage::helper('gallerymedia')->__('XML'));
    return parent::_prepareColumns();
  }

  protected function _prepareMassaction()
  {
    $this->setMassactionIdField('review_id');
    $this->getMassactionBlock()->setFormFieldName('review');
    $this->getMassactionBlock()->addItem('delete', array(
      'label'   => Mage::helper('gallerymedia')->__('Delete'),
      'url'     => $this->getUrl('*/*/massDelete'),
      'confirm' => Mage::helper('gallerymedia')->__('Are you sure?')
    ));
    $statuses = Mage::getSingleton('gallerymedia/status')->getOptionStatus();
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
    //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

  public function getGridUrl()
  {
    return $this->getUrl('*/*/grid', array('_current' => TRUE));
  }
}