<?php
/**
 * Out of stock notification installation script
 * 
 * @category   Kaushik
 * @package    Kaushik_Outofstock
 */
 
$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('outofstock')};
CREATE TABLE {$this->getTable('outofstock')} (
  `outofstock_id` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '' COMMENT 'Customer email',
  `product_id` MEDIUMINT NOT NULL,
  `created_time` datetime NULL,
  PRIMARY KEY (`outofstock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
$installer->endSetup(); 