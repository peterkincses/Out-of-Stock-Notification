<?php
/**
 * @author 	Bikash Kaushik
 * @category   Kaushik
 * @package    Kaushik_Outofstock
 */
 
class Kaushik_Outofstock_IndexController extends Mage_Core_Controller_Front_Action
{
	 //public function indexAction()
     //{
     	//$this->loadLayout();     
	 	//$this->renderLayout();
     //}
    
    public function subscribeAction()
    {
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$response = array();
			$model = Mage::getModel('outofstock/outofstock')->getCollection();
			$model->addFieldToFilter('product_id', array('eq' => $data['product_id']));
			$model->addFieldToFilter('email', array('eq' => $data['email']));
			 
			foreach($model as $data){
				$outofstockId = $data->getOutofstockId();
				$createdTime = $data->getCreatedTime();
			}
			if($outofstockId) {
				$response['msg'] = 'This email is already subscribed on '.$createdTime;
				$response['status'] = 'warning';
			} else {
				$newmodel = Mage::getModel('outofstock/outofstock');
				$data['created_time'] = now();
				$newmodel->setData($data);
				try {
					$newmodel->save();
					$response['msg'] = 'You will be notified when this item is available for purchase.';
					$response['status'] = 'success';
					
					$_product = Mage::getModel('catalog/product')->load($data['product_id']);
					$_customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getWebsite()->getId());
					$_customer->loadByEmail($data['email']);
					$recepientName = $data['email'];
					$recepientemail = $data['email'];
					if($_customer->getEntityId()) {
						$recepientName = $_customer->getFirstname()." ".$_customer->getLastname();
					}
					$sendername = Mage::getStoreConfig('trans_email/ident_general/name');
					$senderemail = Mage::getStoreConfig('trans_email/ident_general/email');
					$vars = Array(
								'customerName'=>$recepientName,
								'alertGrid'=>'Thank you for subscribing for '.$_product->getName().'.',
								'productName'=>$_product->getName()
							);
					$storeId = Mage::app()->getStore()->getId();
					$translate = Mage::getSingleton('core/translate');
					$template_id = 'outofstock_email_subscription';
					$email_template = Mage::getModel('core/email_template')->loadDefault($template_id);
					$email_template->setSenderEmail($senderemail);
					$email_template->setSenderName($sendername);
					$email_template->send($recepientemail, $recepientName, $vars, $storeId);
					$translate->setTranslateInline(true);
					
				} catch(Exception $e) {
					$response['msg'] = $e->getMessage();
					$response['status'] = 'failure';
				}
			}
			$this->getResponse()->setHeader('Content-type', 'application/json',true);
			$this->getResponse()->setBody(json_encode($response));
		}
    }

    public function unsubscribeAction() {
    	if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$response = array();
			$model = Mage::getModel('outofstock/outofstock')->getCollection();
			$model->addFieldToFilter('email', $data['email']);
			$model->addFieldToFilter('product_id', $data['product_id']);
			try {
				$model->walk('delete');
				$response['msg'] = 'Unsubscribed successfully.';
				$response['status'] = 'success';
				
				$_product = Mage::getModel('catalog/product')->load($data['product_id']);
				$_customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getWebsite()->getId());
				$_customer->loadByEmail($data['email']);
				$recepientName = $data['email'];
				$recepientemail = $data['email'];
				if($_customer->getEntityId()) {
					$recepientName = $_customer->getFirstname()." ".$_customer->getLastname();
				}
				$sendername = Mage::getStoreConfig('trans_email/ident_general/name');
				$senderemail = Mage::getStoreConfig('trans_email/ident_general/email');
				$vars = Array(
							'customerName'=>$recepientName,
							'alertGrid'=>'You are successfully unsubsribed for '.$_product->getName().'.',
							'productName'=>$_product->getName()
						);
				$storeId = Mage::app()->getStore()->getId();
				$translate = Mage::getSingleton('core/translate');
				$template_id = 'outofstock_email_unsubscribe';
				$email_template = Mage::getModel('core/email_template')->loadDefault($template_id);
				$email_template->setSenderEmail($senderemail);
				$email_template->setSenderName($sendername);
				$email_template->send($recepientemail, $recepientName, $vars, $storeId);
				$translate->setTranslateInline(true);
			} catch(Exception $e) {
				$response['msg'] = $e->getMessage();
				$response['status'] = 'failure';
			}
			$this->getResponse()->setHeader('Content-type', 'application/json',true);
			$this->getResponse()->setBody(json_encode($response));
		}
    }

    public function subscribedlistAction() {

    	if(Mage::getModel('customer/session')->isLoggedin()) {
	    	$this->loadLayout();
	        $this->_initLayoutMessages('customer/session');
	        $this->_initLayoutMessages('catalog/session');
	        $this->getLayout()->getBlock('head')->setTitle($this->__('My Product Stock Subscriptions'));
	        $this->renderLayout();
    	} else {
    		Mage::app()->getResponse()->setRedirect(Mage::getUrl("customer/account/login/"));
    	}
    }
}