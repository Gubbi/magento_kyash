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

class Fraudgenius_Fraudgenius_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
	protected $_isActiveFraud = -1;
	protected function _prepareColumns()
	{
		if (!$this->isActiveFraud())
			return parent::_prepareColumns();
		$res = parent::_prepareColumns();

		$action = $this->_columns['action'];
		unset($this->_columns['action']);

		$this->addColumn('fraud_score', array(
			'header' => Mage::helper('fraudgenius')->__('Fraud Score'),
			'index' => 'fraud_score',
			'type'  => 'number',
			'width' => '50px',
			'align' => 'center',
			'filter_condition_callback' => array($this, '_filterFraudScore'),
			'renderer'  => 'fraudgenius/adminhtml_renderer_fraudscore',
		));

		$this->_columns['action'] = $action;
		$this->_columns['action']->setId('action');
		$this->_lastColumnId = 'action';
		return $res;
	}
	public function setCollection($collection)
    {
    	if (!$this->isActiveFraud())
			return parent::setCollection($collection);
			
		if ($collection instanceof Mage_Sales_Model_Resource_Order_Grid_Collection)//Magento  1.6.1
		{		
			$collection->getSelect()
				->joinLeft(array('fraudgenius_score' => $collection->getTable('fraudgenius/score')), 'fraudgenius_score.order_id=main_table.entity_id', 'fraud_score');
				
		}
		else if ($collection instanceof Mage_Core_Model_Mysql4_Collection_Abstract)//Magento  1.4.1
		{
			$collection->getSelect()
				->joinLeft(array('fraudgenius_score' => $collection->getTable('fraudgenius/score')), 'fraudgenius_score.order_id=main_table.entity_id', 'fraud_score');
		}
		else if ($collection instanceof Mage_Eav_Model_Entity_Collection_Abstract)
		{			
			$collection->joinTable('fraudgenius/score', 'order_id=entity_id', array("fraud_score" => "fraud_score"), null, "left");
		}	

		return parent::setCollection($collection);
    }
    
	protected function _filterFraudScore($collection, $column)
    {
		if ($collection instanceof Mage_Sales_Model_Resource_Order_Grid_Collection)//Magento 1.6
		{   
			$collection->addFieldToFilter('`fraudgenius_score`.fraud_score' , $column->getFilter()->getCondition());
		}
		else if ($collection instanceof Mage_Core_Model_Mysql4_Collection_Abstract)//Magento 1.4.1
		{
			$collection->addFieldToFilter('`fraudgenius_score`.fraud_score' , $column->getFilter()->getCondition());
		}
		else
		{
			$collection->addFieldToFilter($column->getIndex() , $column->getFilter()->getCondition());
		}
    }
	
	protected function isActiveFraud()
	{
		if (Mage::getStoreConfig("fraudgenius/account/active") == '1')
		{
			return true;
		}
		return false;
	}
}
