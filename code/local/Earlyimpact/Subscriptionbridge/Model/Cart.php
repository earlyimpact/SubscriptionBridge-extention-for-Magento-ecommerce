<?php


class Earlyimpact_Subscriptionbridge_Model_Cart extends Mage_Core_Model_Abstract
{
	public function cartHasPackage($ProductIds) {
		//Mage::log($ProductIds);
		
		foreach($ProductIds as $id) {
			$product  = Mage::getModel('catalog/product')->load($id);
			if($product->getSbLinkid()) return $product;
		}
		
		return false;
	}
	
	
}