<?php
class Kaushik_Outofstock_Block_Adminhtml_Outofstock_Renderer_Customergroup extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$customer = Mage::getModel('customer/customer')->getCollection();
		$customer->addAttributeToFilter('email', $row->getData('email'));
		$custData = $customer->getData()[0];
		$groupname = Mage::getModel('customer/group')->load($custData['group_id'])->getCustomerGroupCode();
		if($groupname == "") {
			$groupname = "Guest";
		}
		return $groupname;
	}
}
