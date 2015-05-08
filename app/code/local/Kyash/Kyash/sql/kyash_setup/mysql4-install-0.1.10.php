<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('sales/order'),'kyash_code','VARCHAR(50)');
$installer->getConnection()->addColumn($this->getTable('sales/order'),'kyash_status','VARCHAR(50)');
$installer->getConnection()->addColumn($this->getTable('sales/order'),'kyash_expires','INT(10) UNSIGNED NOT NULL');

$installer->endSetup();
?>