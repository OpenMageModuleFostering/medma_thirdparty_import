<?php

$installer = $this;

$installer->startSetup();

$installer->run("


CREATE TABLE IF NOT EXISTS {$this->getTable('categorytranslate')} (
  `trans_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
	 `english` varchar(255) NOT NULL DEFAULT '',
  `category_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 
