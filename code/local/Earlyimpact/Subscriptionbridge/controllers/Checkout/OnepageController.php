<?php
include_once('Mage/Checkout/controllers/OnepageController.php');

class Earlyimpact_Subscriptionbridge_Checkout_OnepageController extends Mage_Checkout_OnepageController
{

	 public function preDispatch()
    {

    	
		parent::preDispatch();

        $productsCounter = 0;
        $packagesCounter = 0;
        $maxPackageCounter = 0;
        $quote = Mage::getModel('checkout/session')->getQuote();    
		foreach ($quote->getAllItems() as $item) { 
			if($item->getData('sb_linkid')) { 
				if($item->getQty() > 1) {
					Mage::getModel('checkout/session')->addError( Mage::getStoreConfig('subscriptionbridge_config/messages/msg_checkout_cartnotvalid') );
					return $this->_redirect('checkout/cart');
				}
				$packagesCounter++;
			
			}
			else $productsCounter++;
		}

		
		if(($productsCounter && $packagesCounter) || $packagesCounter > 1) {
			Mage::getModel('checkout/session')->addError( Mage::getStoreConfig('subscriptionbridge_config/messages/msg_checkout_cartnotvalid') );
			return $this->_redirect('checkout/cart');
		} 
		
    	return $this;
    }
    
    
    


	
}