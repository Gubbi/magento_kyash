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
 
class Fraudgenius_Fraudgenius_Block_Adminhtml_Renderer_Fraudscore extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$fraudScore = $row->getData($this->getColumn()->getIndex());
		
		if (!is_null($fraudScore))
		{
			if ($fraudScore < 0) 
				return '&nbsp;';
				
			$title = "";
			
			if (Mage::getStoreConfig("fraudgenius/detection/score")  > 0)
			{
				if(Mage::helper('fraudgenius')->isBad(Mage::getStoreConfig("fraudgenius/detection/score"), $fraudScore))
				{
					$title = "<font color=\"red\">{$fraudScore}</font>" ;
				}
				else
				{
					$title =  "<font color=\"green\">{$fraudScore}</font>";
				}
			}
			
			if ($title)
				return $this->fraudLink($row->getId(),$title);

			return $fraudScore;
		}
		return "";
	}
	
	function fraudLink($id,$title)
	{
		$url = Mage::helper('adminhtml')->getUrl("fraudgenius/index/popup/", array( "id" => $id));
		$html = "<a href=\"#\" onclick=\"FraudgeniusAdmin.popup('{$url}');\">" . $title . "</a>";
		$config = array() ;
		$config["title"] = Mage::helper("fraudgenius")->__("Fraud Detection");
		$js = '<script type="text/javascript">
		FraudgeniusAdmin = new FraudgeniusAdmin('.json_encode($config).');
		</script>';
		return $html . $js;
	}
}