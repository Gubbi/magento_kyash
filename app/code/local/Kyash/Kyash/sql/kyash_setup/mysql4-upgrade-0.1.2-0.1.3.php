<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('sales/order'),'kyash_status','VARCHAR(50)');

$installer->endSetup();
?>