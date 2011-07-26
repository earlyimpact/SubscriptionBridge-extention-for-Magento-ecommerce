<?php

class Earlyimpact_Subscriptionbridge_Adminhtml_ManageviewlinkController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		 
		if(Mage::getStoreConfig('subscriptionbridge_options/api/api_activation') == 0) {
			 Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscriptionbridge')->__('Please configure API credentials before continue'));
			 return $this->_redirect('adminhtml/system_config/edit/section/subscriptionbridge_options');
		}
		
		$this->loadLayout()
			->_setActiveMenu('subscriptionbridge/viewlink')
			;
		return $this;
	}
 
	public function indexAction() {	
		$this->_initAction()
			->renderLayout();
	}
	
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$product  = Mage::getModel('catalog/product')->load($id);

		if ($product->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);

			if (!empty($data)) {
				$product->setData($data);
			}
			
			$sbApiData = Mage::getModel('subscriptionbridge/api')->getGetPackagesRequestSelectUnique($product->getId());
			Mage::register('sb_api_data', $sbApiData);
			Mage::register('product_data', $product);
			
			$this->loadLayout();
			$this->_setActiveMenu('subscriptionbridge/viewlink');
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			
			$this->_addContent($this->getLayout()->createBlock('subscriptionbridge/adminhtml_manageviewlink_edit'))
				->_addLeft($this->getLayout()->createBlock('subscriptionbridge/adminhtml_manageviewlink_edit_tabs'));

			$this->renderLayout();
			
			
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscriptionbridge')->__('An error occured. Please try again'));
       		$this->_redirect('*/*/');
		}
	}
	
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			$product  = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('id'));
			
			//var_dump($data);
			//die();
			
			$product->setData('sb_linkid',$data['sb_linkid']);
			$product->setData('sb_trial',$data['sb_trial']);
			$product->setData('sb_trial_price',$data['sb_trial_price']);
			$product->setData('sb_shipping_trial',$data['sb_shipping_trial']);
			$product->setData('sb_shipping_trial_price',$data['sb_shipping_trial_price']);
			$product->setData('sb_tc_required',$data['sb_tc_required']);
			$product->setData('sb_tc_text',$data['sb_tc_text']);

				
			try {
				//Mage::throwException('kljlkjl');
				$product->save();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('subscriptionbridge')->__('Subscription package link was successfully modified'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $product->getId() ));
					return;
				}
				
				$this->_redirect('*/*/');
				return;
				
            } catch (Exception $e) {
            	/*
				 * TODO: se errore non mostra titolop prodotto 
				*/     
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id') ));
                return;
            }
        }
        //Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blog')->__('Unable to find post to save'));
        //Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscriptionbridge')->__('An error occured. Please try again'));
        //$this->_redirect('*/*/');			
	}
	
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $product = Mage::getModel('catalog/product')
                ->load($id);
            try {       	
                $product->setData('sb_linkid',null);
                $product->setData('sb_trial',null);
                $product->setData('sb_trial_price',null);
                $product->setData('sb_shipping_trial_price',null);  
                $product->setData('sb_shipping_trial',null);  
                $product->setData('sb_tc_required',null);
                $product->setData('sb_tc_text',null);
                $product->save();
                $this->_getSession()->addSuccess(Mage::helper('subscriptionbridge')->__('Link deleted'));
            }
            catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
       // $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store'))));
       $this->_redirect('*/*/');
    }

    

	
	
}