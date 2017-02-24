<?php
class Kaushik_Outofstock_Block_Adminhtml_Outofstock extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_outofstock';
    $this->_blockGroup = 'outofstock';
    $this->_headerText = Mage::helper('outofstock')->__('Out of Stock Manager');
    $this->_addButtonLabel = Mage::helper('outofstock')->__('Add Item');
    parent::__construct();
    $this->_removeButton('add');
  }
}