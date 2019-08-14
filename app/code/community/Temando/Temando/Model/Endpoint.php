<?php
/**
 * Endpoint Model
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 *
 * @method Temando_Temando_Model_Endpoint setOriginCountry()
 *
 * @method string getOriginCountry()
 */

class Temando_Temando_Model_Endpoint extends Mage_Core_Model_Abstract
{
    /**
     * Temando Endpoint URL
     */
    const ENDPOINT_URL = 'https://discovery.temando.com/service/api';

    /**
     * Sandbox Mode
     */
    const ENDPOINT_MODE_SANDBOX = 'sandbox';

    /**
     * Staging Mode
     */
    const ENDPOINT_MODE_STAGING = 'staging';

    /**
     * Production Mode
     */
    const ENDPOINT_MODE_PRODUCTION = 'production';

    /**
     * Sandbox Wsdl
     * @var string
     */
    protected $_apiSandbox;

    /**
     * Production Wsdl
     * @var string
     */
    protected $_apiProduction;

    /**
     * The HTTP Client
     * @var Varien_Http_Client
     */
    protected $_client = null;

    /**
     * Retrieve cache object
     * @var Zend_Cache_Frontend_File
     */
    protected $_cache;


    public function _construct()
    {
        parent::_construct();
        $this->_prepareClient();
        $this->_prepareCache();
    }

    /**
     * Get the required sandbox endpoint
     *
     * @return string
     */
    public function getSandboxEndpoint()
    {
        if (!$this->_apiSandbox) {
            $this->_apiSandbox = $this->getEndpoint(self::ENDPOINT_MODE_SANDBOX);
        }
        return $this->_apiSandbox;
    }

    /**
     * Get the required production endpoint
     *
     * @return string
     */
    public function getProductionEndpoint()
    {
        if (!$this->_apiProduction) {
            $this->_apiProduction = $this->getEndpoint(self::ENDPOINT_MODE_PRODUCTION);
        }
        return $this->_apiProduction;
    }

    /**
     * Return the endpoint based on the mode
     *
     * @param string $mode
     * @return string|boolean
     */
    public function getEndpoint($mode)
    {
        if (!$this->getOriginCountry()) {
            return false;
        }
        if (!$endpoints = $this->loadEndpoints()) {
            return false;
        }
        if (isset($endpoints[$this->getOriginCountry()][$mode])) {
            return $endpoints[$this->getOriginCountry()][$mode];
        }
        return false;
    }

    /**
     * Load the endpoints from the cache or api
     *
     * @return array|boolean
     */
    public function loadEndpoints()
    {
        if ($this->_cache->load('temando_endpoints')) {
            return unserialize($this->_cache->load('temando_endpoints'));
        }
        $endpoints = $this->getEndpoints();
        if (!empty($endpoints)) {
            $this->saveEndpoints($endpoints);
            return $endpoints;
        }
        return false;
    }

    /**
     * Get all of the endpoints from the api
     *
     * @return array
     */
    public function getEndpoints()
    {
        try {
            $this->_client->setUri(self::ENDPOINT_URL);
            $rawBody = $this->_client->request(Varien_Http_Client::GET)->getRawBody();
            return Mage::helper('core')->jsonDecode($rawBody, true);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'temando-endpoints.log', true);
            return array();
        }
    }

    /**
     * Save the endpoint array in the cache
     *
     * @param array $endpoints
     */
    protected function saveEndpoints($endpoints)
    {
        if (!is_array($endpoints)) {
            return;
        }
        try {
            $this->_cache->save(serialize($endpoints), 'temando_endpoints', array('temando_cache'));
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'temando-endpoints.log', true);
        }
    }

    /**
     * Initializes http client to communicate with Endpoint service
     *
     * @return \Temando_Temando_Model_Endpoint
     */
    protected function _prepareClient()
    {
        if (!$this->_client) {
            $this->_client = new Varien_Http_Client();
            $this->_client->setConfig(array('maxredirects' => 0, 'timeout' => 15));
        }
        return $this;
    }

    /**
     * Initializes the cache
     *
     * @return \Temando_Temando_Model_Endpoint
     */
    protected function _prepareCache()
    {
        if (!$this->_cache) {
            $this->_cache = Mage::app()->getCache();
        }
        return $this;
    }
}
