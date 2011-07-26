<?php

class Earlyimpact_Subscriptionbridge_Adminhtml_ManagelinkController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		
		if(Mage::getStoreConfig('subscriptionbridge_options/api/api_activation') == 0) {
			 Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscriptionbridge')->__('Please configure API credentials before continue'));
			 return $this->_redirect('adminhtml/system_config/edit/section/subscriptionbridge_options');
		}
		
		$this->loadLayout()
			->_setActiveMenu('subscriptionbridge/link')
			//->_addBreadcrumb(Mage::helper('subscriptionbridge')->__('Items Manager'), Mage::helper('subscriptionbridge')->__('Item Manager'));
			;
		return $this;
	}
 
	public function indexAction() {
		
		$this->_initAction()
			->renderLayout();
		
	}
	

	public function newAction() {
	   	
	    $productId = (int) $this->getRequest()->getParam('product_id', 0);
		$model  = Mage::getModel('catalog/product')->load($productId); 
	    
        $sbApiData = Mage::getModel('subscriptionbridge/api')->getGetPackagesRequestSelectUnique($productId);
        if(empty($sbApiData)) {
        	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscriptionbridge')->__('No Package available'));
	        return $this->_redirectReferer(); 
        }
        
        if($productId!=0 && $model->getId() ) {
        	
        	Mage::register('sb_api_data', $sbApiData);
        	
        	//$modelLink = Mage::getModel('subscriptionbridge/link')->getCollection()->setProductIdFilter($productId);
			//if($modelLink->count()===1) {
			//	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscriptionbridge')->__('Product is already linked to a subscription package'));
	        //	return $this->_redirect('*/*/');
			//}
			
        	
        	Mage::register('product_data', $model);
	        
			$this->loadLayout();
			$this->_setActiveMenu('subscriptionbridge/link');
			//$this->_addBreadcrumb(Mage::helper('subscriptionbridge')->__('BrandsManager'), Mage::helper('subscriptionbridge')->__('Brands Manager'));
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()
						->createBlock('subscriptionbridge/adminhtml_managelink_edit'))
						->_addLeft($this->getLayout()->createBlock('subscriptionbridge/adminhtml_managelink_edit_tabs'))
						;
			$this->renderLayout();       	
        }
        else {
	 		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscriptionbridge')->__('An error occured. Please try again'));
	        return $this->_redirectReferer();       	
        }
	
        
        



	}

	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			$product  = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product_id')); 

			$product->setData('sb_linkid',$data['sb_linkid']);
			$product->setData('sb_trial',$data['sb_trial']);
			$product->setData('sb_trial_price',$data['sb_trial_price']);
			$product->setData('sb_shipping_trial_price',$data['sb_shipping_trial_price']);
			$product->setData('sb_shipping_trial',$data['sb_shipping_trial']);
			$product->setData('sb_tc_required',$data['sb_tc_required']);
			$product->setData('sb_tc_text',$data['sb_tc_text']);
			
			try {
				//Mage::throwException('kljlkjl');
				$product->save();
			
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('subscriptionbridge')->__('Subscription package link was successfully created'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $product->getId()));
					return;
				}
				
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
            	//die('exc');
            	/*
				 * TODO: se errore non salva i dati 
				*/
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);	
                $this->_redirect('*/*/new', array('id' => $this->getRequest()->getParam('product_id') ));
                return;
            }
            
		}
			
		//Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscriptionbridge')->__('An error occured. Please try again'));
       // $this->_redirect('*/*/');
        
	}
	
}