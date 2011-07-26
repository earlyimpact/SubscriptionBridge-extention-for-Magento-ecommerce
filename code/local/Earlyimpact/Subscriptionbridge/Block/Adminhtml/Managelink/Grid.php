<?php

/*
 * TODO
 * mass actions?
 *  don't show product already linked
 * 
*/

class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Managelink_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('managelinkGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('catalog/product')
      
      ->getCollection()
      ->addAttributeToSelect('name')
      ->addAttributeToSelect('status')
     // ->joinAttribute('sb_linkid', 'catalog_product/sb_linkid', 'sb_linkid', null, 'left')
	 
	 ->addAttributeToSelect('sb_linkid')
	 //->addAttributeToFilter('sb_linkid', array('neq'=>array(null)));
	  //->joinAttribute('sb_linkid', 'entity_id', 'entity_id', null, 'left')
	  ->load()
	  ;

	//  Mage::log( $collection->exportToArray() );
	   
      // don't show product already linked    
      foreach($collection as $col) {
      	if($col->getData('sb_linkid'))$collection->removeItemByKey($col->getId());
      }
      
      

      
      $this->setCollection($collection);
      //return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
      return parent::_prepareCollection();
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
          'header'    => Mage::helper('catalog')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));
      
      $this->addColumn('sku', array(
          'header'    => Mage::helper('catalog')->__('SKU'),
          'align'     =>'left',
          'index'     => 'sku',
      ));
      
	  

      $this->addColumn('status',
                array(
                    'header'=> Mage::helper('catalog')->__('Status'),
                    'width' => '70px',
                    'index' => 'status',
                    'type'  => 'options',
                    'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('subscriptionbridge')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('subscriptionbridge')->__('Create'),
                        'url'       => array('base'=> '*/*/new'),
                        'field'     => 'product_id'
                    )
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
      return $this->getUrl('*/*/new', array('product_id' => $row->getId()));
  }

}