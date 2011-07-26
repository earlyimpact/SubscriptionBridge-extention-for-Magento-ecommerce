<?php



class Earlyimpact_Subscriptionbridge_Model_Sbpayment_Payment extends Mage_Payment_Model_Method_Cc
{
	protected $_code = 'subscriptionbridge';
	protected $_isGateway = true;
	protected $_canAuthorize = true;
	protected $_canCapture = false;
	protected $_canCapturePartial = false;
	protected $_canRefund = false;
	protected $_canVoid = false;
	protected $_canUseInternal = true;
	protected $_canUseCheckout = true;
	protected $_canUseForMultishipping = true;
	protected $_canSaveCc = false;
	

   /**
     * Get checkout session namespace
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current quote
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }
    
    
    public function isAvailable($quote=null)
    {
        //return Mage::getStoreConfig('payment/subscriptionbridge_cc/active') > 0;
        return true;
    }
    
    public function authorize(Varien_Object $payment, $amount)
    {
    	$bridgeApi = Mage::getModel('subscriptionbridge/api');
    	
       	foreach ( $this->getQuote()->getAllItems() as $item ) {
    		$cartItem = $item; break;
    	}

		/*
		 * set SB data
		 */
    	$store = Mage::app()->getStore();
     	
     	$paymentData = $payment->getData();
    	$orderData = $payment->getOrder();
    	$quoteData = $this->getQuote();
    	$billingData = $payment->getOrder()->getBillingAddress();
    	$shippingData = $payment->getOrder()->getShippingAddress();
    	        	
        
 		
	    //Mage::log($cartItem['tax_percent']);
	   // Mage::log($shippingTax);
	    //Mage::log($trialTax);
	    
        $payload = '';
        $payload .= '<Customer>';
        $payload .= '
 			   <Email>'.$orderData['customer_email'].'</Email>
               <FirstName>'.$orderData['customer_firstname'].'</FirstName>
               <LastName>'.$orderData['customer_lastname'].'</LastName>
               <BillingAddress>
                      <FirstName>'.$billingData['firstname'].'</FirstName>
                      <LastName>'.$billingData['lastname'].'</LastName>
                      <Company>'.$billingData['company'].'</Company>
                      <Address>'.$billingData['street'].'</Address> 
                      <City>'.$billingData['city'].'</City>
                      <Region>'.$billingData['region'].'</Region>
                      <PostalCode>'.$billingData['postcode'].'</PostalCode>
                      <Country>'.$billingData['country_id'].'</Country>
                      <Phone>'.$billingData['telephone'].'</Phone>
               </BillingAddress>
               <ShippingAddress>
                      <FirstName>'.$shippingData['firstname'].'</FirstName>
                      <LastName>'.$shippingData['lastname'].'</LastName>
                      <Company>'.$shippingData['company'].'</Company>
                      <Address>'.$shippingData['street'].'</Address>
                      <City>'.$shippingData['city'].'</City>
                      <Region>'.$shippingData['region'].'</Region>
                      <PostalCode>'.$shippingData['postcode'].'</PostalCode>
                      <Country>'.$shippingData['country_id'].'</Country>
                      <Phone>'.$shippingData['telephone'].'</Phone>
               </ShippingAddress>       
        ';
        $payload .= '</Customer>';
        $payload .= '<CreditCard>';
                
        $payload .= '
               <CardNumber>'.$paymentData['cc_number'].'</CardNumber>
               <CardType>'.$this->CCTypeConversion($paymentData['cc_type']).'</CardType>
               <ExpMonth>'.$paymentData['cc_exp_month'].'</ExpMonth>
               <ExpYear>'.$paymentData['cc_exp_year'].'</ExpYear>
               <SecureCode>'.$paymentData['cc_cid'].'</SecureCode>
        ';
        $payload .= '</CreditCard>';
        
        
        
        $isShippable = ($paymentData['shipping_amount'] == '0.0000') ? "false" : "true";
        
        
        $payload .= '<Cart>';
        $payload .= '
               <RegularAmt>'.$orderData['subtotal'].'</RegularAmt>            
               <RegularTax>'.$orderData['tax_amount'].'</RegularTax>           
               <RegularShipping>'.$orderData['shipping_amount'].'</RegularShipping>    
               <IsShippable>'.$isShippable.'</IsShippable>
               <ShipName>'.$orderData['shipping_description'].'</ShipName> 
               <AgreedToTerms>true</AgreedToTerms>
               <LanguageCode>1</LanguageCode>
        ';
        
        
	    $hasTrialPrice =  $cartItem['sb_trial'];
	    $hasTrialShipping =  $cartItem['sb_shipping_trial'];
	        
		$taxCalculationModel = Mage::getSingleton('tax/calculation');
        $shippingTaxClass = Mage::getStoreConfig(Mage_Tax_Model_Config::CONFIG_XML_PATH_SHIPPING_TAX_CLASS, $store);
        $trialTax = 0;
        $shippingTax = 0;
        if ($shippingTaxClass) {
        		$quote = Mage::getSingleton('checkout/session')->getQuote();
	        	$address = $quote->getShippingAddress();
	        	$request = $taxCalculationModel->getRateRequest($address,$quote->getBillingAddress(), $quote->getCustomerTaxClassId(),$store);
	        	$rate = $taxCalculationModel->getRate($request->setProductClassId($shippingTaxClass));
	        	$shippingTax = $cartItem['sb_shipping_trial_price'] * $rate/100; 		
	    }
	    
	    if($hasTrialPrice) $trialTax += ($cartItem['tax_percent'] *  $cartItem['sb_trial_price']/100);
	    if($hasTrialShipping) $trialTax += $shippingTax;
	    
	    if($hasTrialPrice) $payload .= '
	    	<TrialAmt>'.$cartItem['sb_trial_price'].'</TrialAmt>';
	    
	    if($hasTrialShipping) $payload .= '
	    	<TrialShipping>'.$cartItem['sb_shipping_trial_price'].'</TrialShipping>';
       
	    if($hasTrialPrice || $hasTrialShipping) $payload .= '
	    	<TrialTax>'.$trialTax.'</TrialTax>';
                
        
        $payload .= '</Cart>';   

        $IsTrial = ($cartItem['sb_trial']) ? "true" : "true";
        
        $payload .= '<Package>';
        $payload .= '
               <LinkID>'.$cartItem['sb_linkid'].'</LinkID>
               <Plan>
                      <Profile>
                             <IsTrial>'.$IsTrial.'</IsTrial>
                      </Profile>
               </Plan>        
        
        
        ';
        $payload .= '</Package>';
    	
        //Mage::log($payment->toArray());
       // Mage::log($orderData->toArray());
    	//Mage::log($payload);
    	//Mage::log( Mage::registry('aaaaaa') );
    	
        
        //Mage::log( Mage::getSingleton('checkout/session')->toArray() );
        
        
    	
		//Mage::throwException('ssss');
    	//return;
	     
		

    	
    	
    	//Mage::log($guid);
    	//marco test
    	$guid = $bridgeApi->getSubscriptionRequest($payload);
    	//$guid = '1111';
    	
    	
    	
    	if($guid) {
    		
        	$payment
                ->setStatus(self::STATUS_APPROVED)
                ->setCcTransId($guid);
                
    		//$orderData->setSbGuid('aaaa');
    	}
		else {
			$error = Mage::helper('subscriptionbridge')->__('Payment authorization error.');
			$error .= ' '.$bridgeApi->getResponseError();
				//Mage::log($error);
			Mage::throwException($error);
			
			$payment->setStatus(self::STATUS_ERROR);
		} 
		
		
    	return $this;
    }  

    
	public function CCTypeConversion($cctype)
    {
        $verificationExpList = array(
            'VI' => 'Visa',
      		'AE' => 'Amex',
            'MC' => 'MasterCard',
      		'DI' => 'Discover',                   
        );
        return $verificationExpList[$cctype];
    }
    
    

	    
}