<?php
/**
 * Custom grid renderer for shipping paid currency
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Block_Adminhtml_Shipment_Grid_Renderer_Shipcurrency
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency
{

    /**
     * Return the currency code
     *
     * @param Varien_Object $row
     * @return string
     */
    protected function _getCurrencyCode($row)
    {
        $shipment = Mage::getModel('temando/shipment')->load($row->getId());
        return $shipment->getOrder()->getStore()->getCurrentCurrencyCode();
    }
}
