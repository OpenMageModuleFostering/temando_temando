<?php
/**
 * System Config Source Shipment Status
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_System_Config_Source_Shipment_Status
    extends Temando_Temando_Model_System_Config_Source
{
    
    const PENDING =     '0';
    const BOOKED =      '1';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::PENDING     => Mage::helper('temando')->__('Pending'),
            self::BOOKED      => Mage::helper('temando')->__('Booked'),
        );
    }
    
}
