<?php
/**
 * Temando Observer
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_Observer
{

    /**
     * Handles sales_order_place_after event
     */
    public function createTemandoShipment(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();
        /* @var $order Mage_Sales_Model_Order */

	$shippingMethod = $order->getShippingMethod();
	$__t = explode('_', $shippingMethod);
	if ($__t[0] != 'temando') {
            return;
        }

        //prepare data from request/session
        $destinationType = Mage::getSingleton('checkout/session')->getData('destination_type');
        $delivery_options = Mage::getSingleton('checkout/session')->getData('selected_delivery_options');
        if (!is_array($delivery_options)) {
            $delivery_options = array();
        }

        $selected_quote_id = preg_replace('#^[^_]*_#', '', $shippingMethod);
        $selected_options = preg_replace('#^([^_]*_){2}#', '', $shippingMethod);

	$selected_quote = Mage::getModel('temando/quote')->load($selected_quote_id);
	if(!$selected_quote->getId()) {
	    //try loading cheapeast quote
	    try {
		$selected_quote = $this->loadCheapestQuote($order, $delivery_options, $destinationType);
		/* @var $selected_quote Temando_Temando_Model_Quote */
	    } catch (Exception $e) {
		$selected_quote = null;
	    }
	}

	$temando_shipment = Mage::getModel('temando/shipment');
	/* @var $temando_shipment Temando_Temando_Model_Shipment */

	if(Mage::helper('temando')->isQuoteDynamic($selected_quote_id)) {
	    //DYNAMIC: carrier quote selected by customer - must have at least 1 available quote
	    $temando_shipment
			->setAdminSelectedQuoteId($selected_quote->getId())
			->setCustomerSelectedQuoteId($selected_quote->getId())
			->setCustomerSelectedOptions($selected_options);
	    $selected_quote = $temando_shipment->getSelectedQuotePermutation();
	    $temando_shipment
                    ->setCustomerSelectedQuoteDescription($selected_quote->getDescription())
                    ->setAnticipatedCost($selected_quote->getTotalPrice());

	} else {
	    //STATIC: flat rate / free shipping selected by customer
	    $temando_shipment
		    ->setCustomerSelectedQuoteDescription($selected_quote_id == Temando_Temando_Model_Carrier::FLAT_RATE ? Mage::helper('temando')->__('Flat Rate') : Mage::helper('temando')->__('Free Shipping'))
		    ->setCustomerSelectedOptions('insurance_N_carbonoffset_N_footprints_N');

	    if($selected_quote instanceof Temando_Temando_Model_Quote) {
		//set cheapest as admin selected
		$temando_shipment->setAdminSelectedQuoteId($selected_quote->getId())
				 ->setAnticipatedCost($selected_quote->getTotalPrice());
	    }
	}

        $email = $order->getShippingAddress()->getEmail();
        if(!$email) {
            $email = $order->getCustomerEmail();
        }

        $temando_shipment
            ->setOrderId($order->getId() ? $order->getId() : null)
            ->setStatus(Temando_Temando_Model_System_Config_Source_Shipment_Status::PENDING)
            ->setDestinationContactName($order->getShippingAddress()->getName())
            ->setDestinationCompanyName($order->getShippingAddress()->getCompany())
            ->setDestinationStreet(str_replace("\n", ', ', $order->getShippingAddress()->getStreetFull()))
            ->setDestinationRegion($order->getShippingAddress()->getRegionCode())
            ->setDestinationPhone($order->getShippingAddress()->getTelephone())
            ->setDestinationEmail($email)
            ->setDestinationType(
                $destinationType ? $destinationType :
                Temando_Temando_Model_Checkout_Delivery_Options::DESTINATION_RESIDENCE
            )
            ->setDestinationCountry($order->getShippingAddress()->getCountryId())
            ->setDestinationPostcode($order->getShippingAddress()->getPostcode())
            ->setDestinationCity($order->getShippingAddress()->getCity())
            ->setReadyTime('AM')
            ->setCustomerSelectedDeliveryOptions(implode(',', array_keys($delivery_options)))
            ->save();

	foreach ($order->getAllVisibleItems() as $item) {
	    $product = Mage::getModel('catalog/product')->load($item->getProductId());
	    if($product->isVirtual()) { continue; }

	    Mage::helper('temando')->applyTemandoParamsToItem($item);

	    $qty = $item->getQty() ? $item->getQty() : $item->getQtyOrdered();
	    $box = Mage::getModel('temando/box');
	    /* @var $box Temando_Temando_Model_Box */
	    $box
		->setShipmentId($temando_shipment->getId())
		->setComment($item->getName())
		->setQty($item->getQty())
		->setValue($item->getRowTotalInclTax())
		->setLength($item->getTemandoLength())
		->setWidth($item->getTemandoWidth())
		->setHeight($item->getTemandoHeight())
		->setMeasureUnit(Mage::helper('temando')->getConfigData('units/measure'))
		->setWeight($item->getWeight()*$qty)
		->setWeightUnit(Mage::helper('temando')->getConfigData('units/weight'))
		->setPackaging($item->getTemandoPackaging())
		->setFragile($item->getTemandoFragile())
                ->setDangerous($item->getTemandoDangerous())
		->save();
	}
    }


    protected function loadCheapestQuote($order, $delivery_options, $destinationType)
    {
        $allowed_carriers = explode(',', Mage::getStoreConfig('carriers/temando/allowed_methods'));
	$request = Mage::getModel('temando/api_request');
	/* @var $request Temando_Temando_Model_Api_Request */
        $request
            ->setUsername(Mage::helper('temando')->getConfigData('general/username'))
            ->setPassword(Mage::helper('temando')->getConfigData('general/password'))
            ->setSandbox(Mage::helper('temando')->getConfigData('general/sandbox'))
	    ->setMagentoQuoteId($order->getQuoteId())
            ->setDestination(
                $order->getShippingAddress()->getCountry(),
                $order->getShippingAddress()->getPostcode(),
                $order->getShippingAddress()->getCity(),
		$order->getShippingAddress()->getStreet1(),
                $destinationType)
            ->setItems($order->getAllItems())
            ->setReady()
            ->setDeliveryOptions($delivery_options)
            ->setAllowedCarriers($allowed_carriers);

        $quotes = $request->getQuotes();
        if ($quotes instanceof Temando_Temando_Model_Mysql4_Quote_Collection) {
	    return Mage::helper('temando/functions')->getCheapestQuote($quotes->getItems());
	}
	return null;
    }


    public function hookCartSaveAddress($observer)
    {
        $post = $observer->getControllerAction()->getRequest()->getPost();
        if (Mage::getStoreConfig('carriers/temando/active') && isset($post['country_id']) && (Mage::helper('temando')->getDefaultCountryId() == $post['country_id']) && isset($post['region_id']) && isset($post['estimate_city']) && isset($post['estimate_postcode']) && isset($post['pcs'])) {
            $data = array(
                'country_id' => $post['country_id'],
                'region_id' => $post['region_id'],
                'city' => $post['estimate_city'],
                'postcode' => $post['estimate_postcode'],
                'pcs' => $post['pcs'],
            );
            Mage::getSingleton('customer/session')->setData('estimate_product_shipping', new Varien_Object($data));
        }
    }

    /**
     * Saves the destination type in the session if entered in the
     * cart page
     *
     * @return \Temando_Temando_Model_Observer
     */
    public function saveDestinationType()
    {
        $destinationType = Mage::app()->getRequest()->getParam('destination-type');
        if ($destinationType) {
            Mage::getSingleton('checkout/session')->setData(
                'destination_type', $destinationType);
        }
        return $this;
    }
}
