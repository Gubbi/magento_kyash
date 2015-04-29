<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('sales/order'),'kyash_code','VARCHAR(50)');

$installer->endSetup();
?>