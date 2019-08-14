<?php
/**
 * System Config Form Field Version
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Block_Adminhtml_System_Config_Form_Field_Version
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Retrieve the Temando version from the node
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return (string) Mage::getConfig()->getNode()->modules->Temando_Temando->version;
    }
}
