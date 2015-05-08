<?php
class Kyash_Kyash_IndexController extends Mage_Core_Controller_Front_Action
{
	public function handlerAction()
    {
		$dirPath = dirname(__FILE__);
		$dirPath = str_replace('controllers','lib',$dirPath);
		require_once($dirPath.DS.'KyashPay.php');
		$api = new KyashPay(Mage::helper('kyash')->getAuthId(), Mage::helper('kyash')->getAuthPass(), Mage::helper('kyash')->getCallbackPass(),Mage::helper('kyash')->getHmacPass());
		$api->setLogger(Mage::helper('kyash'));
		
		$params = $this->getRequest()->getParams();
		$order_id = trim($params['order_id']);
		$order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
		if( !(is_object($order) && $order->getId() > 0) )
		{
			Mage::log("Handler: HTTP/1.1 500 Order is not found", null, 'kyash.log', true);
			header("HTTP/1.1 500 Order is not found");
			exit;								 
		}
		else
		{
			$url = Mage::getUrl('kyash/index/handler', array('key'=>false));
			$updater = new KyashUpdater($order);
			$api->callback_handler($updater, $order->getKyashCode(),$order->getKyashStatus(),$url);
		}
    }
	
	public function getPaymentPointsAction()
    {
		$dirPath = dirname(__FILE__);
		$dirPath = str_replace('controllers','lib',$dirPath);
		require_once($dirPath.DS.'KyashPay.php');
		$api = new KyashPay(Mage::helper('kyash')->getAuthId(), Mage::helper('kyash')->getAuthPass(), Mage::helper('kyash')->getCallbackPass(),Mage::helper('kyash')->getHmacPass());
		$api->setLogger(Mage::helper('kyash'));
		
		$pincode = $this->getRequest()->getParam('postcode');
        $block = Mage::getConfig()->getBlockClassName('core/template');
        $block = new $block;
		$response = $api->getPaymentPoints($pincode);
		if(isset($response['status']) && $response['status'] == 'error')
		{
			$block->setTemplate('kyash/error.phtml');
			$block->setError($response['message']);
		}
		else
		{
        	$block->setTemplate('kyash/payment_points.phtml');
			$block->setPaypoints($response);
		}
		echo $block->toHtml();
    }
	
	public function getPaymentPoints2Action()
    {
		$dirPath = dirname(__FILE__);
		$dirPath = str_replace('controllers','lib',$dirPath);
		require_once($dirPath.DS.'KyashPay.php');
		$api = new KyashPay(Mage::helper('kyash')->getAuthId(), Mage::helper('kyash')->getAuthPass(), Mage::helper('kyash')->getCallbackPass(),Mage::helper('kyash')->getHmacPass());
		$api->setLogger(Mage::helper('kyash'));
		
		$pincode = $this->getRequest()->getParam('postcode');
        $block = Mage::getConfig()->getBlockClassName('core/template');
        $block = new $block;
		$response = $api->getPaymentPoints($pincode);
		if(isset($response['status']) && $response['status'] == 'error')
		{
			$block->setTemplate('kyash/error.phtml');
			$block->setError($response['message']);
		}
		else
		{
        	$block->setTemplate('kyash/payment_points_2.phtml');
			$block->setPaypoints($response);
		}
		echo $block->toHtml();
    }
	
	public function placeorderAction()
    {
		$dirPath = dirname(__FILE__);
		$dirPath = str_replace('controllers','lib',$dirPath);
		require_once($dirPath.DS.'KyashPay.php');
		$api = new KyashPay(Mage::helper('kyash')->getAuthId(), Mage::helper('kyash')->getAuthPass(), Mage::helper('kyash')->getCallbackPass(),Mage::helper('kyash')->getHmacPass());
		$api->setLogger(Mage::helper('kyash'));
		
		try
		{
			$incrementId = $this->getCheckout()->getLastRealOrderId();
			if(empty($incrementId))
			{
				$this->getCheckout()->addError($this->__('Fatal Error: Can not load order object.'));
				$this->_redirect('checkout/cart');
			}
			else
			{
				$order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
				if( !(is_object($order) && $order->getId() > 0) )
            	{
					$this->getCheckout()->addError($this->__('Fatal Error: Can not load order object.'));
					$this->_redirect('checkout/cart');
				}
				else
				{
					$params = Mage::helper('kyash')->getOrderParams($order);
					$response = $api->createKyashCode($params);
					if(!$response || (isset($response['status']) && $response['status'] == 'error'))
					{
						$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, Mage_Sales_Model_Order::STATE_CANCELED,$this->__('The order was canceled.'));
						$order->save();
						$this->maintainQuote($order->getQuoteId());
						$this->getCheckout()->addError($this->__('Payment error. ').$response['message']);
						$this->_redirect('checkout/cart');
					}
					else
					{
						$order->sendNewOrderEmail();
						$order->setEmailSent(true);
						$order->setKyashCode($response['id']);
						$order->setKyashStatus('pending');
						$order->setKyashExpires($response['expires_on']);
						$order->save();
						$this->_redirect('checkout/onepage/success');
					}
				}
			}
		}
		catch(Exception $e)
		{
			$this->getCheckout()->addError('Error => '.$e->getMessage());
			$this->_redirect('checkout/cart');
		}
	}
	
	protected function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
	
	protected function maintainQuote($quoteId)
    {
        $session = $this->getCheckout();
        if ($quoteId)
        {
            $quote = Mage::getModel('sales/quote')->load($quoteId);
            if ($quote->getId())
            {
                $quote->setIsActive(true)->save();
                $session->setQuoteId($quoteId);
            }
        }
    }
}

class KyashUpdater
{
	public $order = NULL;
	
	public function __construct($order)
	{
		$this->order = $order;
	}
	
	public function update($status,$comment)
	{
		if($status == 'paid')
		{
			$this->order->setState(Mage_Sales_Model_Order::STATE_PROCESSING,Mage_Sales_Model_Order::STATE_PROCESSING,$comment);
			$this->order->setKyashStatus('paid');
			$this->order->save();
		}
		else if($status == 'expired')
		{
			$this->order->setState(Mage_Sales_Model_Order::STATE_CANCELED, Mage_Sales_Model_Order::STATE_CANCELED,$comment);
			$this->order->setKyashStatus('expired');
			$this->order->save();
		}
	}
}