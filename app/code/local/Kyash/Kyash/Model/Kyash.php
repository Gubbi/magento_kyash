<?php
class Kyash_Kyash_Model_Kyash extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'kyash';
    protected $_formBlockType = 'kyash/form';
    protected $_infoBlockType = 'kyash/info';
    protected $_isInitializeNeeded = true;

    public function getOrderPlaceRedirectUrl()
    {
          return Mage::getUrl('kyash/index/placeorder', array('_secure' => true));
    }
	
    public function initialize($paymentAction, $stateObject)
    {
        $state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
        $stateObject->setState($state);
        $stateObject->setStatus('pending_payment');
        $stateObject->setIsNotified(false);
    }
}
?>