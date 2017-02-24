<?php
class Kaushik_Outofstock_Block_Adminhtml_Outofstock_Renderer_Productsku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$product = Mage::getModel('catalog/product')->load($row->getData('product_id'));
		return $product->getSku();
	}
}
