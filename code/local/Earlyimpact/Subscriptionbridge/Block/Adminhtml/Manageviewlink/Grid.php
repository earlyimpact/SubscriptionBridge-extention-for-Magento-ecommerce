<?php

/*
 * TODO
 * mass actions?
 * 
*/

class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Manageviewlink_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('manageviewlinkGrid');
      $this->setDefaultSort('subscriptionbridge_link_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('catalog/product')->getCollection();
      $collection->addAttributeToSelect('id');
      $collection->addAttributeToSelect('name');
      $collection->addAttributeToSelect('sb_linkid');
      $collection->addAttributeToSelect('sb_trial');
      $collection->addAttributeToSelect('sb_trial_price');
      $collection->addAttributeToSelect('sb_shipping_trial');
      $collection->addAttributeToSelect('sb_shipping_trial_price');
      $collection->addAttributeToSelect('sb_tc_required');
       //$collection->addAttributeToSelect('sb_linkid');
      $collection->addAttributeToFilter('sb_linkid', array('neq'=>array(null)));
      
      //$collection->joinTable('subscriptionbridge_link','product_id=entity_id',array('product_id' => 'product_id','id'=>'id'),null,'right');
      //$collection->joinAttribute('product_id', 'entity_id', 'entity_id', null, 'left');
      //$collection->joinTable('subscriptionbridge_link','product_id=entity_id',array('*'),null,'right');
      //$collection->joinTable('subscriptionbridge_link','product_id=entity_id',array('*'),null,'right');
	 // $collection->addAttributeToSelect('*');
     
      
      $this->setCollection($collection);
      return parent::_prepareCollection();;
  }

  protected function _prepareColumns()
  {
  	

        	
      $this->addColumn('id', array(
          'header'    => Mage::helper('catalog')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'entity_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('catalog')->__('Product Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));
      
      $this->addColumn('sb_linkid', array(
          'header'    => Mage::helper('catalog')->__('Linked Package'),
          'align'     =>'left',
          'index'     => 'sb_linkid',
      
      ));

      
      $this->addColumn('sb_trial', array(
          'header'    => Mage::helper('catalog')->__('Trial Price'),
          'align'     =>'left',
          'index'     => 'sb_trial',
      	  'type'      => 'options',
          'options'   => array('1' => Mage::helper('adminhtml')->__('Enabled'), '0' => Mage::helper('adminhtml')->__('Disabled')),
      ));
      	 
       $this->addColumn('sb_shipping_trial', array(
          'header'    => Mage::helper('catalog')->__('Trial Shipping'),
          'align'     =>'left',
          'index'     => 'sb_shipping_trial',
      	  'type'      => 'options',
          'options'   => array('1' => Mage::helper('adminhtml')->__('Enabled'), '0' => Mage::helper('adminhtml')->__('Disabled')),
      ));    

      $this->addColumn('sb_tc_required', array(
          'header'    => Mage::helper('catalog')->__('T&C Required'),
          'align'     =>'left',
          'index'     => 'sb_tc_required',
      	  'type'      => 'options',
          'options'   => array('1' => Mage::helper('adminhtml')->__('Enabled'), '0' => Mage::helper('adminhtml')->__('Disabled')),
      ));

	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('subscriptionbridge')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('subscriptionbridge')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                   
                 
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		

	  
      return parent::_prepareColumns();
  }


  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}