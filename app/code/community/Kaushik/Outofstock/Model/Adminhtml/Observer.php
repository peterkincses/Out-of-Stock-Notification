<?php 
class Kaushik_Outofstock_Model_Adminhtml_Observer
{
    public function onBlockHtmlBefore(Varien_Event_Observer $observer) {
        $block = $observer->getBlock();
        if (!isset($block)) return;

        switch ($block->getType()) {
            case 'adminhtml/catalog_product_grid':
                $block->addColumn('total_subs', array(
                    'header' => Mage::helper('outofstock')->__('Stock Subscription'),
                    'index'  => 'total_subs',
                    'filter' => false,
                    'width'  => '50px'
                ));
                break;
        }
    }

    public function onEavLoadBefore(Varien_Event_Observer $observer) {
        $collection = $observer->getCollection();
        if (!isset($collection)) return;

        if (is_a($collection, 'Mage_Catalog_Model_Resource_Product_Collection')) {
			$stockTable = Mage::getModel('core/resource')->getTableName('outofstock');
            $collection->getSelect()->joinLeft( array('oos'=> $stockTable), 'oos.product_id = e.entity_id', array('total_subs'=>'count(oos.product_id)'))->group('e.entity_id');
        }
    }

    public function deleteSubscription(Varien_Event_Observer $observer) {
        $productdelete = $observer->getEvent()->getProduct();
        $productId = $productdelete->getEntityId();
        
        $model = Mage::getModel('outofstock/outofstock')->getCollection();
        $model->addFieldToFilter('product_id', $productId);
        $model->walk('delete');
    }

}