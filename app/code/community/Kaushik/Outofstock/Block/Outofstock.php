<?php
class Kaushik_Outofstock_Block_Outofstock extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getOutofstock()     
     { 
        if (!$this->hasData('outofstock')) {
            $this->setData('outofstock', Mage::registry('outofstock'));
        }
        return $this->getData('outofstock');
        
    }
}