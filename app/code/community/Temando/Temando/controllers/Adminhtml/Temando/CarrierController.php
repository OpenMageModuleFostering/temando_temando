<?php
/**
 * Carrier Controller
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Adminhtml_Temando_CarrierController extends Mage_Adminhtml_Controller_Action
{

    const CARRIER_ERROR = 'An error occured when synchronizing carriers with temando.com';
    const CARRIER_SUCCESS = 'Synchronized carriers';

    /**
     * Check admin permissions for this controller
     * Add a comment to this line
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
            ->isAllowed('temando');
    }

    /**
     * Adminhtml controller that refreshes carriers in the temando_carrier table.
     * @return
     */
    public function indexAction()
    {
        $showAdhoc = false;
        try {
            $api = Mage::getModel('temando/api_client');
            /* @var $api Temando_Temando_Model_Api_Client */
            $api->connect(
                Mage::helper('temando')->getConfigData('general/username'),
                Mage::helper('temando')->getConfigData('general/password'),
                Mage::helper('temando')->getConfigData('general/sandbox')
            );
            $response = $api->getCarriers(array(
                'clientId' => Mage::helper('temando')->getClientId(),
                'showAdhoc' => $showAdhoc ? 'Y' : 'N'
            ));
            //make sure we have an array
            if (!isset($response->linked->carrier)) {
                $response->linked->carrier = array();
            } elseif (isset($response->linked->carrier) && !is_array($response->linked->carrier)) {
                $response->linked->carrier = array(0 => $response->linked->carrier);
            }
            foreach ($response->linked->carrier as $carrierResponse) {
                $this->loadResponse($carrierResponse);
            }
            //process adhoc carriers
            if ($showAdhoc) {
                if (!isset($response->adhoc->carrier)) {
                    $response->adhoc->carrier = array();
                } elseif (isset($response->adhoc->carrier) && !is_array($response->adhoc->carrier)) {
                    $response->adhoc->carrier = array(0 => $response->adhoc->carrier);
                }
                foreach ($response->adhoc->carrier as $carrierResponse) {
                    $this->loadResponse($carrierResponse);
                }
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('temando')->__(self::CARRIER_SUCCESS));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('temando')->__(self::CARRIER_ERROR));
        }

        return $this->getResponse()->setRedirect($this->getRequest()->getServer('HTTP_REFERER'));
    }

    protected function loadResponse($response)
    {
        if ($response instanceof stdClass) {
            $carrier = Mage::getModel('temando/carrier')->load($response->id, 'carrier_id');
            /* @var $carrier Temando_Temando_Model_Carrier */

            $carrier
                ->setCarrierId(isset($response->id) ? $response->id : '')
                ->setCompanyName(isset($response->companyName) ? $response->companyName : '')
                ->setCompanyContact(isset($response->companyContact) ? $response->companyContact : '')
                ->setStreetAddress(isset($response->streetAddress) ? $response->streetAddress : '')
                ->setStreetSuburb(isset($response->streetSuburb) ? $response->streetSuburb : '')
                ->setStreetCity(isset($response->streetCity) ? $response->streetCity : '')
                ->setStreetState(isset($response->streetState) ? $response->streetState : '')
                ->setStreetPostcode(isset($response->streetCode) ? $response->streetCode : '')
                ->setStreetCountry(isset($response->streetCountry) ? $response->streetCountry : '')
                ->setPostalAddress(isset($response->postalAddress) ? $response->postalAddress : '')
                ->setPostalSuburb(isset($response->postalSuburb) ? $response->postalSuburb : '')
                ->setPostalCity(isset($response->postalCity) ? $response->postalCity : '')
                ->setPostalState(isset($response->postalState) ? $response->postalState : '')
                ->setPostalPostcode(isset($response->postalCode) ? $response->postalCode : '')
                ->setPostalCountry(isset($response->postalCountry) ? $response->postalCountry : '')
                ->setPhone(isset($response->phone1) ? $response->phone1 : '')
                ->setEmail(isset($response->email) ? $response->email : '')
                ->setWebsite(isset($response->website) ? $response->website : '')
                ->save();
        }
    }
}
