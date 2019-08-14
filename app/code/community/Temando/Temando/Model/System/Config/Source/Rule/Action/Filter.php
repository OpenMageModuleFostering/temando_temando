<?php
/**
 * System Config Source Rule Action Filter
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_System_Config_Source_Rule_Action_Filter 
    extends Temando_Temando_Model_System_Config_Source {
    
    const DYNAMIC_ALL                  = 1;
    const DYNAMIC_FASTEST              = 2;
    const DYNAMIC_CHEAPEST             = 3;
    const DYNAMIC_FASTEST_AND_CHEAPEST = 4;
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::DYNAMIC_ALL                  => Mage::helper('temando')->__('All Quotes'),
            self::DYNAMIC_CHEAPEST             => Mage::helper('temando')->__('Cheapest only'),
            self::DYNAMIC_FASTEST              => Mage::helper('temando')->__('Fastest only'),
            self::DYNAMIC_FASTEST_AND_CHEAPEST => Mage::helper('temando')->__('Cheapest and Fastest only'),
        );
    }
    
}