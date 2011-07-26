<?php 

class Earlyimpact_Subscriptionbridge_Block_Checkout_Cart_Totals extends Mage_Checkout_Block_Cart_Totals
{
	
    
    
    
    public function renderTotal($total, $area = null, $colspan = 1)
    {
        $code = $total->getCode();
        if ($total->getAs()) {
            $code = $total->getAs();
        }
        
        $sbCarModel = Mage::getModel('subscriptionbridge/cart');
        $product = $sbCarModel->cartHasPackage(Mage::getSingleton('checkout/cart')->getProductIds());
        
        if($product) {
        	$taxCalculationModel = Mage::getSingleton('tax/calculation');
	        $quote = Mage::getSingleton('checkout/session')->getQuote();
	        
	        $hasTrialPrice =  $product->getSbTrial();
	        $hasTrialShipping =  $product->getSbShippingTrial();
	        
	        $totals = $this->getTotals();

	        $store = $quote->getStore();
	        $shippingTaxClass = Mage::getStoreConfig(Mage_Tax_Model_Config::CONFIG_XML_PATH_SHIPPING_TAX_CLASS, $store);	        
	        
	        $address = $quote->getShippingAddress();
	        
	        $shippingAmount = 0;
	        $shippingTax = 0;
	        
	        if ($shippingTaxClass && isset($totals['shipping'])) {
	        	
		        $shippingTax = 0;
		        
		        $request = $taxCalculationModel->getRateRequest($address,$quote->getBillingAddress(), $quote->getCustomerTaxClassId(),$store);
		        $shippingTaxRate = $taxCalculationModel->getRate($request->setProductClassId($shippingTaxClass));
		        
		        if($hasTrialShipping) {
		            $shippingAmount =  $product->getSbShippingTrialPrice();
		        	$shippingTax = $product->getSbShippingTrialPrice() * $shippingTaxRate/100;

	        	} else {
	        		
	        		$shippingquote = $totals['shipping'];
	        		$shippingAmount = $shippingquote->getValue();
	        		if(isset($totals['tax'])) $taxquote = $totals['tax'];
	        		else $taxquote = 1;
	        		$shippingTax = $shippingAmount * $shippingTaxRate/100;

	        	}
	       
	        } elseif(isset($totals['shipping'])) {
	        	$shippingquote = $totals['shipping'];
	        	$shippingAmount = $shippingquote->getValue();
	        	$shippingTax = 0;
	        }
	        else {
	        	$shippingTax = 0;
	        	$shippingAmount = 0;
	        }

	        $_request = Mage::getSingleton('tax/calculation')->getRateRequest($address,$quote->getBillingAddress(), $quote->getCustomerTaxClassId(),$store);
			$_request->setProductClassId($product->getTaxClassId());
			$_request->setCustomerClassId($quote->getCustomerTaxClassId());
			$productTaxRate = Mage::getSingleton('tax/calculation')->getRate($_request);
			    
	        if ($hasTrialPrice) {
		        $productTax = $store->roundPrice( $product->getSbTrialPrice() * $productTaxRate/100);
		        $subtotal = $product->getSbTrialPrice();
	        } else {
	        	$subtotalquote = $totals['subtotal'];
	        	$subtotal = $subtotalquote->getvalue();
	        	$productTax = $store->roundPrice( $subtotal * $productTaxRate/100);
	        }

            if($code=='grand_total') {
            	$total->setValue($store->roundPrice( ($productTax+$subtotal)+($shippingTax+$shippingAmount) ));	
	        }
	        elseif($code=='subtotal') {
	        	$total->setValue($subtotal);
	        }
	        elseif($code=='shipping') {

	        	if ($hasTrialShipping) $total->setValue($product->getSbShippingTrialPrice());
	        }
	        elseif($code=='tax') {	
	        	
	        	$fi = $total->getFullInfo();
	        	foreach(array_keys($fi) as $arrayKey) break;
	        	$fi[$arrayKey]['amount'] = $store->roundPrice($shippingTax + $productTax);
	        	$fi[$arrayKey]['base_amount'] = $store->roundPrice($shippingTax + $productTax);
	        	$total->setFullInfo($fi);  

	        	$total->setValue( $store->roundPrice($shippingTax + $productTax) );
	        	
 	
	        } 
	        
			//Mage::log($totals);
	      
        }

        
        
        return $this->_getTotalRenderer($code)
            ->setTotal($total)
            ->setColspan($colspan)
            ->setRenderingArea(is_null($area) ? -1 : $area)
            ->toHtml();
    }
}