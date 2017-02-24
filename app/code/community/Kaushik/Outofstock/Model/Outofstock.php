<?php

class Kaushik_Outofstock_Model_Outofstock extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('outofstock/outofstock');
    }
}