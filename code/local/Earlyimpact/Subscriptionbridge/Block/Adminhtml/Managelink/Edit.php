<?php

class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Managelink_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
	    $this->_controller = 'adminhtml_managelink';
	    $this->_blockGroup = 'subscriptionbridge';
        
	  
    }

    public function getHeaderText()
    {
    	if( Mage::registry('product_data') ) {
    		return Mage::helper('subscriptionbridge')->__("You Selected: '%s'", $this->htmlEscape(Mage::registry('product_data')->getName()));
    	}
    	
    	
    }
}