<?php
/**
 * System Config Form Button Rule
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Block_Adminhtml_System_Config_Form_Button_Rule
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $html = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setType('button')->setClass('scalable go disabled')
            ->setLabel('Add New Rule')
            ->setOnClick('return false;')
            ->setTitle('Available in the Business Plan')
            ->toHtml();
        return $html;
    }
}
