<?php
/**
 * Admin Shipment Block
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Block_Adminhtml_Shipment
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    
    public function __construct()
    {
        $this->_blockGroup = 'temando';
        $this->_controller = 'adminhtml_shipment';
        $this->_headerText = Mage::helper('temando')->__('Manage Shipments');
        parent::__construct();
        $this->removeButton('add');
    }
    
}
