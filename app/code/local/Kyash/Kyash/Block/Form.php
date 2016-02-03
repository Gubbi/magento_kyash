<?php
class Kyash_Kyash_Block_Form extends Mage_Payment_Block_Form
{
	protected function _construct()
    {
        $shops = Mage::getConfig()->getBlockClassName('core/template');
        $shops = new $shops;
        $shops->setTemplate('kyash/shops.phtml')
			->setPostcode($this->getPostcode());

        $this->setTemplate('kyash/form.phtml')
            ->setMethodLabelAfterHtml($shops->toHtml());

        parent::_construct();
    }
	
	protected function getPostcode()
    {
        $address = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress();
		if($address)
		{
			return $address->getPostcode();
		}
		return '';
    }
}
?>