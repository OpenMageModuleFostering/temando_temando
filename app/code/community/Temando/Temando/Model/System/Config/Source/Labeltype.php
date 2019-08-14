<?php

class Temando_Temando_Model_System_Config_Source_Labeltype extends Temando_Temando_Model_System_Config_Source
{

    const STANDARD = 'Standard';
    const THERMAL  = 'Thermal';

    protected function _setupOptions()
    {
        $this->_options = array(
            self::STANDARD => Mage::helper('temando')->__('Plain Paper'),
            self::THERMAL  => Mage::helper('temando')->__('Thermal'),
        );
    }

}
