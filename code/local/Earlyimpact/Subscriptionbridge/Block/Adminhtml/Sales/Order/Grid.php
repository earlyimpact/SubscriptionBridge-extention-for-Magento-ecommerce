<?php 

class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
	  protected function _prepareColumns()
    {
    	
    	parent::_prepareColumns();
    	
    	$this->addColumn('cc_trans_id', array(
            'header'=> Mage::helper('subscriptionbridge')->__('Subscription'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'cc_trans_id',
    		'filter'    => false,
            'sortable'  => false,
        ));        
    }


    
    protected function _prepareCollection()
    {
    	//parent::_prepareCollection();
    	$res = Mage::getResourceModel("sales/order_payment"); 
        $paymentWhere = array("entity_type_id" => $res->getTypeId());
        $attributes =$res->loadAllAttributes()->getAttributesByCode();
        
        
        foreach ($attributes as $attrCode=>$attr) {
            if ($attr->getAttributeCode()=="cc_trans_id"){
                $attId = $attr->getAttributeId();
            }
            
        }
        $paymentMethodWhere = "{{table}}.attribute_id = '$attId'";
        
        $collection = Mage::getResourceModel('sales/order_collection')
            ->addAttributeToSelect('*')
            ->joinAttribute('billing_firstname', 'order_address/firstname', 'billing_address_id', null, 'left')
            ->joinAttribute('billing_lastname', 'order_address/lastname', 'billing_address_id', null, 'left')
            ->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
            ->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
                        
            ->joinTable('sales_order_entity', 'parent_id=entity_id', array( 'quote_payment_id_for_join' => 'entity_id' ) , $paymentWhere, 'left' )
            ->joinTable('sales_order_entity_varchar', 'entity_id=quote_payment_id_for_join', array( 'cc_trans_id' => 'value' ) , $paymentMethodWhere, 'left' )
			->joinTable('sales/order_item','order_id=entity_id',array('name'),null,'left') 
			
            ->addExpressionAttributeToSelect('billing_name',
                'CONCAT({{billing_firstname}}, " ", {{billing_lastname}})',
                array('billing_firstname', 'billing_lastname'))
            ->addExpressionAttributeToSelect('shipping_name',
                'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
                array('shipping_firstname', 'shipping_lastname'))
           ->groupByAttribute('entity_id');    
                ;   

                //Mage::log($collection->exportToArray());
    
	    foreach($collection as $col) {
	    	if($col->getCcTransId()):	
	    		$col->setCcTransId($this->helper('subscriptionbridge')->getSbMerchantCenterLink($col->getCcTransId(),$col->getName())  );
	    	endif;
		
	    }
	        
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
    
       
    
}