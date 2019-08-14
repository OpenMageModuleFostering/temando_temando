<?php
/**
 * Custom grid renderer for shipment anticipated currency
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Block_Adminhtml_Shipment_Grid_Renderer_Currency
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
        if ($row->getAnticipatedCurrency()) {
            return $row->getAnticipatedCurrency();
        }
        return Mage::helper('temando')->getDefaultCurrencyCode();
    }
}
