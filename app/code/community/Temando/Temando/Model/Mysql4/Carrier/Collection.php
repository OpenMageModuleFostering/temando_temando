<?php
/**
 * Mysql4 Carrier Collection
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_Mysql4_Carrier_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/carrier');
    }
    
}
