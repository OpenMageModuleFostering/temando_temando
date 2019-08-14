<?php

class Temando_Temando_Model_System_Config_Source_Errorprocess extends Temando_Temando_Model_System_Config_Source
{

    const VIEW  = 'view';
    const FLAT  = 'flat';

    protected function _setupOptions()
    {
        $this->_options = array(
            self::FLAT  => 'Show flat rate',
            self::VIEW  => 'Show error message',
        );
    }

}
