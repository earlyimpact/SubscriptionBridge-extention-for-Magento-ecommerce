<?php

define("API_SUCCESS", "Success");

class Earlyimpact_Subscriptionbridge_Model_Api extends Mage_Core_Model_Abstract
{
	protected $_apiData;
	protected $_responseError;
	
    public function _construct() {
        parent::_construct();
        
    	$this->_apiData['username'] = Mage::getStoreConfig('subscriptionbridge_options/api/api_username');
    	$this->_apiData['key'] = Mage::getStoreConfig('subscriptionbridge_options/api/api_key');
    	$this->_apiData['password'] = Mage::getStoreConfig('subscriptionbridge_options/api/api_password');
    	$this->_apiData['now'] = $this->actual_time("Y-m-d\TH:i:00",1,time()); 
    	$this->_apiData['hash'] = $this->custom_hmac('sha1', $this->_apiData['password'].'|'.$this->_apiData['now'], $this->_apiData['key'], false); 
        $this->_apiData['url'] = Mage::getStoreConfig('subscriptionbridge_options/api/api_url');
    }
     
    public function getResponseError() {
        return $this->_responseError;
    }  

    
    public function getGetPackagesRequest() {
    	
        $method = 'GetPackagesRequest';
        $payload = '<GetPackagesRequest><Username>'.$this->_apiData['username'].'</Username><Token>'.$this->_apiData['hash'].'</Token></GetPackagesRequest>';
        $apiData = $this->callAPI($method,$payload);
        return $apiData;
    }  

    
    public function getGetPackagesRequestSelect() {
        $apiData = $this->getGetPackagesRequest();
        $select = array(  );
        foreach($apiData->response->Packages->Package as $package ) {
        	$LinkID = (string) $package->LinkID;
        	$PackageName = (string) $package->PackageName;
        	array_push( $select, array( 'value' => $LinkID, 'label' => $PackageName ) );
       
        }
        return $select;
     
    }  
    
    /*
     * return packages not used yet in magento
     */
    public function getGetPackagesRequestSelectUnique($product_id) {
   	
    	$apiData = $this->getGetPackagesRequest();
        $select = array(  );
        foreach($apiData->response->Packages->Package as $package ) {
        	$LinkID = (string) $package->LinkID;
        	$PackageName = (string) $package->PackageName;
        	
        	/*
        	 * linkid already used
        	 */
        	$productCollection = Mage::getModel('catalog/product')
        	->getCollection()
            ->addAttributeToFilter('sb_linkid', $LinkID)
            ->load();  	
    	    $productArray = $productCollection->getData();
            if( $productCollection->count() && $productArray[0]['entity_id'] != $product_id ) continue;            
        	
            array_push( $select, array( 'value' => $LinkID, 'label' => $PackageName ) );
       
        }
        return $select;
     
    }    
    
    

    public function packageExsist($MagentoLinkID) {
        $apiData = $this->getGetPackagesRequest(); 
        foreach($apiData->response->Packages->Package as $package ) {
        	$SBLinkID = (string) $package->LinkID;
        	if($SBLinkID == $MagentoLinkID) { return true; }
        }
        
        return false;
    }
      
    
    public function getGetActivationRequest() {
        $method = 'ActivationRequest';
        $payload = '<ActivationRequest><Username>'.$this->_apiData['username'].'</Username><Token>'.$this->_apiData['hash'].'</Token></ActivationRequest>';
        $apiData = $this->callAPI($method,$payload);
        
        if($apiData->status == 200 && $apiData->response->Ack == API_SUCCESS )  {	
        	Mage::getConfig()->saveConfig('subscriptionbridge_options/api/api_activation', 1);
	        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('subscriptionbridge')->__('Store is now connecting with SubscriptionBridge'));
	        return true;
        } 
        
        Mage::throwException($apiData->response->ErrorDetail);
        return false;
        	 
    }     
    

      public function getSubscriptionRequest($sbpayload) {
     	
      	$this->_apiData['now'] = $this->actual_time("Y-m-d\TH:i:00",1,time()); 
      	$this->_apiData['hash'] = $this->custom_hmac('sha1', $this->_apiData['password'].'|'.$this->_apiData['now'], $this->_apiData['key'], false);
      	
        $method = 'SubscriptionRequest';
        $payload = '<SubscriptionRequest><Username>'.$this->_apiData['username'].'</Username><Token>'.$this->_apiData['hash'].'</Token>';
        $payload .= $sbpayload;
        $payload .= '</SubscriptionRequest>';
        $apiData = $this->callAPI($method,$payload);     	
		
        //Mage::log($payload);
              
        if($apiData->status == 200 && $apiData->response->Ack == API_SUCCESS )  {	
        	return $apiData->response->Guid;
        } 
        
        $this->_responseError = $apiData->response->ErrorDetail;
        return false;
    }     
    
     
    public function getGetTermsRequest($linkID) {
        $method = 'GetTermsRequest';
        $payload = '<GetTermsRequest><LinkID>'.$linkID.'</LinkID></GetTermsRequest>';
        $apiData = $this->callAPI($method,$payload);       

        return $apiData->response;
        
    } 

    
    public function getTermsWidget($linkID) {
    	return $this->getGetTermsRequest($linkID);     
    }   
    
   
    
    
    
    
    public function callAPI($method,$payload) {
        $client = new Zend_Http_Client($this->_apiData['url'].$method);
		$request = $client->setRawData($payload)->setEncType('text/xml')->request('POST');
		$obj = (object) array('status' => $request->getStatus(), 'response' => simplexml_load_string($request->getBody()));
		//Mage::log($obj);
		return $obj;
    }     

	function actual_time($format,$offset,$timestamp){
	   //Offset is in hours from gmt, including a - sign if applicable.
	   //So lets turn offset into seconds
	   $offset = $offset*60*60;
	   $timestamp = $timestamp + $offset;
		//Remember, adding a negative is still subtraction ;)
	   return gmdate($format,$timestamp);
	}
	
	function custom_hmac($algo, $data, $key, $raw_output) {
		$algo = strtolower($algo);
		$pack = 'H'.strlen($algo('test'));
		$size = 64;
		$opad = str_repeat(chr(0x5C), $size);
		$ipad = str_repeat(chr(0x36), $size);
	
		if (strlen($key) > $size) {
			$key = str_pad(pack($pack, $algo($key)), $size, chr(0x00));
		} else {
			$key = str_pad($key, $size, chr(0x00));
		}
	
		for ($i = 0; $i < strlen($key) - 1; $i++) {
			$opad[$i] = $opad[$i] ^ $key[$i];
			$ipad[$i] = $ipad[$i] ^ $key[$i];
		}
	
		$output = $algo($opad.pack($pack, $algo($ipad.$data)));
	
		return ($raw_output) ? pack($pack, $output) : $output;
	}   
	    
}