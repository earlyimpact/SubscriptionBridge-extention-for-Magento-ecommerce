<?php 
class  Earlyimpact_Subscriptionbridge_Model_Observer extends Mage_Core_Model_Abstract
{
	public function SBActivation(Varien_Event_Observer $observer) {
		$api_activation = Mage::getStoreConfig('subscriptionbridge_options/api/api_activation');
		$bridgeModel = Mage::getModel('subscriptionbridge/api');
		$bridgeModel->getGetActivationRequest();

	}
	

	public function SalesQuoteItemSetProduct(Varien_Event_Observer $observer) {
	
		/*
		 * TODO: $evproduct doesn't have SB values
		 */
		$quote = $observer->getEvent()->getQuoteItem();
		$evproduct = $observer->getEvent()->getProduct();
		$product = Mage::getModel('catalog/product')->load($evproduct->getId());

		$quote->setSbLinkid( $product->getSbLinkid());
		$quote->setSbTrial( $product->getSbTrial());
		$quote->setSbTrialPrice( $product->getSbTrialPrice() );
		$quote->setSbShippingTrial( $product->getSbShippingTrial() );
		$quote->setSbShippingTrialPrice( $product->getSbShippingTrialPrice() );
	}

	
	

	
}