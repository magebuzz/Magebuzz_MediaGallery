<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Adminhtml_GalleryitemsController extends Mage_Adminhtml_Controller_action
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
    $model = Mage::getModel('gallerymedia/galleryitems')->load($id);
    if ($model->getId() || $id == 0) {
      $data = Mage::getSingleton('adminhtml/session')->getFormData(TRUE);
      if (!empty($data)) {
        $model->setData($data);
      }
      Mage::register('galleryitems_data', $model);
      $this->loadLayout();
      $this->_setActiveMenu('gallerymedia/items');
      $this->getLayout()->getBlock('head')->setCanLoadExtJs(TRUE);
      $this->getLayout()->getBlock('head')->setCanLoadTinyMce(TRUE);
      $this->_addContent($this->getLayout()->createBlock('gallerymedia/adminhtml_galleryitems_edit'))
        ->_addLeft($this->getLayout()->createBlock('gallerymedia/adminhtml_galleryitems_edit_tabs'));
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

  public function albumslistAction()
  {
    $this->loadLayout();
    $this->getLayout()->getBlock('gallerymedia.edit.tab.albums')
      ->setAlbums($this->getRequest()->getPost('palbum', null));
    $this->renderLayout();
  }

  public function albumslistGridAction()
  {
    $this->loadLayout();
    $this->getLayout()->getBlock('gallerymedia.edit.tab.albums')
      ->setAlbums($this->getRequest()->getPost('palbum', null));
    $this->renderLayout();
  }

  public function gridAction()
  {
    $this->loadLayout();
    $this->getResponse()->setBody($this->getLayout()->createBlock('gallerymedia/adminhtml_galleryitems_grid')->toHtml());
  }

  public function saveAction()
  {
    if ($data = $this->getRequest()->getPost()) {
      $model = Mage::getModel('gallerymedia/galleryitems');
      if ($id = $this->getRequest()->getParam('id')) {
        $model->load($id);
      }
      if (isset($_FILES['item_file']['name']) && $_FILES['item_file']['name'] != '') {
        $info = pathinfo($_FILES['item_file']['name']);
        try {
          $uploader = new Varien_File_Uploader('item_file');
          $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'mp4', 'flv', 'avi', 'mpg', 'mpeg', 'mov'));
          $uploader->setAllowRenameFiles(TRUE);
          $uploader->setFilesDispersion(FALSE);
          $path = Mage::getBaseDir('media') . DS . 'gallerymedia' . DS . 'mediafile' . DS;
          $uploader->save($path, $_FILES['item_file']['name']);
        } catch (Exception $e) {
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
          Mage::getSingleton('adminhtml/session')->setFormData($data);
          $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
          return;
        }
        $data['item_file'] = $uploader->getUploadedFileName();
      } elseif ($model->getItemFile()) {
        $data['item_file'] = $model->getItemFile();
      }
      $post = $this->getRequest()->getPost();
      if (isset($post['item_file']['delete']) && $post['item_file']['delete'] == 1) {
        $data['item_file'] = '';
      }
      if (isset($_FILES['media_thumbnail']['name']) && $_FILES['media_thumbnail']['name'] != '') {
        $info = pathinfo($_FILES['media_thumbnail']['name']);
        $newFileName = Mage::helper('gallerymedia')->generateUrl($info['filename']) . '.' . $info['extension'];
        try {
          $uploader = new Varien_File_Uploader('media_thumbnail');
          $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
          $uploader->setAllowRenameFiles(FALSE);
          $uploader->setFilesDispersion(FALSE);
          $path = Mage::getBaseDir('media') . DS . 'gallerymedia' . DS . 'mediafile' . DS . 'thumbnail' . DS;
          $uploader->save($path, $newFileName);
        } catch (Exception $e) {
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
          Mage::getSingleton('adminhtml/session')->setFormData($data);
          $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
          return;
        }
        $data['media_thumbnail'] = $newFileName;
      } elseif ($model->getMediaThumbnail()) {
        $data['media_thumbnail'] = $model->getMediaThumbnail();
      }
      $post = $this->getRequest()->getPost();
      if (isset($post['media_thumbnail']['delete']) && $post['media_thumbnail']['delete'] == 1) {
        $data['media_thumbnail'] = '';
      }
      $is_edit_title = FALSE;
      if ($model->getId() && $model->getItemName() != $data['item_name']) {
        $is_edit_title = TRUE;
      }
      $model->setData($data)
        ->setId($this->getRequest()->getParam('id'));
      if (isset($data['selected_albums'])) {
        $albumIds = array();
        parse_str($data['selected_albums'], $albumIds);
        $albumIds = array_keys($albumIds);
      } else {
        $albumIds = array(0);
      }
      try {
        if ($model->getCreatedTime() == NULL || $model->getUpdateTime() == NULL) {
          $model->setCreatedTime(now())
            ->setUpdateTime(now());
        } else {
          $model->setUpdateTime(now());
        }
        $model->save();
        $item_url = Mage::helper('gallerymedia')->generateUrl($data['item_name']);
        $rewriteModel = Mage::getModel('core/url_rewrite');
        $request_path = 'gallery/item/' . $item_url;
        $id_path = 'gallerymedia/item/' . $model->getId();
        $rewriteModel->loadByIdPath($id_path);
        $store_id = Mage::app()->getStore()->getId();
        if (!$rewriteModel->getId()) {
          //create new item - check if request path is existed.
          $rewriteModel->loadByRequestPath($request_path);
          if ($rewriteModel->getId()) {
            $item_url = $item_url . '-' . $model->getId();
            $request_path = $request_path . '-' . $model->getId();
          }
          $rewriteModel1 = Mage::getModel('core/url_rewrite');
          $rewriteModel1->setData('id_path', 'gallerymedia/item/' . $model->getId());
          $rewriteModel1->setData('request_path', $request_path);
          $rewriteModel1->setData('target_path', 'gallerymedia/media/view/id/' . $model->getId());
          $rewriteModel1->setStoreId($store_id);
          $rewriteModel1->save();
        } else if ($is_edit_title) {
          $rewriteModel->setData('request_path', $request_path);
          $rewriteModel->setData('target_path', 'gallerymedia/media/view/id/' . $model->getId());
          $rewriteModel->setStoreId($store_id);
          $rewriteModel->save();
        }
        //save rewrite url to the model
        $model->setItemUrl($item_url)->save();
        Mage::helper('gallerymedia')->assignMediaToAlbum($model, $albumIds);
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
        $model = Mage::getModel('gallerymedia/galleryitems');
        $model->load($this->getRequest()->getParam('id'));
        $rewriteModel = Mage::getModel('core/url_rewrite');
        $request_path = 'gallery/item/' . $model->getItemUrl();
        $rewriteModel->loadByRequestPath($request_path);
        if ($rewriteModel->getId()) {
          $rewriteModel->delete();
        }
        $model->delete();
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
    $gallerymediaIds = $this->getRequest()->getParam('mediaitems');
    if (!is_array($gallerymediaIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
    } else {
      try {
        $rewriteModel = Mage::getModel('core/url_rewrite');
        foreach ($gallerymediaIds as $gallerymediaId) {
          $gallerymedia = Mage::getModel('gallerymedia/galleryitems')->load($gallerymediaId);
          $request_path = 'gallery/item/' . $gallerymedia->getItemUrl();
          $rewriteModel->loadByRequestPath($request_path);
          if ($rewriteModel->getId()) {
            $rewriteModel->delete();
          }
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
    $gallerymediaIds = $this->getRequest()->getParam('mediaitems');
    if (!is_array($gallerymediaIds)) {
      Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
    } else {
      try {
        foreach ($gallerymediaIds as $gallerymediaId) {
          $gallerymedia = Mage::getSingleton('gallerymedia/galleryitems')
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
    $fileName = 'galleryitems.csv';
    $content = $this->getLayout()->createBlock('gallerymedia/adminhtml_galleryitems_grid')
      ->getCsv();
    $this->_sendUploadResponse($fileName, $content);
  }

  public function exportXmlAction()
  {
    $fileName = 'galleryitems.xml';
    $content = $this->getLayout()->createBlock('gallerymedia/adminhtml_galleryitems_grid')
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
	
	protected function _isAllowed() {
		return Mage::getSingleton('admin/session')->isAllowed('gallerymedia/gallery_media_item');
	}
}
