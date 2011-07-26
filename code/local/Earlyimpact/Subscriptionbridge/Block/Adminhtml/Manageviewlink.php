<?php
class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Manageviewlink extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_manageviewlink';
    $this->_blockGroup = 'subscriptionbridge';
    $this->_headerText = Mage::helper('subscriptionbridge')->__('View/ Modify Subscription Packages');
   // $this->_addButtonLabel = Mage::helper('subscriptionbridge')->__('Add Item');  	
    parent::__construct();
    $this->removeButton('add');
   
  }
}