<?php
/**
 *
 * @author Bikash Kaushik
 * 
 * @category    Kaushik
 * @package     Kaushik_Outofstock
 */
class Kaushik_Outofstock_Block_Adminhtml_Outofstock_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('outofstockGrid');
      $this->setDefaultSort('outofstock_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('outofstock/outofstock')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('outofstock_id', array(
          'header'    => Mage::helper('outofstock')->__('ID'),
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'outofstock_id',
      ));

      $this->addColumn('email', array(
          'header'    => Mage::helper('outofstock')->__('Customer Email'),
          'align'     =>'left',
          'width'     => '250px',
          'index'     => 'email',
      ));

      $this->addColumn('customer_type', array(
          'header'    => Mage::helper('outofstock')->__('Customer Group'),
          'align'     =>'left',
          'width'     => '150px',
          'index'     => 'customer_type',
          'type'      => 'options',
          'renderer'  => 'Kaushik_Outofstock_Block_Adminhtml_Outofstock_Renderer_Customergroup',
      ));

      /*$this->addColumn('product_id', array(
          'header'    => Mage::helper('outofstock')->__('Product ID'),
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'product_id',
      ));*/

      $this->addColumn('sku', array(
          'header'    => Mage::helper('outofstock')->__('Product Sku'),
          'align'     =>'left',
          'width'     => '150px',
          'index'     => 'sku',
          'renderer'  => 'Kaushik_Outofstock_Block_Adminhtml_Outofstock_Renderer_Productsku',
      ));

      $this->addColumn('product_name', array(
          'header'    => Mage::helper('outofstock')->__('Product Name'),
          'align'     =>'left',
          'index'     => 'product_name',
          'renderer'  => 'Kaushik_Outofstock_Block_Adminhtml_Outofstock_Renderer_Productname',
      ));

      $this->addColumn('created_time', array(
          'header'    => Mage::helper('outofstock')->__('Subscription Date'),
          'align'     =>'left',
          'width'     => '200px',
          'index'     => 'created_time',
      ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('outofstock')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('outofstock')->__('Delete'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('outofstock')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('outofstock')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('outofstock_id');
        $this->getMassactionBlock()->setFormFieldName('outofstock');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('outofstock')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('outofstock')->__('Are you sure?')
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return false;
  }

}