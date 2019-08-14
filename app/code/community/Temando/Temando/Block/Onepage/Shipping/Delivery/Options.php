<?php
/**
 * Onepage Shipping Delivery Options
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Block_Onepage_Shipping_Delivery_Options
    extends Mage_Checkout_Block_Onepage_Abstract
{

    /**
     * Delivery options enabled in system configuration for display in checkout
     *
     * @var array
     */
    protected $_enabledOptions = null;

    /**
     * Retrieve Temando system configuration value
     *
     * @param string $field The identifier of config entry
     * @return mixed
     */
    public function getConfigData($field)
    {
        return Mage::helper('temando')->getConfigData($field);
    }

    /**
     * Show location type selection to customer?
     *
     * @return boolean
     */
    public function showLocationType()
    {
        return $this->getConfigData('checkout/location_type');
    }

    /**
     * Show additional delivery options to customer?
     *
     * @return boolean
     */
    public function showOptions()
    {
        return $this->getConfigData('checkout/delivery_options');
    }

    /**
     * Return saved destination type as selected by customer
     *
     * @return string
     */
    public function getDestinationType()
    {
        $destinationType = Mage::getSingleton('checkout/session')->getData('destination_type');
        return $destinationType ?
            $destinationType :
            Temando_Temando_Model_Checkout_Delivery_Options::DESTINATION_RESIDENCE;
    }

    /**
     * Returns enabled checkout options
     *
     * @return array
     */
    public function getEnabledOptions()
    {
        if (!$this->_enabledOptions) {
            $this->_enabledOptions = Mage::getModel('temando/checkout_delivery_options')->getEnabledOptions();
        }
        return $this->_enabledOptions;
    }

    /**
     * Returns customer selected delivery options
     *
     * @return array Customer selected options
     */
    public function getSelectedOptions()
    {
        $selected = Mage::getSingleton('checkout/session')->getData('selected_delivery_options');
        if (!is_array($selected)) {
            $selected = array();
        }
        return $selected;
    }
}
