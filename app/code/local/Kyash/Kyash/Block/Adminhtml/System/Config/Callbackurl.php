<?php
class Kyash_Kyash_Block_Adminhtml_System_Config_Callbackurl extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $url = $this->getUrl('kyash/index/handler', array('key'=>false));
        $html = '<strong>'.$url.'</strong>&nbsp;&nbsp;';
        return $html;
    }
}
