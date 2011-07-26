<?php

class  Earlyimpact_Subscriptionbridge_Block_Checkout_Agreements extends Mage_Checkout_Block_Agreements
{

    public function getAgreements()
    {

		$quote = Mage::getModel('checkout/session')->getQuote();
		foreach ($quote->getAllItems() as $item) { 
			$product  = Mage::getModel('catalog/product')->load($item->getProductId());
		    if ( $item->getData('sb_linkid') && $product->getData('sb_tc_required') ) {              	
		    	$SBagreement = Mage::getModel('checkout/agreement')->getCollection()->getNewEmptyItem();
				$SBagreement->setData('agreement_id',0);
				$SBagreement->setData('name','SB TC');
				$SBagreement->setData('content',Mage::helper('subscriptionbridge')->__($product->getData('sb_tc_text')));
				$SBagreement->setData('content_height', ''); 
				$SBagreement->setData('checkbox_text',Mage::getStoreConfig('subscriptionbridge_config/messages/msg_checkout_tctitle'));
				$SBagreement->setData('is_active',1);
				$SBagreement->setData('is_html', 1);		
		 	}
		 }
		    	 
    	
        if (!$this->hasAgreements()) {
          //  if (!Mage::getStoreConfigFlag('checkout/options/enable_agreements')) {
          //      $agreements = Mage::getModel('checkout/agreement')->getCollection();
          //  } else {
                $agreements = Mage::getModel('checkout/agreement')->getCollection()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->addFieldToFilter('is_active', 1);     
          //  }
        }
        
      
        if(isset($agreements)) {       	
        	if(isset($SBagreement)) $agreements->addItem($SBagreement);
        	$this->setAgreements($agreements); 
        }       
        elseif(!isset($agreements) && isset($SBagreement))  {
        	$agreements = array();
        	$agreements = $SBagreement;
        }
     
        
        return $this->getData('agreements');



    	
    }

    
	
    
    
    
}