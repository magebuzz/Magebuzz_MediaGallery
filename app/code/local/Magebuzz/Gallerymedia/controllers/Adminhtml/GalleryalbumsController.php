<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.7
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Adminhtml_GalleryalbumsController extends Mage_Adminhtml_Controller_action
{
  protected function _initAction()
  {
    $this->loadLayout()
      ->_setActiveMenu('gallerymedia/items')
      ->_addBreadcrumb(Mage::helper('adminhtml')->__('Albums Manager'), Mage::helper('adminhtml')->__('Albums Manager'));

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
    $model = Mage::getModel('gallerymedia/galleryalbums')->load($id);
    if ($model->getId() || $id == 0) {
      $data = Mage::getSingleton('adminhtml/session')->getFormData(TRUE);
      if (!empty($data)) {
        $model->setData($data);
      }
      Mage::register('galleryalbums_data', $model);
      $this->loadLayout();
      $this->_setActiveMenu('gallerymedia/items');
      $this->getLayout()->getBlock('head')->setCanLoadExtJs(TRUE);
      $this->getLayout()->getBlock('head')->setCanLoadTinyMce(TRUE);

      $this->_addContent($this->getLayout()->createBlock('gallerymedia/adminhtml_galleryalbums_edit'))
        ->_addLeft($this->getLayout()->createBlock('gallerymedia/adminhtml_galleryalbums_edit_tabs'));

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
    $this->getResponse()->setBody($this->getLayout()->createBlock('gallerymedia/adminhtml_galleryalbums_grid')->toHtml());
  }

  public function saveAction()
  {
    if ($data = $this->getRequest()->getPost()) {
      $model = Mage::getModel('gallerymedia/galleryalbums');
      if ($id = $this->getRequest()->getParam('id')) {
        $model->load($id);
      }

      if (isset($_FILES['album_image']['name']) && $_FILES['album_image']['name'] != '') {
        $info = pathinfo($_FILES['album_image']['name']);
        $newFileName = Mage::helper('gallerymedia')->generateUrl($info['filename']) . '.' . $info['extension'];
        try {
          $uploader = new Varien_File_Uploader('album_image');
          $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
          $uploader->setAllowRenameFiles(FALSE);
          $uploader->setFilesDispersion(FALSE);
          $path = Mage::getBaseDir('media') . DS . 'gallerymedia' . DS . 'albums' . DS;
          $uploader->save($path, $newFileName);

        } catch (Exception $e) {
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
          Mage::getSingleton('adminhtml/session')->setFormData($data);
          $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
          return;
        }

        //this way the name is saved in DB
        $data['album_image'] = $newFileName;
      } elseif ($model->getAlbumImage()) {
        $data['album_image'] = $model->getAlbumImage();
      }
      $post = $this->getRequest()->getPost();
      if (isset($post['album_image']['delete']) && $post['album_image']['delete'] == 1) {
        $data['album_image'] = '';
      }
      $is_edit_title = FALSE;
      if ($model->getId() && $model->getAlbumName() != $data['album_name']) {
        $is_edit_title = TRUE;
      }
			
			$newStores = (array)$data['store_id'];
			if (in_array(0, $newStores)) {
				$stores = '0';
			}
			else {
				$stores = implode($newStores, ',');
			}			
			$data['store_ids'] = $stores;

      $model->setData($data)
        ->setId($this->getRequest()->getParam('id'));

      try {
        if ($model->getCreatedTime() == NULL || $model->getUpdateTime() == NULL) {
          $model->setCreatedTime(now())
            ->setUpdateTime(now());
        } else {
          $model->setUpdateTime(now());
        }

        $model->save();
        $album_url = Mage::helper('gallerymedia')->generateUrl($data['album_name']);

        $rewriteModel = Mage::getModel('core/url_rewrite');
        $id_path = 'gallerymedia/album/' . $model->getId();

        $request_path = 'gallery/album/' . $album_url;
        $rewriteModel->loadByIdPath($id_path);

        if (!$rewriteModel->getId()) {
          $rewriteModel->loadByRequestPath($request_path);
          if ($rewriteModel->getId()) {
            $album_url = $album_url . '-' . $model->getId();
            $request_path = $request_path . '-' . $model->getId();
          }
          $rewriteModel1 = Mage::getModel('core/url_rewrite');
          $rewriteModel1->setData('id_path', 'gallerymedia/album/' . $model->getId());
          $rewriteModel1->setData('request_path', $request_path);
          $rewriteModel1->setData('target_path', 'gallerymedia/album/view/id/' . $model->getId());
          $rewriteModel1->save();
        } else if ($is_edit_title) {
          $rewriteModel->setData('request_path', $request_path);
          $rewriteModel->setData('target_path', 'gallerymedia/album/view/id/' . $model->getId());
          $rewriteModel->setStoreId($store_id);
          $rewriteModel->save();
        }
        $model->setAlbumUrl($album_url)->save();

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

        $model = Mage::getModel('gallerymedia/galleryalbums');

        //delete rewrite url
        $model->load($this->getRequest()->getParam('id'));
        $rewriteModel = Mage::getModel('core/url_rewrite');
        $request_path = 'gallery/album/' . $model->getAlbumUrl();
        $rewriteModel->loadByRequestPath($request_path);

        if ($rewriteModel->getId()) {
          $rewriteModel->delete();
        }

        //delete album
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
    $gallerymediaIds = $this->getRequest()->getParam('albums');
    if (!is_array($gallerymediaIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
    } else {
      try {
        $rewriteModel = Mage::getModel('core/url_rewrite');
        foreach ($gallerymediaIds as $gallerymediaId) {
          $gallerymedia = Mage::getModel('gallerymedia/galleryalbums')->load($gallerymediaId);
          $request_path = 'gallery/album/' . $gallerymedia->getAlbumUrl();
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
    $gallerymediaIds = $this->getRequest()->getParam('albums');
    if (!is_array($gallerymediaIds)) {
      Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
    } else {
      try {
        foreach ($gallerymediaIds as $gallerymediaId) {
          $gallerymedia = Mage::getSingleton('gallerymedia/galleryalbums')
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
    $fileName = 'galleryalbums.csv';
    $content = $this->getLayout()->createBlock('gallerymedia/adminhtml_galleryalbums_grid')
      ->getCsv();
    $this->_sendUploadResponse($fileName, $content);
  }

  public function exportXmlAction()
  {
    $fileName = 'galleryalbums.xml';
    $content = $this->getLayout()->createBlock('gallerymedia/adminhtml_galleryalbums_grid')
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
		return Mage::getSingleton('admin/session')->isAllowed('gallerymedia/gallery_media_album');
	}
}