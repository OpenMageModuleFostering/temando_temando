<?php
/**
 * System Config Source Carbon
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_System_Config_Source_Carbon
    extends Temando_Temando_Model_System_Config_Source
{
    
    const DISABLED  = 'disabled';
    const OPTIONAL  = 'optional';
    const MANDATORY = 'mandatory';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::DISABLED  => Mage::helper('temando')->__('Disabled'),
            self::OPTIONAL  => Mage::helper('temando')->__('Optional'),
            self::MANDATORY => Mage::helper('temando')->__('Mandatory'),
        );
    }
    
}
