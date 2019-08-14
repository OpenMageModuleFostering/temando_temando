<?php
/**
 * Checkout Delivery Options
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_Checkout_Delivery_Options extends Mage_Core_Model_Abstract
{

    const DESTINATION_RESIDENCE = 'residence';
    const DESTINATION_BUSINESS  = 'business';

    /**
     * All available delivery options
     * key must be the same as defined in system.xml
     */
    protected $_allOptions = array(
        self::DESTINATION_RESIDENCE => array(
            'unattended_delivery'   => 'Unattended delivery',
        ),
        self::DESTINATION_BUSINESS => array(
        )
    );

    /**
     * Delivery options enabled in system configuration
     *
     * @var array
     */
    protected $_enabledOptions = null;


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Returns enabled checkout options
     *
     * @return array
     */
    public function getEnabledOptions()
    {
        if (!$this->_enabledOptions) {
            foreach ($this->_allOptions as $destType => $options) {
                foreach ($options as $key => $description) {
                    if ($this->getConfigData("checkout/{$key}")) {
                        $this->_enabledOptions[$destType][$key] = $description;
                    }
                }
            }
        }
        return $this->_enabledOptions;
    }

    /**
     * Return all available delivery options
     *
     * @return array
     */
    public function getAllOptions()
    {
        return $this->_allOptions;
    }

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
}
