<?php
define('KYASH_LOG',1);
class Kyash_Kyash_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getAuthId()
	{
		return trim(Mage::getStoreConfig('payment/kyash/public_api_id'));
	}
	
	public function getAuthPass()
	{
		return trim(Mage::getStoreConfig('payment/kyash/api_secrets'));
	}
	
	public function getCallbackPass()
	{
		return trim(Mage::getStoreConfig('payment/kyash/callback_secret'));
	}
	
	public function getHmacPass()
	{
		return trim(Mage::getStoreConfig('payment/kyash/hmac_secret'));
	}
	
	public function getInstructions()
	{
		return nl2br(Mage::getStoreConfig('payment/kyash/instructions'));
	}
	
	public function canLog()
	{
		return KYASH_LOG;
	}
	
	public function modifiedJsonDecode($json,$assoc=false)
    {
		$search = array ("/\s\s+/","/(:(\s|)\')/",'/(\'(\s|):)/','/(,(\s|)\')/', '/(\'(\s|),)/', '/({(\s|)\')/', '/(\'(\s|)})/');
		$replace = array (" ", ':"', '":', ',"', '",', '{"', '"}');
		$json = preg_replace($search, $replace, $json);
        return json_decode($this->removeTrailingCommas(utf8_encode($json)),$assoc);
    }
   
    public function removeTrailingCommas($json)
    {
        $json=preg_replace('/,\s*([\]}])/m', '$1', $json);
        return $json;
    }
	
	public function getIncrementId($order)
	{
		$incrementId = $order->getOriginalIncrementId();
		if($incrementId == null || empty($incrementId) || !$incrementId)
		{
			$incrementId = $order->getIncrementId();
	  	}
		return $incrementId;
	}
	
	public function getOrderParams($order)
	{
		$billingAddress  = $order->getBillingAddress()->getData();
		$street = $billingAddress['street'];
		$street = explode("\n", $street);
		$address1 = $street[0];
		if(isset($street[1]) && !empty($street[1]))
		{
			$address1 .= ','.$street[1];
		}
		
		$deliveryAddress  = $order->getShippingAddress()->getData();
		$street = $deliveryAddress['street'];
		$street = explode("\n", $street);
		$address2 = $street[0];
		if(isset($street[1]) && !empty($street[1]))
		{
			$address2 .= ','.$street[1];
		}
		
        $params = array (
            'order_id' => $this->getIncrementId($order),
			'amount' => $order->getGrandTotal(),
			'billing_contact.first_name' => $billingAddress['firstname'],
			'billing_contact.last_name' => $billingAddress['lastname'],
			'billing_contact.email' => $order->getCustomerEmail(),
			'billing_contact.address' => $address1,
			'billing_contact.city' => $billingAddress['city'],
			'billing_contact.state' => $billingAddress['region'],
			'billing_contact.pincode' => $billingAddress['postcode'],
            'billing_contact.phone' => $billingAddress['telephone'],
            'shipping_contact.first_name' => $deliveryAddress['firstname'],
			'shipping_contact.last_name' => $deliveryAddress['firstname'],
			'shipping_contact.address' => $address2,
			'shipping_contact.city' => $deliveryAddress['city'],
			'shipping_contact.state' => $deliveryAddress['region'],
            'shipping_contact.pincode' => $deliveryAddress['postcode'],
            'shipping_contact.phone' => $deliveryAddress['telephone']
        );
		
		return http_build_query($params);
    }
	
	public function write($message)
	{
		Mage::log($message, null, 'kyash.log', true);
	}
}