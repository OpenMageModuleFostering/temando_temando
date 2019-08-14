<?php
/**
 * Api Request Anywhere
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_Api_Request_Anywhere extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/api_request_anywhere');
        $this->setDeliveryOptions(array());
    }

    public function toRequestArray()
    {
        if (!$this->validate()) {
            return false;
        }

        switch ($this->getDestinationType()) {

            case Temando_Temando_Model_Checkout_Delivery_Options::DESTINATION_BUSINESS:

        $data = array(
            'itemNature' => 'Domestic',
            'itemMethod' => 'Door to Door',
            'destinationCountry' => $this->getDestinationCountry(),
            'destinationCode' => $this->getDestinationPostcode(),
            'destinationSuburb' => $this->getDestinationCity(),
            'destinationIs' => ucfirst(Temando_Temando_Model_Checkout_Delivery_Options::DESTINATION_BUSINESS),
            'destinationBusNotifyBefore' => 'N',
            'destinationBusLimitedAccess' => 'N',
            'destinationBusUnattended' => array_key_exists(
                'unattended_delivery',
                $this->getDeliveryOptions()
            ) ? 'Y' : 'N',
            'originBusNotifyBefore' => 'Y',
	    'originDescription' => Temando_Temando_Helper_Data::DEFAULT_WAREHOUSE_NAME
        );

        if (Mage::helper('temando')->isStreetWithPO($this->getDestinationStreet())) {
                    $data['destinationBusPostalBox'] = 'Y';
                }
                break;
            default:
                $data = array(
                    'itemNature' => 'Domestic',
                    'itemMethod' => 'Door to Door',
                    'destinationCountry' => $this->getDestinationCountry(),
                    'destinationCode' => $this->getDestinationPostcode(),
                    'destinationSuburb' => $this->getDestinationCity(),
                    'destinationIs' => ucfirst(Temando_Temando_Model_Checkout_Delivery_Options::DESTINATION_RESIDENCE),
                    'destinationResNotifyBefore' => 'N',
                    'destinationResLimitedAccess' => 'N',
                    'destinationResUnattended' => array_key_exists(
                        'unattended_delivery',
                        $this->getDeliveryOptions()
                    ) ? 'Y' : 'N',
                    'originBusNotifyBefore' => 'Y',
                    'originDescription' => Temando_Temando_Helper_Data::DEFAULT_WAREHOUSE_NAME
                );

                if (Mage::helper('temando')->isStreetWithPO($this->getDestinationStreet())) {
                    $data['destinationResPostalBox'] = 'Y';
                }
                break;
        }

        return $data;
    }

    public function validate()
    {
        return
            $this->getDestinationCountry() &&
            $this->getDestinationPostcode() &&
            $this->getDestinationCity();
    }

}
