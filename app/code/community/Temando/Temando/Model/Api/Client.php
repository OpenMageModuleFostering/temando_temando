<?php

class DebugSoapClient extends SoapClient {

    public function __doRequest($request, $location, $action, $version, $one_way = 0) {
	Mage::log($request, null, 'raw-request.xml', true);
	$response = parent::__doRequest($request, $location, $action, $version, $one_way);
	Mage::log($response, null, 'raw-response.xml', true);
	return $response;
    }

}

class Temando_Temando_Model_Api_Client extends Mage_Core_Model_Abstract {

    protected $_client = null;
    protected $_is_sand = null;

    public function __construct() {
	parent::__construct();
    }

    public function connect($username = null, $password = null, $sandbox = false) {
	$this->_is_sand = $sandbox;
	if ($sandbox) {
	    $url = "https://training-api3.temando.com/schema/2009_06/server.wsdl";
	} else {
	    $url = "https://api.temando.com/schema/2009_06/server.wsdl";
	}

	if (!$username) {
	    $username = Mage::helper('temando')->getConfigData('general/username');
	}
	if (!$password) {
	    $password = Mage::helper('temando')->getConfigData('general/password');
	}

	// The WSDL cache should be set to on to prevent the WSDL being loaded everytime.
	ini_set("soap.wsdl_cache_enabled", "1");

	// Create a new SoapClient referencing the Temando WSDL file.
	$this->_client = new SoapClient($url, array('soap_version' => SOAP_1_2, 'trace' => TRUE));

	// Define the security string that wraps your login details. Due to limitations
	// with the PHP language this header information can only be provided via a string.
	$headerSecurityStr = "<Security><UsernameToken><Username>" . htmlentities($username) . "</Username>" .
		"<Password>" . htmlentities($password) . "</Password></UsernameToken></Security>";

	// Create a new SoapVar using the header security string.
	$headerSecurityVar = new SoapVar($headerSecurityStr, XSD_ANYXML);

	// Create a new SoapHeader containing all your login details.
	$soapHeader = new SoapHeader('wsse:http://schemas.xmlsoap.org/ws/2002/04/secext', 'soapenv:Header', $headerSecurityVar);

	// Add the SoapHeader to your SoapClient.
	$this->_client->__setSoapHeaders(array($soapHeader));

	return $this;
    }

    /**
     * Gets quotes for a delivery.
     *
     * @param array $request the request parameters, in an array format.
     * @return array
     */
    public function getQuotesByRequest($request) {
	return $this->getQuotes($request);
    }

    public function getQuotes($request) {
	if (!$this->_client) {
	    return false;
	}
	
	if (!$this->_is_sand) {
	    $request['clientId'] = Mage::helper('temando')->getClientId();
	}
	
	$response = $this->_client->getQuotes($request);
	
	//make sure we have an array
	if (!isset($response->quotes->quote)) {
	    $response->quotes->quote = array();
	} else if (isset($response->quotes->quote) && !is_array($response->quotes->quote)) {
	    $response->quotes->quote = array(0 => $response->quotes->quote);
	}

	$quotes = array();
	foreach ($response->quotes->quote as $quoteData) {
	    $quotes[] = Mage::getModel('temando/quote')->loadResponse($quoteData);
	}

	return $quotes;
    }

    public function makeBookingByRequest($request) {
	return $this->makeBooking($request);
    }
    
    public function makeBooking($request) {
	if (!$this->_is_sand) {
	    $request['clientId'] = Mage::helper('temando')->getClientId();
	}
	if (!$this->_client) {
	    return false;
	}

	return $this->_client->makeBooking($request);
    }

    public function getRequest($request) {
	if (!$this->_client) {
	    return false;
	}

	return $this->_client->getRequest($request);
    }

    public function confirmManifest($request) {
	if (!$this->_client) {
	    return false;
	}

	return $this->_client->confirmManifest($request);
    }

    public function getManifest($request) {
	if (!$this->_client) {
	    return false;
	}

	return $this->_client->getManifest($request);
    }

    /**
     * get list of location
     *
     * @param $request
     * @return bool
     */
    public function getLocations($request) {
	if (!$this->_client) {
	    return false;
	}

	return $this->_client->getLocations($request);
    }

    /**
     * create location
     *
     * @param $request
     * @return bool
     */
    public function createLocation($request) {
	if (!$this->_client) {
	    return false;
	}

	$request['clientId'] = Mage::helper('temando')->getClientId();

	return $this->_client->createLocation($request);
    }

    /**
     * update location
     *
     * @param $request
     * @return bool
     */
    public function updateLocation($request) {
	if (!$this->_client) {
	    return false;
	}
	
	$request['clientId'] = Mage::helper('temando')->getClientId();

	return $this->_client->updateLocation($request);
    }

    /**
     * Gets the multiplier for insurance (currently 1%).
     *
     * To add insurance to a quote, the total price should be multiplied by
     * this value.
     */
    public function getInsuranceMultiplier() {
	return 1.01; // 1%
    }


    public function getClient($request) {
	if (!$this->_client) {
	    return false;
	}

	return $this->_client->getClient($request);
    }



}
