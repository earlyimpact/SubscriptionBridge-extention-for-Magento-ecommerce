<?php

class Earlyimpact_Subscriptionbridge_Block_Adminhtml_Manageviewlink_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
	    $this->_controller = 'adminhtml_manageviewlink';
	    $this->_blockGroup = 'subscriptionbridge';
        
        $this->_updateButton('save', 'label', Mage::helper('subscriptionbridge')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('subscriptionbridge')->__('Delete'));
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
		
		$this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
			    
      
    }

    public function getHeaderText()
    {
    	if( Mage::registry('product_data') ) {
    		return Mage::helper('subscriptionbridge')->__("Edit: '%s'", $this->htmlEscape(Mage::registry('product_data')->getName()));
    	}
    	
    	
    }
}