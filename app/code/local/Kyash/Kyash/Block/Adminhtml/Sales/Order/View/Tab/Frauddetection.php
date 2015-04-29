<?php
/**
 * Fraud Genius EULA
 * http://www.fraudgenius.com/
 * Read the license at http://www.fraudgenius.com/license.txt
 *
 * Do not edit or add to this file, please refer to http://www.fraudgenius.com for more information.
 *
 * @category    Fraudgenius
 * @package     Fraudgenius_Fraudgenius
 * @copyright   Copyright (c) 2013 Fraud Genius (http://www.fraudgenius.com)
 * @license     http://www.fraudgenius.com/license.txt
 */

class Fraudgenius_Fraudgenius_Block_Adminhtml_Sales_Order_View_Tab_Frauddetection extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('fraudgenius/tab.phtml' );
	}

	public function getTabLabel()
	{
		return $this->__( 'Fraud Genius' );
	}

	public function getTabTitle()
	{
		return $this->__( 'Fraud Genius' );
	}

	public function getTabClass()
	{
		return '';
	}

	public function getClass()
	{
		return $this->getTabClass();
	}

	public function canShowTab()
	{
		return true;
	}

	public function isHidden()
	{
		if (Mage::getStoreConfig("fraudgenius/account/active") != '1')
			return true;
		return false;
	}

	public function getOrder()
	{
		$order = Mage::registry( 'current_order' );
		return $order;
	}
}