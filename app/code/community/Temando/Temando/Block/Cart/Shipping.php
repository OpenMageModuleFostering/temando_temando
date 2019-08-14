<?php
/**
 * Cart Shipping Block
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Block_Cart_Shipping
    extends Mage_Checkout_Block_Cart_Shipping
{

    /**
     * Check if the city is active
     *
     * @return boolean
     */
    public function getCityActive()
    {
        return (bool)Mage::getStoreConfig('carriers/temando/active') || parent::getCityActive();
    }

    /**
     * Check if one of carriers require state/province
     *
     * @return bool
     */
    public function isStateProvinceRequired()
    {
        return (bool)Mage::getStoreConfig('carriers/temando/active') || parent::isStateProvinceRequired();
    }

    /**
     * Check if one of carriers require city
     *
     * @return bool
     */
    public function isCityRequired()
    {
        return (bool)Mage::getStoreConfig('carriers/temando/active') || parent::isCityRequired();
    }

    /**
     * Check if one of carriers require zip code
     *
     * @return bool
     */
    public function isZipCodeRequired()
    {
        return (bool)Mage::getStoreConfig('carriers/temando/active') || parent::isZipCodeRequired();
    }

    /**
     * Get the estimate postcode
     *
     * @return string
     */
    public function getEstimatePostcode()
    {
        $return = parent::getEstimatePostcode();
        if (!$return && Mage::helper('temando')->getSessionPostcode()) {
            $return = Mage::helper('temando')->getSessionPostcode();
        }

        return $return;
    }

    /**
     * Get the estimate city
     *
     * @return string
     */
    public function getEstimateCity()
    {
        $return = parent::getEstimateCity();
        if (!$return && Mage::helper('temando')->getSessionCity()) {
            $return = Mage::helper('temando')->getSessionCity();
        }

        return $return;
    }

    /**
     * Get the estimate region id
     *
     * @return string
     */
    public function getEstimateRegionId()
    {
        $return = parent::getEstimateRegionId();
        if (!$return && Mage::helper('temando')->getSessionRegionId()) {
            $return = Mage::helper('temando')->getSessionRegionId();
        }

        return $return;
    }

    /**
     * Get the estimate region
     *
     * @return string
     */
    public function getEstimateRegion()
    {
        $return = parent::getEstimateRegion();
        if (!$return && Mage::helper('temando')->getSessionRegion()) {
            $return = Mage::helper('temando')->getSessionRegion();
        }

        return $return;
    }

    /**
     * Show location type selection to customer?
     *
     * @return boolean
     */
    public function showLocationType()
    {
        $options = Mage::helper('temando')->getConfigData('checkout/delivery_options');
        if (!$options) {
            return false;
        }
        return Mage::helper('temando')->getConfigData('checkout/location_type');
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
}