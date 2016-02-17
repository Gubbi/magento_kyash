<?php
class Kyash_Kyash_Model_Observer
{
	public function cancelled(Varien_Event_Observer $observer)
	{
		$dirPath = dirname(__FILE__);
		$dirPath = str_replace('Model','lib',$dirPath);
		require_once($dirPath.DS.'KyashPay.php');
		$api = new KyashPay(Mage::helper('kyash')->getAuthId(), Mage::helper('kyash')->getAuthPass(), Mage::helper('kyash')->getCallbackPass(),Mage::helper('kyash')->getHmacPass());
		$api->setLogger(Mage::helper('kyash'));
		
		try 
		{
			$order = $observer->getPayment()->getOrder();
			if($order->getId() > 0 && $order->getKyashCode() != '')
			{
				$kyash_status = $order->getKyashStatus();
				if($kyash_status == 'pending')
				{
					$response = $api->cancel($order->getKyashCode());
					if(isset($response['status']) && $response['status'] == 'error')
					{
						throw new Exception($response['message']);
					}
					else
					{
						$message = 'Kyash payment collection has been cancelled for this order.';
						Mage::log($message, null, 'kyash.log', true);
						Mage::getSingleton('adminhtml/session')->addSuccess($message);
						$order->setKyashStatus('cancelled');
						$order->save();
					}
				}
				else if($kyash_status == 'paid')
				{
					$response = $api->cancel($order->getKyashCode());
					if(isset($response['status']) && $response['status'] == 'error')
					{
						throw new Exception($response['message']);
					}
					else
					{
						$message = 'Kyash payment collection has been cancelled for this order.';
						Mage::log($message, null, 'kyash.log', true);
						Mage::getSingleton('adminhtml/session')->addSuccess($message);
						$order->setKyashStatus('cancelled');
						$order->save();
					}
				}
			}
		}
		catch(Exception $e) 
		{
			$message = 'Kyash Cancel Failed: '.$e->getMessage();
			Mage::log($message, null, 'kyash.log', true);
			Mage::getSingleton('adminhtml/session')->addError($message);
		}
	}
}