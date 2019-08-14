<?php
/**
 * Manifest Model
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_Manifest extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/manifest');
    }
}
