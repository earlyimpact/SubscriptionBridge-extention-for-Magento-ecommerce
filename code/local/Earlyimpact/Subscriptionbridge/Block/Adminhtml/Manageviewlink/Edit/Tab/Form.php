<?php

class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Manageviewlink_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('managelink_form', array('legend'=>Mage::helper('subscriptionbridge')->__('Item information')));
     

      
      
      $fieldset->addField('sb_linkid', 'select', array(
          'label'     => Mage::helper('subscriptionbridge')->__('Packages'),
          'name'      => 'sb_linkid',
      	  'class'     => 'required-entry',
         // 'values'    =>  Mage::getModel('subscriptionbridge/api')->getGetPackagesRequestSelectUnique(Mage::registry('product_data')->getId()) 
          'values'    =>  Mage::registry('sb_api_data')
    
      ));
       $fieldset->addField('sb_trial', 'select', array(
          'label'     => Mage::helper('subscriptionbridge')->__('Package include a trial'),
          'name'      => 'sb_trial',
       'class'     => 'required-entry',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('subscriptionbridge')->__('No'),
              ),

              array(
                  'value'     => 1,
                  'label'     => Mage::helper('subscriptionbridge')->__('Yes'),
              ),
              

                           
          ),
      ));


      $fieldset->addField('sb_trial_price', 'text', array(
          'label'     => Mage::helper('subscriptionbridge')->__('The Trial Price is'),
          'class'     => 'validate-zero-or-greater',
          'name'      => 'sb_trial_price',
      ));     

      
       $fieldset->addField('sb_shipping_trial', 'select', array(
          'label'     => Mage::helper('subscriptionbridge')->__('Package include a shipping trial'),
          'name'      => 'sb_shipping_trial',
       'class'     => 'required-entry',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('subscriptionbridge')->__('No'),
              ),

              array(
                  'value'     => 1,
                  'label'     => Mage::helper('subscriptionbridge')->__('Yes'),
              ),
              

                           
          ),
      ));

      
      $fieldset->addField('sb_shipping_trial_price', 'text', array(
          'label'     => Mage::helper('subscriptionbridge')->__('The Shipping Trial Price is'),
          'class'     => 'validate-zero-or-greater',
          'name'      => 'sb_shipping_trial_price',
      ));         
      
      
     $fieldset->addField('sb_tc_required', 'select', array(
          'label'     => Mage::helper('subscriptionbridge')->__('Require that customers agree to the following Agreement'),
          'name'      => 'sb_tc_required',
       'class'     => 'required-entry',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('subscriptionbridge')->__('No'),
              ),

              array(
                  'value'     => 1,
                  'label'     => Mage::helper('subscriptionbridge')->__('Yes'),
              ),
              

                           
          ),
      ));         
      
      
      $fieldset->addField('sb_tc_text', 'editor', array(
          'label'     => Mage::helper('subscriptionbridge')->__('Agreement to be displayed'),
          'title'     => Mage::helper('subscriptionbridge')->__('Agreement to be displayed'),
          'name'      => 'sb_tc_text',
      	  'style'     => 'width:700px; height:100px;'
      ));

      

      
      if ( Mage::registry('product_data') ) {
		  $form->setValues(Mage::registry('product_data')->getData());
	  }
      
      return parent::_prepareForm();
  }
}