<?php

class Kaushik_Outofstock_Model_Mysql4_Outofstock extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the outofstock_id refers to the key field in your database table.
        $this->_init('outofstock/outofstock', 'outofstock_id');
    }
}