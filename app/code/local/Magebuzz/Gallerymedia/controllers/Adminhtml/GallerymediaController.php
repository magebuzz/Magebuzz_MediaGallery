<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Adminhtml_GallerymediaController extends Mage_Adminhtml_Controller_action
{

  protected function _initAction()
  {
    $this->loadLayout()
      ->_setActiveMenu('gallerymedia/items')
      ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

    return $this;
  }

  public function indexAction()
  {
    $this->_initAction()
      ->renderLayout();
  }

  public function editAction()
  {
    $id = $this->getRequest()->getParam('id');
    $model = Mage::getModel('gallerymedia/gallerymedia')->load($id);

    if ($model->getId() || $id == 0) {
      $data = Mage::getSingleton('adminhtml/session')->getFormData(TRUE);
      if (!empty($data)) {
        $model->setData($data);
      }

      Mage::register('gallerymedia_data', $model);

      $this->loadLayout();
      $this->_setActiveMenu('gallerymedia/items');

      $this->getLayout()->getBlock('head')->setCanLoadExtJs(TRUE);
      $this->getLayout()->getBlock('head')->setCanLoadTinyMce(TRUE);

      $this->_addContent($this->getLayout()->createBlock('gallerymedia/adminhtml_gallerymedia_edit'))
        ->_addLeft($this->getLayout()->createBlock('gallerymedia/adminhtml_gallerymedia_edit_tabs'));

      $this->renderLayout();
    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallerymedia')->__('Item does not exist'));
      $this->_redirect('*/*/');
    }
  }

  public function newAction()
  {
    $this->_forward('edit');
  }

  public function gridAction()
  {
    $this->loadLayout();
    $this->getResponse()->setBody($this->getLayout()->createBlock('gallerymedia/adminhtml_gallerymedia_grid')->toHtml());
  }

  public function saveAction()
  {
    if ($data = $this->getRequest()->getPost()) {

      if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
        try {
          /* Starting upload */
          $uploader = new Varien_File_Uploader('filename');

          // Any extention would work
          $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
          $uploader->setAllowRenameFiles(FALSE);

          // Set the file upload mode
          // false -> get the file directly in the specified folder
          // true -> get the file in the product like folders
          //	(file.jpg will go in something like /media/f/i/file.jpg)

          $uploader->setFilesDispersion(FALSE);

          // We set media as the upload dir
          $path = Mage::getBaseDir('media') . DS . 'gallerymedia' . DS;
          $uploader->save($path, $_FILES['filename']['name']);

        } catch (Exception $e) {

        }

        //this way the name is saved in DB
        $data['filename'] = $_FILES['filename']['name'];
      }


      $model = Mage::getModel('gallerymedia/gallerymedia');
      $model->setData($data)
        ->setId($this->getRequest()->getParam('id'));

      try {
        if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
          $model->setCreatedTime(now())
            ->setUpdateTime(now());
        } else {
          $model->setUpdateTime(now());
        }

        $model->save();
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('gallerymedia')->__('Item was successfully saved'));
        Mage::getSingleton('adminhtml/session')->setFormData(FALSE);

        if ($this->getRequest()->getParam('back')) {
          $this->_redirect('*/*/edit', array('id' => $model->getId()));
          return;
        }
        $this->_redirect('*/*/');
        return;
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        Mage::getSingleton('adminhtml/session')->setFormData($data);
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
        return;
      }
    }
    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallerymedia')->__('Unable to find item to save'));
    $this->_redirect('*/*/');
  }

  public function deleteAction()
  {
    if ($this->getRequest()->getParam('id') > 0) {
      try {
        $model = Mage::getModel('gallerymedia/gallerymedia');

        $model->setId($this->getRequest()->getParam('id'))
          ->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
        $this->_redirect('*/*/');
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
      }
    }
    $this->_redirect('*/*/');
  }

  public function massDeleteAction()
  {
    $gallerymediaIds = $this->getRequest()->getParam('gallerymedia');
    if (!is_array($gallerymediaIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
    } else {
      try {
        foreach ($gallerymediaIds as $gallerymediaId) {
          $gallerymedia = Mage::getModel('gallerymedia/gallerymedia')->load($gallerymediaId);
          $gallerymedia->delete();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('adminhtml')->__(
            'Total of %d record(s) were successfully deleted', count($gallerymediaIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

  public function massStatusAction()
  {
    $gallerymediaIds = $this->getRequest()->getParam('gallerymedia');
    if (!is_array($gallerymediaIds)) {
      Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
    } else {
      try {
        foreach ($gallerymediaIds as $gallerymediaId) {
          $gallerymedia = Mage::getSingleton('gallerymedia/gallerymedia')
            ->load($gallerymediaId)
            ->setStatus($this->getRequest()->getParam('status'))
            ->setIsMassupdate(TRUE)
            ->save();
        }
        $this->_getSession()->addSuccess(
          $this->__('Total of %d record(s) were successfully updated', count($gallerymediaIds))
        );
      } catch (Exception $e) {
        $this->_getSession()->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

  public function exportCsvAction()
  {
    $fileName = 'gallerymedia.csv';
    $content = $this->getLayout()->createBlock('gallerymedia/adminhtml_gallerymedia_grid')
      ->getCsv();

    $this->_sendUploadResponse($fileName, $content);
  }

  public function exportXmlAction()
  {
    $fileName = 'gallerymedia.xml';
    $content = $this->getLayout()->createBlock('gallerymedia/adminhtml_gallerymedia_grid')
      ->getXml();

    $this->_sendUploadResponse($fileName, $content);
  }

  protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
  {
    $response = $this->getResponse();
    $response->setHeader('HTTP/1.1 200 OK', '');
    $response->setHeader('Pragma', 'public', TRUE);
    $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', TRUE);
    $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
    $response->setHeader('Last-Modified', date('r'));
    $response->setHeader('Accept-Ranges', 'bytes');
    $response->setHeader('Content-Length', strlen($content));
    $response->setHeader('Content-type', $contentType);
    $response->setBody($content);
    $response->sendResponse();
    die;
  }
}