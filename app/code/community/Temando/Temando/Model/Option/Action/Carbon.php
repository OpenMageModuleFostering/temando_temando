<?php
/**
 * Option Action Carbon
 *
 * @package     Temando_Temando
 * @author      Temando Magento Team <marketing@temando.com>
 */
class Temando_Temando_Model_Option_Action_Carbon extends Temando_Temando_Model_Option_Action_Abstract
{
    
    public function apply(&$quote)
    {
        /* @var $quote Temando_Temando_Model_Quote */
        $price = $quote->getTotalPrice();
        $carbon_price = $quote->getCarbonTotalPrice();
        
        $quote->setTotalPrice($price + $carbon_price);
    }
    
}
