<?php
/**
 * @category  Magebuzz
 * @package   Magebuzz_Gallerymedia
 * @version   0.1.6
 * @copyright Copyright (c) 2012-2015 http://www.magebuzz.com
 * @license   http://www.magebuzz.com/terms-conditions/
 */

class Magebuzz_Gallerymedia_Adminhtml_GallerymultipleitemsController extends Mage_Adminhtml_Controller_action
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
      $this->_addContent($this->getLayout()->createBlock('gallerymedia/adminhtml_gallerymultipleitems_edit'))
        ->_addLeft($this->getLayout()->createBlock('gallerymedia/adminhtml_gallerymultipleitems_edit_tabs'));
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

  public function saveAction()
  {
    $data = $this->getRequest()->getPost();
    $listImages = json_decode($data['media_gallery']['images']);
    $imageIds = array();
    $status = 2;
    if (isset($data['selected_albums'])) {
      $albumIds = array();
      parse_str($data['selected_albums'], $albumIds);
      $albumIds = array_keys($albumIds);
    } else {
      $albumIds = array(0);
    }
    foreach ($listImages as $image) {
      if ($image->removed == 0) {
        if ($image->disabled == 0) {
          $status = 1;
        } else {
          $status = 2;
        }
        $thumbnail = Mage::helper('gallerymedia/image')->saveThumbnail($image->file, 150, 150, $ds = '');
        $imagesData = array(
          'item_name' => $image->label, 
          'media_type' => 1, 
          'item_file' => $image->file, 
          'created_time"'=> now(), 
          'status' => $status, 
          'media_thumbnail' => $thumbnail,
          'sort_order' => $image->position );
        $model = Mage::getModel('gallerymedia/galleryitems');
        $model->setData($imagesData);
        $model->save();
        Mage::helper('gallerymedia')->assignMediaToAlbum($model, $albumIds);
        $item_url = Mage::helper('gallerymedia')->generateUrl($image->label);
        $rewriteModel = Mage::getModel('core/url_rewrite');
        $request_path = 'gallery/item/' . $item_url;
        $id_path = 'gallerymedia/item/' . $model->getId();
        $rewriteModel->loadByIdPath($id_path);
        $store_id = Mage::app()->getStore()->getId();
        if (!$rewriteModel->getId()) {
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
        }
        $model->setItemUrl($item_url)->save();
      }
    }
    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('gallerymedia')->__('Item was successfully saved'));
    $this->_redirect('*/adminhtml_galleryitems/index');
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

  public function uploadAction()
  {
    try {
      $uploader = new Mage_Core_Model_File_Uploader('image');
      $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
      $uploader->setAllowRenameFiles(TRUE);
      $uploader->setFilesDispersion(TRUE);
      $result = $uploader->save(
        Mage::getSingleton('gallerymedia/config')->getBaseMediaPath()
      );
      $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
      $result['path'] = str_replace(DS, "/", $result['path']);
      $result['url'] = Mage::getSingleton('gallerymedia/config')->getMediaUrl($result['file']);
      $result['file'] = $result['file'];
      $result['cookie'] = array(
        'name'     => session_name(),
        'value'    => $this->_getSession()->getSessionId(),
        'lifetime' => $this->_getSession()->getCookieLifetime(),
        'path'     => $this->_getSession()->getCookiePath(),
        'domain'   => $this->_getSession()->getCookieDomain()
      );
    } catch (Exception $e) {
      $result = array(
        'error'     => $e->getMessage(),
        'errorcode' => $e->getCode());
    }
    Mage::register('data_media', $result);
    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
  }
	
	protected function _isAllowed() {
		return Mage::getSingleton('admin/session')->isAllowed('gallerymedia/gallery_media_multiple_item');
	}
}