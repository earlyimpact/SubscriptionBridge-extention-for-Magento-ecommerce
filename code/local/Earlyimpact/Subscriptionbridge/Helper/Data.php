<?php

class Earlyimpact_Subscriptionbridge_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	public function getSbCustomerCenterLinkDeatils($guid,$email)
    {
    	return $this->getSbCustomerCenterLink($guid,$email,'details'); 
    }
    
	public function getSbCustomerCenterLink($guid,$email,$mode)
    {
    	$url = Mage::getStoreConfig('subscriptionbridge_config/sbcenterlinks/customer_center');
    	return '<a target="_blank" href="'.$url.'AutoLogin.asp?ID='.$guid.'&Email='.$email.'&mode='.$mode.'">'.Mage::helper('subscriptionbridge')->__('Subscription Details').'</a>'; 
    }
    
	public function getSbMerchantCenterLink($guid,$text=null)
    {
    	if(!$text) $text = Mage::helper('subscriptionbridge')->__('Subscription Details');
    	$url = Mage::getStoreConfig('subscriptionbridge_config/sbcenterlinks/merchant_center');
    	return '<a target="_blank" href="'.$url.'SubscriptionslistDetails.asp?GUID='.$guid.'">'.$text.'</a>'; 
    }
       
    
	public function getSbApiWidget($sbLinkid)
    {
    	 $apiData = Mage::getModel('subscriptionbridge/api')->getTermsWidget($sbLinkid);
    	 //var_dump( $apiData );
    	 if((string)$apiData->Ack != 'Success') return null;
    	 $data = "";
    	 $data .= '
			<style type="text/css" media="all">
			/* CSS Style for Subscription Term Widget */
			
			/* This DIV wraps around the entire Widget */
			.sbTermsWrapper {
				width: 250px;
				background-color: #E9EEFE;
				border: 1px solid  #CCC;
				margin: 10px 0 15px 10px;
				padding: 10px;
				color: #000000;
				font-size: 12px;
				text-align: left;
				-moz-border-radius: 5px;
				-webkit-border-radius: 5px;
			}
			
			/* This DIV contains the current price (e.g. "Free for the first 15 days") */
			.sbTerms {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				color: #000000;
				text-align: left;
				margin-bottom: 2px;
			}
			
			/* This DIV contains the ongoing subscription amount (e.g. "$14.95 every month after 15 day trial") */
			.sbTermsSub {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #666666;
				text-align: left;
				margin-bottom: 2px;
			}
			
			/* This DIV contains additional, optional information (e.g. "Taxes added at checkout") */
			.sbTermsCustom {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #6699CC;
				text-align: left;
				font-style: italic;
			}
			
			</style>  	 
    	 ';
    	 
 
    	 $data .= '<div id="sbMainContainer"><div class="sbTermsWrapper">';
    	 if((string)$apiData->TermsTrialBilling) $data .= '<div class="sbTerms">'.$apiData->TermsTrialBilling.'</div>';
    	 if((string)$apiData->TermsBilling) $data .= '<div class="sbTermsSub">'.$apiData->TermsBilling.'</div>';
    	 if((string)$apiData->TermsCustom) $data .= '<div class="sbTermsCustom">'.$apiData->TermsCustom.'</div>';;
    	 $data .= "</div></div>";
    	 return $data; 
    }

    
    
}