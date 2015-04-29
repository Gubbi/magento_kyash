<?php
class Kyash_Kyash_Block_Success extends Mage_Checkout_Block_Onepage_Success
{
	public function getOrder()
    {
		$order = Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());
        return $order;
    }
	
	public function getPostcode()
    {
		$order = $this->getOrder();
		$address  = $order->getBillingAddress()->getData();
        return $address['postcode'];
    }
}
?>