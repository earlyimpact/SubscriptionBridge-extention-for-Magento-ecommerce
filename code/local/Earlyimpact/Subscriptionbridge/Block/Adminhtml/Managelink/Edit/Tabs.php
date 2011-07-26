<?php

class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Managelink_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('managelink_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('subscriptionbridge')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('subscriptionbridge')->__('Item Information'),
          'title'     => Mage::helper('subscriptionbridge')->__('Item Information'), 
          'content'   => $this->getLayout()->createBlock('subscriptionbridge/adminhtml_managelink_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}