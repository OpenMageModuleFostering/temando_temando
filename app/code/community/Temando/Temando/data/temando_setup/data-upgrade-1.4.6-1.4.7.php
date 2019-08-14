<?php

set_time_limit(0);

$installer = $this;
$installer->startSetup();

/**
 * Remove unused files from the Adminhtml controller directory
 *
 * This is because of Magento security patch SUPEE-6788
 * Supresses errors when trying to delete.
 */
$temandoFiles = array (
    'WizardController',
    'ShipmentController',
    'ManifestController',
    'JsonController',
    'CarrierController'
);

foreach ($temandoFiles as $temandoFile) {
    $directory = Mage::getModuleDir('controllers', 'Temando_Temando').DS.'Adminhtml';
    $filename = $directory.DS.$temandoFile.'.php';
    if (!file_exists($filename)) {
        continue;
    }
    @unlink($filename);
}

$installer->endSetup();
