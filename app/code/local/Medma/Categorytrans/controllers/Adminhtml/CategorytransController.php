<?php

class Medma_Categorytrans_Adminhtml_CategorytransController extends Mage_Adminhtml_Controller_action
{
	public function indexAction() {

		
	    	$this->loadLayout(); 
		$this->_setActiveMenu('catalog/categorytrans');
		//$this->_addBreadcrumb($this->__('Manage Grouped Product Discount Rule'), $this->__('Discount')); 
		$this->_addContent($this->getLayout()->createBlock('categorytrans/adminhtml_categorytrans'));
 	    	$this->renderLayout();

	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');

		$model  = Mage::getModel('categorytrans/categorytrans')->load($id);
		
		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('categorytrans_data', $model);
			$this->loadLayout();
			$this->_setActiveMenu('catalog/categorytrans');

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('categorytrans/adminhtml_categorytrans_edit'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('categorytrans')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 	
	

	
	public function newAction() {
		$this->editAction();
	}
 
	public function saveAction() {
 	//$id= $this->getRequest()->getParam('id');
		
	    $model= Mage::getModel('categorytrans/categorytrans');	
		//print_r($model);
		if ($data = $this->getRequest()->getPost()) {
			//print_r($data);exit;
			try {

						$model->setCategoryId($data['category_id']);
						$model->setCategoryName($data['category_name']);
						$model->setEnglish($data['english']);
							

				//$model->setData($data);
				$model->save();

				 Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('categorytrans')->__('Category is successfully translated'));
				
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('categorytrans')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
        }
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('categorytrans/categorytrans');
				 
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
	
	

}
