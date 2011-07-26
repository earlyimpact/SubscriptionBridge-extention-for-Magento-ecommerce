<?php

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->startSetup();


$setup->addAttribute('catalog_product', 'sb_linkid', array(
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'SB Link Id',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => '',
        'is_configurable'   => false
    ));
    
 $setup->addAttribute('catalog_product', 'sb_trial', array(
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'SB Is Trial',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => '',
        'is_configurable'   => false
    ));   
    
  $setup->addAttribute('catalog_product', 'sb_trial_price', array(
        'type'              => 'decimal',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'SB Trial Price',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => '',
        'is_configurable'   => false
    ));
    
  $setup->addAttribute('catalog_product', 'sb_shipping_trial_price', array(
        'type'              => 'decimal',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'SB Shipping Trial Price',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => '',
        'is_configurable'   => false
    ));
    
  $setup->addAttribute('catalog_product', 'sb_tc_required', array(
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'SB Terms Required',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => '',
        'is_configurable'   => false
    ));
    
   $setup->addAttribute('catalog_product', 'sb_tc_text', array(
        'type'              => 'text',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'SB Terms Text',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => '',
        'is_configurable'   => false
    ));   
    
 $setup->addAttribute('catalog_product', 'sb_shipping_trial', array(
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'SB Shipping Is Trial',
        'input'             => '',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => '',
        'is_configurable'   => false
    ));

  $setup->run("
    ALTER TABLE {$this->getTable('sales_flat_quote_item')}
        ADD COLUMN `sb_linkid` VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL,
        ADD COLUMN `sb_trial`  TINYINT(1) DEFAULT '0',
        ADD COLUMN `sb_trial_price` DECIMAL(12,4)  NOT NULL  DEFAULT '0.0000',
        ADD COLUMN `sb_shipping_trial` TINYINT(1) DEFAULT '0',
        ADD COLUMN `sb_shipping_trial_price` DECIMAL(12,4)  NOT NULL  DEFAULT '0.0000';
    ");
  
  $setup->run("
    ALTER TABLE {$this->getTable('sales_flat_order_item')}
        ADD COLUMN `sb_linkid` VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL,
        ADD COLUMN `sb_trial`  TINYINT(1) DEFAULT '0',
        ADD COLUMN `sb_trial_price` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
        ADD COLUMN `sb_shipping_trial` TINYINT(1) DEFAULT '0'
        ADD COLUMN `sb_shipping_trial_price` DECIMAL(12,4)  NOT NULL  DEFAULT '0.0000';
    ");



  
  
$setup->endSetup();


?>

