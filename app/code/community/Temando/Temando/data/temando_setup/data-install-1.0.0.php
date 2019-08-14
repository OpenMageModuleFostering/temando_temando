<?php

set_time_limit(0);

/* @var $this Mage_Eav_Model_Entity_Setup */
/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer = $this;
$installer->startSetup();

/*
 * Set the current specific country based on the default country
 */
$countryCode = Mage::getStoreConfig('general/country/default');

$coreConfig = new Mage_Core_Model_Config();
$coreConfig->saveConfig('carriers/temando/specificcountry', $countryCode, 'default', 0);

/*
 * Set default origin data
 */
$warehouseData = Mage::helper('temando')->getDefaultOriginData($countryCode);

if (!empty($warehouseData)) {
    $coreConfig->saveConfig('temando/origin/street', $warehouseData['street'], 'default', 0);
    $coreConfig->saveConfig('temando/origin/city', $warehouseData['city'], 'default', 0);
    $coreConfig->saveConfig('temando/origin/postcode', $warehouseData['postcode'], 'default', 0);
    $coreConfig->saveConfig('temando/origin/country', $warehouseData['country'], 'default', 0);
    $coreConfig->saveConfig('temando/origin/region', $warehouseData['region'], 'default', 0);
    $coreConfig->saveConfig('temando/origin/phone1', $warehouseData['phone1'], 'default', 0);
}

$installer->endSetup();
