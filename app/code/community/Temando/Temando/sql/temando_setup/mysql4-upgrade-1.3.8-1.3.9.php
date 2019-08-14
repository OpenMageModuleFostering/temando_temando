<?php

set_time_limit(0);

/* @var $this Mage_Eav_Model_Entity_Setup */
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer = $this;
$installer->startSetup();

/**
 * Remove free shipping option in Temando shipping method
 */
$installer->deleteConfigData('carriers/temando/free_shipping_enable');
$installer->deleteConfigData('carriers/temando/free_shipping_subtotal');

/**
 * Remove limited access and pobox from warehouse options
 */
$installer->deleteConfigData('temando/origin/limited_access');
$installer->deleteConfigData('temando/origin/pobox');

/**
 * Dangerous Goods Support
 */
$installer
    ->getConnection()
    ->addColumn(
        $this->getTable('temando_box'),
        'dangerous',
        'tinyint(1) NOT NULL DEFAULT 0 AFTER `fragile`'
);

$installer->addAttribute('catalog_product', 'temando_dangerous',
        array(
            'type' => 'int',
            'label' => 'Dangerous',
            'group' => 'Temando',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'source' => 'eav/entity_attribute_source_boolean',
            'input' => 'select',
            'default' => false,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false
        )
);

$installer
    ->getConnection()
    ->addColumn(
        $this->getTable('temando_shipment'),
        'customer_selected_delivery_options',
        'varchar(500) NULL after `customer_selected_options`'
);

$installer
    ->getConnection()
    ->addColumn(
        $this->getTable('temando_shipment'),
        'destination_type',
        "varchar(30) NOT NULL DEFAULT 'residence' AFTER `destination_email`"
);

$installer->endSetup();
