<?php

class Earlyimpact_Subscriptionbridge_Model_Checkout_Cart extends Mage_Checkout_Model_Cart
{
	 public function addProduct($product, $info=null)
    {
    	
		$product = $this->_getProduct($product);
        $request = $this->_getProductRequest($info);
        
        $productInCartIsSubscription = false;
     	$currentProductIsSubscription = false;
        
        // one product has been previously added into the cart
        if ($product->getId() && $this->getItemsCount() > 0 ) {

        	foreach($this->getItems() as $item) { $productInCartId = $item->getProductId(); break; }
        	
        	if($item->getSbLinkid()) $productInCartIsSubscription = true;
        	if($product->getSbLinkid()) $currentProductIsSubscription = true;

        	
			if($productInCartIsSubscription && $currentProductIsSubscription) Mage::throwException(Mage::getStoreConfig('subscriptionbridge_config/messages/msg_cart_onesubcart'));
			if($productInCartIsSubscription) Mage::throwException(Mage::getStoreConfig('subscriptionbridge_config/messages/msg_cart_subnoprod'));
			if(!$productInCartIsSubscription && $currentProductIsSubscription) Mage::throwException(Mage::getStoreConfig('subscriptionbridge_config/messages/msg_cart_prodnosub'));
 			if($currentProductIsSubscription && $request->getQty() > 1) Mage::throwException(Mage::getStoreConfig('subscriptionbridge_config/messages/msg_cart_onesubcart'));
 			
        }
      
    	return parent::addProduct($product, $info=null);
    }
    
    
     public function updateItems($data)
    {
    	
    	
    	foreach ($data as $itemId => $itemInfo) {
            $item = $this->getQuote()->getItemById($itemId);
            if (!$item) {
                continue;
            }
       
            $qty = isset($itemInfo['qty']) ? (float) $itemInfo['qty'] : false;
            if ($qty > 1 && $item['sb_linkid'] ) {
               $item->setQty(1);
               Mage::throwException(Mage::getStoreConfig('subscriptionbridge_config/messages/msg_cart_onesubcart'));
               return $this;
               
            }
        }
        
        
    	return parent::updateItems($data);
    }
}