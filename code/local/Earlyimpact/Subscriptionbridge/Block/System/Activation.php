<?php


class Earlyimpact_Subscriptionbridge_Block_System_Activation
	extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{


    public function render(Varien_Data_Form_Element_Abstract $element)
    {
    	$html = "";
		if(Mage::getStoreConfig('subscriptionbridge_options/api/api_activation'))
    		$html .= "<b>This store is communicating with SubscriptionBridge</b><br/>";
    	else 
    		$html .= "<b>This store is not communicating with SubscriptionBridge yet</b><br/>";

        return $html;
    }


  
}
