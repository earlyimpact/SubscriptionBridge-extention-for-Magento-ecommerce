<?php
class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Managelink extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_managelink';
    $this->_blockGroup = 'subscriptionbridge';
    $this->_headerText = Mage::helper('subscriptionbridge')->__('Create Package Link');
   // $this->_addButtonLabel = Mage::helper('subscriptionbridge')->__('Add Item');  	
    parent::__construct();
    $this->removeButton('add');
   
  }
}