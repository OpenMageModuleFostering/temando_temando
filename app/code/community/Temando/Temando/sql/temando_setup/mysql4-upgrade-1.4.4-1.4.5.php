<?php

set_time_limit(0);

/* @var $this Mage_Eav_Model_Entity_Setup */
/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Support for anticipated cost currency
 */

$installer
    ->getConnection()
    ->addColumn(
        $this->getTable('temando_shipment'),
        'anticipated_currency',
        'varchar(3) DEFAULT NULL AFTER `anticipated_cost`'
);

$installer->endSetup();
