<?php
/** @var Mage_Catalog_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

$productalertStockAttr = $installer->addAttribute('catalog_product', 'productalert_stock', [
    'input' => 'select',
    'input_renderer' => 'simpleproductalert/adminhtml_select_renderer',
    'label' => 'Allow stock notifications for product?',
    'source' => 'catalog/product_attribute_source_boolean',
    'required' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'default' => 1,
    'apply_to' => 'configurable,simple'
]);

$installer->endSetup();