<?php 

class Earlyimpact_Subscriptionbridge_Helper_Checkout_Data extends Mage_Checkout_Helper_Data
{
	 public function getRequiredAgreementIds()
    {
    	$agreements = parent::getRequiredAgreementIds();
    	
    	$quote = Mage::getModel('checkout/session')->getQuote();
		foreach ($quote->getAllItems() as $item) { 
			$product  = Mage::getModel('catalog/product')->load($item->getProductId());
		    if ( $item->getData('sb_linkid') && $product->getData('sb_tc_required') ) {              	
		    	array_push($agreements,0);		
		 	}
		 }
		 
    	return $agreements;
    }
}