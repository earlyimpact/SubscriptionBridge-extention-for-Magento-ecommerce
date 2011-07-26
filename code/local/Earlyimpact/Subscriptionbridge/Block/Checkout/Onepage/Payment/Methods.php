<?php

class Earlyimpact_Subscriptionbridge_Block_Checkout_Onepage_Payment_Methods extends Mage_Checkout_Block_Onepage_Payment_Methods
{

    protected function _canUseMethod($method)
    {
 
    	$isSB = false;
        foreach ( $this->getQuote()->getAllItems() as $item ) {
    		$cartItem = $item;
    		if($cartItem['sb_linkid']){ $isSB = true; break; }
    	}
    	
    	if($isSB && $method->getCode() != 'subscriptionbridge') {
    		return false;
    	}	
    	elseif(!$isSB && $method->getCode() == 'subscriptionbridge') {
    		return false;
    	}  

    	
        return parent::_canUseMethod($method);
    }


}
