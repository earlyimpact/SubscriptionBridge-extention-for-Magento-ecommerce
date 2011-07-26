<?php
include_once('Mage/Checkout/controllers/MultishippingController.php');
class Earlyimpact_Subscriptionbridge_Checkout_MultishippingController extends Mage_Checkout_MultishippingController
{
	 public function postDispatch()
    {
    	$sbCarModel = Mage::getModel('subscriptionbridge/cart');
        $product = $sbCarModel->cartHasPackage(Mage::getSingleton('checkout/cart')->getProductIds());
        if($product) {
        	
        	Mage::getModel('checkout/session')->addError( Mage::getStoreConfig('subscriptionbridge_config/messages/msg_checkout_cartnotvalid') );
        	return $this->_redirect('checkout/cart');
        }
    }
    
    /*
   
	 public function indexAction()
    {
    	
       
		parent::indexAction();

        $productsCounter = 0;
        $packagesCounter = 0;
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
			Mage::getSingleton('checkout/session')->addError( Mage::getStoreConfig('subscriptionbridge_config/messages/msg_checkout_cartnotvalid') );
			return $this->_redirect('checkout/cart');
		} 
		
    	
    }
    */
    

	
}