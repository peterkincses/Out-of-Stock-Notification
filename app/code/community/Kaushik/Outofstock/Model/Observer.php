<?php 
/**
 * @author 	Bikash Kaushik
 * @category   Kaushik
 * @package    Kaushik_Outofstock
 */
 
class Kaushik_Outofstock_Model_Observer {
	public function stockChangeItem($observer) {
		$product = $observer->getEvent()->getProduct();
		if ($product) {
			if ($product->getStockItem()) {
				$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
				$inStock = $stockItem->getIsInStock();
				if($inStock >= 1) {
					$model = Mage::getModel('outofstock/outofstock')->getCollection();
					$model->addFieldToFilter('product_id', $product->getId());
					foreach($model as $data) {
						$email = $data->getEmail();
						$outofstock_id = $data->getOutofstockId();
						try {
							$_customer = Mage::getModel('customer/customer')->setWebsiteId(1);
							$_customer->loadByEmail($email);
							$recepientName = $email;
							$recepientemail = $email;
							if($_customer->getEntityId()) {
								$recepientName = $_customer->getFirstname()." ".$_customer->getLastname();
							}
							$sendername = Mage::getStoreConfig('trans_email/ident_general/name');
							$senderemail = Mage::getStoreConfig('trans_email/ident_general/email');
							$vars = Array(
										'customerName'=>$recepientName,
										'alertGrid'=>'<a href="'.$product->getProductUrl().'" >'.$product->getName().'</a> is back in stock.',
										'productName'=>$product->getName()
									);
							$storeId = Mage::app()->getStore()->getId();
							$translate = Mage::getSingleton('core/translate');
							$template_id = 'outofstock_email_productinstock';
							$email_template = Mage::getModel('core/email_template')->loadDefault($template_id);
							$email_template->setSenderEmail($senderemail);
							$email_template->setSenderName($sendername);
							$email_template->send($recepientemail, $recepientName, $vars, $storeId);
							$translate->setTranslateInline(true);
							
							$deleteInfo = Mage::getModel('outofstock/outofstock');
							$deleteInfo->setOutofstockId($outofstock_id)->delete();
						} catch(Exception $e) {
							Mage::log(print_r($e->getMessage(),true), null, 'outofstockmailerror.log');
						}
					}					
				}
			}
		}
	}
	
	public function cancelOrderItem($observer) {
		
	}
}