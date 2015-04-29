<?php
class Kyash_Kyash_Block_Info extends Mage_Payment_Block_Info
{
	protected function _prepareSpecificInformation($transport = null)
    {
		if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
		
		$kyash_code = @$this->getInfo()->getOrder()->getKyashCode();
		if($kyash_code)
		{
			$transport = parent::_prepareSpecificInformation($transport);
			$info['Kyash Code'] = $kyash_code;
			return $transport->addData($info);
		}
		
		return  parent::_prepareSpecificInformation($transport);
    }
}
?>